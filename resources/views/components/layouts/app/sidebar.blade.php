<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
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
            <div class="px-6 py-6">
                <flux:navlist variant="outline" class="space-y-1">
                    <!-- Dashboard -->
                    <flux:navlist.group :heading="__('Overview')" class="space-y-1 mb-6">
                        <flux:navlist.item 
                            icon="home" 
                            :href="route('dashboard')" 
                            :current="request()->routeIs('dashboard')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Dashboard') }}</span>
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <!-- Core Management -->
                    <flux:navlist.group :heading="__('Management')" class="space-y-1 mb-6">
                        <flux:navlist.item 
                            icon="squares-2x2" 
                            :href="route('clubs.index')" 
                            :current="request()->routeIs('clubs.*')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Clubs') }}</span>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="users" 
                            :href="route('students.index')" 
                            :current="request()->routeIs('students.*')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Students') }}</span>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="building-office" 
                            :href="route('schools.index')" 
                            :current="request()->routeIs('schools.*')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Schools') }}</span>
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <!-- Activities & Tracking -->
                    <flux:navlist.group :heading="__('Activities & Tracking')" class="space-y-1 mb-6">
                        <flux:navlist.item 
                            icon="chart-bar" 
                            :href="route('attendance.index')" 
                            :current="request()->routeIs('attendance.*')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Attendance') }}</span>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="clipboard-document-check" 
                            :href="route('clubs.index')" 
                            :current="request()->routeIs('assessments.*')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Assessments') }}</span>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="document-text" 
                            :href="route('clubs.index')" 
                            :current="request()->routeIs('reports.*')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Reports') }}</span>
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <!-- Tools -->
                    <flux:navlist.group :heading="__('Tools')" class="space-y-1">
                        <flux:navlist.item 
                            icon="code-bracket" 
                            :href="route('scratch.ide')" 
                            :current="request()->routeIs('scratch.*')" 
                            wire:navigate
                            class="px-3 py-2.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('Scratch IDE') }}</span>
                        </flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
            </div>

            <!-- User Profile Section -->
            <div class="mt-auto px-6 py-4 border-t border-slate-200/60 dark:border-slate-700/60">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-sm font-medium">
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
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </flux:sidebar>

        <!-- Main Content Area -->
        <div class="lg:pl-72">
            <!-- Header -->
            <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-700/60">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Mobile menu button -->
                        <flux:sidebar.toggle class="lg:hidden" icon="bars-3" />
                        
                        <!-- Page Title -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                                {{ $title ?? config('app.name') }}
                            </h1>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zm0-8h6V5H4v6z"></path>
                                </svg>
                            </button>

                            <!-- Settings -->
                            <button class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>