@props([
    'activities' => [],
])

<div class="bg-white rounded-2xl p-6 border border-[#E2E8F0]">
    <h3 class="font-bold text-sm text-[#1E293B] mb-4" style="font-family: 'Nunito', sans-serif">Recent Activity</h3>
    <div class="space-y-3">
        @foreach($activities as $activity)
            <div class="flex gap-3">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background: {{ $activity['color'] }}">
                    {{ $activity['initial'] }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-[#1E293B] leading-relaxed">
                        <span class="font-semibold">{{ $activity['user'] }}</span>
                        {{ $activity['action'] }}
                        <span style="color: #0BC5C1" class="font-medium">"{{ $activity['task'] }}"</span>
                    </p>
                    <p class="text-xs text-[#94A3B8] mt-0.5">{{ $activity['time'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
