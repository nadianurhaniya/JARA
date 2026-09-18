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
                    <div class="group relative bg-white rounded-2xl border border-[#E2E8F0] p-5 transition hover:border-[#0BC5C1] hover:shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <a href="{{ route('task-lists.tasks.index', $taskList) }}" class="min-w-0 flex-1">
                                <span class="flex items-center gap-1.5 font-display font-bold text-[#1E293B] group-hover:text-[#0A8F8C]">
                                    <span class="truncate">{{ $taskList->name }}</span>
                                    <svg
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        class="w-4 h-4 shrink-0 text-[#0BC5C1] opacity-0 -translate-x-1 transition group-hover:opacity-100 group-hover:translate-x-0"
                                        aria-hidden="true"
                                    >
                                        <path d="M5 12h14M13 6l6 6-6 6" />
                                    </svg>
                                </span>
                                <p class="mt-1 text-xs text-[#94A3B8]">{{ $taskList->tasks_count }} tugas</p>
                                @if ($taskList->description)
                                    <p class="mt-2 text-sm text-[#64748B]">{{ $taskList->description }}</p>
                                @endif
                            </a>

                            <div class="relative flex items-center gap-3 text-sm shrink-0">
                                <a href="{{ route('task-lists.edit', $taskList) }}" class="text-[#64748B] hover:text-[#1E293B]">Edit</a>
                                <form action="{{ route('task-lists.destroy', $taskList) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
