@extends('layouts.app')

@section('page-title', 'Edit Tugas')
@section('page-subtitle', $taskList->name)

@section('content')
    <div class="p-4 lg:p-6 max-w-lg mx-auto space-y-6">
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6">
            <h2 class="font-display font-bold text-lg text-[#1E293B] mb-5">Edit Tugas</h2>

            <form action="{{ route('task-lists.tasks.update', [$taskList, $task]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @include('tasks._form', ['task' => $task])

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('task-lists.tasks.index', $taskList) }}" class="text-sm text-[#64748B] hover:text-[#1E293B]">Batal</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6">
            <h3 class="font-display font-bold text-sm text-[#1E293B] mb-4">Sub-tugas</h3>

            <ul class="mb-4 space-y-2">
                @forelse ($task->subtasks as $subtask)
                    <li class="flex items-center justify-between rounded-xl border border-[#E2E8F0] px-3 py-2">
                        <form action="{{ route('subtasks.toggle-complete', $subtask) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="flex items-center gap-2 text-left">
                                <span
                                    class="flex h-4 w-4 items-center justify-center rounded border {{ $subtask->is_completed ? 'border-[#0BC5C1] bg-[#0BC5C1]' : 'border-[#CBD5E1]' }}"
                                ></span>
                                <span class="text-sm {{ $subtask->is_completed ? 'text-[#94A3B8] line-through' : 'text-[#1E293B]' }}">
                                    {{ $subtask->title }}
                                </span>
                            </button>
                        </form>

                        <form action="{{ route('subtasks.destroy', $subtask) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:text-red-700">Hapus</button>
                        </form>
                    </li>
                @empty
                    <li class="text-sm text-[#94A3B8]">Belum ada sub-tugas.</li>
                @endforelse
            </ul>

            <form action="{{ route('subtasks.store', $task) }}" method="POST" class="flex gap-2">
                @csrf
                <input
                    type="text"
                    name="title"
                    placeholder="Tambah sub-tugas baru"
                    class="w-full px-4 py-2.5 rounded-xl border border-[#E2E8F0] bg-white text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] hover:border-[#CBD5E1]"
                    required
                >
                <button type="submit" class="shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
                    Tambah
                </button>
            </form>
        </div>
    </div>
@endsection
