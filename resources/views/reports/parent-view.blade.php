<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->student->student_first_name }}'s Coding Club Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div class="min-h-screen p-4">
        <!-- Header -->
        <div class="max-w-6xl mx-auto">
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            🎓 {{ $report->student->student_first_name }} {{ $report->student->student_last_name }}'s Coding Journey
                        </h1>
                        <p class="text-lg text-gray-600">{{ $report->club->club_name }}</p>
                        @if($report->student->student_grade_level)
                            <p class="text-sm text-gray-500">{{ $report->student->student_grade_level }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl px-6 py-4 text-white">
                            <div class="text-3xl font-bold">{{ $report->student_initials ?? strtoupper(substr($report->student->student_first_name, 0, 1) . substr($report->student->student_last_name, 0, 1)) }}</div>
                            <div class="text-sm text-blue-200">Student Initials</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Information -->
            @if($report->club->school)
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">🏫 {{ $report->club->school->school_name }}</h2>
                        <p class="text-gray-600">Coding Club Program</p>
                        <p class="text-sm text-gray-500 mt-2">{{ $report->club->club_name }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Report Summary -->
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-white/20">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Report Summary</h2>
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6">
                            <p class="text-gray-700 leading-relaxed text-lg">{{ $report->report_summary_text }}</p>
                        </div>
                    </div>

                    <!-- Coding Skills Assessment -->
                    @if($report->problem_solving_score || $report->creativity_score || $report->collaboration_score || $report->persistence_score)
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-white/20">
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
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-white/20">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">🎮 Your Child's Scratch Projects</h2>
                        <div class="space-y-4">
                            @php
                                $projectIds = json_decode($report->scratch_project_ids, true) ?? [];
                            @endphp
                            @if(count($projectIds) > 0)
                                @foreach($projectIds as $index => $projectId)
                                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border border-orange-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-orange-800 mb-2">🎨 Project {{ $index + 1 }}</h3>
                                                <p class="text-orange-700 font-mono text-sm">Project ID: {{ $projectId }}</p>
                                            </div>
                                            <div class="flex space-x-3">
                                                <a href="https://scratch.mit.edu/projects/{{ $projectId }}" 
                                                   target="_blank"
                                                   class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                                                    <i class="fas fa-external-link-alt mr-2"></i>
                                                    View Project
                                                </a>
                                                <button onclick="previewProject('{{ $projectId }}')"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                                                    <i class="fas fa-eye mr-2"></i>
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

                    <!-- Learning Journey -->
                    @if($report->challenges_overcome || $report->special_achievements || $report->areas_for_growth || $report->next_steps)
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-white/20">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">🚀 Learning Journey</h2>
                        <div class="space-y-6">
                            @if($report->challenges_overcome)
                            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-200">
                                <h3 class="text-lg font-semibold text-red-800 mb-3">🏆 Challenges Overcome</h3>
                                <p class="text-red-700 leading-relaxed">{{ $report->challenges_overcome }}</p>
                            </div>
                            @endif

                            @if($report->special_achievements)
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                                <h3 class="text-lg font-semibold text-indigo-800 mb-3">🏅 Special Achievements</h3>
                                <p class="text-indigo-700 leading-relaxed">{{ $report->special_achievements }}</p>
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
                                <h3 class="text-lg font-semibold text-violet-800 mb-3">🚀 Next Steps</h3>
                                <p class="text-violet-700 leading-relaxed">{{ $report->next_steps }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Parent Feedback -->
                    @if($report->parent_feedback)
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-white/20">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">💬 Teacher's Message</h2>
                        <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl p-6 border border-rose-200">
                            <p class="text-rose-700 leading-relaxed text-lg">{{ $report->parent_feedback }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Quick Stats</h3>
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
                            @if($report->scratch_project_ids)
                                @php
                                    $projectIds = json_decode($report->scratch_project_ids, true) ?? [];
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Projects</span>
                                    <span class="font-semibold text-gray-900">{{ count($projectIds) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Favorite Concept -->
                    @if($report->favorite_concept)
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">⭐ Favorite Concept</h3>
                        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl p-4 border border-yellow-200">
                            <p class="text-yellow-700 font-medium text-center">{{ $report->favorite_concept }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Print Button -->
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                        <button onclick="window.print()" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-print mr-2"></i>
                            Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Preview Modal JavaScript -->
    <script>
        function previewProject(projectId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">🎮 Project Preview</h3>
                        <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-100 rounded-xl p-4 mb-4">
                            <p class="text-sm text-gray-600 mb-2">Project ID: <span class="font-mono font-bold">${projectId}</span></p>
                            <div class="flex space-x-3">
                                <a href="https://scratch.mit.edu/projects/${projectId}" 
                                   target="_blank"
                                   class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Open in Scratch
                                </a>
                                <button onclick="closePreview()" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200">
                                    Close
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
            
            window.closePreview = function() {
                document.body.removeChild(modal);
                delete window.closePreview;
            };
            
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
            iframe.height = '400';
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
                            <i class="fas fa-external-link-alt mr-2"></i>
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

    <!-- Print Styles -->
    <style media="print">
        body { background: white !important; }
        .bg-gradient-to-br { background: white !important; }
        .backdrop-blur-xl { backdrop-filter: none !important; }
        .shadow-2xl { box-shadow: none !important; }
        button { display: none !important; }
    </style>
</body>
</html>
