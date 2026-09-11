<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'JARA') — JARA</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#F0FAFA]">
        <div class="min-h-screen flex">
            {{-- Brand panel --}}
            <aside class="hidden lg:flex flex-col w-[420px] bg-[#0BC5C1] p-10 relative overflow-hidden shrink-0">
                <div class="absolute inset-0 opacity-10" aria-hidden="true">
                    <div class="absolute top-10 left-10 w-40 h-40 rounded-full bg-white"></div>
                    <div class="absolute bottom-20 right-5 w-64 h-64 rounded-full bg-white"></div>
                    <div class="absolute top-1/2 left-1/4 w-20 h-20 rounded-full bg-white"></div>
                </div>
                <div class="relative z-10 flex-1 flex flex-col justify-between">
                    <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="flex items-center gap-3 w-fit">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-lg">J</div>
                        <span class="font-display font-extrabold text-2xl text-white">JARA</span>
                    </a>
                    <div>
                        <h2 class="font-display font-extrabold text-3xl text-white leading-tight mb-4">
                            Manage your tasks.<br>Collaborate with your team.
                        </h2>
                        <p class="text-white/80 text-sm leading-relaxed">
                            JARA helps you organize projects, track deadlines, and collaborate seamlessly with your team — all in one place.
                        </p>
                        <div class="mt-8 space-y-3">
                            @foreach (['Create & manage projects', 'Track task progress', 'Collaborate with teammates', 'Monitor deadlines'] as $feature)
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" class="w-3 h-3" aria-hidden="true">
                                            <path d="M20 6L9 17l-5-5" />
                                        </svg>
                                    </div>
                                    <span class="text-white/90 text-sm">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-white/50 text-xs">© {{ date('Y') }} JARA. All rights reserved.</p>
                </div>
            </aside>

            {{-- Form panel --}}
            <div class="flex-1 flex items-center justify-center p-6">
                <div class="w-full max-w-md">
                    <div class="lg:hidden flex items-center gap-2 justify-center mb-8">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold" style="background: #0BC5C1;">J</div>
                        <span class="font-display font-extrabold text-2xl text-[#1E293B]">JARA</span>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-[#E2E8F0]">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
