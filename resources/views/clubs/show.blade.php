<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             activeTab: 'overview',
             showStudent: false,
             selectedClubId: {{ $club->id }}
         }">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('clubs.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                                {{ $club->club_name }}
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $club->school->school_name }} • {{ ucfirst($club->club_level ?? 'Not specified') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="showStudent=true" class="group relative overflow-hidden rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Add Student</span>
                            </div>
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
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Students</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $club->students_count }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Sessions</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $club->sessions_count }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Assessments</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $club->assessments_count }}</p>
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
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Duration</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $club->club_duration_weeks ?? 8 }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">weeks</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 mb-8">
                <div class="border-b border-slate-200 dark:border-slate-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'overview'" 
                                :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Overview
                        </button>
                        <button @click="activeTab = 'students'" 
                                :class="activeTab === 'students' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Students ({{ $club->students_count }})
                        </button>
                        <button @click="activeTab = 'attendance'" 
                                :class="activeTab === 'attendance' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Attendance
                        </button>
                        <button @click="activeTab = 'assessments'" 
                                :class="activeTab === 'assessments' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Assessments ({{ $club->assessments_count }})
                        </button>
                        <button @click="activeTab = 'reports'" 
                                :class="activeTab === 'reports' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Reports
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Club Information -->
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Club Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-slate-600 dark:text-slate-400">Name:</span>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $club->club_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-600 dark:text-slate-400">Level:</span>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ ucfirst($club->club_level ?? 'Not specified') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-600 dark:text-slate-400">Duration:</span>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $club->club_duration_weeks ?? 8 }} weeks</span>
                                    </div>
                                    @if($club->club_start_date)
                                        <div class="flex justify-between">
                                            <span class="text-slate-600 dark:text-slate-400">Start Date:</span>
                                            <span class="font-medium text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($club->club_start_date)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-slate-600 dark:text-slate-400">School:</span>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $club->school->school_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h3>
                                <div class="space-y-3">
                                    <a href="{{ route('attendance.grid', ['club_id' => $club->id, 'week' => 1]) }}" class="flex items-center space-x-3 p-3 bg-white dark:bg-slate-800 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-white">Take Attendance</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">Mark student attendance</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('assessments.create', ['club_id' => $club->id]) }}" class="flex items-center space-x-3 p-3 bg-white dark:bg-slate-800 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-white">Create Assessment</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400">Add new assessment</p>
                                        </div>
                                    </a>
                                    <form method="post" action="{{ route('reports.generate', ['club_id' => $club->id]) }}" class="contents">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center space-x-3 p-3 bg-white dark:bg-slate-800 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900 dark:text-white">Generate Report</p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">Create progress report</p>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Tab -->
                    <div x-show="activeTab === 'students'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Students ({{ $club->students_count }})</h3>
                            <button @click="showStudent=true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Add Student
                            </button>
                        </div>
                        
                        @if($club->students->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($club->students as $student)
                                    <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900 dark:text-white">{{ $student->student_first_name }} {{ $student->student_last_name }}</p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">Grade {{ $student->student_grade_level }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No students yet</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-4">Add students to this club to get started</p>
                                <button @click="showStudent=true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Add First Student
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Attendance Tab -->
                    <div x-show="activeTab === 'attendance'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Attendance</h3>
                            <a href="{{ route('attendance.grid', ['club_id' => $club->id, 'week' => 1]) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                View Attendance Grid
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-6 text-center">
                                <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $attendanceSummary['total_sessions'] }}</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Total Sessions</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-6 text-center">
                                <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $attendanceSummary['attended_sessions'] }}</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Attended Sessions</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-6 text-center">
                                <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $attendanceSummary['average_attendance'] }}%</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Average Attendance</div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessments Tab -->
                    <div x-show="activeTab === 'assessments'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Assessments ({{ $club->assessments_count }})</h3>
                            <a href="{{ route('assessments.create', ['club_id' => $club->id]) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                Create Assessment
                            </a>
                        </div>
                        
                        @if($recentAssessments->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentAssessments as $assessment)
                                    <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-medium text-slate-900 dark:text-white">{{ $assessment->assessment_name }}</h4>
                                                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg text-xs font-medium">
                                                        {{ ucfirst($assessment->assessment_type) }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-4 text-sm text-slate-600 dark:text-slate-400">
                                                    <span>{{ $assessment->created_at->format('M d, Y') }}</span>
                                                    <span>{{ $assessment->questions->count() }} questions</span>
                                                    @if($assessment->questions->count() > 0)
                                                        <span class="text-xs">
                                                            ({{ $assessment->questions->where('question_type', 'multiple_choice')->count() }} MC, 
                                                            {{ $assessment->questions->where('question_type', 'practical_project')->count() }} Projects)
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right ml-4">
                                                <div class="text-lg font-bold text-slate-900 dark:text-white">{{ $assessment->scores->count() }}</div>
                                                <div class="text-sm text-slate-600 dark:text-slate-400">scores</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No assessments yet</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-4">Create assessments to track student progress</p>
                                <a href="{{ route('assessments.create', ['club_id' => $club->id]) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                    Create First Assessment
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Reports Tab -->
                    <div x-show="activeTab === 'reports'" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Reports</h3>
                            <form method="post" action="{{ route('reports.generate', ['club_id' => $club->id]) }}" class="contents">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    Generate Report
                                </button>
                            </form>
                        </div>
                        
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Generate Reports</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-4">Create comprehensive reports for this club</p>
                            <form method="post" action="{{ route('reports.generate', ['club_id' => $club->id]) }}" class="contents">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    Generate Club Report
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Student Modal -->
        <div x-show="showStudent" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Add Student to Club</h3>
                    <button @click="showStudent=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="post" action="{{ route('students.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="club_id" value="{{ $club->id }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">First Name</label>
                            <input name="student_first_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Last Name</label>
                            <input name="student_last_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Grade Level</label>
                        <select name="student_grade_level" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Select Grade</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">Grade {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Parent Name</label>
                        <input name="student_parent_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Parent Email</label>
                        <input type="email" name="student_parent_email" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showStudent=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
