@extends('layouts.app')

@section('title', 'Edit Daftar/Proyek')

@section('content')
    <h1 class="mb-6 text-xl font-semibold text-gray-900">Edit Daftar/Proyek</h1>

    <form action="{{ route('task-lists.update', $taskList) }}" method="POST" class="max-w-lg space-y-6">
        @csrf
        @method('PUT')

        @include('task-lists._form', ['taskList' => $taskList])

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('task-lists.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
        </div>
    </form>
@endsection
