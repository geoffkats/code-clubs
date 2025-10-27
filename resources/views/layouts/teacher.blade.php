<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="flex h-screen">
            <!-- Teacher Sidebar -->
            <flux:sidebar sticky stashable class="border-e border-slate-200/60 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl">
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

                <!-- Enhanced Logo Section -->
                <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60">
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center space-x-3 rtl:space-x-reverse group" wire:navigate>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                                Teacher
                            </h1>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Dashboard</p>
                        </div>
                    </a>
                </div>

                <!-- Teacher Navigation -->
                <div class="px-4 py-6 space-y-6">
                    <!-- Main Navigation -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Main
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
                            <flux:navlist.item 
                                icon="home" 
                                :href="route('teacher.dashboard')" 
                                :current="request()->routeIs('teacher.dashboard')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Dashboard') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="squares-2x2" 
                                :href="route('teacher.clubs')" 
                                :current="request()->routeIs('teacher.clubs')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('My Clubs') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="calendar-days" 
                                :href="route('teacher.sessions')" 
                                :current="request()->routeIs('teacher.sessions.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('My Sessions') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>

                    <!-- Reports -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Reports
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
                            <flux:navlist.item 
                                icon="document-text" 
                                :href="route('teacher.reports')" 
                                :current="request()->routeIs('teacher.reports.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('My Reports') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="clipboard-document-check" 
                                :href="route('teacher.reports.create')" 
                                :current="request()->routeIs('teacher.reports.create')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Create Report') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>

                    <!-- Feedback -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Feedback
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
                            <flux:navlist.item 
                                icon="star" 
                                :href="route('teacher.feedback.index')" 
                                :current="request()->routeIs('teacher.feedback.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Session Feedback') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>

                    <!-- Resources -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Resources
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
                            <flux:navlist.item 
                                icon="camera" 
                                :href="route('teacher.proofs.index')" 
                                :current="request()->routeIs('teacher.proofs.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Upload Proofs') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="chart-bar" 
                                :href="route('attendance.index')" 
                                :current="request()->routeIs('attendance.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Attendance') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="clipboard-document-check" 
                                :href="route('assessments.index')" 
                                :current="request()->routeIs('assessments.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Assessments') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>

                    <!-- Settings -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Settings
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
                            <flux:navlist.item 
                                icon="cog-6-tooth" 
                                :href="route('profile.edit')" 
                                :current="request()->routeIs('profile.edit')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Profile Settings') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="px-6 py-4 border-t border-slate-200/60 dark:border-slate-700/60">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </flux:sidebar>

            <!-- Main Content -->
            <div class="flex-1 overflow-y-auto">
                <!-- Top Navigation Bar -->
                <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-700/60">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                                @yield('title', 'Teacher Dashboard')
                            </h2>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-6">
                    @yield('content')
                    {{ $slot ?? '' }}
                </main>
            </div>
        </div>

        @stack('modals')
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </body>
</html>
