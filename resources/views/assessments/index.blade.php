<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-amber-50 to-orange-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             showCreate: false,
             selectedClub: '',
             clubs: @js($clubs ?? [])
         }">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-amber-900 to-orange-900 dark:from-white dark:via-amber-100 dark:to-orange-100 bg-clip-text text-transparent">
                                Assessments
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Create and manage quizzes, tests, and assignments</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="showCreate = true" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Assessment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Assessments</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessments->total() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Quizzes</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessments->where('assessment_type', 'quiz')->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Tests</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessments->where('assessment_type', 'test')->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Assignments</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assessments->where('assessment_type', 'assignment')->count() ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 mb-8">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" placeholder="Search assessments..." class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="md:w-64">
                        <select x-model="selectedClub" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">All Clubs</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->club_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:w-48">
                        <select class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">All Types</option>
                            <option value="quiz">Quiz</option>
                            <option value="test">Test</option>
                            <option value="assignment">Assignment</option>
                            <option value="project">Project</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Assessments Grid -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">All Assessments</h2>
                </div>
                
                @if($assessments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach($assessments as $assessment)
                            <div class="group relative bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800 rounded-xl border border-slate-200 dark:border-slate-600 p-6 hover:shadow-lg transition-all duration-300">
                                <!-- Assessment Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">
                                            {{ $assessment->assessment_name }}
                                        </h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            {{ $assessment->club->club_name ?? 'No Club' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg text-xs font-medium">
                                            {{ ucfirst($assessment->assessment_type) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Assessment Details -->
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @if($assessment->due_date)
                                            Due: {{ \Carbon\Carbon::parse($assessment->due_date)->format('M d, Y') }}
                                        @else
                                            No due date
                                        @endif
                                    </div>
                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        {{ $assessment->total_points ?? 0 }} points
                                    </div>
                                    <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $assessment->questions->count() }} questions
                                        @if($assessment->questions->count() > 0)
                                            <span class="ml-2 text-xs">
                                                ({{ $assessment->questions->where('question_type', 'multiple_choice')->count() }} MC, 
                                                {{ $assessment->questions->where('question_type', 'practical_project')->count() }} Projects,
                                                {{ $assessment->questions->where('question_type', 'image_question')->count() }} Images,
                                                {{ $assessment->questions->where('question_type', 'text_question')->count() }} Text)
                                            </span>
                                        @endif
                                    </div>
                                    @if($assessment->description)
                                        <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">
                                            {{ Str::limit($assessment->description, 100) }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Progress Stats -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-blue-600">{{ $assessment->scores_count ?? 0 }}</div>
                                        <span class="text-xs text-slate-500">Submissions</span>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-emerald-600">{{ $assessment->average_score ?? 0 }}%</div>
                                        <span class="text-xs text-slate-500">Avg Score</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('assessments.show', $assessment->id) }}" class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('assessments.scores', $assessment->id) }}" class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg font-medium hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors text-xs">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Scores
                                        </a>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('assessments.edit', $assessment->id) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Edit Assessment">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('assessments.destroy', $assessment->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this assessment? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete Assessment">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $assessments->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No assessments found</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">Get started by creating your first assessment</p>
                        <button @click="showCreate = true" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            Create First Assessment
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Assessment Modal -->
        <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700 flex-shrink-0">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Create New Assessment</h3>
                    <button @click="showCreate=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto p-6">
                <form method="post" action="#" class="space-y-4" id="createAssessmentForm" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Club</label>
                            <select name="club_id" id="clubSelect" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                <option value="">Select a club</option>
                                @foreach($clubs as $club)
                                    @php
                                        $clubType = '';
                                        if (stripos($club->club_name, 'robot') !== false) {
                                            $clubType = '🤖 Robotics';
                                        } elseif (stripos($club->club_name, 'python') !== false) {
                                            $clubType = '🐍 Python';
                                        } elseif (stripos($club->club_name, 'web') !== false || stripos($club->club_name, 'html') !== false || stripos($club->club_name, 'css') !== false || stripos($club->club_name, 'javascript') !== false) {
                                            $clubType = '🌐 Web Development';
                                        } elseif (stripos($club->club_name, 'scratch') !== false) {
                                            $clubType = '🎨 Scratch';
                                        } elseif (stripos($club->club_name, 'java') !== false) {
                                            $clubType = '☕ Java';
                                        } elseif (stripos($club->club_name, 'mobile') !== false || stripos($club->club_name, 'app') !== false) {
                                            $clubType = '📱 Mobile Development';
                                        } else {
                                            $clubType = '💻 Coding';
                                        }
                                    @endphp
                                    <option value="{{ $club->id }}">{{ $club->club_name }} {{ $clubType }} - {{ $club->school->school_name ?? 'No School' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Name</label>
                            <input name="assessment_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="e.g., Python Basics Quiz" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assessment Type</label>
                            <select name="assessment_type" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                <option value="quiz">📝 Quiz (Multiple Choice Questions)</option>
                                <option value="test">📊 Test (Mixed Questions)</option>
                                <option value="assignment">📋 Assignment (Text Questions)</option>
                                <option value="project">🎯 Project (Practical Work)</option>
                            </select>
                        </div>
                    </div>
                    <!-- Question Management Section -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Add Questions</h4>
                        
                        <!-- Question Type Selection -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <button type="button" onclick="addQuestion('multiple_choice')" class="p-3 border-2 border-blue-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">📝</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Multiple Choice</div>
                                </div>
                            </button>
                            <button type="button" onclick="addQuestion('practical_project')" class="p-3 border-2 border-green-200 rounded-lg hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🎯</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Practical Project</div>
                                </div>
                            </button>
                            <button type="button" onclick="addQuestion('image_question')" class="p-3 border-2 border-purple-200 rounded-lg hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🖼️</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Image Question</div>
                                </div>
                            </button>
                            <button type="button" onclick="addQuestion('text_question')" class="p-3 border-2 border-orange-200 rounded-lg hover:border-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">📝</div>
                                    <div class="text-sm font-medium text-slate-700 dark:text-slate-300">Text Question</div>
                                </div>
                            </button>
                        </div>
                        
                        <!-- Questions Container -->
                        <div id="questionsContainer" class="space-y-4">
                            <p class="text-slate-500 dark:text-slate-400 text-center py-8">Click a question type above to add questions to your assessment</p>
                        </div>
                    </div>
                </form>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200 dark:border-slate-700 flex-shrink-0">
                    <button type="button" @click="showCreate=false" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" form="createAssessmentForm" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                        Create Assessment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle form submission and question management -->
    <script>
        let questionCounter = 0;
        
        document.getElementById('createAssessmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const clubId = document.getElementById('clubSelect').value;
            if (!clubId) {
                alert('Please select a club first.');
                return;
            }
            
            // Update form action with the selected club_id
            this.action = `{{ route('assessments.store', ['club_id' => ':club_id']) }}`.replace(':club_id', clubId);
            
            // Submit the form
            this.submit();
        });
        
        function addQuestion(type) {
            questionCounter++;
            const container = document.getElementById('questionsContainer');
            
            // Remove the placeholder text if it exists
            const placeholder = container.querySelector('p');
            if (placeholder) {
                placeholder.remove();
            }
            
            let questionHtml = '';
            
            switch(type) {
                case 'multiple_choice':
                    questionHtml = createMultipleChoiceQuestion(questionCounter);
                    break;
                case 'practical_project':
                    questionHtml = createPracticalProjectQuestion(questionCounter);
                    break;
                case 'image_question':
                    questionHtml = createImageQuestion(questionCounter);
                    break;
                case 'text_question':
                    questionHtml = createTextQuestion(questionCounter);
                    break;
            }
            
            container.insertAdjacentHTML('beforeend', questionHtml);
        }
        
        function createMultipleChoiceQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Multiple Choice</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="multiple_choice">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text</label>
                            <textarea name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="Enter your question here..." required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option A</label>
                                <input name="questions[${counter}][option_a]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option B</label>
                                <input name="questions[${counter}][option_b]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option C</label>
                                <input name="questions[${counter}][option_c]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Option D</label>
                                <input name="questions[${counter}][option_d]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Correct Answer</label>
                            <select name="questions[${counter}][correct_answer]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                <option value="">Select correct answer</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="1" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function createPracticalProjectQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Practical Project</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="practical_project">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Project Title</label>
                            <input name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="e.g., Create a Calculator App" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Project Instructions</label>
                            <textarea name="questions[${counter}][project_instructions]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="Describe what students need to build..." required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Output Format</label>
                            <select name="questions[${counter}][project_output_format]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                <option value="">Select output format</option>
                                <option value="scratch_project">🎨 Scratch Project</option>
                                <option value="python_file">🐍 Python File</option>
                                <option value="html_file">🌐 HTML File</option>
                                <option value="javascript_file">📜 JavaScript File</option>
                                <option value="mobile_app">📱 Mobile App</option>
                                <option value="robotics_project">🤖 Robotics Project</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Requirements (one per line)</label>
                            <textarea name="questions[${counter}][project_requirements]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="• Must have at least 3 functions&#10;• Should include error handling&#10;• Must be interactive"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="10" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function createImageQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Image Question</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="image_question">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text</label>
                            <textarea name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="What do you see in this image? What is wrong with this code?" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Upload Image</label>
                            <input type="file" name="questions[${counter}][image_file]" accept="image/*" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" onchange="previewImage(this, 'preview-${counter}')">
                            <div id="preview-${counter}" class="mt-2 hidden">
                                <img class="max-w-full h-32 object-contain rounded-lg border border-slate-200 dark:border-slate-600" alt="Image preview">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Image Description</label>
                            <textarea name="questions[${counter}][image_description]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="Describe what students should see or identify in the image..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Expected Answer</label>
                            <textarea name="questions[${counter}][correct_answer]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="What should students identify or explain?"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="5" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function createTextQuestion(counter) {
            return `
                <div class="question-item bg-slate-50 dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-slate-900 dark:text-white">Question ${counter} - Text Question</h5>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="questions[${counter}][type]" value="text_question">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text</label>
                            <textarea name="questions[${counter}][question_text]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="2" placeholder="Explain the concept of..." required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sample Answer (for reference)</label>
                            <textarea name="questions[${counter}][correct_answer]" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" placeholder="Key points students should mention..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Points</label>
                            <input name="questions[${counter}][points]" type="number" value="3" min="1" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function removeQuestion(button) {
            button.closest('.question-item').remove();
            
            // Show placeholder if no questions left
            const container = document.getElementById('questionsContainer');
            if (container.children.length === 0) {
                container.innerHTML = '<p class="text-slate-500 dark:text-slate-400 text-center py-8">Click a question type above to add questions to your assessment</p>';
            }
        }
        
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = preview.querySelector('img');
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
</x-layouts.app>
