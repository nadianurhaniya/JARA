@props([
    'projects' => [],
])

<div class="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
    <h3 class="font-bold text-sm text-[#1E293B] mb-4" style="font-family: 'Nunito', sans-serif">Project Overview</h3>
    <div class="space-y-4">
        @foreach($projects as $project)
            @php
                $totalTasks = $project['total_tasks'] ?? 0;
                $completedTasks = $project['completed_tasks'] ?? 0;
                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                $daysLeft = ceil((strtotime($project['deadline']) - time()) / 86400);
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background: {{ $project['color'] }}"></div>
                        <span class="text-sm font-medium text-[#1E293B]">{{ $project['name'] }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-[#94A3B8]">{{ $completedTasks }}/{{ $totalTasks }} tasks</span>
                        <span class="text-xs font-medium {{ $daysLeft < 30 ? 'text-orange-500' : 'text-[#64748B]' }}">{{ $daysLeft }}d left</span>
                        <span class="font-bold text-sm text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $percentage }}%</span>
                    </div>
                </div>
                <div class="h-2 bg-[#F1F5F9] rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700" style="width: {{ $percentage }}%; background: {{ $project['color'] }}"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
