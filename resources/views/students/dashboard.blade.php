@extends('layouts.student')

@section('page-title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-white via-blue-100 to-indigo-100 bg-clip-text text-transparent">
                        Welcome back, {{ Auth::guard('student')->user()->student_first_name }}! 👋
                    </h1>
                    <p class="mt-2 text-lg text-slate-300">
                        Track your coding journey and achievements
                    </p>
                </div>
                <div class="hidden sm:block">
                    <div class="text-right">
                        <p class="text-sm text-slate-400">Student ID</p>
                        <p class="text-lg font-mono font-semibold text-white">
                            {{ Auth::guard('student')->user()->student_id_number }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Clubs -->
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/20 shadow-xl hover:shadow-2xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Enrolled Clubs</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['total_clubs'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                        <span class="text-sm text-slate-400">Active memberships</span>
                </div>
            </div>

            <!-- Total Assessments -->
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/20 shadow-xl hover:shadow-2xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Completed Assessments</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['total_assessments'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-clipboard-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-slate-400">Total completed</span>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/20 shadow-xl hover:shadow-2xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Average Score</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['average_assessment_score'], 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-slate-400">Overall performance</span>
                </div>
            </div>

            <!-- Attendance -->
            <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700/20 shadow-xl hover:shadow-2xl transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Attendance Rate</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['attendance_percentage'], 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-slate-400">Session attendance</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Assessments -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-slate-700/20 shadow-xl">
                    <div class="p-6 border-b border-slate-200/60 dark:border-slate-700/60">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Recent Assessments</h2>
                            <a href="{{ route('student.assignments') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentAssessments->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentAssessments as $score)
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-blue-50 dark:from-slate-700/50 dark:to-slate-600/50 rounded-xl border border-slate-200/60 dark:border-slate-600/60 hover:shadow-lg transition-all duration-300">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-clipboard-check text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ $score->assessment ? $score->assessment->assessment_name : 'Unknown Assessment' }}</h3>
                                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $score->assessment && $score->assessment->club ? $score->assessment->club->club_name : 'Unknown Club' }}</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-500">{{ $score->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $percentage = ($score->score_value / $score->score_max_value) * 100;
                                                $color = $percentage >= 80 ? 'green' : ($percentage >= 60 ? 'yellow' : 'red');
                                            @endphp
                                            <div class="text-2xl font-bold text-{{ $color }}-600 mb-1">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ $score->score_value }}/{{ $score->score_max_value }} points
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clipboard-list text-slate-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No assessments yet</h3>
                                <p class="text-slate-600 dark:text-slate-400">Complete your first assessment to see your progress here!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Sessions -->
            <div>
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-slate-700/20 shadow-xl">
                    <div class="p-6 border-b border-slate-200/60 dark:border-slate-700/60">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Upcoming Sessions</h2>
                    </div>
                    <div class="p-6">
                        @if($upcomingSessions->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingSessions->take(3) as $session)
                                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-xl border border-blue-200/60 dark:border-blue-700/60">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar text-white text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-slate-900 dark:text-white">{{ $session->club_name }}</h3>
                                                <p class="text-sm text-slate-600 dark:text-slate-400">Week {{ $session->session_week_number }}</p>
                                                <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                                    {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-times text-slate-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No upcoming sessions</h3>
                                <p class="text-slate-600 dark:text-slate-400">Check back later for new sessions!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-slate-700/20 shadow-xl">
                    <div class="p-6 border-b border-slate-200/60 dark:border-slate-700/60">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Quick Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('student.assignments') }}" 
                           class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-xl border border-blue-200/60 dark:border-blue-700/60 hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-clipboard-list text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white">View Assignments</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Take quizzes and assessments</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                        </a>

                        <a href="{{ route('student.progress') }}" 
                           class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-xl border border-green-200/60 dark:border-green-700/60 hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white">View Progress</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Track your learning journey</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                        </a>

                        <!-- Reports section removed - students should not access their reports -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection