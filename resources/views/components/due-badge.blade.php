@props(['task'])

@php($status = $task->dueStatus())

@if ($status === 'overdue')
    <span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700']) }}>
        Terlambat
    </span>
@elseif ($status === 'due_soon')
    <span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700']) }}>
        Jatuh Tempo Segera
    </span>
@endif
