@extends('layouts.app')

@section('title', 'Daftar/Proyek Baru')

@section('content')
    <h1 class="mb-6 text-xl font-semibold text-gray-900">Daftar/Proyek Baru</h1>

    <form action="{{ route('task-lists.store') }}" method="POST" class="max-w-lg space-y-6">
        @csrf

        @include('task-lists._form')

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Simpan
            </button>
            <a href="{{ route('task-lists.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
        </div>
    </form>
@endsection
