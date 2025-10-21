@extends('layouts.admin')
@section('title', $report->report_name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-lg p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">📊 Student Report</h1>
                    <p class="text-blue-100 text-lg">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</p>
                    <p class="text-blue-200">{{ $report->club->club_name }}</p>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 rounded-lg px-6 py-4">
                        <div class="text-3xl font-bold">{{ round($report->report_overall_score) }}%</div>
                        <div class="text-sm text-blue-100">Overall Score</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-8">
            <button onclick="showEmailModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send to Parent
                </button>
            
            <a href="{{ route('reports.public', ['report_id' => $report->id]) }}?code={{ $report->access_code?->access_code_plain_preview ?? 'demo' }}" 
               target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Parent Report
            </a>
            
            <a href="{{ route('reports.pdf', $report->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                🖨️ Print PDF
            </a>
        </div>

        <!-- Report Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Student Header with Initials -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-lg p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold mb-2">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</h2>
                            <p class="text-blue-100 text-lg">{{ $report->club->club_name }}</p>
                            @if($report->student->student_grade_level)
                                <p class="text-blue-200 text-sm">{{ $report->student->student_grade_level }}</p>
                            @endif
                            <p class="text-blue-200 text-sm mt-1">Coding Club Report</p>
                        </div>
                        <div class="text-right">
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-4">
                                <div class="text-4xl font-bold">{{ $report->student_initials ?? strtoupper(substr($report->student->student_first_name, 0, 1) . substr($report->student->student_last_name, 0, 1)) }}</div>
                                <div class="text-sm text-blue-200">Student Initials</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Report Summary</h2>
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6">
                        <p class="text-gray-700 leading-relaxed text-lg">{{ $report->report_summary_text }}</p>
                    </div>
                </div>

                <!-- Coding Skills Assessment -->
                @if($report->problem_solving_score || $report->creativity_score || $report->collaboration_score || $report->persistence_score)
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🎮 Coding Skills Assessment</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($report->problem_solving_score)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-green-800">🧩 Problem Solving</h3>
                                <div class="text-2xl font-bold text-green-600">{{ $report->problem_solving_score }}/10</div>
                            </div>
                            <div class="w-full bg-green-200 rounded-full h-3">
                                <div class="bg-green-600 h-3 rounded-full" style="width: {{ ($report->problem_solving_score / 10) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif

                        @if($report->creativity_score)
                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-purple-800">🎨 Creativity & Innovation</h3>
                                <div class="text-2xl font-bold text-purple-600">{{ $report->creativity_score }}/10</div>
                            </div>
                            <div class="w-full bg-purple-200 rounded-full h-3">
                                <div class="bg-purple-600 h-3 rounded-full" style="width: {{ ($report->creativity_score / 10) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif

                        @if($report->collaboration_score)
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-blue-800">🤝 Teamwork & Collaboration</h3>
                                <div class="text-2xl font-bold text-blue-600">{{ $report->collaboration_score }}/10</div>
                            </div>
                            <div class="w-full bg-blue-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ ($report->collaboration_score / 10) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif

                        @if($report->persistence_score)
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-orange-800">💪 Persistence & Perseverance</h3>
                                <div class="text-2xl font-bold text-orange-600">{{ $report->persistence_score }}/10</div>
                            </div>
                            <div class="w-full bg-orange-200 rounded-full h-3">
                                <div class="bg-orange-600 h-3 rounded-full" style="width: {{ ($report->persistence_score / 10) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Scratch Projects -->
                @if($report->scratch_project_ids)
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🎮 Scratch Projects</h2>
                    <div class="space-y-4">
                        @php
                            $projectIds = json_decode($report->scratch_project_ids, true) ?? [];
                        @endphp
                        @if(count($projectIds) > 0)
                            @foreach($projectIds as $projectId)
                                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border border-orange-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-orange-800 mb-2">🎨 Scratch Project</h3>
                                            <p class="text-orange-700 font-mono text-sm">Project ID: {{ $projectId }}</p>
                                        </div>
                                        <div class="flex space-x-3">
                                            <a href="https://scratch.mit.edu/projects/{{ $projectId }}" 
                                               target="_blank"
                                               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                View Project
                                            </a>
                                            <button onclick="previewProject('{{ $projectId }}')"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Preview
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 text-center">
                                <p class="text-gray-600">No Scratch projects attached yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Projects and Achievements -->
                @if($report->favorite_concept || $report->special_achievements)
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🏆 Achievements & Learning</h2>
                    <div class="space-y-6">
                        @if($report->favorite_concept)
                        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl p-6 border border-yellow-200">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-3">⭐ Favorite Coding Concept</h3>
                            <p class="text-yellow-700 text-lg font-medium">{{ $report->favorite_concept }}</p>
                        </div>
                        @endif

                        @if($report->special_achievements)
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                            <h3 class="text-lg font-semibold text-indigo-800 mb-3">🏅 Special Achievements & Recognition</h3>
                            <p class="text-indigo-700 leading-relaxed">{{ $report->special_achievements }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Learning Journey -->
                @if($report->challenges_overcome || $report->areas_for_growth || $report->next_steps)
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">🚀 Learning Journey</h2>
                    <div class="space-y-6">
                        @if($report->challenges_overcome)
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-200">
                            <h3 class="text-lg font-semibold text-red-800 mb-3">🏆 Challenges Overcome</h3>
                            <p class="text-red-700 leading-relaxed">{{ $report->challenges_overcome }}</p>
                        </div>
                        @endif

                        @if($report->areas_for_growth)
                        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl p-6 border border-teal-200">
                            <h3 class="text-lg font-semibold text-teal-800 mb-3">🌱 Areas for Growth</h3>
                            <p class="text-teal-700 leading-relaxed">{{ $report->areas_for_growth }}</p>
                        </div>
                        @endif

                        @if($report->next_steps)
                        <div class="bg-gradient-to-r from-violet-50 to-purple-50 rounded-xl p-6 border border-violet-200">
                            <h3 class="text-lg font-semibold text-violet-800 mb-3">🚀 Next Steps & Recommendations</h3>
                            <p class="text-violet-700 leading-relaxed">{{ $report->next_steps }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Parent Feedback -->
                @if($report->parent_feedback)
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">💬 Parent Feedback</h2>
                    <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl p-6 border border-rose-200">
                        <p class="text-rose-700 leading-relaxed text-lg">{{ $report->parent_feedback }}</p>
                    </div>
                </div>
                @endif

                <!-- Performance Metrics -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Performance Metrics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-semibold text-green-800 mb-2">🌟 Strengths</h4>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• Excellent attendance record</li>
                                <li>• Strong problem-solving skills</li>
                                <li>• Great teamwork and collaboration</li>
                                <li>• Creative approach to coding challenges</li>
                            </ul>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-800 mb-2">🎯 Areas for Growth</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Continue practicing coding fundamentals</li>
                                <li>• Explore more advanced programming concepts</li>
                                <li>• Share knowledge with peers</li>
                                <li>• Work on debugging skills</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Assessment Scores -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Assessment Scores</h3>
                    @if($assessments->count() > 0)
                    <div class="space-y-4">
                            @foreach($assessments as $assessment)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $assessment['name'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $assessment['type'] }} • Week {{ $assessment['week'] }}</p>
                            </div>
                                        <div class="text-right">
                                            @if($assessment['score'] !== null)
                                                <div class="text-2xl font-bold text-blue-600">{{ $assessment['percentage'] }}%</div>
                                                <div class="text-sm text-gray-500">{{ $assessment['score'] }}/{{ $assessment['max_score'] }}</div>
                                            @else
                                                <div class="text-lg text-gray-400">Not Completed</div>
                                            @endif
                            </div>
                        </div>
                                    @if($assessment['score'] !== null)
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $assessment['percentage'] }}%"></div>
                            </div>
                                    @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>No assessments completed yet</p>
                        </div>
                    @endif
                </div>

                <!-- Scratch Projects -->
                @if($scratch_projects->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">🎯 Scratch Projects</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($scratch_projects as $project)
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <h4 class="font-semibold text-purple-900">{{ $project['name'] }}</h4>
                                    </div>
                                    <button onclick="previewProject('{{ $project['name'] }}')" 
                                            class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                        👁️ Preview
                                    </button>
                                </div>
                                @if($project['description'])
                                    <p class="text-sm text-purple-700 mb-2">{{ $project['description'] }}</p>
                                @endif
                                <div class="flex justify-between items-center text-xs text-purple-600">
                                    <span>{{ $project['created_at']->format('M j, Y') }}</span>
                                    <span>{{ number_format($project['file_size'] / 1024, 1) }} KB</span>
                            </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Student Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Student Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-sm text-gray-600">{{ $report->club->club_name }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">
                                @if($report->report_generated_at)
                                    @if(is_string($report->report_generated_at))
                                        {{ \Carbon\Carbon::parse($report->report_generated_at)->format('M j, Y') }}
                                    @else
                                        {{ $report->report_generated_at->format('M j, Y') }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Access Code -->
                @if($report->access_code)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 border border-blue-200">
                        <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Access Code
                        </h3>
                        <p class="text-sm text-blue-700 mb-3">Share this code with parents to view the report</p>
                        <div class="flex items-center justify-between">
                            <div class="text-xl font-mono font-bold text-blue-900 bg-white px-4 py-2 rounded-lg border-2 border-blue-300">
                                {{ $report->access_code->access_code_plain_preview }}
                            </div>
                            <button onclick="copyAccessCode('{{ $report->access_code->access_code_plain_preview }}', this)" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Copy
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Overall Score</span>
                            <span class="font-semibold text-gray-900">{{ round($report->report_overall_score) }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Attendance</span>
                            <span class="font-semibold text-gray-900">{{ number_format($attendance_percentage, 1) }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Assessments</span>
                            <span class="font-semibold text-gray-900">{{ $assessments->where('score', '!=', null)->count() }}/{{ $assessments->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Projects</span>
                            <span class="font-semibold text-gray-900">{{ $scratch_projects->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Report Status</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Generated</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm text-gray-500">{{ $report->updated_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Access Code Info -->
                @if($report->access_code)
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Parent Access</h3>
                    <div class="space-y-2">
                        <div class="text-sm">Access Code:</div>
                        <div class="bg-white/20 rounded-lg px-3 py-2 font-mono text-lg">{{ $report->access_code->access_code_plain_preview }}</div>
                        <div class="text-xs text-blue-100">Share this code with parents to view the report</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style media="print">
        .bg-gradient-to-r { background: #667eea !important; }
        .shadow-lg { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
        button { display: none !important; }
    </style>

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
            
            <form id="emailForm" method="post" action="{{ route('reports.send', ['report_id' => $report->id]) }}">
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
                           value="{{ $report->student->student_parent_email }}"
                           required>
                    <p class="text-xs text-gray-500 mt-1">
                        Current email on file: {{ $report->student->student_parent_email ?? 'Not provided' }}
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

    <!-- Scratch Project Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Scratch Project Preview</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="previewContent" class="text-center">
                    <div class="bg-gray-100 rounded-lg p-8 mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Scratch Project Preview</h4>
                        <p class="text-gray-600 mb-4">This is a placeholder for the Scratch project preview functionality.</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800">
                                <strong>Note:</strong> To implement full Scratch preview functionality, you would need to:
                            </p>
                            <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside">
                                <li>Upload .sb3 files to a public directory</li>
                                <li>Integrate with Scratch's embed API</li>
                                <li>Add project metadata parsing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EmailJS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    
    <script>
        // Initialize EmailJS
        (function() {
            emailjs.init("w4BP3cPYFveExmYVj"); // Your EmailJS public key
        })();

        // Global variables to store report data
        let currentReportData = {
            reportId: {{ $report->id }},
            studentName: "{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}",
            clubName: "{{ $report->club->club_name }}",
            accessCode: "{{ $report->access_code?->access_code_plain_preview ?? 'N/A' }}",
                reportUrl: "{{ url('/parent-welcome') }}",
                projectsCompleted: "{{ count(json_decode($report->scratch_project_ids ?? '[]', true)) }}",
                skillScore: "{{ round($report->report_overall_score) }}",
                attendanceRate: "{{ number_format($attendance_percentage, 1) }}"
        };

        // Email Modal Functions
        function showEmailModal() {
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
                sender_name: 'Code Club System',
                current_date: new Date().toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }),
                projects_completed: currentReportData.projectsCompleted || '0',
                skill_score: currentReportData.skillScore || '0',
                attendance_rate: currentReportData.attendanceRate || '0'
            };
            
            // Send email using EmailJS
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

        // Scratch Project Preview Functions
        function previewProject(projectName) {
            document.getElementById('previewModal').classList.remove('hidden');
            document.getElementById('previewContent').innerHTML = `
                <div class="bg-gray-100 rounded-lg p-8 mb-4">
                    <svg class="w-16 h-16 mx-auto text-purple-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">${projectName}</h4>
                    <p class="text-gray-600 mb-4">Preview functionality would be implemented here with the actual Scratch project embed.</p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Future Enhancement:</strong> This will show an interactive preview of the Scratch project using Scratch's embed API.
                        </p>
                    </div>
                </div>
            `;
        }

        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePreview();
            }
        });

        // Copy access code function
        function copyAccessCode(code, button) {
            navigator.clipboard.writeText(code).then(function() {
                // Show success feedback
                const originalHTML = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Copied!
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

        function previewProject(projectId) {
            // Create a modal to preview the Scratch project
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">🎮 Scratch Project Preview</h3>
                        <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-100 rounded-xl p-4 mb-4">
                            <p class="text-sm text-gray-600 mb-2">Project ID: <span class="font-mono font-bold">${projectId}</span></p>
                            <div class="flex space-x-3">
                                <a href="https://scratch.mit.edu/projects/${projectId}"
                                   target="_blank"
                                   class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open in Scratch
                                </a>
                                <button onclick="closePreview()"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200">
                                    Close Preview
                                </button>
                            </div>
                        </div>
                        
                        <!-- Scratch Project Embed -->
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-6 border border-orange-200">
                            <div class="text-center mb-6">
                                <div class="text-6xl mb-4">🎨</div>
                                <h4 class="text-lg font-semibold text-orange-800 mb-2">Your Child's Scratch Project</h4>
                                <p class="text-orange-700 mb-4">Watch your child's coding creation in action!</p>
                            </div>
                            
                            <!-- Scratch Player Embed -->
                            <div class="bg-white rounded-lg border border-orange-200 overflow-hidden">
                                <div class="bg-orange-600 text-white p-3 text-center font-semibold">
                                    🎮 Scratch Project Player
                                </div>
                                <div class="p-4">
                                    <div id="scratch-player-${projectId}" class="scratch-player-container">
                                        <div class="bg-gray-100 rounded-lg p-8 text-center">
                                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto mb-4"></div>
                                            <p class="text-gray-600 font-medium">Loading Scratch project...</p>
                                            <p class="text-sm text-gray-500 mt-2">Project ID: ${projectId}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-4 border border-orange-200 mt-4">
                                <p class="text-sm text-gray-600">💡 <strong>Tip:</strong> Click the green flag to start the project! Use the arrow keys or mouse to interact with your child's creation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Load Scratch player after modal is added
            setTimeout(() => {
                loadScratchPlayer(projectId);
            }, 100);

            // Add close function to window
            window.closePreview = function() {
                document.body.removeChild(modal);
                delete window.closePreview;
            };

            // Close on backdrop click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.closePreview();
                }
            });
        }
        
        function loadScratchPlayer(projectId) {
            const container = document.getElementById(`scratch-player-${projectId}`);
            if (!container) return;
            
            // Create iframe for Scratch player
            const iframe = document.createElement('iframe');
            iframe.src = `https://scratch.mit.edu/projects/${projectId}/embed`;
            iframe.width = '100%';
            iframe.height = '500';
            iframe.frameBorder = '0';
            iframe.scrolling = 'no';
            iframe.allowFullscreen = true;
            iframe.style.borderRadius = '8px';
            iframe.style.border = 'none';
            
            // Add loading error handling
            iframe.onload = function() {
                console.log('Scratch player loaded successfully');
            };
            
            iframe.onerror = function() {
                container.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <div class="text-red-600 text-4xl mb-3">⚠️</div>
                        <h4 class="text-red-800 font-semibold mb-2">Unable to Load Project</h4>
                        <p class="text-red-700 text-sm mb-4">The Scratch project could not be loaded. This might be because:</p>
                        <ul class="text-red-600 text-sm text-left max-w-md mx-auto mb-4">
                            <li>• The project ID is invalid</li>
                            <li>• The project is private or deleted</li>
                            <li>• Network connectivity issues</li>
                        </ul>
                        <a href="https://scratch.mit.edu/projects/${projectId}" 
                           target="_blank"
                           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-200 inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Try Opening in Scratch
                        </a>
                    </div>
                `;
            };
            
            // Replace loading spinner with iframe
            container.innerHTML = '';
            container.appendChild(iframe);
        }
    </script>
@endsection
