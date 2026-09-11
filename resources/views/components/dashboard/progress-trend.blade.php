@props([
    'weeklyData' => [],
    'monthlyData' => [],
])

<div {{ $attributes->class('bg-white rounded-2xl p-6 border border-[#E2E8F0]') }} x-data="{ timeRange: 'weekly' }">
    <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-sm text-[#1E293B]" style="font-family: 'Nunito', sans-serif">Task Completion Trend</h3>
        <div class="flex bg-[#F1F5F9] rounded-lg p-0.5">
            <button
                @click="timeRange = 'weekly'"
                :class="timeRange === 'weekly' ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8]'"
                class="px-3 py-1 rounded-md text-xs font-medium transition-all"
            >Weekly</button>
            <button
                @click="timeRange = 'monthly'"
                :class="timeRange === 'monthly' ? 'bg-white text-[#1E293B] shadow-sm' : 'text-[#94A3B8]'"
                class="px-3 py-1 rounded-md text-xs font-medium transition-all"
            >Monthly</button>
        </div>
    </div>

    <!-- Weekly Chart -->
    <div x-show="timeRange === 'weekly'" class="relative">
        @php
            $maxValue = 0;
            foreach($weeklyData as $d) {
                $maxValue = max($maxValue, $d['completed'], $d['created']);
            }
            if($maxValue === 0) $maxValue = 1;
        @endphp
        <div class="flex items-end justify-between h-48 px-2">
            @foreach($weeklyData as $i => $d)
                @php
                    $completedHeight = ($d['completed'] / $maxValue) * 100;
                    $createdHeight = ($d['created'] / $maxValue) * 100;
                @endphp
                <div class="flex flex-col items-center gap-1 flex-1">
                    <div class="flex items-end gap-0.5 h-40">
                        <div
                            class="w-4 rounded-t-md transition-all duration-500"
                            style="height: {{ $completedHeight }}%; background: #0BC5C1;"
                            title="Completed: {{ $d['completed'] }}"
                        ></div>
                        <div
                            class="w-4 rounded-t-md transition-all duration-500"
                            style="height: {{ $createdHeight }}%; background: #8B5CF6;"
                            title="Created: {{ $d['created'] }}"
                        ></div>
                    </div>
                    <span class="text-[10px] text-[#94A3B8]">{{ $d['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Monthly Chart -->
    <div x-show="timeRange === 'monthly'" class="relative">
        @php
            $maxValue = 0;
            foreach($monthlyData as $d) {
                $maxValue = max($maxValue, $d['completed'], $d['created']);
            }
            if($maxValue === 0) $maxValue = 1;
        @endphp
        <div class="flex items-end justify-between h-48 px-2">
            @foreach($monthlyData as $i => $d)
                @php
                    $completedHeight = ($d['completed'] / $maxValue) * 100;
                    $createdHeight = ($d['created'] / $maxValue) * 100;
                @endphp
                <div class="flex flex-col items-center gap-1 flex-1">
                    <div class="flex items-end gap-0.5 h-40">
                        <div
                            class="w-5 rounded-t-md transition-all duration-500"
                            style="height: {{ $completedHeight }}%; background: #0BC5C1;"
                            title="Completed: {{ $d['completed'] }}"
                        ></div>
                        <div
                            class="w-5 rounded-t-md transition-all duration-500"
                            style="height: {{ $createdHeight }}%; background: #8B5CF6;"
                            title="Created: {{ $d['created'] }}"
                        ></div>
                    </div>
                    <span class="text-[10px] text-[#94A3B8]">{{ $d['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex gap-4 mt-4">
        <div class="flex items-center gap-1.5">
            <div class="w-2 h-2 rounded-full" style="background: #0BC5C1"></div>
            <span class="text-xs text-[#94A3B8]">Completed</span>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="w-2 h-2 rounded-full" style="background: #8B5CF6"></div>
            <span class="text-xs text-[#94A3B8]">Created</span>
        </div>
    </div>
</div>
