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

            <!-- Enhanced Navigation -->
            <div class="px-6 py-8 space-y-3">
                <flux:navlist variant="outline" class="space-y-2">
                    <flux:navlist.group :heading="__('Platform')" class="space-y-2">
                        <flux:navlist.item 
                            icon="home" 
                            :href="route('dashboard')" 
                            :current="request()->routeIs('dashboard')" 
                            wire:navigate
                            class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0a1 1 0 01-1-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 01-1 1z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Dashboard') }}</span>
                            </div>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="users" 
                            :href="route('students.index')" 
                            :current="request()->routeIs('students.*')" 
                            wire:navigate
                            class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Students') }}</span>
                            </div>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="squares-2x2" 
                            :href="route('clubs.index')" 
                            :current="request()->routeIs('clubs.*')" 
                            wire:navigate
                            class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Clubs') }}</span>
                            </div>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="clipboard-document-check" 
                            :href="route('assessments.create', ['club_id' => 0])" 
                            :current="request()->routeIs('assessments.*')" 
                            wire:navigate
                            class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-amber-50 hover:to-orange-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Assessments') }}</span>
                            </div>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="chart-bar" 
                            :href="route('attendance.grid', ['club_id' => 0])" 
                            :current="request()->routeIs('attendance.*')" 
                            wire:navigate
                            class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Attendance') }}</span>
                            </div>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="document-text" 
                            :href="route('reports.show', ['report_id' => 0])" 
                            :current="request()->routeIs('reports.*')" 
                            wire:navigate
                            class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Reports') }}</span>
                            </div>
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="building-office" 
                            :href="route('schools.index')" 
                            :current="request()->routeIs('schools.*')" 
                            wire:navigate
                            class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Schools') }}</span>
                            </div>
                        </flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
            </div>

            <flux:spacer />

            <!-- AI & Advanced Features -->
            <div class="px-6 py-6 border-t border-slate-200/60 dark:border-slate-700/60">
                <flux:navlist variant="outline" class="space-y-2">
                    <flux:navlist.item 
                        icon="sparkles" 
                        href="#" 
                        class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="font-medium">{{ __('AI Assistant') }}</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Smart recommendations</p>
                            </div>
                            <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 text-xs rounded-full font-medium">Soon</span>
                        </div>
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="chart-pie" 
                        href="#" 
                        class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="font-medium">{{ __('Analytics Hub') }}</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Advanced insights</p>
                            </div>
                            <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs rounded-full font-medium">Soon</span>
                        </div>
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="cpu-chip" 
                        href="#" 
                        class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="font-medium">{{ __('Smart Automation') }}</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Auto workflows</p>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs rounded-full font-medium">Soon</span>
                        </div>
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="bell" 
                        href="#" 
                        class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-amber-50 hover:to-orange-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="font-medium">{{ __('Notifications') }}</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Smart alerts</p>
                            </div>
                            <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300 text-xs rounded-full font-medium">Soon</span>
                        </div>
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="cog-6-tooth" 
                        href="#" 
                        class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-slate-50 hover:to-gray-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-4"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-slate-500 to-gray-600 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="font-medium">{{ __('System Settings') }}</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Configuration</p>
                            </div>
                        </div>
                    </flux:navlist.item>
                </flux:navlist>
            </div>

            <!-- Enhanced Desktop User Menu -->
            <div class="px-6 py-6 border-t border-slate-200/60 dark:border-slate-700/60">
                <flux:dropdown class="w-full" position="top" align="start">
                    <button class="w-full group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-gradient-to-r hover:from-slate-50 hover:to-gray-50 dark:hover:from-slate-800 dark:hover:to-slate-700 p-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <span class="text-sm font-bold">{{ auth()->user()->initials() }}</span>
                            </div>
                            <div class="flex-1 text-left">
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <flux:menu class="w-[240px]">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-3 px-3 py-3 text-start text-sm bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-800 dark:to-slate-700 rounded-t-lg">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                                        <span class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 text-white shadow-lg">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate class="group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ __('Settings') }}</span>
                                </div>
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" class="w-full group" data-test="logout-button">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-lg flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-red-600 dark:text-red-400">{{ __('Log Out') }}</span>
                                </div>
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </flux:sidebar>

        <!-- Enhanced Mobile User Menu -->
        <flux:header class="lg:hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-700/60">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <button class="group relative overflow-hidden rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-105 p-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-all duration-300">
                            <span class="text-xs font-bold">{{ auth()->user()->initials() }}</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>

                <flux:menu class="w-[240px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-3 px-3 py-3 text-start text-sm bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-800 dark:to-slate-700 rounded-t-lg">
                                <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                                    <span class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 text-white shadow-lg">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate class="group">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ __('Settings') }}</span>
                            </div>
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" class="w-full group" data-test="logout-button">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-lg flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-red-600 dark:text-red-400">{{ __('Log Out') }}</span>
                            </div>
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
