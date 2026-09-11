@extends('layouts.app')

@section('title', $taskList->name)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('task-lists.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Daftar/Proyek Saya</a>
            <h1 class="mt-1 text-xl font-semibold text-gray-900">{{ $taskList->name }}</h1>
        </div>
        <a
            href="{{ route('task-lists.tasks.create', $taskList) }}"
            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
        >
            + Tugas Baru
        </a>
    </div>

    <form method="GET" class="mb-6 flex flex-wrap items-center gap-3 text-sm" onchange="this.submit()">
        <select name="sort" class="rounded-md border border-gray-300 px-3 py-1.5">
            <option value="priority" @selected($sort !== 'due_date')>Urutkan: Prioritas</option>
            <option value="due_date" @selected($sort === 'due_date')>Urutkan: Tenggat Waktu</option>
        </select>

        <select name="direction" class="rounded-md border border-gray-300 px-3 py-1.5">
            <option value="asc" @selected($direction === 'asc')>Naik</option>
            <option value="desc" @selected($direction === 'desc')>Turun</option>
        </select>

        <select name="status" class="rounded-md border border-gray-300 px-3 py-1.5">
            <option value="all" @selected($status === 'all')>Semua Status</option>
            <option value="incomplete" @selected($status === 'incomplete')>Belum Selesai</option>
            <option value="completed" @selected($status === 'completed')>Selesai</option>
        </select>

        <noscript><button type="submit" class="rounded-md bg-gray-200 px-3 py-1.5">Terapkan</button></noscript>
    </form>

    @if ($tasks->isEmpty())
        <p class="text-sm text-gray-500">Belum ada tugas di daftar/proyek ini.</p>
    @else
        <ul class="space-y-2">
            @foreach ($tasks as $task)
                <li class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">
                    <div class="flex items-center gap-3">
                        <form action="{{ route('tasks.toggle-complete', [$taskList, $task]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="Tandai selesai/belum selesai">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full border {{ $task->is_completed ? 'border-emerald-500 bg-emerald-500' : 'border-gray-300' }}"
                                ></span>
                            </button>
                        </form>

                        <div>
                            <a
                                href="{{ route('task-lists.tasks.edit', [$taskList, $task]) }}"
                                class="font-medium {{ $task->is_completed ? 'text-gray-400 line-through' : 'text-gray-900' }} hover:underline"
                            >
                                {{ $task->title }}
                            </a>

                            <div class="mt-1 flex items-center gap-2">
                                <x-priority-badge :priority="$task->priority" />

                                @if ($task->due_date)
                                    <span class="text-xs text-gray-500">{{ $task->due_date->format('d M Y') }}</span>
                                @endif

                                <x-due-badge :task="$task" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ route('task-lists.tasks.edit', [$taskList, $task]) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                        <form action="{{ route('task-lists.tasks.destroy', [$taskList, $task]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @endif
@endsection
