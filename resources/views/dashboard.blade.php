@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Your task overview and account activity')

@section('content')
    @php
        $user = auth()->user();
        $firstName = explode(' ', trim($user->name))[0] ?? $user->name;
        $cards = [
            ['label' => 'Role', 'value' => $user->role->label(), 'hint' => $user->isAdmin() ? 'Full system access' : 'Standard member access'],
            ['label' => 'Account status', 'value' => $user->is_active ? 'Active' : 'Inactive', 'hint' => 'Account standing'],
            ['label' => 'Member since', 'value' => $user->created_at?->format('d M Y'), 'hint' => 'Registration date'],
            ['label' => 'Email', 'value' => $user->email, 'hint' => 'Sign-in address'],
        ];
    @endphp

    <div class="p-4 lg:p-6 max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 lg:p-8 mb-6">
            <div class="flex items-center gap-4">
                <x-avatar :name="$user->name" size="lg" />
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-[#1E293B]">Welcome back, {{ $firstName }}</h2>
                    <p class="text-sm text-[#64748B] mt-1">
                        You're signed in to JARA. Task, project and collaboration modules are on the way.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-display font-bold text-lg text-[#1E293B]">Team Collaboration</h3>
                <p class="text-sm text-[#64748B] mt-1">Lihat proyek, kelola anggota, undangan, dan assignment tugas.</p>
            </div>
            <a
                href="{{ route('jara.app') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA] shrink-0"
            >
                Buka Kolaborasi
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            @foreach ($cards as $card)
                <div class="bg-white rounded-2xl border border-[#E2E8F0] p-5">
                    <p class="text-xs font-semibold text-[#94A3B8] uppercase tracking-wide">{{ $card['label'] }}</p>
                    <p class="font-display font-extrabold text-xl text-[#1E293B] mt-2 break-words">{{ $card['value'] }}</p>
                    <p class="text-xs text-[#94A3B8] mt-1">{{ $card['hint'] }}</p>
                </div>
            @endforeach
        </div>

        @if ($user->isAdmin() && Route::has('admin.users.index'))
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-display font-bold text-lg text-[#1E293B]">Administrator tools</h3>
                    <p class="text-sm text-[#64748B] mt-1">Manage accounts, roles, and review audit activity.</p>
                </div>
                <a
                    href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA] shrink-0"
                >
                    Manage users
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
@endsection
