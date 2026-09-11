@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')
    @if (session('status'))
        <x-alert type="success" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <div class="mb-6">
        <h1 class="font-display font-extrabold text-2xl text-[#1E293B]">Welcome back</h1>
        <p class="text-sm text-[#64748B] mt-1">Sign in to your JARA account</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}" class="space-y-4" data-submit>
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

        <x-form.password-input
            label="Password"
            name="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
        />

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-[#64748B]">
                <input type="checkbox" name="remember" value="1" class="rounded border-[#CBD5E1] text-[#0BC5C1] focus:ring-[#0BC5C1]/30">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="text-xs text-[#0BC5C1] hover:underline">Forgot password?</a>
        </div>

        <button
            type="submit"
            class="w-full py-2.5 rounded-xl bg-[#0BC5C1] text-white font-semibold text-sm hover:bg-[#0AAEAA] active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            Sign in
        </button>
    </form>

    <p class="text-center text-sm text-[#64748B] mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-[#0BC5C1] font-semibold hover:underline">Sign up</a>
    </p>
@endsection
