@props(['priority'])

@php
    $styles = match ($priority) {
        \App\Enums\TaskPriority::High => 'bg-[#FEE2E2] text-[#DC2626]',
        \App\Enums\TaskPriority::Medium => 'bg-[#FEF3C7] text-[#D97706]',
        \App\Enums\TaskPriority::Low => 'bg-[#ECFDF5] text-[#065F46]',
    };
@endphp

<span {{ $attributes->merge(['class' => "text-xs px-2.5 py-1 rounded-full font-medium {$styles}"]) }}>
    {{ $priority->label() }}
</span>
