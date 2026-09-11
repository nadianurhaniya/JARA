<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Models\User;
use App\Notifications\RemovedFromProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TaskListMemberController extends Controller
{
    /**
     * Display the members of the specified project.
     */
    public function index(TaskList $taskList): JsonResponse
    {
        Gate::authorize('viewCollaboration', $taskList);

        $members = $taskList->members()
            ->orderByRaw("CASE WHEN task_list_user.role = 'owner' THEN 0 ELSE 1 END")
            ->orderBy('users.name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->pivot->role,
                'is_active' => $user->is_active,
                'joined_at' => $user->pivot->created_at,
            ]);

        return response()->json(['data' => $members]);
    }

    /**
     * Remove the specified member from the project.
     */
    public function destroy(TaskList $taskList, User $user): JsonResponse
    {
        Gate::authorize('removeMember', $taskList);

        if ($user->id === $taskList->user_id) {
            return response()->json(['message' => 'Pemilik proyek tidak dapat dihapus.'], 422);
        }

        if (! $taskList->isMember($user)) {
            return response()->json(['message' => 'User tersebut bukan member proyek ini.'], 404);
        }

        $taskList->members()->detach($user->id);
        $taskList->tasks()->where('assignee_id', $user->id)->update(['assignee_id' => null]);

        $user->notify(new RemovedFromProject($taskList));

        return response()->json(['message' => "Member \"{$user->name}\" dihapus dari proyek."]);
    }
}
