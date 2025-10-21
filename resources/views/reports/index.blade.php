@extends('layouts.student')
@section('title', 'Reports')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Enterprise Header -->
        <div class="bg-gradient-to-r from-slate-800 via-blue-900 to-indigo-900 rounded-3xl shadow-2xl p-8 text-white mb-8 border border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 mr-4">
                            <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-200 to-purple-200 bg-clip-text text-transparent">
                                Student Reports Dashboard
                            </h1>
                            <p class="text-slate-300 text-lg mt-1">Comprehensive performance analytics and reporting system</p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-sm rounded-2xl px-8 py-6 border border-white/20">
                        <div class="text-4xl font-bold text-blue-200">{{ $reports->total() }}</div>
                        <div class="text-sm text-slate-300 font-medium">Total Reports Generated</div>
                        <div class="text-xs text-slate-400 mt-1">Showing {{ $reports->count() }} of {{ $reports->total() }}</div>
                        <div class="text-xs text-slate-400">Enterprise Grade System</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enterprise Filters and Actions Panel -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 dark:from-slate-800 dark:to-slate-700 rounded-3xl shadow-xl p-8 border border-slate-600 dark:border-slate-600 mb-8">
            <div class="space-y-6">
                <!-- Search and Filters Row -->
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <!-- Search Bar -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="bg-emerald-500/20 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <label class="text-sm font-semibold text-slate-300">Search:</label>
                        </div>
                        <form method="GET" action="{{ route('reports.index') }}" class="flex items-center space-x-2">
                            <input type="hidden" name="club_id" value="{{ $clubId }}">
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}"
                                   placeholder="Search by student name, club, or report..."
                                   class="px-4 py-3 border-2 border-slate-600 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-slate-700 dark:bg-slate-700 shadow-sm font-medium text-slate-200 dark:text-slate-200 min-w-[300px] placeholder-slate-400">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                                Search
                            </button>
                            @if($search)
                                <a href="{{ route('reports.index', ['club_id' => $clubId]) }}" 
                                   class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-3 rounded-xl font-semibold transition-all duration-200">
                                    Clear
                                </a>
                            @endif
                        </form>
                    </div>
                    
                    <!-- Advanced Club Filter -->
                <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="bg-blue-500/20 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </div>
                            <label class="text-sm font-semibold text-slate-300">Club Filter:</label>
                        </div>
                        <select onchange="filterByClub(this.value)" 
                                class="px-6 py-3 border-2 border-slate-600 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-700 dark:bg-slate-700 shadow-sm font-medium text-slate-200 dark:text-slate-200 min-w-[200px]">
                        <option value="">All Clubs</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}" {{ $clubId == $club->id ? 'selected' : '' }}>
                                {{ $club->club_name }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                </div>

                <!-- Actions Row -->
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <!-- Generate Reports Action -->
                @if($clubId)
                        <a href="{{ route('reports.generate', ['club_id' => $clubId]) }}" 
                           class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                           title="🤖 AI will automatically analyze assessments and attendance to generate personalized content for each student's report">
                            <div class="bg-white/20 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                            </div>
                            🤖 Generate AI Reports
                    </a>
                    @else
                        <div class="bg-slate-100 rounded-xl px-6 py-3 text-slate-600 font-medium">
                            Select a club to generate reports
                        </div>
                @endif
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        @if($reports->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Reports -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl p-6 border border-slate-600 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Total Reports</p>
                        <p class="text-3xl font-bold text-white">{{ $reports->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl p-6 border border-slate-600 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Average Score</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($reports->avg('report_overall_score') ?? 0, 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Clubs -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl p-6 border border-slate-600 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Active Clubs</p>
                        <p class="text-3xl font-bold text-white">{{ $clubs->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl p-6 border border-slate-600 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">This Week</p>
                        <p class="text-3xl font-bold text-white">{{ $reports->where('report_generated_at', '>=', now()->subWeek())->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-6 border border-slate-600 dark:border-slate-600 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-200">Quick Filters</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-slate-400">Filter by:</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="filterByScore('excellent')" 
                        class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-star mr-2"></i>Excellent (90%+)
                </button>
                <button onclick="filterByScore('good')" 
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-thumbs-up mr-2"></i>Good (70-89%)
                </button>
                <button onclick="filterByScore('average')" 
                        class="px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-minus mr-2"></i>Average (50-69%)
                </button>
                <button onclick="filterByScore('poor')" 
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Needs Improvement (<50%)
                </button>
                <button onclick="clearFilters()" 
                        class="px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white rounded-lg text-sm font-medium transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>Clear All
                </button>
            </div>
        </div>
        @endif

        <!-- Reports Grid -->
        @if($reports->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reports as $report)
                    <div class="bg-slate-800 dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-600 dark:border-slate-600 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 group" 
                         data-report-id="{{ $report->id }}"
                         data-projects-completed="{{ count(json_decode($report->scratch_project_ids ?? '[]', true)) }}"
                         data-skill-score="{{ round($report->report_overall_score) }}"
                         data-attendance-rate="{{ $report->attendance_percentage ?? 0 }}">
                        <!-- Enterprise Report Header -->
                        <div class="bg-gradient-to-br from-slate-700 via-blue-700 to-indigo-800 p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12"></div>
                            <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                        <div class="flex items-center mb-1">
                                            <h3 class="text-xl font-bold text-white student-name">{{ $report->student ? ($report->student->student_first_name . ' ' . $report->student->student_last_name) : 'Unknown Student' }}</h3>
                                            <div class="ml-3 bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1">
                                                <span class="text-xs font-bold text-white">{{ $report->student_initials ?? ($report->student ? strtoupper(substr($report->student->student_first_name, 0, 1) . substr($report->student->student_last_name, 0, 1)) : '??') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center text-blue-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="text-sm font-medium club-name">{{ $report->club->club_name ?? 'Unknown Club' }}</span>
                                            @if($report->student && $report->student->student_grade_level)
                                                <span class="text-xs text-blue-300 ml-2">• {{ $report->student->student_grade_level }}</span>
                                            @endif
                                        </div>
                                </div>
                                <div class="text-right">
                                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-3">
                                            <div class="text-3xl font-bold text-white">{{ round($report->report_overall_score ?? 0) }}%</div>
                                            <div class="text-xs text-blue-200 font-medium">Overall Score</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Content -->
                        <div class="p-6">
                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-slate-200 dark:text-slate-200">{{ round($report->report_overall_score ?? 0) }}%</div>
                                    <div class="text-sm text-slate-400 dark:text-slate-400">Overall Score</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-slate-200 dark:text-slate-200">
                                        @if($report->report_generated_at)
                                            @if(is_string($report->report_generated_at))
                                                {{ \Carbon\Carbon::parse($report->report_generated_at)->format('M j') }}
                                            @else
                                                {{ $report->report_generated_at->format('M j') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="text-sm text-slate-400 dark:text-slate-400">Generated</div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="w-full bg-slate-600 dark:bg-slate-600 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-1000" 
                                         style="width: {{ $report->report_overall_score ?? 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Summary Preview -->
                            <div class="mb-6">
                                <p class="text-slate-400 dark:text-slate-400 text-sm line-clamp-3">{{ Str::limit($report->report_summary_text, 100) }}</p>
                            </div>

                            <!-- Enterprise Action Buttons -->
                            <div class="space-y-3">
                                <!-- Primary Action -->
                                <a href="{{ route('reports.show', $report->id) }}" 
                                   class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Full Report
                                </a>
                                
                                <!-- Secondary Actions Row 1 -->
                                <div class="grid grid-cols-3 gap-1">
                                <a href="{{ route('reports.pdf', $report->id) }}" 
                                       class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-2 py-2 rounded-lg text-xs font-semibold transition-all duration-200 text-center shadow-md hover:shadow-lg flex items-center justify-center">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="showEmailModal({{ $report->id }}, '{{ $report->student->student_parent_email ?? '' }}')" 
                                            class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white px-2 py-2 rounded-lg text-xs font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                    <form method="post" action="{{ route('reports.generate-ai-single', $report->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white px-2 py-2 rounded-lg text-xs font-semibold transition-all duration-200 text-center shadow-md hover:shadow-lg flex items-center justify-center"
                                                onclick="return confirm('Generate AI content for {{ $report->student ? ($report->student->student_first_name . ' ' . $report->student->student_last_name) : 'Unknown Student' }}?')"
                                                title="🤖 Generate AI content for this student">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- CRUD Actions Row 2 -->
                                <div class="grid grid-cols-3 gap-1">
                                    <a href="{{ route('reports.edit', $report->id) }}" 
                                       class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white px-2 py-2 rounded-lg text-xs font-semibold transition-all duration-200 text-center shadow-md hover:shadow-lg flex items-center justify-center">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="post" action="{{ route('reports.regenerate-access-code', $report->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600 text-white px-2 py-2 rounded-lg text-xs font-semibold transition-all duration-200 text-center shadow-md hover:shadow-lg flex items-center justify-center"
                                                onclick="return confirm('Regenerate access code for this report?')">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <form method="post" action="{{ route('reports.destroy', $report->id) }}" class="inline">
                                    @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-2 py-2 rounded-lg text-xs font-semibold transition-all duration-200 text-center shadow-md hover:shadow-lg flex items-center justify-center"
                                                onclick="return confirm('Are you sure you want to delete this report? This action cannot be undone.')">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                    </button>
                                </form>
                                </div>
                            </div>
                        </div>

                        <!-- Report Footer -->
                        <div class="bg-slate-700/50 dark:bg-slate-700/50 px-6 py-4 border-t border-slate-600 dark:border-slate-600">
                            <div class="space-y-3">
                                <!-- Generated Date -->
                            <div class="flex items-center justify-between text-sm text-slate-400 dark:text-slate-400">
                                <span>
                                    @if($report->report_generated_at)
                                        @if(is_string($report->report_generated_at))
                                            {{ \Carbon\Carbon::parse($report->report_generated_at)->format('M j, Y') }}
                                        @else
                                            {{ $report->report_generated_at->format('M j, Y') }}
                                        @endif
                                    @else
                                        Not generated
                                    @endif
                                </span>
                                </div>
                                
                                <!-- Access Code with Copy Feature -->
                                @if($report->access_code)
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-xs font-medium text-blue-700 mb-1">Access Code</div>
                                                <div class="text-sm font-mono font-bold text-blue-900 bg-white px-2 py-1 rounded border access-code">
                                                    {{ $report->access_code->access_code_plain_preview }}
                                                </div>
                                            </div>
                                            <button onclick="copyAccessCode('{{ $report->access_code->access_code_plain_preview }}', this)" 
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-all duration-200 flex items-center justify-center group">
                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-100 rounded-lg p-3 border border-gray-200">
                                        <div class="text-xs font-medium text-gray-600">No Access Code</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reports Found</h3>
                <p class="text-gray-600 mb-6">
                    @if($clubId)
                        No reports have been generated for this club yet.
                    @else
                        No reports have been generated yet. Select a club to generate reports.
                    @endif
                </p>
                @if($clubId)
                    <a href="{{ route('reports.generate', ['club_id' => $clubId]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center"
                       title="🤖 AI will automatically analyze assessments and attendance to generate personalized content for each student's report">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        🤖 Generate AI Reports
                    </a>
                @else
                    <p class="text-sm text-gray-500">Select a club from the filter above to generate reports.</p>
                @endif
            </div>
        @endif

        <!-- Enterprise Pagination -->
        @if($reports->hasPages())
            <div class="mt-8">
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-600">
                            Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} results
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $reports->links() }}
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-slate-600">Per page:</span>
                            <select onchange="changePerPage(this.value)" 
                                    class="px-3 py-1 border border-slate-300 rounded-lg text-sm">
                                <option value="6" {{ $perPage == 6 ? 'selected' : '' }}>6</option>
                                <option value="12" {{ $perPage == 12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>24</option>
                                <option value="48" {{ $perPage == 48 ? 'selected' : '' }}>48</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Email Input Modal with Glassmorphism -->
    <div id="emailModal" class="fixed inset-0 bg-white/10 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl max-w-md w-full p-8 shadow-2xl border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Send Report to Parent</h3>
                <button onclick="closeEmailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="emailForm" method="post" action="">
                @csrf
                <div class="mb-4">
                    <label for="parentEmail" class="block text-sm font-medium text-gray-700 mb-2">
                        Parent/Guardian Email Address
                    </label>
                    <input type="email" 
                           id="parentEmail" 
                           name="parent_email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                           placeholder="Enter parent's email address"
                           required>
                    <p class="text-xs text-gray-500 mt-1">
                        Current email on file: <span id="currentEmail">Not provided</span>
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeEmailModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Send Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EmailJS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    
    <!-- JavaScript for filtering and email modal -->
    <script>
        // Initialize EmailJS
        (function() {
            emailjs.init("w4BP3cPYFveExmYVj"); // Your EmailJS public key
        })();

        function filterByClub(clubId) {
            if (clubId) {
                window.location.href = '{{ route("reports.index") }}?club_id=' + clubId;
            } else {
                window.location.href = '{{ route("reports.index") }}';
            }
        }

        // Global variables to store report data
        let currentReportData = {};

        // Email Modal Functions
        function showEmailModal(reportId, currentEmail) {
            // Store report data for EmailJS
            currentReportData = {
                reportId: reportId,
                studentName: document.querySelector(`[data-report-id="${reportId}"] .student-name`)?.textContent || 'Student',
                clubName: document.querySelector(`[data-report-id="${reportId}"] .club-name`)?.textContent || 'Coding Club',
                accessCode: document.querySelector(`[data-report-id="${reportId}"] .access-code`)?.textContent || 'N/A',
                reportUrl: `${window.location.origin}/parent-welcome`,
                projectsCompleted: document.querySelector(`[data-report-id="${reportId}"]`)?.dataset.projectsCompleted || '0',
                skillScore: document.querySelector(`[data-report-id="${reportId}"]`)?.dataset.skillScore || '0',
                attendanceRate: document.querySelector(`[data-report-id="${reportId}"]`)?.dataset.attendanceRate || '0'
            };
            
            document.getElementById('emailForm').action = '{{ route("reports.send", ":id") }}'.replace(':id', reportId);
            document.getElementById('parentEmail').value = currentEmail || '';
            document.getElementById('currentEmail').textContent = currentEmail || 'Not provided';
            document.getElementById('emailModal').classList.remove('hidden');
            document.getElementById('parentEmail').focus();
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
        }

        // Close email modal when clicking outside
        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEmailModal();
            }
        });

        // Email form submission with EmailJS
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const parentEmail = document.getElementById('parentEmail').value;
            
            if (!parentEmail) {
                showToast('Please enter a valid email address', 'error');
                return;
            }
            
            // Update button to show loading state
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Sending...
            `;
            submitBtn.disabled = true;
            
            // Prepare EmailJS template parameters
            const templateParams = {
                to_email: parentEmail,
                student_name: currentReportData.studentName,
                club_name: currentReportData.clubName,
                access_code: currentReportData.accessCode,
                report_url: currentReportData.reportUrl,
                sender_name: 'Code Academy Uganda',
                current_date: new Date().toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }),
                projects_completed: currentReportData.projectsCompleted || '0',
                skill_score: currentReportData.skillScore || '0',
                attendance_rate: currentReportData.attendanceRate || '0'
            };
            
            // EmailJS is now configured - use real email sending
            if (false) { // Changed to false to use real EmailJS
                // EmailJS not configured yet - show test message
                setTimeout(() => {
                    showToast(`Test: Report would be sent to ${parentEmail}!`, 'success');
                    alert(`✅ Test Success!\n\nReport would be sent to: ${parentEmail}\nStudent: ${currentReportData.studentName}\nAccess Code: ${currentReportData.accessCode}\n\nTo enable actual email sending, configure your EmailJS credentials.`);
                    closeEmailModal();
                    
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 1000);
            } else {
                // Send email using EmailJS (when configured)
                emailjs.send('service_c38n38w', 'template_0o15p1m', templateParams)
                    .then(function(response) {
                        console.log('Email sent successfully!', response.status, response.text);
                        showToast(`Report sent successfully to ${parentEmail}!`, 'success');
                        closeEmailModal();
                    }, function(error) {
                        console.error('Failed to send email:', error);
                        showToast('Failed to send email. Please try again.', 'error');
                        alert(`❌ Email Error: ${error.text || 'Unknown error occurred'}`);
                    })
                    .finally(function() {
                        // Reset button
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            }
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            console.log('Showing toast:', message, type); // Debug log
            
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl border-2 transition-all duration-300 transform ${
                type === 'success' 
                    ? 'bg-green-500 text-white border-green-600' 
                    : 'bg-red-500 text-white border-red-600'
            }`;
            
            toast.style.transform = 'translateX(100%)'; // Start off-screen
            toast.style.transition = 'transform 0.3s ease-in-out, opacity 0.3s ease-in-out';
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <div>
                        <div class="font-semibold">${type === 'success' ? 'Success!' : 'Error!'}</div>
                        <div class="text-sm opacity-90">${message}</div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 4000);
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEmailModal();
            }
        });

        // Pagination function
        function changePerPage(value) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }

        // Copy access code function
        function copyAccessCode(code, button) {
            navigator.clipboard.writeText(code).then(function() {
                // Show success feedback
                const originalHTML = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600');
                
                // Reset after 2 seconds
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 2000);
                
                // Show toast notification
                showToast('Access code copied to clipboard!', 'success');
            }).catch(function(err) {
                console.error('Failed to copy access code: ', err);
                showToast('Failed to copy access code', 'error');
            });
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
    </script>

    <!-- Quick Filter Functions -->
    <script>
        function filterByScore(level) {
            const reports = document.querySelectorAll('[data-report-id]');
            reports.forEach(report => {
                const score = parseInt(report.querySelector('.text-3xl.font-bold.text-white').textContent);
                let show = false;
                
                switch(level) {
                    case 'excellent':
                        show = score >= 90;
                        break;
                    case 'good':
                        show = score >= 70 && score < 90;
                        break;
                    case 'average':
                        show = score >= 50 && score < 70;
                        break;
                    case 'poor':
                        show = score < 50;
                        break;
                }
                
                report.style.display = show ? 'block' : 'none';
            });
            
            // Update button states
            document.querySelectorAll('[onclick^="filterByScore"]').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-white', 'ring-opacity-50');
            });
            event.target.classList.add('ring-2', 'ring-white', 'ring-opacity-50');
        }
        
        function clearFilters() {
            const reports = document.querySelectorAll('[data-report-id]');
            reports.forEach(report => {
                report.style.display = 'block';
            });
            
            // Remove active states from filter buttons
            document.querySelectorAll('[onclick^="filterByScore"], [onclick="clearFilters()"]').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-white', 'ring-opacity-50');
            });
        }
        
        function filterByClub(clubId) {
            if (clubId) {
                window.location.href = '{{ route("reports.index") }}?club_id=' + clubId;
            } else {
                window.location.href = '{{ route("reports.index") }}';
            }
        }
    </script>

    <!-- Success Message -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.top-4.right-4').remove();
            }, 5000);
        </script>
    @endif
@endsection
