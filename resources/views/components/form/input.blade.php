@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'autocomplete' => 'off',
    'autofocus' => false,
])

@php
    $hasError = $errors->has($name);
@endphp

<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-xs font-semibold text-[#475569] uppercase tracking-wide">
        {{ $label }}
    </label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        autocomplete="{{ $autocomplete }}"
        @if ($required) required @endif
        @if ($autofocus) autofocus @endif
        @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 rounded-xl border text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] '
                .($hasError ? 'border-red-400 bg-red-50' : 'border-[#E2E8F0] bg-white hover:border-[#CBD5E1]'),
        ]) }}
    />
    @error($name)
        <p id="{{ $name }}-error" class="text-xs text-red-500 flex items-center gap-1">
            <span aria-hidden="true">⚠</span>{{ $message }}
        </p>
    @enderror
</div>
