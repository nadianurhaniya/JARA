@props([
    'type' => 'success',
])

@php
    $styles = [
        'success' => ['wrapper' => 'bg-emerald-50 border-emerald-200', 'text' => 'text-emerald-700', 'icon' => 'text-emerald-500', 'symbol' => '✓'],
        'error' => ['wrapper' => 'bg-red-50 border-red-200', 'text' => 'text-red-700', 'icon' => 'text-red-500', 'symbol' => '!'],
        'info' => ['wrapper' => 'bg-[#E8F9F9] border-[#0BC5C1]/30', 'text' => 'text-[#0A8F8C]', 'icon' => 'text-[#0BC5C1]', 'symbol' => 'i'],
    ][$type] ?? ['wrapper' => 'bg-slate-50 border-slate-200', 'text' => 'text-slate-700', 'icon' => 'text-slate-500', 'symbol' => 'i'];
@endphp

<div {{ $attributes->merge(['class' => "p-3 border rounded-xl flex items-start gap-2 {$styles['wrapper']}"]) }} role="status">
    <span class="font-bold {{ $styles['icon'] }}">{{ $styles['symbol'] }}</span>
    <p class="text-sm {{ $styles['text'] }}">{{ $slot }}</p>
</div>
