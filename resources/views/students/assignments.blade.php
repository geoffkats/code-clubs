@extends('layouts.student')

@section('page-title', 'Assignments & Quizzes')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Assignments & Quizzes</h1>
            <p class="mt-2 text-gray-600">Complete your assessments and track your progress</p>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('pending')" id="pending-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                    Pending Assessments
                </button>
                <button onclick="showTab('completed')" id="completed-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Completed Assessments
                </button>
            </nav>
        </div>

        <!-- Pending Assessments Tab -->
        <div id="pending-content" class="tab-content">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Pending Assessments</h2>
                    <p class="text-gray-600">Complete these assessments to improve your skills</p>
                </div>
                <div class="p-6">
                    @if($pendingAssessments->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($pendingAssessments as $assessment)
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200 hover:shadow-lg transition-shadow">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                @switch($assessment->assessment_type)
                                                    @case('quiz')
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        @break
                                                    @case('assignment')
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        @break
                                                    @case('project')
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                        </svg>
                                                        @break
                                                    @default
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                @endswitch
                                            </div>
                                            <div class="ml-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($assessment->assessment_type) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $assessment->assessment_name }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">{{ $assessment->club->club_name }}</p>
                                    
                                    @if($assessment->description)
                                        <p class="text-sm text-gray-700 mb-4 line-clamp-2">{{ $assessment->description }}</p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                        <span>{{ $assessment->questions->count() }} questions</span>
                                        <span>{{ $assessment->total_points ?? $assessment->questions->sum('points') }} points</span>
                                    </div>
                                    
                                    @if($assessment->due_date)
                                        <div class="mb-4">
                                            <span class="text-sm font-medium text-gray-700">Due: </span>
                                            <span class="text-sm {{ $assessment->due_date < now() ? 'text-red-600' : 'text-gray-600' }}">
                                                {{ \Carbon\Carbon::parse($assessment->due_date)->format('M d, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <a href="{{ route('student.assessment.show', $assessment->id) }}" 
                                       class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center block font-medium">
                                        Start Assessment
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending assessments</h3>
                            <p class="mt-1 text-sm text-gray-500">Great job! You've completed all available assessments.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Completed Assessments Tab -->
        <div id="completed-content" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Completed Assessments</h2>
                    <p class="text-gray-600">Review your completed assessments and scores</p>
                </div>
                <div class="p-6">
                    @if($completedAssessments->count() > 0)
                        <div class="space-y-4">
                            @foreach($completedAssessments as $assessment)
                                @php
                                    $score = $assessment->scores->where('student_id', auth()->guard('student')->id())->first();
                                    $percentage = $score ? ($score->score_value / $score->score_max_value) * 100 : 0;
                                    $scoreColor = $percentage >= 80 ? 'green' : ($percentage >= 60 ? 'yellow' : 'red');
                                @endphp
                                
                                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h3 class="text-lg font-bold text-gray-900 mr-3">{{ $assessment->assessment_name }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($assessment->assessment_type) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">{{ $assessment->club->club_name }}</p>
                                            <p class="text-xs text-gray-500">
                                                Completed on {{ $score->submitted_at->format('M d, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                        
                                        <div class="text-right">
                                            <div class="text-3xl font-bold text-{{ $scoreColor }}-600 mb-1">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $score->score_value }}/{{ $score->score_max_value }} points
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('student.assessment.show', $assessment->id) }}" 
                                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No completed assessments</h3>
                            <p class="mt-1 text-sm text-gray-500">Start taking assessments to see your results here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    document.getElementById(tabName + '-tab').classList.remove('border-transparent', 'text-gray-500');
    document.getElementById(tabName + '-tab').classList.add('border-blue-500', 'text-blue-600');
}
</script>
@endsection
