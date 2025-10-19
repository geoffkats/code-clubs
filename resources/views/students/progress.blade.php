@extends('layouts.student')

@section('page-title', 'My Progress')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Progress</h1>
            <p class="mt-2 text-gray-600">Track your learning journey across all clubs</p>
        </div>

        <!-- Overall Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Clubs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $student->clubs->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed Assessments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $student->assessment_scores->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Average Score</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($student->getAverageAssessmentScore(), 1) }}%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Attendance</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($student->getAttendancePercentage(), 1) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Club Progress -->
        <div class="space-y-8">
            @forelse($progressData as $data)
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $data['club']->club_name }}</h2>
                                <p class="text-gray-600">{{ $data['club']->club_description ?? 'Coding Club' }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($data['average_score'], 1) }}%</div>
                                <div class="text-sm text-gray-500">Average Score</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Assessment Progress -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-medium text-blue-900">Assessments</h3>
                                    <span class="text-sm text-blue-600">{{ $data['completed_assessments'] }}/{{ $data['total_assessments'] }}</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['completion_rate'] }}%"></div>
                                </div>
                                <p class="text-sm text-blue-700 mt-1">{{ number_format($data['completion_rate'], 1) }}% completed</p>
                            </div>

                            <!-- Score Progress -->
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-medium text-green-900">Performance</h3>
                                    <span class="text-sm text-green-600">{{ number_format($data['average_score'], 1) }}%</span>
                                </div>
                                <div class="w-full bg-green-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $data['average_score'] }}%"></div>
                                </div>
                                <p class="text-sm text-green-700 mt-1">
                                    @if($data['average_score'] >= 80)
                                        Excellent
                                    @elseif($data['average_score'] >= 70)
                                        Good
                                    @elseif($data['average_score'] >= 60)
                                        Satisfactory
                                    @else
                                        Needs Improvement
                                    @endif
                                </p>
                            </div>

                            <!-- Attendance Progress -->
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-medium text-purple-900">Attendance</h3>
                                    <span class="text-sm text-purple-600">{{ number_format($data['attendance_percentage'], 1) }}%</span>
                                </div>
                                <div class="w-full bg-purple-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $data['attendance_percentage'] }}%"></div>
                                </div>
                                <p class="text-sm text-purple-700 mt-1">
                                    @if($data['attendance_percentage'] >= 90)
                                        Outstanding
                                    @elseif($data['attendance_percentage'] >= 80)
                                        Good
                                    @elseif($data['attendance_percentage'] >= 70)
                                        Fair
                                    @else
                                        Needs Improvement
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Recent Assessments in this Club -->
                        @if($data['club']->assessments->count() > 0)
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Assessments</h3>
                                <div class="space-y-3">
                                    @foreach($data['club']->assessments->take(3) as $assessment)
                                        @php
                                            $score = $assessment->scores->where('student_id', $student->id)->first();
                                        @endphp
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $assessment->assessment_name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $assessment->assessment_type }} • {{ $assessment->questions->count() }} questions</p>
                                            </div>
                                            <div class="text-right">
                                                @if($score)
                                                    @php
                                                        $percentage = ($score->score_value / $score->score_max_value) * 100;
                                                        $color = $percentage >= 80 ? 'green' : ($percentage >= 60 ? 'yellow' : 'red');
                                                    @endphp
                                                    <div class="text-lg font-bold text-{{ $color }}-600">{{ number_format($percentage, 1) }}%</div>
                                                    <div class="text-xs text-gray-500">{{ $score->created_at->format('M d') }}</div>
                                                @else
                                                    <span class="text-sm text-gray-500">Not completed</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No clubs enrolled</h3>
                    <p class="mt-1 text-sm text-gray-500">You're not enrolled in any clubs yet. Contact your instructor to get started.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
