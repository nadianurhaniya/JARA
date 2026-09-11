<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the user management and activity log screens.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $role = (string) $request->query('role');
        $status = (string) $request->query('status');
        $tab = $request->query('tab') === 'activity' ? 'activity' : 'users';

        $users = User::query()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, ['admin', 'user'], true), fn (Builder $query) => $query->where('role', $role))
            ->when($status === 'active', fn (Builder $query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn (Builder $query) => $query->where('is_active', false))
            ->latest()
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'stats' => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
                'admins' => User::where('role', UserRole::Admin)->count(),
            ],
            'activityLogs' => $tab === 'activity'
                ? ActivityLog::with(['user', 'targetUser'])->latest()->paginate(15, ['*'], 'activity_page')->withQueryString()
                : null,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'tab' => $tab,
        ]);
    }

    /**
     * Create a new user account on behalf of an administrator.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => UserRole::from($request->validated('role')),
            'is_active' => true,
        ]);

        $this->log($request, 'Created user account', $user);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Account for {$user->name} was created.");
    }

    /**
     * Display a single user's account details.
     */
    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user,
            'logs' => ActivityLog::with('user')
                ->where('target_user_id', $user->id)
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Activate or deactivate a user account.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            abort(403, 'Administrator accounts cannot be deactivated.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $this->log(
            $request,
            $user->is_active ? 'Reactivated user' : 'Deactivated user',
            $user
        );

        return back()->with('status', "{$user->name} was ".($user->is_active ? 'reactivated' : 'deactivated').'.');
    }

    /**
     * Record an account-management action for auditing.
     */
    private function log(Request $request, string $action, User $target): void
    {
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'description' => "{$target->name} ({$target->email})",
            'target_user_id' => $target->id,
        ]);
    }
}
