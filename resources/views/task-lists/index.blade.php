@extends('layouts.app')

@section('title', 'Daftar/Proyek Saya')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">Daftar/Proyek Saya</h1>
        <a
            href="{{ route('task-lists.create') }}"
            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
        >
            + Daftar Baru
        </a>
    </div>

    @if ($taskLists->isEmpty())
        <p class="text-sm text-gray-500">Belum ada daftar/proyek. Buat yang pertama sekarang.</p>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($taskLists as $taskList)
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <a
                                href="{{ route('task-lists.tasks.index', $taskList) }}"
                                class="font-medium text-gray-900 hover:underline"
                            >
                                {{ $taskList->name }}
                            </a>
                            <p class="mt-1 text-sm text-gray-500">{{ $taskList->tasks_count }} tugas</p>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <a href="{{ route('task-lists.edit', $taskList) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                            <form action="{{ route('task-lists.destroy', $taskList) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </div>
                    </div>

                    @if ($taskList->description)
                        <p class="mt-2 text-sm text-gray-600">{{ $taskList->description }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endsection
