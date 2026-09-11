@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
    <h1 class="mb-1 text-xl font-semibold text-gray-900">Edit Tugas</h1>
    <p class="mb-6 text-sm text-gray-500">di {{ $taskList->name }}</p>

    <form action="{{ route('task-lists.tasks.update', [$taskList, $task]) }}" method="POST" class="max-w-lg space-y-6">
        @csrf
        @method('PUT')

        @include('tasks._form', ['task' => $task])

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('task-lists.tasks.index', $taskList) }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
        </div>
    </form>

    <div class="mt-10 max-w-lg">
        <h2 class="mb-3 text-sm font-semibold text-gray-900">Sub-tugas</h2>

        <ul class="mb-4 space-y-2">
            @forelse ($task->subtasks as $subtask)
                <li class="flex items-center justify-between rounded-md border border-gray-200 bg-white px-3 py-2">
                    <form action="{{ route('subtasks.toggle-complete', $subtask) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="flex items-center gap-2 text-left">
                            <span
                                class="flex h-4 w-4 items-center justify-center rounded border {{ $subtask->is_completed ? 'border-emerald-500 bg-emerald-500' : 'border-gray-300' }}"
                            ></span>
                            <span class="text-sm {{ $subtask->is_completed ? 'text-gray-400 line-through' : 'text-gray-800' }}">
                                {{ $subtask->title }}
                            </span>
                        </button>
                    </form>

                    <form action="{{ route('subtasks.destroy', $subtask) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                </li>
            @empty
                <li class="text-sm text-gray-500">Belum ada sub-tugas.</li>
            @endforelse
        </ul>

        <form action="{{ route('subtasks.store', $task) }}" method="POST" class="flex gap-2">
            @csrf
            <input
                type="text"
                name="title"
                placeholder="Tambah sub-tugas baru"
                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-400 focus:outline-none"
                required
            >
            <button type="submit" class="shrink-0 rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Tambah
            </button>
        </form>
    </div>
@endsection
