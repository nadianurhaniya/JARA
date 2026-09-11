@extends('layouts.app')

@section('page-title', 'Tasks & Projects')
@section('page-subtitle', 'Daftar/proyek yang kamu kelola')

@section('content')
    <div class="p-4 lg:p-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-display font-extrabold text-xl text-[#1E293B]">Daftar/Proyek Saya</h2>
            <a
                href="{{ route('task-lists.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]"
            >
                + Daftar Baru
            </a>
        </div>

        @if ($taskLists->isEmpty())
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-8 text-center">
                <p class="text-sm text-[#64748B]">Belum ada daftar/proyek. Buat yang pertama sekarang.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($taskLists as $taskList)
                    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <a
                                    href="{{ route('task-lists.tasks.index', $taskList) }}"
                                    class="font-display font-bold text-[#1E293B] hover:text-[#0A8F8C]"
                                >
                                    {{ $taskList->name }}
                                </a>
                                <p class="mt-1 text-xs text-[#94A3B8]">{{ $taskList->tasks_count }} tugas</p>
                            </div>

                            <div class="flex items-center gap-3 text-sm shrink-0">
                                <a href="{{ route('task-lists.edit', $taskList) }}" class="text-[#64748B] hover:text-[#1E293B]">Edit</a>
                                <form action="{{ route('task-lists.destroy', $taskList) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>

                        @if ($taskList->description)
                            <p class="mt-2 text-sm text-[#64748B]">{{ $taskList->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
