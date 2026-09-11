@props(['task'])

@php($status = $task->dueStatus())

@if ($status === 'overdue')
    <span {{ $attributes->merge(['class' => 'text-xs px-2.5 py-1 rounded-full font-medium bg-[#FEE2E2] text-[#DC2626]']) }}>
        Terlambat
    </span>
@elseif ($status === 'due_soon')
    <span {{ $attributes->merge(['class' => 'text-xs px-2.5 py-1 rounded-full font-medium bg-[#FEF3C7] text-[#D97706]']) }}>
        Jatuh Tempo Segera
    </span>
@endif
