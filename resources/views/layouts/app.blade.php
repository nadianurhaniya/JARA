@php
    $user = auth()->user();
    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => request()->routeIs('dashboard'),
            'icon' => 'M3 12l9-9 9 9M5 10v10h14V10',
        ],
        [
            'label' => 'Tasks & Projects',
            'route' => null,
            'active' => false,
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ],
        [
            'label' => 'Team Collaboration',
            'route' => null,
            'active' => false,
            'icon' => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100 8 4 4 0 000-8M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75',
        ],
    ];

    if ($user?->isAdmin()) {
        $navItems[] = [
            'label' => 'Admin Panel',
            'route' => 'admin.users.index',
            'active' => request()->routeIs('admin.*'),
            'icon' => 'M12 15a3 3 0 100-6 3 3 0 000 6zM19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09A1.65 1.65 0 008 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H2a2 2 0 110-4h.09A1.65 1.65 0 003.6 8a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H8a1.65 1.65 0 001-1.51V2a2 2 0 114 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V8a1.65 1.65 0 001.51 1H22a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z',
        ];
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('page-title', 'Dashboard') — JARA</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-[#F0FAFA]">
        <div class="h-full flex">
            <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" aria-hidden="true"></div>

            {{-- Sidebar --}}
            <aside
                id="app-sidebar"
                class="fixed inset-y-0 left-0 z-40 w-[260px] bg-white border-r border-[#E2E8F0] flex flex-col -translate-x-full lg:translate-x-0 lg:static transition-transform duration-200"
                aria-label="Primary navigation"
            >
                <div class="h-16 px-5 flex items-center gap-3 border-b border-[#E2E8F0] shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-[#0BC5C1] flex items-center justify-center text-white font-bold">J</div>
                    <span class="font-display font-extrabold text-xl text-[#1E293B]">JARA</span>
                </div>

                <nav class="flex-1 overflow-y-auto p-3 space-y-1">
                    @foreach ($navItems as $item)
                        @php
                            $available = $item['route'] && Route::has($item['route']);
                        @endphp

                        @if ($available)
                            <a
                                href="{{ route($item['route']) }}"
                                @if ($item['active']) aria-current="page" @endif
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $item['active'] ? 'bg-[#E8F9F9] text-[#0A8F8C]' : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B]' }}"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4.5 h-4.5 shrink-0" aria-hidden="true">
                                    <path d="{{ $item['icon'] }}" />
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        @else
                            <span
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#94A3B8] cursor-not-allowed select-none"
                                title="Coming soon"
                                aria-disabled="true"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4.5 h-4.5 shrink-0" aria-hidden="true">
                                    <path d="{{ $item['icon'] }}" />
                                </svg>
                                {{ $item['label'] }}
                                <span class="ml-auto text-[10px] font-semibold uppercase tracking-wide text-[#94A3B8]">Soon</span>
                            </span>
                        @endif
                    @endforeach
                </nav>

                <div class="p-3 border-t border-[#E2E8F0] shrink-0">
                    <div class="flex items-center gap-3 px-2 py-2">
                        <x-avatar :name="$user->name" size="sm" />
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#1E293B] truncate">{{ $user->name }}</p>
                            <p class="text-xs text-[#94A3B8] truncate">{{ $user->role->label() }}</p>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main column --}}
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <header class="h-16 bg-white border-b border-[#E2E8F0] flex items-center justify-between gap-3 px-4 lg:px-6 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <button
                            type="button"
                            data-sidebar-toggle
                            class="lg:hidden p-2 -ml-1 rounded-lg text-[#64748B] hover:bg-[#F1F5F9]"
                            aria-label="Toggle navigation"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5" aria-hidden="true">
                                <path d="M3 12h18M3 6h18M3 18h18" />
                            </svg>
                        </button>
                        <div class="min-w-0">
                            <h1 class="font-display font-extrabold text-lg text-[#1E293B] truncate">@yield('page-title', 'Dashboard')</h1>
                            @hasSection('page-subtitle')
                                <p class="text-xs text-[#94A3B8] truncate">@yield('page-subtitle')</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="hidden sm:flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full font-medium {{ $user->isAdmin() ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-[#E8F9F9] text-[#0BC5C1]' }}">
                            {{ $user->role->label() }}
                        </span>
                        <x-avatar :name="$user->name" size="sm" />
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto">
                    @yield('content')
                </main>

                <footer class="px-4 py-2 border-t border-[#E2E8F0] bg-white flex items-center justify-between gap-3 shrink-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2 h-2 rounded-full shrink-0 {{ $user->is_active ? 'bg-[#10B981]' : 'bg-[#94A3B8]' }}"></span>
                        <span class="text-xs text-[#94A3B8] truncate">
                            Logged in as <strong class="text-[#64748B]">{{ $user->name }}</strong> · {{ $user->isAdmin() ? 'Administrator' : 'Member' }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-[#94A3B8] hover:text-red-500 flex items-center gap-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </footer>
            </div>
        </div>
    </body>
</html>
