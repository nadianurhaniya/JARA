@props([
    'priorities' => [],
])

@php
    $total = 0;
    foreach($priorities as $p) {
        $total += $p['value'];
    }
@endphp

<div {{ $attributes->class('bg-white rounded-2xl p-6 border border-[#E2E8F0]') }}>
    <h3 class="font-bold text-sm text-[#1E293B] mb-5" style="font-family: 'Nunito', sans-serif">Priority Distribution</h3>

    <!-- Donut Chart -->
    @php
        $radius = 45;
        $circumference = 2 * M_PI * $radius;
        $offsets = [];
        $rotations = [];
        $cumulative = 0;

        foreach($priorities as $i => $p) {
            $pct = $total > 0 ? ($p['value'] / $total) * 100 : 0;
            $offsets[$i] = $circumference * (1 - $pct / 100);
            $rotations[$i] = ($cumulative / 100) * 360;
            $cumulative += $pct;
        }
    @endphp

    <div class="flex justify-center mb-4">
        <div class="relative w-36 h-36">
            <svg viewBox="0 0 100 100" class="w-full h-full -rotate-90">
                <circle cx="50" cy="50" r="{{ $radius }}" fill="none" stroke="#E2E8F0" stroke-width="12" />
                @foreach($priorities as $i => $p)
                    <circle
                        cx="50" cy="50" r="{{ $radius }}" fill="none"
                        stroke="{{ $p['color'] }}" stroke-width="12"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $offsets[$i] }}"
                        transform="rotate({{ $rotations[$i] }} 50 50)"
                        class="transition-all duration-700"
                    />
                @endforeach
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="font-bold text-xl text-[#1E293B]" style="font-family: 'Nunito', sans-serif">{{ $total }}</span>
                <span class="text-[10px] text-[#94A3B8]">tasks</span>
            </div>
        </div>
    </div>

    <div class="space-y-2">
        @foreach($priorities as $p)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full" style="background: {{ $p['color'] }}"></div>
                    <span class="text-xs text-[#64748B]">{{ $p['name'] }}</span>
                </div>
                <span class="text-xs font-semibold text-[#1E293B]">{{ $p['value'] }} tasks</span>
            </div>
        @endforeach
    </div>
</div>
