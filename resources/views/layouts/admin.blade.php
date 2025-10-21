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
                                :href="route('admin.clubs.index')" 
                                :current="request()->routeIs('admin.clubs.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Clubs') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="users" 
                                :href="route('admin.students.index')" 
                                :current="request()->routeIs('admin.students.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Students') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="building-office" 
                                :href="route('admin.schools.index')" 
                                :current="request()->routeIs('admin.schools.*')" 
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
                                icon="user-plus" 
                                :href="route('admin.students.index')" 
                                :current="request()->routeIs('admin.students.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Student Accounts') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="users" 
                                :href="route('admin.users.index')" 
                                :current="request()->routeIs('admin.users.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Manage Users') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="document-text" 
                                :href="route('admin.resources.index')" 
                                :current="request()->routeIs('admin.resources.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Resources') }}
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

                            <flux:navlist.item 
                                icon="calendar-days" 
                                :href="route('sessions.index')" 
                                :current="request()->routeIs('sessions.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Sessions') }}
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
                                :href="route('reports.index')" 
                                :current="request()->routeIs('reports.*') && !request()->routeIs('reports.approval')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('All Reports') }}
                            </flux:navlist.item>

                            @if(Route::has('reports.approval'))
                            <flux:navlist.item 
                                icon="check-circle" 
                                :href="route('reports.approval')" 
                                :current="request()->routeIs('reports.approval')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Report Approval') }}
                            </flux:navlist.item>
                            @endif

                            <flux:navlist.item 
                                icon="star" 
                                :href="route('admin.feedback.index')" 
                                :current="request()->routeIs('admin.feedback.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Session Feedback') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="chart-bar-square" 
                                :href="route('admin.feedback.analytics')" 
                                :current="request()->routeIs('admin.feedback.analytics')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Feedback Analytics') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="camera" 
                                :href="route('admin.proofs.index')" 
                                :current="request()->routeIs('admin.proofs.index')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Teacher Proofs') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="archive-box" 
                                :href="route('admin.proofs.archived')" 
                                :current="request()->routeIs('admin.proofs.archived')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Archived Proofs') }}
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
                                :href="route('admin.settings')" 
                                :current="request()->routeIs('admin.settings.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('System Settings') }}
                            </flux:navlist.item>

                            <flux:navlist.item 
                                icon="bell" 
                                :href="route('admin.notifications.settings')" 
                                :current="request()->routeIs('admin.notifications.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Notifications') }}
                            </flux:navlist.item>
                            <flux:navlist.item 
                                icon="user-circle" 
                                :href="route('admin.profile')" 
                                :current="request()->routeIs('admin.profile.*')" 
                                wire:navigate
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                {{ __('Profile Settings') }}
                            </flux:navlist.item>
                        </flux:navlist>
                    </div>
                </div>

                <!-- User Profile Section -->
                <div class="mt-auto p-4 border-t border-slate-200/60 dark:border-slate-700/60">
                    <div class="flex items-center space-x-3 mb-3">
                        <img class="w-8 h-8 rounded-lg object-cover border border-slate-200 dark:border-slate-600" 
                             src="{{ auth()->user()->profile_image_url }}" 
                             alt="{{ auth()->user()->name }}"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random'">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Profile Link -->
                    <div class="mb-3">
                        <a href="{{ route('admin.profile') }}" 
                           class="flex items-center w-full px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile Settings
                        </a>
                    </div>
                    
                    <!-- Logout Button -->
                    <div class="mt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to logout?')">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </flux:sidebar>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-700/60 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                                @yield('title', 'Dashboard')
                            </h1>
                            @hasSection('subtitle')
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    @yield('subtitle')
                                </p>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell -->
                            <livewire:notification-bell />
                            
                            <!-- User Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <img class="w-8 h-8 rounded-lg object-cover border border-slate-200 dark:border-slate-600" 
                                         src="{{ auth()->user()->profile_image_url }}" 
                                         alt="{{ auth()->user()->name }}"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random'">
                                    <div class="text-left hidden sm:block">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ ucfirst(auth()->user()->user_role ?? 'admin') }}
                                        </p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('admin.profile') }}" 
                                           class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Profile Settings
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center w-full px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                
                <main class="flex-1 overflow-auto">
                    @yield('content')
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
