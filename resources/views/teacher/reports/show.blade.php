@extends('layouts.teacher')

@section('title', 'Report Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $report->report_name }}</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Student progress report</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('teacher.reports') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Reports
            </a>
            @if($report->status === 'revision_requested')
                <a href="{{ route('teacher.reports.edit', $report) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Report
                </a>
            @endif
        </div>
    </div>

    <!-- Report Status -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Report Status</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Current approval status</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if($report->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                @elseif($report->status === 'facilitator_approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                @elseif($report->status === 'admin_approved') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                @elseif($report->status === 'revision_requested') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                @elseif($report->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 @endif">
                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
            </span>
        </div>
    </div>

    <!-- Report Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Information -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Student Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Student Name</label>
                    <p class="text-slate-900 dark:text-white">{{ $report->student->student_first_name ?? 'N/A' }} {{ $report->student->student_last_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Club</label>
                    <p class="text-slate-900 dark:text-white">{{ $report->club->club_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Grade Level</label>
                    <p class="text-slate-900 dark:text-white">{{ $report->student->student_grade_level ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Created</label>
                    <p class="text-slate-900 dark:text-white">{{ $report->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Assessment Scores -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Assessment Scores</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Problem Solving</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $report->problem_solving_score ?? 'N/A' }}/100</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($report->problem_solving_score ?? 0) }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Creativity</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $report->creativity_score ?? 'N/A' }}/100</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($report->creativity_score ?? 0) }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Collaboration</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $report->collaboration_score ?? 'N/A' }}/100</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($report->collaboration_score ?? 0) }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Persistence</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $report->persistence_score ?? 'N/A' }}/100</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-orange-600 h-2 rounded-full" style="width: {{ ($report->persistence_score ?? 0) }}%"></div>
                    </div>
                </div>
                
                @if($report->report_overall_score)
                <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-lg font-semibold text-slate-900 dark:text-white">Overall Score</span>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $report->report_overall_score }}/100</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $report->report_overall_score }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Report Timeline -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Report Timeline</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">Report Created</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $report->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                
                @if($report->facilitator_approved_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">Facilitator Approved</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $report->facilitator_approved_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                @endif
                
                @if($report->admin_approved_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">Admin Approved</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $report->admin_approved_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Report Content</h3>
        
        @if($report->report_summary_text)
        <div class="mb-6">
            <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Summary</h4>
            <p class="text-slate-700 dark:text-slate-300">{{ $report->report_summary_text }}</p>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($report->favorite_concept)
            <div>
                <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Favorite Concept</h4>
                <p class="text-slate-700 dark:text-slate-300">{{ $report->favorite_concept }}</p>
            </div>
            @endif
            
            @if($report->challenges_overcome)
            <div>
                <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Challenges Overcome</h4>
                <p class="text-slate-700 dark:text-slate-300">{{ $report->challenges_overcome }}</p>
            </div>
            @endif
            
            @if($report->special_achievements)
            <div>
                <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Special Achievements</h4>
                <p class="text-slate-700 dark:text-slate-300">{{ $report->special_achievements }}</p>
            </div>
            @endif
            
            @if($report->areas_for_growth)
            <div>
                <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Areas for Growth</h4>
                <p class="text-slate-700 dark:text-slate-300">{{ $report->areas_for_growth }}</p>
            </div>
            @endif
            
            @if($report->next_steps)
            <div>
                <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Next Steps</h4>
                <p class="text-slate-700 dark:text-slate-300">{{ $report->next_steps }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Feedback Section -->
    @if($report->facilitator_feedback || $report->admin_feedback)
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Feedback</h3>
        
        @if($report->facilitator_feedback)
        <div class="mb-4">
            <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Facilitator Feedback</h4>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <p class="text-slate-700 dark:text-slate-300">{{ $report->facilitator_feedback }}</p>
            </div>
        </div>
        @endif
        
        @if($report->admin_feedback)
        <div>
            <h4 class="text-md font-medium text-slate-900 dark:text-white mb-2">Admin Feedback</h4>
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <p class="text-slate-700 dark:text-slate-300">{{ $report->admin_feedback }}</p>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
