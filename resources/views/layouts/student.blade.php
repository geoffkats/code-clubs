<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Code Club System') }} - Student Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .uganda-text {
            color: #FFD700;
            font-weight: 700;
        }
    </style>
</head>
<body class="h-full bg-slate-900 dark">
    <div class="min-h-full" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800/90 backdrop-blur-xl border-e border-slate-700/60 shadow-2xl lg:translate-x-0" 
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            
            <div class="flex items-center justify-between h-16 px-6 border-b border-slate-700/60">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-lg font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                            Student Portal
                        </h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Code Club System</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav class="mt-6 px-4">
                <div class="space-y-1">
                    <a href="{{ route('student.dashboard') }}" 
                       class="flex items-center px-3 py-2.5 mx-1 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('student.assignments') }}" 
                       class="flex items-center px-3 py-2.5 mx-1 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.assignments*') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Assignments & Quizzes
                    </a>

                    <a href="{{ route('student.progress') }}" 
                       class="flex items-center px-3 py-2.5 mx-1 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.progress') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Progress
                    </a>

                    <!-- Reports navigation removed - students should not access their reports -->

                    <a href="{{ route('student.profile') }}" 
                       class="flex items-center px-3 py-2.5 mx-1 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.profile') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                </div>
            </nav>

            <!-- Upcoming Sessions Section -->
            <div class="px-4 py-4 border-t border-slate-200/60 dark:border-slate-700/60">
                <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Upcoming Sessions</h3>
                @if(isset($upcomingSessions) && $upcomingSessions->count() > 0)
                    <div class="space-y-2">
                        @foreach($upcomingSessions->take(2) as $session)
                            <div class="bg-slate-700/50 dark:bg-slate-700/50 rounded-lg p-3 border border-slate-600/50 dark:border-slate-600/50">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-md flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-calendar text-white text-xs"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-200 dark:text-slate-200 truncate">
                                            {{ $session->club->club_name }}
                                        </p>
                                        <p class="text-xs text-slate-400 dark:text-slate-400">
                                            {{ \Carbon\Carbon::parse($session->session_date)->format('M d') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($upcomingSessions->count() > 2)
                            <div class="text-center">
                                <a href="{{ route('student.dashboard') }}" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">
                                    View all {{ $upcomingSessions->count() }} sessions
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-calendar-times text-slate-400 text-sm"></i>
                        </div>
                        <p class="text-xs text-slate-400 dark:text-slate-400">No upcoming sessions</p>
                    </div>
                @endif
            </div>

            <!-- Student Profile Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-200/60 dark:border-slate-700/60 bg-slate-50/80 dark:bg-slate-800/80 backdrop-blur-xl">
                <div class="flex items-center space-x-3 mb-4">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-slate-200 dark:border-slate-600" 
                         src="{{ Auth::guard('student')->user()->profile_image_url }}" 
                         alt="{{ Auth::guard('student')->user()->full_name }}">
                    <div>
                        <div class="font-medium text-slate-900 dark:text-white text-sm">{{ Auth::guard('student')->user()->full_name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ Auth::guard('student')->user()->student_id_number }}</div>
                    </div>
                </div>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-xl transition-all duration-200 border border-red-200 dark:border-red-700/50 hover:shadow-lg"
                            onclick="return confirm('Are you sure you want to logout?')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main content -->
        <div class="lg:ml-64 bg-slate-900 min-h-screen">
            <!-- Top navigation -->
            <header class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-lg border-b border-slate-200/60 dark:border-slate-700/60">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="lg:hidden mr-4 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-6 h-6 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                            @yield('page-title', 'Student Portal')
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Student info -->
                        <div class="flex items-center space-x-3">
                            <img class="w-8 h-8 rounded-full border-2 border-slate-200 dark:border-slate-600" 
                                 src="{{ Auth::guard('student')->user()->profile_image_url }}" 
                                 alt="{{ Auth::guard('student')->user()->full_name }}">
                            <div class="hidden sm:block">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ Auth::guard('student')->user()->full_name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Grade {{ Auth::guard('student')->user()->student_grade_level }}</p>
                            </div>
                        </div>
                        
                        <!-- Logout Button in Header -->
                        <form method="POST" action="{{ route('student.logout') }}" class="hidden lg:block">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-700/50 text-sm leading-4 font-medium rounded-xl text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-sm hover:shadow-md"
                                    onclick="return confirm('Are you sure you want to logout?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mx-4 mt-4" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mx-4 mt-4" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
             @click="sidebarOpen = false"
             style="display: none;"></div>
    </div>

    @stack('scripts')
</body>
</html>
