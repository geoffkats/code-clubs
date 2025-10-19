@extends('layouts.student')

@section('page-title', 'My Reports')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 fs:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Reports</h1>
            <p class="mt-2 text-gray-600">View your progress reports and achievements</p>
        </div>

        <!-- Reports List -->
        @if($reports->count() > 0)
            <div class="space-y-6">
                @foreach($reports as $report)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-3">
                                        <h2 class="text-xl font-bold text-gray-900 mr-3">{{ $report->club->club_name }} Progress Report</h2>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $report->report_period ?? 'Progress Report' }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-blue-50 rounded-lg p-3">
                                            <p class="text-sm font-medium text-blue-700">Problem Solving</p>
                                            <p class="text-2xl font-bold text-blue-900">{{ $report->problem_solving_score ?? 'N/A' }}/10</p >
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3">
                                            <p class="text-sm font-medium text-green-700">Creativity</p>
                                            <p class="text-2xl font-bold text-green-900">{{ $report->creativity_score ?? 'N/A' }}/10</p>
                                        </div>
                                        <div class="bg-yellow-50 rounded-lg p-3">
                                            <p class="text-sm font-medium text-yellow-700">Collaboration</p>
                                            <p class="text-2xl font-bold text-yellow-900">{{ $report->collaboration_score ?? 'N/A' }}/10</p>
                                        </div>
                                        <div class="bg-purple-50 rounded-lg p-3">
                                            <p class="text-sm font-medium text-purple-700">Persistence</p>
                                            <p class="text-2xl font-bold text-purple-900">{{ $report->persistence_score ?? 'N/A' }}/10</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                        <span>Generated on {{ $report->created_at->format('M d, Y') }}</span>
                                        @if($report->access_code)
                                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">Access Code: {{ $report->access_code }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Report Content Preview -->
                                    @if($report->favorite_concept || $report->challenges_overcome)
                                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                            @if($report->favorite_concept)
                                                <div class="mb-3">
                                                    <h4 class="font-medium text-gray-900 mb-1">Favorite Concept</h4>
                                                    <p class="text-gray-700 text-sm">{{ $report->favorite_concept }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($report->challenges_overcome)
                                                <div class="                                         <h4 class="font-medium text-gray-900 mb-1">Challenges Overcome</h4>
                                                    <p class="text-gray-700 text-sm">{{ Str::limit($report->challenges_overcome, 150) }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="ml-6 flex flex-col space-y-2">
                                    <a href="{{ route('reports.show', $report->id) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
                                        View Full Report
                                    </a>
                                    
                                    <a href="{{ route('reports.pdf', $report->id) }}" 
                                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-center font-medium">
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No reports available</h3>
                <p class="mt-1 text-sm text-gray-500">Reports will be generated as you progress through your clubs and complete assessments.</p>
                <div class="mt-6">
                    <a href="{{ route('student.assignments') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Start Taking Assessments
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
