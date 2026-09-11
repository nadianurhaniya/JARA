@extends('layouts.app')

@section('page-title', 'Admin Panel')
@section('page-subtitle', 'Manage users and system activity')

@section('content')
    @php
        $tabLink = fn (string $target) => route('admin.users.index', array_merge(
            request()->only(['search', 'role', 'status']),
            ['tab' => $target],
        ));

        $statCards = [
            ['label' => 'Total Users', 'value' => $stats['total'], 'bg' => '#E8F9F9', 'color' => '#0BC5C1'],
            ['label' => 'Active Users', 'value' => $stats['active'], 'bg' => '#ECFDF5', 'color' => '#10B981'],
            ['label' => 'Inactive Users', 'value' => $stats['inactive'], 'bg' => '#F1F5F9', 'color' => '#94A3B8'],
            ['label' => 'Admins', 'value' => $stats['admins'], 'bg' => '#FEF3C7', 'color' => '#F59E0B'],
        ];
    @endphp

    <div class="p-4 lg:p-6">
        @if (session('status'))
            <x-alert type="success" class="mb-5">{{ session('status') }}</x-alert>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach ($statCards as $card)
                <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0]">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background: {{ $card['bg'] }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $card['color'] }}" stroke-width="2" class="w-4 h-4" aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                    <p class="font-display font-extrabold text-2xl text-[#1E293B]">{{ $card['value'] }}</p>
                    <p class="text-sm text-[#64748B]">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Tabs --}}
        <div class="flex gap-1 bg-[#F1F5F9] rounded-xl p-1 w-fit mb-5" role="tablist">
            <a
                href="{{ $tabLink('users') }}"
                role="tab"
                @if ($tab === 'users') aria-selected="true" @endif
                class="px-5 py-1.5 rounded-lg text-xs font-medium {{ $tab === 'users' ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8] hover:text-[#64748B]' }}"
            >
                User Management
            </a>
            <a
                href="{{ $tabLink('activity') }}"
                role="tab"
                @if ($tab === 'activity') aria-selected="true" @endif
                class="px-5 py-1.5 rounded-lg text-xs font-medium {{ $tab === 'activity' ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8] hover:text-[#64748B]' }}"
            >
                Activity Log
            </a>
        </div>

        @if ($tab === 'users')
            {{-- Toolbar --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white rounded-2xl border border-[#E2E8F0] p-4 mb-4">
                <input type="hidden" name="tab" value="users">
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-2 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl px-3 py-2 flex-1 min-w-48">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-[#94A3B8]" aria-hidden="true">
                            <circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" />
                        </svg>
                        <label for="search" class="sr-only">Search users</label>
                        <input
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search users..."
                            class="bg-transparent text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none flex-1"
                        >
                    </div>

                    <label for="role" class="sr-only">Filter by role</label>
                    <select id="role" name="role" class="px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                        <option value="all" @selected($role === 'all' || $role === '')>All Roles</option>
                        <option value="admin" @selected($role === 'admin')>Admin</option>
                        <option value="user" @selected($role === 'user')>User</option>
                    </select>

                    <label for="status" class="sr-only">Filter by status</label>
                    <select id="status" name="status" class="px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                        <option value="all" @selected($status === 'all' || $status === '')>All Status</option>
                        <option value="active" @selected($status === 'active')>Active</option>
                        <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-xl border border-[#E2E8F0] text-sm font-medium text-[#64748B] hover:bg-[#F8FAFC]">
                        Apply
                    </button>
                    @if ($search !== '' || in_array($role, ['admin', 'user'], true) || in_array($status, ['active', 'inactive'], true))
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-[#94A3B8] hover:text-[#64748B]">Reset</a>
                    @endif

                    <button
                        type="button"
                        data-modal-open="add-user-modal"
                        class="ml-auto flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Add User
                    </button>
                </div>
            </form>

            {{-- User list --}}
            <div class="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
                                <th class="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-5 py-3">User</th>
                                <th class="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3 hidden md:table-cell">Email</th>
                                <th class="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3">Role</th>
                                <th class="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3">Status</th>
                                <th class="text-left text-xs font-semibold text-[#94A3B8] uppercase tracking-wide px-3 py-3 hidden lg:table-cell">Member since</th>
                                <th class="px-3 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-[#F1F5F9] last:border-0 hover:bg-[#F8FAFC]">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <x-avatar :name="$user->name" size="sm" :muted="! $user->is_active" />
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-[#1E293B] hover:text-[#0BC5C1]">
                                                {{ $user->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3.5 hidden md:table-cell">
                                        <span class="text-sm text-[#64748B]">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <x-role-badge :role="$user->role" />
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <x-status-badge :active="$user->is_active" />
                                    </td>
                                    <td class="px-3 py-3.5 hidden lg:table-cell">
                                        <span class="text-sm text-[#94A3B8]">{{ $user->created_at?->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <div class="flex items-center gap-1 justify-end">
                                            <a
                                                href="{{ route('admin.users.show', $user) }}"
                                                class="p-1.5 rounded-lg hover:bg-[#F1F5F9] text-[#94A3B8] hover:text-[#64748B]"
                                                aria-label="View {{ $user->name }}"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5" aria-hidden="true">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>
                                            @unless ($user->isAdmin())
                                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        class="p-1.5 rounded-lg {{ $user->is_active ? 'hover:bg-red-50 text-[#94A3B8] hover:text-red-500' : 'hover:bg-emerald-50 text-[#94A3B8] hover:text-emerald-500' }}"
                                                        aria-label="{{ $user->is_active ? 'Deactivate' : 'Reactivate' }} {{ $user->name }}"
                                                    >
                                                        @if ($user->is_active)
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5" aria-hidden="true">
                                                                <circle cx="12" cy="12" r="10" /><line x1="15" y1="9" x2="9" y2="15" /><line x1="9" y1="9" x2="15" y2="15" />
                                                            </svg>
                                                        @else
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5" aria-hidden="true">
                                                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14" /><path d="M22 4L12 14.01l-3-3" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>
                                            @endunless
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <p class="text-sm text-[#94A3B8]">No users match your filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @else
            {{-- Activity log --}}
            <div class="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden">
                <div class="px-5 py-3 border-b border-[#E2E8F0] bg-[#F8FAFC]">
                    <h3 class="font-display font-bold text-sm text-[#1E293B]">Admin Activity Log</h3>
                </div>
                <div class="divide-y divide-[#F1F5F9]">
                    @forelse ($activityLogs as $log)
                        <div class="px-5 py-4 flex items-start gap-4">
                            <x-avatar :name="$log->user?->name ?? 'Admin'" size="sm" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-[#1E293B]">
                                    <span class="font-semibold">{{ $log->user?->name ?? 'Admin' }}</span>
                                    <span class="text-[#64748B]"> {{ $log->action }}:</span>
                                    <span class="text-[#0BC5C1]">{{ $log->targetUser?->name ?? $log->description }}</span>
                                </p>
                                <p class="text-xs text-[#94A3B8] mt-0.5">{{ $log->created_at?->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-sm text-[#94A3B8]">No admin activity recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $activityLogs?->links() }}
            </div>
        @endif
    </div>

    {{-- Add user modal --}}
    <div
        id="add-user-modal"
        class="fixed inset-0 bg-black/40 z-50 {{ $errors->any() ? '' : 'hidden' }} items-center justify-center p-4"
        data-auto-open="{{ $errors->any() ? 'add-user-modal' : '' }}"
        data-modal-close="add-user-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="add-user-title"
    >
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6 max-h-[90vh] overflow-y-auto">
            <h2 id="add-user-title" class="font-display font-bold text-lg text-[#1E293B] mb-5">Add New User</h2>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4" data-submit>
                @csrf

                <x-form.input label="Full Name *" name="name" placeholder="Full name" autocomplete="off" required />
                <x-form.input label="Email *" name="email" type="email" placeholder="user@example.com" autocomplete="off" required />
                <x-form.password-input label="Password *" name="password" placeholder="Min. 8 characters" autocomplete="new-password" required />
                <x-form.password-input label="Confirm Password *" name="password_confirmation" placeholder="Repeat the password" autocomplete="new-password" required />

                <div class="space-y-1.5">
                    <span class="block text-xs font-semibold text-[#475569] uppercase tracking-wide">Role</span>
                    <div class="flex gap-2">
                        @foreach (['user' => 'User', 'admin' => 'Admin'] as $value => $label)
                            <label class="flex-1">
                                <input type="radio" name="role" value="{{ $value }}" class="peer sr-only" @checked(old('role', 'user') === $value)>
                                <span class="block text-center py-2 rounded-xl text-sm font-medium border transition-all cursor-pointer border-[#E2E8F0] text-[#64748B] hover:bg-[#F8FAFC] peer-checked:bg-[#0BC5C1] peer-checked:text-white peer-checked:border-[#0BC5C1] peer-focus-visible:ring-2 peer-focus-visible:ring-[#0BC5C1]/30">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="text-xs text-red-500">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button
                        type="button"
                        data-modal-close="add-user-modal"
                        data-modal-close-always
                        class="flex-1 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]"
                    >
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
