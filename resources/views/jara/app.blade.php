@extends('layouts.app')

@section('page-title', 'Team Collaboration')
@section('page-subtitle', 'Anggota, undangan, dan penugasan')

@section('content')
    <script>
        window.JARA_SESSION_USER = @json([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);
        window.JARA_INITIAL_TAB = @json($tab ?? 'members');
    </script>

    <div id="jara-app" class="px-4 lg:px-6 pb-6"></div>

    <noscript>
        <div class="p-6 text-center text-sm text-[#64748B]">
            JARA membutuhkan JavaScript untuk berjalan.
        </div>
    </noscript>
@endsection
