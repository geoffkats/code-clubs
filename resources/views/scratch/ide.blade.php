<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-orange-50 to-red-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             activeTab: 'editor',
             showProjectModal: false,
             showSaveModal: false,
             projects: [],
             currentProject: null,
             isFullscreen: false
         }">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('students.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-slate-900 via-orange-900 to-red-900 dark:from-white dark:via-orange-100 dark:to-red-100 bg-clip-text text-transparent">
                                Scratch IDE
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">Visual Programming Environment</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="showProjectModal = true" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Project
                        </button>
                        <button @click="isFullscreen = !isFullscreen" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="px-6 py-4">
            <div class="flex space-x-1 bg-slate-100 dark:bg-slate-800 rounded-lg p-1">
                <button @click="activeTab = 'editor'" 
                        :class="activeTab === 'editor' ? 'bg-white dark:bg-slate-700 text-orange-600 dark:text-orange-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Editor
                </button>
                <button @click="activeTab = 'projects'" 
                        :class="activeTab === 'projects' ? 'bg-white dark:bg-slate-700 text-orange-600 dark:text-orange-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    My Projects
                </button>
                <button @click="activeTab = 'gallery'" 
                        :class="activeTab === 'gallery' ? 'bg-white dark:bg-slate-700 text-orange-600 dark:text-orange-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Gallery
                </button>
                <button @click="activeTab = 'tutorials'" 
                        :class="activeTab === 'tutorials' ? 'bg-white dark:bg-slate-700 text-orange-600 dark:text-orange-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Tutorials
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 pb-8">
            <!-- Scratch Editor Tab -->
            <div x-show="activeTab === 'editor'" class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Scratch Editor</h2>
                            <div class="flex items-center space-x-2">
                                <button class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Play
                                </button>
                                <button class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Stop
                                </button>
                                <button @click="showSaveModal = true" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scratch Editor Container -->
                    <div class="relative" :class="isFullscreen ? 'h-screen' : 'h-96'">
                        <iframe 
                            src="https://scratch.mit.edu/projects/editor/?tutorial=getStarted" 
                            class="w-full h-full border-0"
                            allowfullscreen>
                        </iframe>
                        
                        <!-- Loading Overlay -->
                        <div class="absolute inset-0 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400">Loading Scratch Editor...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Projects Tab -->
            <div x-show="activeTab === 'projects'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">My Scratch Projects</h2>
                    <button @click="showProjectModal = true" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
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
                            <p class="text-sm text-slate-600 dark:text-slate-400">A simple platformer game</p>
                        </div>
                        <div class="mb-4">
                            <img src="https://via.placeholder.com/300x200/FF6B35/FFFFFF?text=Scratch+Game" alt="Project thumbnail" class="w-full h-32 object-cover rounded-lg">
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-xs font-medium">
                                Published
                            </span>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-lg transition-colors">
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
                            <p class="text-sm text-slate-600 dark:text-slate-400">Choose your own adventure</p>
                        </div>
                        <div class="mb-4">
                            <img src="https://via.placeholder.com/300x200/4ECDC4/FFFFFF?text=Story+Project" alt="Project thumbnail" class="w-full h-32 object-cover rounded-lg">
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg text-xs font-medium">
                                In Progress
                            </span>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-lg transition-colors">
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

            <!-- Gallery Tab -->
            <div x-show="activeTab === 'gallery'" class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Featured Projects</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Featured Project 1 -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 hover:shadow-lg transition-shadow">
                            <img src="https://via.placeholder.com/200x150/3B82F6/FFFFFF?text=Amazing+Game" alt="Featured project" class="w-full h-24 object-cover rounded-lg mb-3">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Amazing Game</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">By Student Name</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 dark:text-slate-400">⭐ 4.8</span>
                                <button class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View</button>
                            </div>
                        </div>

                        <!-- Featured Project 2 -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 hover:shadow-lg transition-shadow">
                            <img src="https://via.placeholder.com/200x150/10B981/FFFFFF?text=Creative+Art" alt="Featured project" class="w-full h-24 object-cover rounded-lg mb-3">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Creative Art</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">By Student Name</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 dark:text-slate-400">⭐ 4.9</span>
                                <button class="text-xs text-green-600 dark:text-green-400 hover:underline">View</button>
                            </div>
                        </div>

                        <!-- Featured Project 3 -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-4 hover:shadow-lg transition-shadow">
                            <img src="https://via.placeholder.com/200x150/8B5CF6/FFFFFF?text=Music+Player" alt="Featured project" class="w-full h-24 object-cover rounded-lg mb-3">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Music Player</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">By Student Name</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 dark:text-slate-400">⭐ 4.7</span>
                                <button class="text-xs text-purple-600 dark:text-purple-400 hover:underline">View</button>
                            </div>
                        </div>

                        <!-- Featured Project 4 -->
                        <div class="bg-gradient-to-br from-orange-50 to-red-100 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-4 hover:shadow-lg transition-shadow">
                            <img src="https://via.placeholder.com/200x150/F59E0B/FFFFFF?text=Math+Quiz" alt="Featured project" class="w-full h-24 object-cover rounded-lg mb-3">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Math Quiz</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">By Student Name</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 dark:text-slate-400">⭐ 4.6</span>
                                <button class="text-xs text-orange-600 dark:text-orange-400 hover:underline">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutorials Tab -->
            <div x-show="activeTab === 'tutorials'" class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Scratch Tutorials</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tutorial 1 -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-orange-600 dark:text-orange-400 font-bold">1</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Getting Started with Scratch</h3>
                                    <p class="text-slate-600 dark:text-slate-400 mb-4">Learn the basics of Scratch programming and create your first project.</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-500 dark:text-slate-400">5 minutes</span>
                                        <button class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                                            Start Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tutorial 2 -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold">2</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Creating Your First Game</h3>
                                    <p class="text-slate-600 dark:text-slate-400 mb-4">Build a simple game with sprites, movement, and interactions.</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-500 dark:text-slate-400">15 minutes</span>
                                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                            Start Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tutorial 3 -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-green-600 dark:text-green-400 font-bold">3</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Animation and Sound</h3>
                                    <p class="text-slate-600 dark:text-slate-400 mb-4">Add animations, sound effects, and music to your projects.</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-500 dark:text-slate-400">10 minutes</span>
                                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                            Start Tutorial
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tutorial 4 -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-purple-600 dark:text-purple-400 font-bold">4</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Advanced Programming</h3>
                                    <p class="text-slate-600 dark:text-slate-400 mb-4">Learn about variables, lists, and advanced programming concepts.</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-500 dark:text-slate-400">20 minutes</span>
                                        <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                                            Start Tutorial
                                        </button>
                                    </div>
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
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Create New Scratch Project</h3>
                    <button @click="showProjectModal=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Project Name</label>
                        <input type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Enter project name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Describe your project"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Project Type</label>
                        <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="game">Game</option>
                            <option value="story">Interactive Story</option>
                            <option value="animation">Animation</option>
                            <option value="art">Digital Art</option>
                            <option value="music">Music</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <button type="button" @click="showProjectModal=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Save Project Modal -->
        <div x-show="showSaveModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Save Project</h3>
                    <button @click="showSaveModal=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Project Name</label>
                        <input type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Enter project name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Describe your project"></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <button type="button" @click="showSaveModal=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                            Save Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
