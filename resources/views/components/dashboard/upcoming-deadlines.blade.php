@props([
    'deadlines' => [],
])

@php
    function daysUntil($date) {
        $diff = strtotime($date) - time();
        $days = ceil($diff / 86400);
        return $days;
    }
@endphp

<div class="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
    <h3 class="font-bold text-sm text-[#1E293B] mb-4" style="font-family: 'Nunito', sans-serif">Upcoming Deadlines</h3>
    <div class="space-y-3">
        @foreach($deadlines as $deadline)
            @php
                $days = daysUntil($deadline['date']);
            @endphp
            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-[#F8FAFC] transition-colors">
                <div class="w-2 h-2 rounded-full shrink-0" style="background: {{ $deadline['priority_color'] }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-[#1E293B] truncate">{{ $deadline['title'] }}</p>
                    <p class="text-xs text-[#94A3B8] truncate">{{ $deadline['project'] }}</p>
                </div>
                <div class="shrink-0">
                    @if($days < 0)
                        <span class="text-red-500 text-xs font-medium">Overdue {{ abs($days) }}d</span>
                    @elseif($days === 0)
                        <span class="text-orange-500 text-xs font-medium">Due today</span>
                    @elseif($days <= 3)
                        <span class="text-orange-400 text-xs font-medium">Due in {{ $days }}d</span>
                    @else
                        <span class="text-[#94A3B8] text-xs">{{ $days }}d left</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
