@props([
    'name',
    'size' => 'md',
    'muted' => false,
])

@php
    $initials = collect(preg_split('/\s+/', trim((string) $name)))
        ->filter()
        ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->take(2)
        ->implode('');

    $sizes = [
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-16 h-16 text-xl',
    ];
@endphp

<div
    {{ $attributes->merge([
        'class' => 'rounded-full bg-[#0BC5C1] flex items-center justify-center text-white font-bold shrink-0 '
            .($sizes[$size] ?? $sizes['md']).($muted ? ' opacity-50' : ''),
    ]) }}
    aria-hidden="true"
>{{ $initials }}</div>
