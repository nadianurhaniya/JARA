@props([
    'completed' => 12,
    'inProgress' => 7,
    'notStarted' => 5,
])

@php
    $total = $completed + $inProgress + $notStarted;
    $completedPct = $total > 0 ? round(($completed / $total) * 100) : 0;
    $inProgressPct = $total > 0 ? round(($inProgress / $total) * 100) : 0;
    $notStartedPct = $total > 0 ? round(($notStarted / $total) * 100) : 0;

    $radius = 45;
    $circumference = 2 * M_PI * $radius;

    // Calculate stroke-dashoffset for each segment
    $completedOffset = $circumference * (1 - $completedPct / 100);
    $inProgressOffset = $circumference * (1 - $inProgressPct / 100);
    $notStartedOffset = $circumference * (1 - $notStartedPct / 100);

    // Calculate rotation offsets
    $completedRotation = 0;
    $inProgressRotation = ($completedPct / 100) * 360;
    $notStartedRotation = (($completedPct + $inProgressPct) / 100) * 360;
@endphp

<div class="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
    <h3 class="font-bold text-sm text-[#1E293B] mb-5" style="font-family: 'Nunito', sans-serif">Task Status Distribution</h3>
    <div class="flex justify-center mb-4">
        <div class="relative w-40 h-40">
            <svg viewBox="0 0 100 100" class="w-full h-full -rotate-90">
                <!-- Background circle -->
                <circle cx="50" cy="50" r="{{ $radius }}" fill="none" stroke="#E2E8F0" stroke-width="12" />

                <!-- Not Started (gray) -->
                <circle
                    cx="50" cy="50" r="{{ $radius }}" fill="none"
                    stroke="#94A3B8" stroke-width="12"
                    stroke-dasharray="{{ $circumference }}"
                    stroke-dashoffset="{{ $notStartedOffset }}"
                    transform="rotate({{ $notStartedRotation }} 50 50)"
                    class="transition-all duration-700"
                />

                <!-- In Progress (cyan) -->
                <circle
                    cx="50" cy="50" r="{{ $radius }}" fill="none"
                    stroke="#0BC5C1" stroke-width="12"
                    stroke-dasharray="{{ $circumference }}"
                    stroke-dashoffset="{{ $inProgressOffset }}"
                    transform="rotate({{ $inProgressRotation }} 50 50)"
                    class="transition-all duration-700"
                />

                <!-- Completed (green) -->
                <circle
                    cx="50" cy="50" r="{{ $radius }}" fill="none"
                    stroke="#10B981" stroke-width="12"
                    stroke-dasharray="{{ $circumference }}"
                    stroke-dashoffset="{{ $completedOffset }}"
                    transform="rotate({{ $completedRotation }} 50 50)"
                    class="transition-all duration-700"
                />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="font-bold text-2xl text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $total }}</span>
                <span class="text-xs text-[#94A3B8]">tasks</span>
            </div>
        </div>
    </div>
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full" style="background: #10B981"></div>
                <span class="text-xs text-[#64748B]">Completed</span>
            </div>
            <span class="text-xs font-semibold text-[#1E293B]">{{ $completed }} tasks</span>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full" style="background: #0BC5C1"></div>
                <span class="text-xs text-[#64748B]">In Progress</span>
            </div>
            <span class="text-xs font-semibold text-[#1E293B]">{{ $inProgress }} tasks</span>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full" style="background: #94A3B8"></div>
                <span class="text-xs text-[#64748B]">Not Started</span>
            </div>
            <span class="text-xs font-semibold text-[#1E293B]">{{ $notStarted }} tasks</span>
        </div>
    </div>
</div>
