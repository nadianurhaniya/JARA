@extends('layouts.app')

@section('page-title', $taskList->name)
@section('page-subtitle', 'Tasks & Projects')

@section('content')
    <div class="p-4 lg:p-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('task-lists.index') }}" class="text-sm text-[#64748B] hover:text-[#1E293B]">&larr; Daftar/Proyek Saya</a>
            <a
                href="{{ route('task-lists.tasks.create', $taskList) }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]"
            >
                + Tugas Baru
            </a>
        </div>

        <form method="GET" class="bg-white rounded-2xl border border-[#E2E8F0] p-4 mb-4 flex flex-wrap items-center gap-3" onchange="this.submit()">
            <select name="sort" class="px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="priority" @selected($sort !== 'due_date')>Urutkan: Prioritas</option>
                <option value="due_date" @selected($sort === 'due_date')>Urutkan: Tenggat Waktu</option>
            </select>

            <select name="direction" class="px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="asc" @selected($direction === 'asc')>Naik</option>
                <option value="desc" @selected($direction === 'desc')>Turun</option>
            </select>

            <select name="status" class="px-3 py-2 rounded-xl border border-[#E2E8F0] text-sm text-[#64748B] bg-white outline-none focus:border-[#0BC5C1]">
                <option value="all" @selected($status === 'all')>Semua Status</option>
                <option value="incomplete" @selected($status === 'incomplete')>Belum Selesai</option>
                <option value="completed" @selected($status === 'completed')>Selesai</option>
            </select>

            <noscript><button type="submit" class="px-3 py-2 rounded-xl bg-[#F1F5F9] text-sm text-[#64748B]">Terapkan</button></noscript>
        </form>

        @if ($tasks->isEmpty())
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-8 text-center">
                <p class="text-sm text-[#64748B]">Belum ada tugas di daftar/proyek ini.</p>
            </div>
        @else
            <ul class="space-y-2">
                @foreach ($tasks as $task)
                    <li class="flex items-center justify-between bg-white rounded-2xl border border-[#E2E8F0] px-4 py-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <form action="{{ route('tasks.toggle-complete', [$taskList, $task]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Tandai selesai/belum selesai">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full border {{ $task->is_completed ? 'border-[#0BC5C1] bg-[#0BC5C1]' : 'border-[#CBD5E1]' }}"
                                    ></span>
                                </button>
                            </form>

                            <div class="min-w-0">
                                <a
                                    href="{{ route('task-lists.tasks.edit', [$taskList, $task]) }}"
                                    class="font-medium {{ $task->is_completed ? 'text-[#94A3B8] line-through' : 'text-[#1E293B]' }} hover:text-[#0A8F8C]"
                                >
                                    {{ $task->title }}
                                </a>

                                <div class="mt-1 flex items-center gap-2 flex-wrap">
                                    <x-priority-badge :priority="$task->priority" />

                                    @if ($task->due_date)
                                        <span class="text-xs text-[#94A3B8]">{{ $task->due_date->format('d M Y') }}</span>
                                    @endif

                                    <x-due-badge :task="$task" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-sm shrink-0">
                            <a href="{{ route('task-lists.tasks.edit', [$taskList, $task]) }}" class="text-[#64748B] hover:text-[#1E293B]">Edit</a>
                            <form action="{{ route('task-lists.tasks.destroy', [$taskList, $task]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
@endsection
