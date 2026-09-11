@extends('layouts.app')

@section('title', 'Tugas Baru')

@section('content')
    <h1 class="mb-1 text-xl font-semibold text-gray-900">Tugas Baru</h1>
    <p class="mb-6 text-sm text-gray-500">di {{ $taskList->name }}</p>

    <form action="{{ route('task-lists.tasks.store', $taskList) }}" method="POST" class="max-w-lg space-y-6">
        @csrf

        @include('tasks._form')

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Simpan
            </button>
            <a href="{{ route('task-lists.tasks.index', $taskList) }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
        </div>
    </form>
@endsection
