@props([
    'active',
])

<span {{ $attributes->merge(['class' => 'text-xs px-2.5 py-1 rounded-full font-medium inline-flex items-center gap-1 w-fit '.($active ? 'bg-[#ECFDF5] text-[#065F46]' : 'bg-[#F1F5F9] text-[#64748B]')]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $active ? 'bg-[#10B981]' : 'bg-[#94A3B8]' }}"></span>
    {{ $active ? 'Active' : 'Inactive' }}
</span>
