@props([
    'members' => [],
])

<div class="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
    <h3 class="font-bold text-sm text-[#1E293B] mb-5" style="font-family: 'Nunito', sans-serif">Team Progress</h3>

    @php
        $maxTasks = 0;
        foreach($members as $m) {
            $maxTasks = max($maxTasks, $m['completed'] + $m['in_progress']);
        }
        if($maxTasks === 0) $maxTasks = 1;
    @endphp

    <div class="space-y-4">
        @foreach($members as $member)
            @php
                $completedWidth = ($member['completed'] / $maxTasks) * 100;
                $inProgressWidth = ($member['in_progress'] / $maxTasks) * 100;
                $totalTasks = $member['completed'] + $member['in_progress'] + ($member['not_started'] ?? 0);
                $percentage = $totalTasks > 0 ? round(($member['completed'] / $totalTasks) * 100) : 0;
            @endphp
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background: {{ $member['color'] }}">
                    {{ $member['initials'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-[#1E293B]">{{ $member['name'] }}</span>
                        <span class="text-xs text-[#94A3B8]">{{ $percentage }}%</span>
                    </div>
                    <div class="flex h-2 gap-0.5">
                        <div class="rounded-full transition-all duration-500" style="width: {{ $completedWidth }}%; background: #0BC5C1"></div>
                        <div class="rounded-full transition-all duration-500" style="width: {{ $inProgressWidth }}%; background: #E2E8F0"></div>
                    </div>
                    <div class="flex gap-3 mt-1">
                        <span class="text-[10px] text-[#94A3B8]">{{ $member['completed'] }} done</span>
                        <span class="text-[10px] text-[#94A3B8]">{{ $member['in_progress'] }} active</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex gap-4 mt-4">
        <div class="flex items-center gap-1.5">
            <div class="w-2 h-2 rounded-full" style="background: #0BC5C1"></div>
            <span class="text-xs text-[#94A3B8]">Completed</span>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="w-2 h-2 rounded-full" style="background: #E2E8F0"></div>
            <span class="text-xs text-[#94A3B8]">In Progress</span>
        </div>
    </div>
</div>
