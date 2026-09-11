@props([
    'percentage' => 50,
    'completed' => 12,
    'inProgress' => 7,
    'notStarted' => 5,
])

@php
    $radius = 52;
    $circumference = 2 * M_PI * $radius;
    $offset = $circumference * (1 - $percentage / 100);
@endphp

<div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] flex flex-col items-center justify-center">
    <p class="font-bold text-sm text-[#64748B] uppercase tracking-wide mb-4" style="font-family: 'Nunito', sans-serif">Overall Progress</p>
    <div class="relative w-36 h-36">
        <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
            <circle cx="60" cy="60" r="{{ $radius }}" fill="none" stroke="#E2E8F0" stroke-width="10" />
            <circle
                cx="60" cy="60" r="{{ $radius }}" fill="none"
                stroke="#0BC5C1" stroke-width="10"
                stroke-linecap="round"
                stroke-dasharray="{{ $circumference }}"
                stroke-dashoffset="{{ $offset }}"
                class="transition-all duration-700"
            />
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="font-bold text-3xl text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $percentage }}%</span>
            <span class="text-xs text-[#94A3B8]">complete</span>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-3 mt-5 w-full">
        <div class="text-center">
            <div class="w-2 h-2 rounded-full mx-auto mb-1" style="background: #10B981"></div>
            <p class="font-bold text-sm text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $completed }}</p>
            <p class="text-xs text-[#94A3B8]">Done</p>
        </div>
        <div class="text-center">
            <div class="w-2 h-2 rounded-full mx-auto mb-1" style="background: #0BC5C1"></div>
            <p class="font-bold text-sm text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $inProgress }}</p>
            <p class="text-xs text-[#94A3B8]">Active</p>
        </div>
        <div class="text-center">
            <div class="w-2 h-2 rounded-full mx-auto mb-1" style="background: #94A3B8"></div>
            <p class="font-bold text-sm text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $notStarted }}</p>
            <p class="text-xs text-[#94A3B8]">Pending</p>
        </div>
    </div>
</div>
