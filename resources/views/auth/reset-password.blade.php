@extends('layouts.auth')

@section('title', 'Set new password')

@section('content')
    @if (session('status'))
        <x-alert type="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <div class="mb-6">
        <a href="{{ route('login') }}" class="flex items-center gap-1 text-sm text-[#64748B] hover:text-[#0BC5C1] mb-4 w-fit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4" aria-hidden="true">
                <path d="M19 12H5M12 5l-7 7 7 7" />
            </svg>
            Back to login
        </a>
        <h1 class="font-display font-extrabold text-2xl text-[#1E293B]">Set new password</h1>
        <p class="text-sm text-[#64748B] mt-1">Choose a strong password for your account</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4" data-submit>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-form.input
            label="Email"
            name="email"
            type="email"
            :value="$email"
            autocomplete="username"
            required
        />

        <x-form.password-input
            label="New Password"
            name="password"
            placeholder="Min. 8 characters"
            autocomplete="new-password"
            required
            autofocus
        />

        <x-form.password-input
            label="Confirm Password"
            name="password_confirmation"
            placeholder="Repeat your new password"
            autocomplete="new-password"
            required
        />

        <button
            type="submit"
            class="w-full py-2.5 rounded-xl bg-[#0BC5C1] text-white font-semibold text-sm hover:bg-[#0AAEAA] active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            Reset password
        </button>
    </form>
@endsection
