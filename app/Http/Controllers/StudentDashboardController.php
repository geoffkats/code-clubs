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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $answers = $request->input('answers');
        $totalScore = 0;
        $maxScore = 0;

        // Calculate score
        foreach ($assessment->questions as $question) {
            $maxScore += $question->points;
            
            if (isset($answers[$question->id])) {
                $studentAnswer = $answers[$question->id];
                
                // Check if answer is correct based on question type
                switch ($question->question_type) {
                    case 'multiple_choice':
                        if ($studentAnswer === $question->correct_answer) {
                            $totalScore += $question->points;
                        }
                        break;
                    case 'practical_project':
                        // For practical projects, assume partial credit for any submission
                        $totalScore += $question->points * 0.8; // 80% for attempting
                        break;
                    case 'text_question':
                        // For text questions, give full credit for any reasonable answer
                        if (strlen($studentAnswer) > 10) {
                            $totalScore += $question->points;
                        } else {
                            $totalScore += $question->points * 0.5; // 50% for short answers
                        }
                        break;
                    case 'image_question':
                        // For image questions, give partial credit for any answer
                        $totalScore += $question->points * 0.7;
                        break;
                }
            }
        }

        // Save the score
        AssessmentScore::create([
            'student_id' => $student->id,
            'assessment_id' => $assessment->id,
            'score_value' => $totalScore,
            'score_max_value' => $maxScore,
            'submitted_at' => now(),
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