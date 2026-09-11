@extends('layouts.auth')

@section('title', 'Reset password')

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
        <h1 class="font-display font-extrabold text-2xl text-[#1E293B]">Reset password</h1>
        <p class="text-sm text-[#64748B] mt-1">We'll send a reset link to your email</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4" data-submit>
        @csrf

        <x-form.input
            label="Email"
            name="email"
            type="email"
            placeholder="you@company.com"
            autocomplete="username"
            required
            autofocus
        />

        <button
            type="submit"
            class="w-full py-2.5 rounded-xl bg-[#0BC5C1] text-white font-semibold text-sm hover:bg-[#0AAEAA] active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            Send reset link
        </button>
    </form>
@endsection
