<x-layouts.app :title="$report->report_name">
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
            <form method="post" action="{{ route('reports.send', ['report_id' => $report->id]) }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send to Parent
                </button>
            </form>
            
            <a href="{{ route('reports.public', ['report_id' => $report->id]) }}?code={{ $report->access_code?->access_code ?? 'demo' }}" 
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
                <!-- Report Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Report Summary</h2>
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6">
                        <p class="text-gray-700 leading-relaxed text-lg">{{ $report->report_summary_text }}</p>
                    </div>
                </div>

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

                <!-- Skills Assessment -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Skills Assessment</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Problem Solving</span>
                                <span class="text-sm text-gray-500">85%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Code Quality</span>
                                <span class="text-sm text-gray-500">78%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Creativity</span>
                                <span class="text-sm text-gray-500">92%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-3 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Collaboration</span>
                                <span class="text-sm text-gray-500">88%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-3 rounded-full" style="width: 88%"></div>
                            </div>
                        </div>
                    </div>
                </div>
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

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Overall Score</span>
                            <span class="font-semibold text-gray-900">{{ round($report->report_overall_score) }}%</span>
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
                        <div class="bg-white/20 rounded-lg px-3 py-2 font-mono text-lg">{{ $report->access_code->access_code }}</div>
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
</x-layouts.app>
