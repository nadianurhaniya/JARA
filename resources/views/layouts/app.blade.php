<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'JARA') &mdash; {{ config('app.name', 'JARA') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 sm:px-6">
                <a href="{{ route('task-lists.index') }}" class="font-semibold text-gray-900">JARA</a>
                <a href="{{ route('task-lists.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Daftar/Proyek Saya</a>
            </div>
        </nav>

        <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
            @if (session('status'))
                <div class="mb-6 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>
