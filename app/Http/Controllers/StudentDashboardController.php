<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentScore;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentDashboardController extends Controller
{
    /**
     * Show student's assignments and quizzes
     */
    public function assignments()
    {
        $student = Auth::guard('student')->user();
        
        // Get all assessments from student's clubs
        $assessments = collect();
        foreach ($student->clubs as $club) {
            $clubAssessments = $club->assessments()
                ->with(['questions', 'scores' => function($query) use ($student) {
                    $query->where('student_id', $student->id);
                }])
                ->get();
            $assessments = $assessments->merge($clubAssessments);
        }

        // Separate completed and pending assessments
        $completedAssessments = $assessments->filter(function($assessment) use ($student) {
            return $assessment->scores->where('student_id', $student->id)->isNotEmpty();
        });

        $pendingAssessments = $assessments->filter(function($assessment) use ($student) {
            return $assessment->scores->where('student_id', $student->id)->isEmpty();
        });

        return view('students.assignments', compact('assessments', 'completedAssessments', 'pendingAssessments'));
    }

    /**
     * Show a specific assessment for the student
     */
    public function showAssessment($assessmentId)
    {
        $student = Auth::guard('student')->user();
        
        $assessment = Assessment::with(['questions', 'club'])
            ->whereHas('club.students', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->findOrFail($assessmentId);

        // Check if student has already completed this assessment
        $existingScore = AssessmentScore::where('student_id', $student->id)
            ->where('assessment_id', $assessment->id)
            ->first();

        if ($existingScore) {
            return view('students.assessment-results', compact('assessment', 'existingScore'));
        }

        return view('students.take-assessment', compact('assessment'));
    }

    /**
     * Submit assessment answers
     */
    public function submitAssessment(Request $request, $assessmentId)
    {
        $student = Auth::guard('student')->user();
        
        $assessment = Assessment::with('questions')
            ->whereHas('club.students', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->findOrFail($assessmentId);

        // Check if student has already completed this assessment
        $existingScore = AssessmentScore::where('student_id', $student->id)
            ->where('assessment_id', $assessment->id)
            ->first();

        if ($existingScore) {
            return redirect()->route('student.assessment.show', $assessmentId)
                ->with('error', 'You have already completed this assessment.');
        }

        // Validate answers
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'answers.*' => 'required|string',
            'project_files.*' => 'nullable|file|mimes:sb,sb2,sb3,py,js,html,css,zip,rar,pdf,doc,docx|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $answers = $request->input('answers');
        $projectFiles = $request->file('project_files', []);
        $totalScore = 0;
        $maxScore = 0;
        $submissionText = '';
        $submissionFilePath = null;
        $submissionFileName = null;

        // Check if this is a project-based assessment
        $isProjectBased = $assessment->questions->where('question_type', 'practical_project')->count() > 0;

        // Calculate score and handle submissions
        foreach ($assessment->questions as $question) {
            $maxScore += $question->points;
            
            if (isset($answers[$question->id])) {
                $studentAnswer = $answers[$question->id];
                
                // Handle project submissions
                if ($question->question_type === 'practical_project') {
                    $submissionText .= "Question {$question->id}: {$studentAnswer}\n\n";
                    
                    // Handle file upload for this question
                    if (isset($projectFiles[$question->id]) && $projectFiles[$question->id]->isValid()) {
                        $file = $projectFiles[$question->id];
                        $filename = time() . '_' . $student->id . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('student_submissions', $filename, 'public');
                        
                        $submissionFilePath = $path;
                        $submissionFileName = $file->getClientOriginalName();
                    }
                    
                    // For project-based assessments, don't auto-grade - set to 0 until admin reviews
                    $totalScore = 0;
                } else {
                    // Auto-grade other question types
                    switch ($question->question_type) {
                        case 'multiple_choice':
                            if ($studentAnswer === $question->correct_answer) {
                                $totalScore += $question->points;
                            }
                            break;
                        case 'text_question':
                            if (strlen($studentAnswer) > 10) {
                                $totalScore += $question->points;
                            } else {
                                $totalScore += $question->points * 0.5;
                            }
                            break;
                        case 'image_question':
                            $totalScore += $question->points * 0.7;
                            break;
                        default:
                            $totalScore += $question->points * 0.5;
                    }
                }
            }
        }

        // Determine status based on assessment type
        $status = $isProjectBased ? 'submitted' : 'graded';

        // Save the score
        AssessmentScore::create([
            'student_id' => $student->id,
            'assessment_id' => $assessment->id,
            'score_value' => $totalScore,
            'score_max_value' => $maxScore,
            'submission_text' => $submissionText ?: json_encode($answers),
            'submission_file_path' => $submissionFilePath,
            'submission_file_name' => $submissionFileName,
            'status' => $status,
            'student_answers' => $answers,
        ]);

        return redirect()->route('student.assessment.show', $assessmentId)
            ->with('success', 'Assessment submitted successfully!');
    }

    /**
     * Show student's progress and reports
     */
    public function progress()
    {
        $student = Auth::guard('student')->user();
        
        $student->load([
            'clubs.assessments.scores',
            'assessment_scores.assessment',
            'reports'
        ]);

        // Calculate progress statistics
        $progressData = [];
        
        foreach ($student->clubs as $club) {
            $clubAssessments = $club->assessments;
            $completedAssessments = $clubAssessments->filter(function($assessment) use ($student) {
                return $assessment->scores->where('student_id', $student->id)->isNotEmpty();
            });

            $totalScore = 0;
            $maxScore = 0;
            
            foreach ($completedAssessments as $assessment) {
                $score = $assessment->scores->where('student_id', $student->id)->first();
                if ($score) {
                    $totalScore += $score->score_value;
                    $maxScore += $score->score_max_value;
                }
            }

            $progressData[] = [
                'club' => $club,
                'total_assessments' => $clubAssessments->count(),
                'completed_assessments' => $completedAssessments->count(),
                'completion_rate' => $clubAssessments->count() > 0 ? ($completedAssessments->count() / $clubAssessments->count()) * 100 : 0,
                'average_score' => $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0,
                'attendance_percentage' => $this->calculateClubAttendance($student, $club),
            ];
        }

        return view('students.progress', compact('student', 'progressData'));
    }

    /**
     * Show student's reports
     */
    public function reports()
    {
        $student = Auth::guard('student')->user();
        
        $reports = $student->reports()
            ->with('club')
            ->latest()
            ->get();

        return view('students.reports', compact('student', 'reports'));
    }

    /**
     * Calculate attendance percentage for a specific club
     */
    private function calculateClubAttendance($student, $club): float
    {
        $sessions = $club->sessions;
        $attended = 0;
        
        foreach ($sessions as $session) {
            $attendance = $session->attendance_records->where('student_id', $student->id)->first();
            if ($attendance && $attendance->attendance_status === 'present') {
                $attended++;
            }
        }

        return $sessions->count() > 0 ? ($attended / $sessions->count()) * 100 : 0;
    }
}