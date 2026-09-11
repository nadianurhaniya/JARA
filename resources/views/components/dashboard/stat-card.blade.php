@props([
    'label',
    'value',
    'color' => '#0BC5C1',
    'subtitle' => null,
])

<div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] hover:shadow-md transition-all duration-200">
    <div class="flex items-start justify-between mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $color }}18">
            <span style="color: {{ $color }}">
                {{ $slot }}
            </span>
        </div>
    </div>
    <p class="text-2xl font-bold text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $value }}</p>
    <p class="text-sm text-[#64748B] mt-0.5">{{ $label }}</p>
    @if($subtitle)
        <p class="text-xs text-[#94A3B8] mt-1">{{ $subtitle }}</p>
    @endif
</div>
