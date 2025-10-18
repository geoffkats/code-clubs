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
            <button onclick="showEmailModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Send to Parent
            </button>
            
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
                            <span class="font-semibold text-gray-900">{{ $attendance_percentage }}%</span>
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

    <!-- Email Input Modal -->
    <div id="emailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
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
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
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

    <script>
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

        // Email form submission with loading state
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Sending...
            `;
            submitBtn.disabled = true;
        });

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
    </script>
</x-layouts.app>
