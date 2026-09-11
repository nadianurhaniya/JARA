@extends('layouts.app')

@section('page-title', 'User Details')
@section('page-subtitle', $user->name)

@section('content')
    <div class="p-4 lg:p-6 max-w-3xl mx-auto">
        @if (session('status'))
            <x-alert type="success" class="mb-5">{{ session('status') }}</x-alert>
        @endif

        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-[#64748B] hover:text-[#0BC5C1] mb-5">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4" aria-hidden="true">
                <path d="M19 12H5M12 5l-7 7 7 7" />
            </svg>
            Back to user list
        </a>

        <div class="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden">
            <div class="p-6 text-center border-b border-[#E2E8F0]">
                <x-avatar :name="$user->name" size="lg" class="mx-auto mb-3" />
                <h2 class="font-display font-bold text-lg text-[#1E293B]">{{ $user->name }}</h2>
                <p class="text-sm text-[#94A3B8]">{{ $user->email }}</p>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <x-role-badge :role="$user->role" />
                    <x-status-badge :active="$user->is_active" />
                </div>
            </div>

            <div class="p-6 space-y-3">
                @php
                    $details = [
                        ['label' => 'Member since', 'value' => $user->created_at?->format('d M Y, H:i')],
                        ['label' => 'Last updated', 'value' => $user->updated_at?->format('d M Y, H:i')],
                        ['label' => 'Email verified', 'value' => $user->email_verified_at ? 'Yes' : 'No'],
                    ];
                @endphp

                @foreach ($details as $detail)
                    <div class="flex justify-between gap-4">
                        <span class="text-sm text-[#94A3B8]">{{ $detail['label'] }}</span>
                        <span class="text-sm font-medium text-[#1E293B] text-right">{{ $detail['value'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="px-6 pb-6 flex flex-col sm:flex-row gap-3">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex-1 text-center py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] hover:bg-[#F8FAFC]"
                >
                    Close
                </a>
                @unless ($user->isAdmin())
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="w-full py-2.5 rounded-xl text-sm font-semibold {{ $user->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-[#ECFDF5] text-[#065F46] hover:bg-emerald-100' }}"
                        >
                            {{ $user->is_active ? 'Deactivate' : 'Reactivate' }}
                        </button>
                    </form>
                @endunless
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden mt-6">
            <div class="px-5 py-3 border-b border-[#E2E8F0] bg-[#F8FAFC]">
                <h3 class="font-display font-bold text-sm text-[#1E293B]">Account Activity</h3>
            </div>
            <div class="divide-y divide-[#F1F5F9]">
                @forelse ($logs as $log)
                    <div class="px-5 py-4 flex items-start gap-4">
                        <x-avatar :name="$log->user?->name ?? 'Admin'" size="sm" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-[#1E293B]">
                                <span class="font-semibold">{{ $log->user?->name ?? 'Admin' }}</span>
                                <span class="text-[#64748B]"> {{ $log->action }}</span>
                            </p>
                            <p class="text-xs text-[#94A3B8] mt-0.5">{{ $log->created_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-sm text-[#94A3B8]">No activity recorded for this account.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
