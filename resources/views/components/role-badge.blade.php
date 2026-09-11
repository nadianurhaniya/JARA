@props([
    'role',
])

@php
    $isAdmin = $role === \App\Enums\UserRole::Admin;
@endphp

<span {{ $attributes->merge(['class' => 'text-xs px-2.5 py-1 rounded-full font-medium '.($isAdmin ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-[#E8F9F9] text-[#0BC5C1]')]) }}>
    {{ $role->label() }}
</span>
