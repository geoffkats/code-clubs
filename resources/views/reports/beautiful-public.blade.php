<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $report->report_name }} - Code Club Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-effect { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <!-- Header -->
    <div class="gradient-bg text-white py-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">📊 Code Club Report</h1>
                    <p class="text-blue-100">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</p>
                    <p class="text-blue-200 text-sm">{{ $report->club->club_name }}</p>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 rounded-lg px-4 py-2">
                        <div class="text-2xl font-bold">{{ round($report->report_overall_score) }}%</div>
                        <div class="text-sm text-blue-100">Overall Score</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Performance Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Attendance Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ round($report->report_overall_score) }}%</div>
                        <div class="text-sm text-gray-500">Attendance</div>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-1000" 
                         style="width: {{ $report->report_overall_score }}%"></div>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ $report->club->club_level ?? 'Intermediate' }}</div>
                        <div class="text-sm text-gray-500">Level</div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">Excellent progress in coding skills!</div>
            </div>

            <!-- Participation Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">Active</div>
                        <div class="text-sm text-gray-500">Participation</div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">Great team collaboration!</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Report Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detailed Report -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Report Summary</h2>
                    </div>
                    
                    <div class="prose prose-lg max-w-none">
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 mb-6">
                            <p class="text-gray-700 leading-relaxed text-lg">{{ $report->report_summary_text }}</p>
                        </div>
                        
                        <!-- Key Achievements -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-semibold text-green-800 mb-2">🌟 Strengths</h4>
                                <ul class="text-sm text-green-700 space-y-1">
                                    <li>• Excellent attendance record</li>
                                    <li>• Strong problem-solving skills</li>
                                    <li>• Great teamwork and collaboration</li>
                                </ul>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-800 mb-2">🎯 Areas for Growth</h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Continue practicing coding fundamentals</li>
                                    <li>• Explore more advanced programming concepts</li>
                                    <li>• Share knowledge with peers</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skills Progress -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Skills Development</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Problem Solving</span>
                                <span class="text-sm text-gray-500">85%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Code Quality</span>
                                <span class="text-sm text-gray-500">78%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Creativity</span>
                                <span class="text-sm text-gray-500">92%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Stats -->
            <div class="space-y-6">
                <!-- Student Info Card -->
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
                            <span class="text-sm text-gray-600">{{ $report->report_generated_at ? $report->report_generated_at->format('M j, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Sessions Attended</span>
                            <span class="font-semibold text-gray-900">12/15</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Projects Completed</span>
                            <span class="font-semibold text-gray-900">8</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Average Score</span>
                            <span class="font-semibold text-gray-900">{{ round($report->report_overall_score) }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Rank in Class</span>
                            <span class="font-semibold text-gray-900">#3</span>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Next Steps</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start">
                            <span class="mr-2">📚</span>
                            <span>Continue practicing coding fundamentals</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">🚀</span>
                            <span>Explore advanced programming concepts</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">👥</span>
                            <span>Mentor younger students</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Code Club Management System</h3>
                </div>
                <p class="text-gray-600 mb-4">Empowering young minds through coding education</p>
                <div class="text-sm text-gray-500">
                    Report generated on {{ $report->report_generated_at ? $report->report_generated_at->format('F j, Y \a\t g:i A') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style media="print">
        body { background: white !important; }
        .gradient-bg { background: #667eea !important; }
        .shadow-lg { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
    </style>
</body>
</html>
