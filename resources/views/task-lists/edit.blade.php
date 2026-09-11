@extends('layouts.app')

@section('page-title', 'Edit Daftar/Proyek')

@section('content')
    <div class="p-4 lg:p-6 max-w-lg mx-auto">
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6">
            <h2 class="font-display font-bold text-lg text-[#1E293B] mb-5">Edit Daftar/Proyek</h2>

            <form action="{{ route('task-lists.update', $taskList) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @include('task-lists._form', ['taskList' => $taskList])

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('task-lists.index') }}" class="text-sm text-[#64748B] hover:text-[#1E293B]">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
