@extends('layouts.app')

@section('page-title', 'Daftar/Proyek Baru')

@section('content')
    <div class="p-4 lg:p-6 max-w-lg mx-auto">
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6">
            <h2 class="font-display font-bold text-lg text-[#1E293B] mb-5">Daftar/Proyek Baru</h2>

            <form action="{{ route('task-lists.store') }}" method="POST" class="space-y-6">
                @csrf

                @include('task-lists._form')

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0BC5C1] text-white text-sm font-semibold hover:bg-[#0AAEAA]">
                        Simpan
                    </button>
                    <a href="{{ route('task-lists.index') }}" class="text-sm text-[#64748B] hover:text-[#1E293B]">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
