<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50 to-teal-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" 
         x-data="{ 
             showAddScore: false,
             selectedStudent: '',
             students: @js($students ?? [])
         }">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('assessments.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-emerald-900 to-teal-900 dark:from-white dark:via-emerald-100 dark:to-teal-100 bg-clip-text text-transparent">
                                Assessment Scores
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $assessment->assessment_name ?? 'Assessment' }} • {{ $assessment->club->club_name ?? 'Club' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="showAddScore = true" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Score
                        </button>
                    </div>
                </div>
            </div>
	</div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <!-- Assessment Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Assessment Type</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ ucfirst($assessment->assessment_type ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Points</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $assessment->total_points ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Submissions</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $scores->count() ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Average Score</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $scores->avg('percentage') ?? 0 }}%</p>
                    </div>
                </div>
            </div>

            <!-- Scores Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Student Scores</h2>
                </div>
                
                @if($scores->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-slate-900 dark:text-white">Student</th>
                                    <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Score</th>
                                    <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Percentage</th>
                                    <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Grade</th>
                                    <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Submitted</th>
                                    <th class="px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($scores as $score)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-sm font-medium">
                                                    {{ substr($score->student->student_first_name, 0, 1) }}{{ substr($score->student->student_last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-slate-900 dark:text-white">
                                                        {{ $score->student->student_first_name }} {{ $score->student->student_last_name }}
                                                    </p>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400">Grade {{ $score->student->student_grade_level }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($score->status === 'submitted')
                                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                    Pending Review
                                                </span>
                                            @else
                                                <span class="text-lg font-bold text-slate-900 dark:text-white">
                                                    {{ $score->score_value }}/{{ $assessment->total_points ?? 0 }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($score->status === 'submitted')
                                                <span class="px-3 py-1 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                    Pending
                                                </span>
                                            @else
                                                @php
                                                    $percentage = $score->score_max_value > 0 ? ($score->score_value / $score->score_max_value) * 100 : 0;
                                                @endphp
                                                <span class="px-3 py-1 rounded-lg text-sm font-medium
                                                    @if($percentage >= 90) bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300
                                                    @elseif($percentage >= 80) bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                                    @elseif($percentage >= 70) bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                                                    @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                                    @endif">
                                                    {{ number_format($percentage, 1) }}%
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($score->status === 'submitted')
                                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                    -
                                                </span>
                                            @else
                                                @php
                                                    $percentage = $score->score_max_value > 0 ? ($score->score_value / $score->score_max_value) * 100 : 0;
                                                @endphp
                                                <span class="text-lg font-bold text-slate-900 dark:text-white">
                                                    @if($percentage >= 90) A
                                                    @elseif($percentage >= 80) B
                                                    @elseif($percentage >= 70) C
                                                    @elseif($percentage >= 60) D
                                                    @else F
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                                {{ $score->created_at->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <button onclick="openSubmissionModal({{ $score->id }}, '{{ addslashes($score->student->student_first_name . ' ' . $score->student->student_last_name) }}', {{ $score->id }})" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                                                    View Submission
                                                </button>
                                                @if($score->status === 'submitted')
                                                    <button onclick="openGradeModal({{ $score->id }}, '{{ $score->student->student_first_name }} {{ $score->student->student_last_name }}')" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                        Grade
                                                    </button>
                                                @else
                                                    <button onclick="openEditGradeModal({{ $score->id }}, {{ $score->score_value }}, '{{ $score->student->student_first_name }} {{ $score->student->student_last_name }}')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No scores yet</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">Add scores for students who have completed this assessment</p>
                        <button @click="showAddScore = true" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                            Add First Score
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Add Score Modal -->
        <div x-show="showAddScore" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Add Student Score</h3>
                    <button @click="showAddScore=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="post" action="{{ route('assessments.scores.store', $assessment->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Student</label>
                        <select name="student_id" x-model="selectedStudent" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
                            <option value="">Choose a student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->student_first_name }} {{ $student->student_last_name }} (Grade {{ $student->student_grade_level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Score</label>
                        <input name="score" type="number" min="0" max="{{ $assessment->total_points ?? 100 }}" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Enter score" required>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Out of {{ $assessment->total_points ?? 100 }} points</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Comments (Optional)</label>
                        <textarea name="comments" rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Add any comments about the score..."></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <button type="button" @click="showAddScore=false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                            Add Score
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Submission Modal -->
        <div id="submissionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
            <div class="w-full max-w-4xl rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Student Submission Review</h3>
                    <button onclick="closeSubmissionModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <!-- Student Info -->
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                        <h4 class="font-semibold text-slate-900 dark:text-white mb-2" id="submissionStudentName">Student Name</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Review the student's submission below before grading</p>
                    </div>

                    <!-- Submission Content -->
                    <div class="space-y-4">
                        <div>
                            <h5 class="font-medium text-slate-900 dark:text-white mb-2">Text Submission</h5>
                            <div id="submissionText" class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 min-h-[100px] border border-slate-200 dark:border-slate-600">
                                <!-- Submission text will be loaded here -->
                            </div>
                        </div>

                        <div id="submissionFileSection" class="hidden">
                            <h5 class="font-medium text-slate-900 dark:text-white mb-2">File Submission</h5>
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 border border-slate-200 dark:border-slate-600">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p id="submissionFileName" class="font-medium text-slate-900 dark:text-white"></p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">Click to download</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-200 dark:border-slate-600">
                        <button onclick="closeSubmissionModal()" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Close
                        </button>
                        <button onclick="openGradeFromSubmission()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                            Grade This Submission
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Assessment Modal -->
        <div id="gradeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Grade Assessment</h3>
                    <button onclick="closeGradeModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="gradeForm" method="post" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Student</label>
                        <p id="gradeStudentName" class="text-slate-900 dark:text-white font-medium"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Score</label>
                        <input name="score_value" type="number" min="0" max="{{ $assessment->total_points ?? 100 }}" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Enter score" required>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Out of {{ $assessment->total_points ?? 100 }} points</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Feedback (Optional)</label>
                        <textarea name="admin_feedback" rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Add feedback for the student..."></textarea>
                    </div>
                    <input type="hidden" name="status" value="graded">
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <button type="button" onclick="closeGradeModal()" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                            Grade Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentScoreId = null;
        let currentStudentName = null;

        function openSubmissionModal(scoreId, studentName, scoreIdParam) {
            currentScoreId = scoreId;
            currentStudentName = studentName;
            
            document.getElementById('submissionStudentName').textContent = studentName;
            
            // Show loading state
            const textContainer = document.getElementById('submissionText');
            textContainer.innerHTML = '<p class="text-slate-500 italic">Loading submission...</p>';
            
            // Fetch submission data via AJAX
            fetch(`/assessments/scores/${scoreIdParam}/submission`)
                .then(response => response.json())
                .then(data => {
                    // Display submission text
                    if (data.submission_text && data.submission_text !== 'null' && data.submission_text.trim() !== '') {
                        try {
                            // Try to parse as JSON (for structured answers)
                            const parsed = JSON.parse(data.submission_text);
                            if (typeof parsed === 'object') {
                                let html = '<div class="space-y-3">';
                                Object.keys(parsed).forEach(key => {
                                    html += `<div><strong>Question ${key}:</strong><br><div class="ml-4 mt-1 p-2 bg-white dark:bg-slate-600 rounded border">${parsed[key]}</div></div>`;
                                });
                                html += '</div>';
                                textContainer.innerHTML = html;
                            } else {
                                textContainer.textContent = data.submission_text;
                            }
                        } catch (e) {
                            textContainer.textContent = data.submission_text;
                        }
                    } else {
                        textContainer.innerHTML = '<p class="text-slate-500 italic">No text submission provided</p>';
                    }
                    
                    // Display file submission if exists
                    const fileSection = document.getElementById('submissionFileSection');
                    if (data.submission_file_name && data.submission_file_name !== 'null' && data.submission_file_name.trim() !== '') {
                        document.getElementById('submissionFileName').textContent = data.submission_file_name;
                        fileSection.classList.remove('hidden');
                    } else {
                        fileSection.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching submission:', error);
                    textContainer.innerHTML = '<p class="text-red-500 italic">Error loading submission data</p>';
                });
            
            document.getElementById('submissionModal').classList.remove('hidden');
        }

        function closeSubmissionModal() {
            document.getElementById('submissionModal').classList.add('hidden');
        }

        function openGradeFromSubmission() {
            closeSubmissionModal();
            openGradeModal(currentScoreId, currentStudentName);
        }

        function openGradeModal(scoreId, studentName) {
            document.getElementById('gradeStudentName').textContent = studentName;
            document.getElementById('gradeForm').action = `/assessments/scores/${scoreId}/grade`;
            document.getElementById('gradeModal').classList.remove('hidden');
        }

        function closeGradeModal() {
            document.getElementById('gradeModal').classList.add('hidden');
        }

        function openEditGradeModal(scoreId, currentScore, studentName) {
            document.getElementById('gradeStudentName').textContent = studentName;
            document.querySelector('input[name="score_value"]').value = currentScore;
            document.getElementById('gradeForm').action = `/assessments/scores/${scoreId}/grade`;
            document.getElementById('gradeModal').classList.remove('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('submissionModal').addEventListener('click', function(e) {
            if (e.target === this) closeSubmissionModal();
        });

        document.getElementById('gradeModal').addEventListener('click', function(e) {
            if (e.target === this) closeGradeModal();
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSubmissionModal();
                closeGradeModal();
            }
        });
    </script>
</x-layouts.app>