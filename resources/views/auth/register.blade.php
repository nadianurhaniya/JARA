@extends('layouts.auth')

@section('title', 'Create account')

@section('content')
    <div class="mb-6">
        <h1 class="font-display font-extrabold text-2xl text-[#1E293B]">Create account</h1>
        <p class="text-sm text-[#64748B] mt-1">Join JARA and start managing tasks</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4" data-submit>
        @csrf

        <x-form.input
            label="Full Name"
            name="name"
            :value="old('name')"
            placeholder="Your full name"
            autocomplete="name"
            required
            autofocus
        />

        <x-form.input
            label="Email"
            name="email"
            type="email"
            placeholder="you@company.com"
            autocomplete="username"
            required
        />

        <x-form.password-input
            label="Password"
            name="password"
            placeholder="Min. 8 characters"
            autocomplete="new-password"
            required
        />

        <x-form.password-input
            label="Confirm Password"
            name="password_confirmation"
            placeholder="Repeat your password"
            autocomplete="new-password"
            required
        />

        <button
            type="submit"
            class="w-full py-2.5 rounded-xl bg-[#0BC5C1] text-white font-semibold text-sm hover:bg-[#0AAEAA] active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            Create account
        </button>
    </form>

    <p class="text-center text-sm text-[#64748B] mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="text-[#0BC5C1] font-semibold hover:underline">Sign in</a>
    </p>
@endsection
