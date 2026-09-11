@props([
    'label',
    'name',
    'value' => '',
    'placeholder' => '••••••••',
    'required' => false,
    'autocomplete' => 'new-password',
])

@php
    $hasError = $errors->has($name);
@endphp

<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-xs font-semibold text-[#475569] uppercase tracking-wide">
        {{ $label }}
    </label>
    <div class="relative">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="password"
            value="{{ old($name) }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if ($required) required @endif
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            {{ $attributes->merge([
                'class' => 'w-full px-4 py-2.5 pr-10 rounded-xl border text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] '
                    .($hasError ? 'border-red-400 bg-red-50' : 'border-[#E2E8F0] bg-white hover:border-[#CBD5E1]'),
            ]) }}
        />
        <button
            type="button"
            data-toggle-password="{{ $name }}"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94A3B8] hover:text-[#64748B]"
            aria-label="Toggle password visibility"
            aria-pressed="false"
        >
            <svg data-eye class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            <svg data-eye-off class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22" />
            </svg>
        </button>
    </div>
    @error($name)
        <p id="{{ $name }}-error" class="text-xs text-red-500 flex items-center gap-1">
            <span aria-hidden="true">⚠</span>{{ $message }}
        </p>
    @enderror
</div>
