<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'JARA') }} - Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #0BC5C1;
            --background: #F0FAFA;
            --foreground: #1E293B;
            --card: #ffffff;
            --muted: #F1F5F9;
            --muted-foreground: #64748B;
            --border: #E2E8F0;
            --secondary: #E8F9F9;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--background);
            color: var(--foreground);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Nunito', system-ui, sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>
<body class="bg-[#F0FAFA] min-h-screen" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile sidebar overlay -->
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 z-50 w-16 lg:w-56 flex flex-col bg-white border-r border-[#E2E8F0] shrink-0 transition-transform duration-300"
        >
            <div class="h-16 flex items-center px-4 border-b border-[#E2E8F0]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm" style="background: linear-gradient(135deg, #0BC5C1, #0891B2)">
                        J
                    </div>
                    <span class="hidden lg:block font-bold text-lg text-[#1E293B] tracking-tight" style="font-family: 'Nunito', sans-serif">JARA</span>
                </div>
            </div>

            <nav class="flex-1 py-4 px-2">
                <div class="space-y-1">
                    <a href="#" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium bg-[#E8F9F9] text-[#0BC5C1]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#0BC5C1]">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>
                        <span class="hidden lg:block">Dashboard</span>
                        <span class="hidden lg:block ml-auto w-1.5 h-1.5 rounded-full bg-[#0BC5C1]"></span>
                    </a>
                    <a href="#" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B] transition-colors">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#94A3B8]">
                            <path d="M9 11l3 3L22 4" />
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                        </svg>
                        <span class="hidden lg:block">Tasks</span>
                    </a>
                    <a href="#" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#1E293B] transition-colors">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-[#94A3B8]">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                        <span class="hidden lg:block">Team</span>
                    </a>
                </div>
            </nav>

            <div class="p-3 border-t border-[#E2E8F0]">
                <div class="flex items-center gap-3 px-2 py-2 rounded-xl">
                    <div class="w-8 h-8 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold shrink-0">
                        BH
                    </div>
                    <div class="hidden lg:block text-left min-w-0">
                        <p class="text-xs font-semibold text-[#1E293B] truncate">Budi Hartono</p>
                        <p class="text-xs text-[#94A3B8] truncate">Member</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-16 px-4 lg:px-6 flex items-center justify-between border-b border-[#E2E8F0] bg-white shrink-0 relative z-20">
                <div class="flex items-center gap-3">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden w-9 h-9 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center text-[#64748B]"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="font-bold text-xl text-[#1E293B]" style="font-family: 'Nunito', sans-serif">Dashboard</h1>
                        <p class="text-xs text-[#94A3B8] mt-0.5">Your task overview and team activity</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl px-3 py-2 w-52">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-[#94A3B8]">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                        <input type="text" placeholder="Search..." class="bg-transparent text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none w-full">
                    </div>

                    <div class="relative" x-data="{ showNotifs: false }">
                        <button
                            @click="showNotifs = !showNotifs"
                            class="relative w-9 h-9 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center text-[#64748B] hover:bg-[#E8F9F9] hover:text-[#0BC5C1] hover:border-[#0BC5C1] transition-colors"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
                            </svg>
                            <span class="absolute -top-1 -right-1 min-w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center font-bold px-0.5">2</span>
                        </button>
                    </div>

                    <div class="w-8 h-8 rounded-full bg-[#0BC5C1] flex items-center justify-center text-white text-xs font-bold">
                        BH
                    </div>
                </div>
            </header>

            <!-- Dashboard content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-6">
                <!-- Greeting -->
                <div>
                    <h2 class="font-bold text-2xl text-[#1E293B]" style="font-family: 'Nunito', sans-serif">
                        Good morning, Budi 👋
                    </h2>
                    <p class="text-sm text-[#64748B] mt-1">Here's what's happening with your projects today.</p>
                </div>

                <!-- Stat cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-dashboard.stat-card label="Total Tasks" :value="24" color="#0BC5C1">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><rect x="9" y="9" width="13" height="13" rx="2" /><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" /></svg>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Completed" :value="12" subtitle="50% done" color="#10B981">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" /><path d="M22 4L12 14.01l-3-3" /></svg>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="In Progress" :value="7" color="#8B5CF6">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Overdue" :value="2" color="#EF4444">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /><line x1="12" y1="9" x2="12" y2="13" /><line x1="12" y1="17" x2="12.01" y2="17" /></svg>
                    </x-dashboard.stat-card>
                </div>

                <!-- Progress ring + project summary -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <x-dashboard.overall-progress
                        :percentage="50"
                        :completed="12"
                        :inProgress="7"
                        :notStarted="5"
                    />

                    <div class="lg:col-span-2">
                        <x-dashboard.project-progress :projects="[
                            ['name' => 'Website Redesign', 'color' => '#0BC5C1', 'total_tasks' => 6, 'completed_tasks' => 2, 'deadline' => '2026-10-30'],
                            ['name' => 'Mobile App MVP', 'color' => '#8B5CF6', 'total_tasks' => 4, 'completed_tasks' => 2, 'deadline' => '2026-12-15'],
                            ['name' => 'Q4 Marketing Campaign', 'color' => '#F59E0B', 'total_tasks' => 3, 'completed_tasks' => 1, 'deadline' => '2026-12-31'],
                        ]" />
                    </div>
                </div>

                <!-- Charts row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <x-dashboard.progress-trend
                            :weeklyData="[
                                ['label' => 'Aug W1', 'completed' => 4, 'created' => 6],
                                ['label' => 'Aug W2', 'completed' => 7, 'created' => 8],
                                ['label' => 'Aug W3', 'completed' => 5, 'created' => 5],
                                ['label' => 'Aug W4', 'completed' => 9, 'created' => 10],
                                ['label' => 'Sep W1', 'completed' => 6, 'created' => 7],
                                ['label' => 'Sep W2', 'completed' => 3, 'created' => 4],
                            ]"
                            :monthlyData="[
                                ['label' => 'Apr', 'completed' => 12, 'created' => 15],
                                ['label' => 'May', 'completed' => 18, 'created' => 20],
                                ['label' => 'Jun', 'completed' => 22, 'created' => 25],
                                ['label' => 'Jul', 'completed' => 28, 'created' => 30],
                                ['label' => 'Aug', 'completed' => 25, 'created' => 28],
                                ['label' => 'Sep', 'completed' => 9, 'created' => 11],
                            ]"
                        />
                    </div>

                    <div class="space-y-4 flex flex-col">
                        <x-dashboard.task-status-chart
                            :completed="12"
                            :inProgress="7"
                            :notStarted="5"
                        />
                        <x-dashboard.priority-distribution :priorities="[
                            ['name' => 'High', 'value' => 7, 'color' => '#EF4444'],
                            ['name' => 'Medium', 'value' => 4, 'color' => '#F59E0B'],
                            ['name' => 'Low', 'value' => 2, 'color' => '#10B981'],
                        ]" />
                    </div>
                </div>

                <!-- Member progress + Activity + Upcoming -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <x-dashboard.member-progress :members="[
                        ['name' => 'Budi Hartono', 'initials' => 'BH', 'completed' => 5, 'in_progress' => 2, 'not_started' => 1, 'color' => '#0BC5C1'],
                        ['name' => 'Citra Dewi', 'initials' => 'CD', 'completed' => 3, 'in_progress' => 1, 'not_started' => 1, 'color' => '#8B5CF6'],
                        ['name' => 'Eko Prasetyo', 'initials' => 'EP', 'completed' => 2, 'in_progress' => 2, 'not_started' => 1, 'color' => '#F59E0B'],
                        ['name' => 'Farah Nadia', 'initials' => 'FN', 'completed' => 1, 'in_progress' => 1, 'not_started' => 1, 'color' => '#10B981'],
                    ]" />

                    <x-dashboard.recent-activity :activities="[
                        ['user' => 'Eko Prasetyo', 'initial' => 'E', 'action' => 'completed', 'task' => 'Setup Storybook', 'time' => '2h ago', 'color' => '#10B981'],
                        ['user' => 'Citra Dewi', 'initial' => 'C', 'action' => 'updated', 'task' => 'SEO audit and optimization', 'time' => '4h ago', 'color' => '#0BC5C1'],
                        ['user' => 'Budi Hartono', 'initial' => 'B', 'action' => 'created', 'task' => 'Performance testing', 'time' => '6h ago', 'color' => '#8B5CF6'],
                        ['user' => 'Farah Nadia', 'initial' => 'F', 'action' => 'commented on', 'task' => 'Social media content calendar', 'time' => '1d ago', 'color' => '#F59E0B'],
                        ['user' => 'Eko Prasetyo', 'initial' => 'E', 'action' => 'started', 'task' => 'Task list screen', 'time' => '1d ago', 'color' => '#0BC5C1'],
                    ]" />

                    <x-dashboard.upcoming-deadlines :deadlines="[
                        ['title' => 'Develop component library', 'project' => 'Website Redesign', 'date' => '2026-09-25', 'priority_color' => '#EF4444'],
                        ['title' => 'SEO audit and optimization', 'project' => 'Website Redesign', 'date' => '2026-10-05', 'priority_color' => '#F59E0B'],
                        ['title' => 'Performance testing', 'project' => 'Website Redesign', 'date' => '2026-10-20', 'priority_color' => '#F59E0B'],
                        ['title' => 'Email campaign design', 'project' => 'Q4 Marketing Campaign', 'date' => '2026-10-10', 'priority_color' => '#F59E0B'],
                        ['title' => 'Task list screen', 'project' => 'Mobile App MVP', 'date' => '2026-09-30', 'priority_color' => '#EF4444'],
                    ]" />
                </div>

                <!-- Export button -->
                <div class="flex justify-end">
                    <x-dashboard.export-report />
                </div>
            </main>

            <!-- Footer status bar -->
            <div class="px-4 py-2 border-t border-[#E2E8F0] bg-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                    <span class="text-xs text-[#94A3B8]">Logged in as <strong class="text-[#64748B]">Budi Hartono</strong> · Member</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
