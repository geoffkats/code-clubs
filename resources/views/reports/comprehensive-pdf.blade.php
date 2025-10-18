<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->student->student_first_name }} {{ $report->student->student_last_name }} - {{ $report->club->club_name }} Report</title>
    <style>
        @page {
            margin: 0.5in;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2563eb;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }
        
        .header h2 {
            color: #6b7280;
            font-size: 18px;
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        
        .student-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-weight: bold;
            color: #374151;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            color: #111827;
            font-size: 14px;
            margin-top: 2px;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #2563eb;
            color: white;
            padding: 12px 20px;
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px 6px 0 0;
        }
        
        .section-content {
            border: 1px solid #e5e7eb;
            border-top: none;
            padding: 20px;
            border-radius: 0 0 6px 6px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            text-align: center;
            padding: 15px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        
        .assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .assessment-table th,
        .assessment-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        
        .assessment-table th {
            background: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        
        .assessment-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .score-excellent { color: #059669; font-weight: bold; }
        .score-good { color: #0891b2; font-weight: bold; }
        .score-developing { color: #d97706; font-weight: bold; }
        .score-foundational { color: #dc2626; font-weight: bold; }
        
        .scratch-projects {
            display: grid;
            gap: 15px;
        }
        
        .project-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            background: #f9fafb;
        }
        
        .project-name {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        
        .project-details {
            font-size: 12px;
            color: #6b7280;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            transition: width 0.3s ease;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        
        @media print {
            .print-button { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print PDF</button>

    <!-- Header -->
    <div class="header">
        <h1>{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</h1>
        <h2>{{ $report->club->club_name }} - Scratch Programming Report</h2>
        <p>{{ $report->club->club_level ? ucfirst($report->club->club_level) : 'Beginner' }} Level • Generated on {{ now()->format('F j, Y') }}</p>
    </div>

    <!-- Student Information -->
    <div class="student-info">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Student Name</span>
                <span class="info-value">{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Club</span>
                <span class="info-value">{{ $report->club->club_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Level</span>
                <span class="info-value">{{ $report->club->club_level ? ucfirst($report->club->club_level) : 'Beginner' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Report Period</span>
                <span class="info-value">{{ now()->format('F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Overall Performance -->
    <div class="section">
        <h3 class="section-title">📊 Overall Performance Summary</h3>
        <div class="section-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ round($report->report_overall_score) }}%</div>
                    <div class="stat-label">Overall Score</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $report->club->students()->count() }}</div>
                    <div class="stat-label">Club Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $report->club->assessments()->count() }}</div>
                    <div class="stat-label">Total Assessments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $report->club->sessions()->count() }}</div>
                    <div class="stat-label">Sessions Attended</div>
                </div>
            </div>
            
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $report->report_overall_score }}%"></div>
            </div>
            
            <p><strong>Performance Level:</strong> 
                @if($report->report_overall_score >= 85)
                    <span class="score-excellent">🌟 Excellent</span>
                @elseif($report->report_overall_score >= 70)
                    <span class="score-good">👍 Good</span>
                @elseif($report->report_overall_score >= 50)
                    <span class="score-developing">📈 Developing</span>
                @else
                    <span class="score-foundational">🌱 Foundational</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Assessment Results -->
    <div class="section">
        <h3 class="section-title">📝 Assessment Results & Quiz Scores</h3>
        <div class="section-content">
            @php
                $assessments = $report->club->assessments()->with('scores')->get();
                $studentAssessments = $assessments->map(function($assessment) use ($report) {
                    $score = $assessment->scores->firstWhere('student_id', $report->student->id);
                    return [
                        'name' => $assessment->assessment_name,
                        'type' => $assessment->assessment_type,
                        'week' => $assessment->assessment_week_number,
                        'score' => $score ? $score->score_value : null,
                        'max_score' => $score ? $score->score_max_value : 100,
                        'percentage' => $score && $score->score_max_value > 0 ? 
                            round(($score->score_value / $score->score_max_value) * 100, 2) : 0
                    ];
                })->filter(function($assessment) {
                    return $assessment['score'] !== null;
                });
            @endphp

            @if($studentAssessments->count() > 0)
                <table class="assessment-table">
                    <thead>
                        <tr>
                            <th>Assessment</th>
                            <th>Type</th>
                            <th>Week</th>
                            <th>Score</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentAssessments as $assessment)
                            <tr>
                                <td>{{ $assessment['name'] }}</td>
                                <td>{{ ucfirst($assessment['type']) }}</td>
                                <td>{{ $assessment['week'] ? 'Week ' . $assessment['week'] : 'N/A' }}</td>
                                <td>{{ $assessment['score'] }}/{{ $assessment['max_score'] }}</td>
                                <td>
                                    @if($assessment['percentage'] >= 85)
                                        <span class="score-excellent">A ({{ $assessment['percentage'] }}%)</span>
                                    @elseif($assessment['percentage'] >= 70)
                                        <span class="score-good">B ({{ $assessment['percentage'] }}%)</span>
                                    @elseif($assessment['percentage'] >= 50)
                                        <span class="score-developing">C ({{ $assessment['percentage'] }}%)</span>
                                    @else
                                        <span class="score-foundational">D ({{ $assessment['percentage'] }}%)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #6b7280; font-style: italic;">
                    No assessment scores available yet. Assessments will appear here as they are completed.
                </p>
            @endif
        </div>
    </div>

    <!-- Scratch Projects -->
    <div class="section">
        <h3 class="section-title">🎮 Scratch Projects & Creative Work</h3>
        <div class="section-content">
            @php
                // Get Scratch project attachments
                $scratchProjects = \App\Models\Attachment::where('attachable_type', 'App\\Models\\Assessment')
                    ->whereHas('attachable', function($q) use ($report) {
                        $q->where('club_id', $report->club->id);
                    })
                    ->where(function($q) {
                        $q->where('file_type', 'scratch')
                            ->orWhere('file_name', 'like', '%.sb3')
                            ->orWhere('description', 'like', '%scratch%');
                    })
                    ->get();
            @endphp

            @if($scratchProjects->count() > 0)
                <div class="scratch-projects">
                    @foreach($scratchProjects as $project)
                        <div class="project-card">
                            <div class="project-name">🎨 {{ $project->file_name }}</div>
                            <div class="project-details">
                                <strong>Type:</strong> Scratch Project (.sb3)<br>
                                <strong>Size:</strong> {{ round($project->file_size / 1024, 2) }} KB<br>
                                <strong>Created:</strong> {{ $project->created_at->format('M j, Y g:i A') }}<br>
                                @if($project->description)
                                    <strong>Description:</strong> {{ $project->description }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: #6b7280; font-style: italic;">
                    No Scratch projects uploaded yet. Projects will appear here as they are shared.
                </p>
            @endif
        </div>
    </div>

    <!-- Learning Progress -->
    <div class="section">
        <h3 class="section-title">📈 Learning Progress & Skills Development</h3>
        <div class="section-content">
            <h4>Key Programming Concepts Mastered:</h4>
            <ul>
                <li>✅ Basic Scratch interface and navigation</li>
                <li>✅ Motion blocks and sprite movement</li>
                <li>✅ Looks blocks for visual effects</li>
                <li>✅ Sound blocks for audio</li>
                <li>✅ Control blocks for logic and flow</li>
                <li>✅ Sensing blocks for interactivity</li>
                <li>✅ Variables and data storage</li>
                <li>✅ Event handling and user input</li>
            </ul>
            
            <h4>Creative Skills Demonstrated:</h4>
            <ul>
                <li>🎨 Storytelling through interactive narratives</li>
                <li>🎮 Game design and mechanics</li>
                <li>🎵 Sound design and music creation</li>
                <li>🎬 Animation and visual effects</li>
                <li>🧩 Problem-solving and debugging</li>
            </ul>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="section">
        <h3 class="section-title">💡 Recommendations for Continued Growth</h3>
        <div class="section-content">
            <h4>Next Steps:</h4>
            <ul>
                <li>Continue exploring advanced Scratch features like cloning and custom blocks</li>
                <li>Try creating more complex games with multiple levels</li>
                <li>Experiment with creating interactive stories and animations</li>
                <li>Share projects with classmates for feedback and collaboration</li>
                <li>Consider transitioning to text-based programming languages</li>
            </ul>
            
            <h4>Areas for Improvement:</h4>
            <ul>
                <li>Focus on completing assessments on time</li>
                <li>Practice debugging skills when projects don't work as expected</li>
                <li>Try to create original projects rather than following tutorials</li>
                <li>Document your projects with clear descriptions</li>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Code Club Management System</strong></p>
        <p>Report generated on {{ now()->format('F j, Y \a\t g:i A') }} | {{ $report->club->club_name }}</p>
        <p>This report reflects the student's progress in Scratch programming and creative computing skills.</p>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
