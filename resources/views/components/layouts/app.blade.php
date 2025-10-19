<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <flux:sidebar sticky stashable class="border-e border-slate-200/60 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl">
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

                <!-- Enhanced Logo Section -->
                <div class="px-6 py-4 border-b border-slate-200/60 dark:border-slate-700/60">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 rtl:space-x-reverse group" wire:navigate>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                                Code Club
                            </h1>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Management System</p>
                        </div>
                    </a>
                </div>

                <!-- Professional Navigation -->
                <div class="px-4 py-6 space-y-6">
                    <!-- Main Navigation -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Main
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
                            <flux:navlist.item 
                                icon="home" 
                                :href="route('dashboard')" 
                                :current="request()->routeIs('dashboard')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Dashboard') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="squares-2x2" 
                                :href="route('clubs.index')" 
                                :current="request()->routeIs('clubs.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Clubs') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="users" 
                                :href="route('students.index')" 
                                :current="request()->routeIs('students.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Students') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="building-office" 
                                :href="route('schools.index')" 
                                :current="request()->routeIs('schools.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Schools') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>

                    <!-- Management -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Management
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
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

                            <flux:navlist.item 
                                icon="document-text" 
                                :href="route('reports.index')" 
                                :current="request()->routeIs('reports.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Reports') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="calendar" 
                                :href="route('sessions.index')" 
                                :current="request()->routeIs('sessions.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Sessions') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>

                    <!-- Tools -->
                    <div class="space-y-1">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                            Tools
                        </h3>
                        
                        <flux:navlist variant="outline" class="space-y-1">
                            <flux:navlist.item 
                                icon="code-bracket" 
                                :href="route('scratch.ide')" 
                                :current="request()->routeIs('scratch.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Scratch IDE') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>
                </div>

                <!-- User Profile Section -->
                <div class="mt-auto px-4 py-4 border-t border-slate-200/60 dark:border-slate-700/60">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-slate-400 to-slate-600 rounded-lg flex items-center justify-center text-white text-sm font-medium">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Logout Button -->
                    <div class="mt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </flux:sidebar>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <main class="flex-1 overflow-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>