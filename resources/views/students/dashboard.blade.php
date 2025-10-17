<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             activeTab: 'dashboard',
             showProjectModal: false,
             showSubmissionModal: false,
             selectedProject: null,
             projects: [],
             assignments: [],
             achievements: []
         }">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                                Student Dashboard
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Welcome back, {{ $student->student_first_name ?? 'Student' }}!</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-slate-600 dark:text-slate-400">Grade {{ $student->student_grade_level ?? 'N/A' }}</p>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $student->clubs->first()->club_name ?? 'No Club' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="px-6 py-4">
            <div class="flex space-x-1 bg-slate-100 dark:bg-slate-800 rounded-lg p-1">
                <button @click="activeTab = 'dashboard'" 
                        :class="activeTab === 'dashboard' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Dashboard
                </button>
                <button @click="activeTab = 'assignments'" 
                        :class="activeTab === 'assignments' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Assignments
                </button>
                <button @click="activeTab = 'projects'" 
                        :class="activeTab === 'projects' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    My Projects
                </button>
                <button @click="activeTab = 'scratch'" 
                        :class="activeTab === 'scratch' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Scratch IDE
                </button>
                <button @click="activeTab = 'progress'" 
                        :class="activeTab === 'progress' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Progress
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 pb-8">
            <!-- Dashboard Tab -->
            <div x-show="activeTab === 'dashboard'" class="space-y-8">
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Assignments</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-white">3</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Projects Created</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-white">7</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Average Score</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-white">87%</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Achievements</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-white">12</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Recent Activity</h2>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-900 dark:text-white">Completed Python Basics Quiz</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Scored 95% - Great job!</p>
                            </div>
                            <span class="text-sm text-slate-500 dark:text-slate-400">2 hours ago</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-900 dark:text-white">Created new Scratch project</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">"My First Game" - Interactive story</p>
                            </div>
                            <span class="text-sm text-slate-500 dark:text-slate-400">1 day ago</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-900 dark:text-white">Earned "Code Master" badge</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Completed 10 coding challenges</p>
                            </div>
                            <span class="text-sm text-slate-500 dark:text-slate-400">3 days ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignments Tab -->
            <div x-show="activeTab === 'assignments'" class="space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">My Assignments</h2>
                    
                    <div class="space-y-4">
                        <!-- Assignment 1 -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Python Variables Quiz</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Due: March 15, 2024</p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-medium">
                                    In Progress
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 mb-4">
                                Test your understanding of Python variables, data types, and basic operations.
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">10 questions</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">30 minutes</span>
                                </div>
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Start Quiz
                                </button>
                            </div>
                        </div>

                        <!-- Assignment 2 -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Scratch Animation Project</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Due: March 20, 2024</p>
                                </div>
                                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg text-sm font-medium">
                                    Not Started
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 mb-4">
                                Create an animated story using Scratch. Include at least 3 characters and 5 scenes.
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Project</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">100 points</span>
                                </div>
                                <button class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                    View Details
                                </button>
                            </div>
                        </div>

                        <!-- Assignment 3 -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Code Review Assignment</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Due: March 18, 2024</p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm font-medium">
                                    Completed
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 mb-4">
                                Review and provide feedback on a classmate's Scratch project.
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Score: 92/100</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Submitted 2 days ago</span>
                                </div>
                                <button class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                    View Feedback
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Tab -->
            <div x-show="activeTab === 'projects'" class="space-y-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">My Projects</h2>
                    <button @click="showProjectModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        New Project
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Project 1 -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">My First Game</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">A simple platformer game created in Scratch</p>
                        </div>
                        <div class="mb-4">
                            <img src="https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Scratch+Project" alt="Project thumbnail" class="w-full h-32 object-cover rounded-lg">
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-xs font-medium">
                                Published
                            </span>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Project 2 -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Interactive Story</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">A choose-your-own-adventure story</p>
                        </div>
                        <div class="mb-4">
                            <img src="https://via.placeholder.com/300x200/10B981/FFFFFF?text=Story+Project" alt="Project thumbnail" class="w-full h-32 object-cover rounded-lg">
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg text-xs font-medium">
                                In Progress
                            </span>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scratch IDE Tab -->
            <div x-show="activeTab === 'scratch'" class="space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Scratch IDE</h2>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Scratch Programming Environment</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-2xl mx-auto">
                            Create amazing interactive stories, games, and animations using Scratch's visual programming blocks. 
                            No coding experience required!
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                                Open Scratch IDE
                            </button>
                            <button class="px-6 py-3 border-2 border-orange-600 text-orange-600 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors font-medium">
                                View Tutorials
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Tab -->
            <div x-show="activeTab === 'progress'" class="space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">My Progress</h2>
                    
                    <!-- Progress Chart Placeholder -->
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-8 text-center mb-8">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-4xl font-bold text-white">87%</span>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Overall Progress</h3>
                        <p class="text-slate-600 dark:text-slate-400">You're doing great! Keep up the excellent work.</p>
                    </div>

                    <!-- Skills Progress -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Skills Progress</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Scratch Programming</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">95%</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-600 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" style="width: 95%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Python Basics</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">78%</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-600 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full" style="width: 78%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Problem Solving</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">82%</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-600 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: 82%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Project Modal -->
        <div x-show="showProjectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Create New Project</h3>
                    <button @click="showProjectModal=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Project Name</label>
                        <input type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter project name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe your project"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Project Type</label>
                        <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="game">Game</option>
                            <option value="story">Interactive Story</option>
                            <option value="animation">Animation</option>
                            <option value="art">Digital Art</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <button type="button" @click="showProjectModal=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
