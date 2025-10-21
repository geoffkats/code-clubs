<?php

namespace App\Http\Controllers;

use App\Models\SessionFeedback;
use App\Models\Session;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SessionFeedbackController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of session feedbacks
     */
    public function index(Request $request)
    {
        $query = SessionFeedback::with(['session', 'facilitator', 'teacher', 'club']);

        // Filter by user role
        if (Auth::user()->user_role === 'facilitator') {
            $query->byFacilitator(Auth::id());
        } elseif (Auth::user()->user_role === 'teacher') {
            $query->forTeacher(Auth::id());
        } elseif (Auth::user()->user_role === 'admin') {
            // Admin can see all feedback
        }

        // Apply filters
        if ($request->filled('club_id')) {
            $query->byClub($request->club_id);
        }

        if ($request->filled('teacher_id')) {
            $query->forTeacher($request->teacher_id);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('rating_min')) {
            $query->where('overall_rating', '>=', $request->rating_min);
        }

        $feedbacks = $query->latest()->paginate(20);

        $clubs = Club::orderBy('club_name')->get();
        $teachers = User::where('user_role', 'teacher')->orderBy('name')->get();

        return view('session-feedback.index', compact('feedbacks', 'clubs', 'teachers'));
    }

    /**
     * Show the form for creating a new session feedback
     */
    public function create(Session $session)
    {
        // Ensure the user is authorized to provide feedback for this session
        $this->authorize('create', [SessionFeedback::class, $session]);

        return view('session-feedback.create', compact('session'));
    }

    /**
     * Store a newly created session feedback
     */
    public function store(Request $request, Session $session)
    {
        // Ensure the user is authorized to provide feedback for this session
        $this->authorize('create', [SessionFeedback::class, $session]);

        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
            'suggestions' => 'nullable|array',
            'suggestions.*' => 'string|max:500',
            'feedback_type' => 'required|in:positive,constructive,critical,mixed',
            'content_delivery_rating' => 'required|integer|min:1|max:5',
            'student_engagement_rating' => 'required|integer|min:1|max:5',
            'session_management_rating' => 'required|integer|min:1|max:5',
            'preparation_rating' => 'required|integer|min:1|max:5',
            'overall_rating' => 'required|integer|min:1|max:5',
        ]);

        // Get the teacher for this session
        $teacher = $session->teacher;

        if (!$teacher) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No teacher assigned to this session.');
        }

        try {
            $feedback = SessionFeedback::create([
                'session_id' => $session->id,
                'facilitator_id' => Auth::id(),
                'teacher_id' => $teacher->id,
                'club_id' => $session->club_id,
                'content' => $validated['content'],
                'suggestions' => $validated['suggestions'] ?? [],
                'feedback_type' => $validated['feedback_type'],
                'content_delivery_rating' => $validated['content_delivery_rating'],
                'student_engagement_rating' => $validated['student_engagement_rating'],
                'session_management_rating' => $validated['session_management_rating'],
                'preparation_rating' => $validated['preparation_rating'],
                'overall_rating' => $validated['overall_rating'],
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // Send notification to teacher
            $teacher->notify(new \App\Notifications\SessionFeedbackReceived($feedback));

            return redirect()->route('session-feedback.show', $feedback)
                ->with('success', 'Session feedback submitted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit feedback. Please try again.');
        }
    }

    /**
     * Display the specified session feedback
     */
    public function show(SessionFeedback $sessionFeedback)
    {
        $this->authorize('view', $sessionFeedback);

        return view('session-feedback.show', compact('sessionFeedback'));
    }

    /**
     * Show the form for editing the specified session feedback
     */
    public function edit(SessionFeedback $sessionFeedback)
    {
        $this->authorize('update', $sessionFeedback);

        return view('session-feedback.edit', compact('sessionFeedback'));
    }

    /**
     * Update the specified session feedback
     */
    public function update(Request $request, SessionFeedback $sessionFeedback)
    {
        $this->authorize('update', $sessionFeedback);

        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
            'suggestions' => 'nullable|array',
            'suggestions.*' => 'string|max:500',
            'feedback_type' => 'required|in:positive,constructive,critical,mixed',
            'content_delivery_rating' => 'required|integer|min:1|max:5',
            'student_engagement_rating' => 'required|integer|min:1|max:5',
            'session_management_rating' => 'required|integer|min:1|max:5',
            'preparation_rating' => 'required|integer|min:1|max:5',
            'overall_rating' => 'required|integer|min:1|max:5',
            'status' => 'sometimes|in:draft,submitted,reviewed,actioned',
        ]);

        try {
            $sessionFeedback->update($validated);

            if ($validated['status'] === 'reviewed' || $validated['status'] === 'actioned') {
                $sessionFeedback->update(['reviewed_at' => now()]);
            }

            return redirect()->route('session-feedback.show', $sessionFeedback)
                ->with('success', 'Session feedback updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update feedback. Please try again.');
        }
    }

    /**
     * Remove the specified session feedback
     */
    public function destroy(SessionFeedback $sessionFeedback)
    {
        $this->authorize('delete', $sessionFeedback);

        try {
            $sessionFeedback->delete();

            return redirect()->route('session-feedback.index')
                ->with('success', 'Session feedback deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete feedback. Please try again.');
        }
    }

    /**
     * Get analytics data for feedback
     */
    public function analytics(Request $request)
    {
        $this->authorize('viewAnalytics', SessionFeedback::class);

        $query = SessionFeedback::query();

        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply club filter
        if ($request->filled('club_id')) {
            $query->byClub($request->club_id);
        }

        $feedbacks = $query->get();

        // Calculate analytics
        $analytics = [
            'total_feedbacks' => $feedbacks->count(),
            'average_rating' => $feedbacks->avg('overall_rating'),
            'rating_distribution' => $feedbacks->groupBy('overall_rating')->map->count(),
            'feedback_type_distribution' => $feedbacks->groupBy('feedback_type')->map->count(),
            'teacher_performance' => $feedbacks->groupBy('teacher_id')
                ->map(function ($teacherFeedbacks) {
                    return [
                        'teacher' => $teacherFeedbacks->first()->teacher,
                        'average_rating' => $teacherFeedbacks->avg('overall_rating'),
                        'total_feedbacks' => $teacherFeedbacks->count(),
                    ];
                }),
            'club_performance' => $feedbacks->groupBy('club_id')
                ->map(function ($clubFeedbacks) {
                    return [
                        'club' => $clubFeedbacks->first()->club,
                        'average_rating' => $clubFeedbacks->avg('overall_rating'),
                        'total_feedbacks' => $clubFeedbacks->count(),
                    ];
                }),
        ];

        $clubs = Club::orderBy('club_name')->get();

        return view('session-feedback.analytics', compact('analytics', 'clubs'));
    }

    /**
     * Export feedback data
     */
    public function export(Request $request)
    {
        $this->authorize('export', SessionFeedback::class);

        $format = $request->input('format', 'excel');
        
        // Apply same filters as analytics
        $query = SessionFeedback::with(['session', 'facilitator', 'teacher', 'club']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('club_id')) {
            $query->byClub($request->club_id);
        }

        $feedbacks = $query->get();

        if ($format === 'pdf') {
            return $this->exportToPDF($feedbacks, $request);
        } else {
            return $this->exportToExcel($feedbacks, $request);
        }
    }

    /**
     * Export to Excel format
     */
    private function exportToExcel($feedbacks, $request)
    {
        $filename = 'session_feedback_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($feedbacks) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Session Title',
                'Club Name',
                'Teacher Name',
                'Facilitator Name',
                'Session Date',
                'Content Delivery Rating',
                'Student Engagement Rating',
                'Session Management Rating',
                'Preparation Rating',
                'Overall Rating',
                'Feedback Type',
                'Status',
                'Feedback Content',
                'Suggestions',
                'Created At',
                'Submitted At'
            ]);

            // Add data rows
            foreach ($feedbacks as $feedback) {
                fputcsv($file, [
                    $feedback->session->title ?? 'N/A',
                    $feedback->club->club_name ?? 'N/A',
                    $feedback->teacher->name ?? 'N/A',
                    $feedback->facilitator->name ?? 'N/A',
                    $feedback->session->session_date ? $feedback->session->session_date->format('Y-m-d') : 'N/A',
                    $feedback->content_delivery_rating,
                    $feedback->student_engagement_rating,
                    $feedback->session_management_rating,
                    $feedback->preparation_rating,
                    $feedback->overall_rating,
                    ucfirst($feedback->feedback_type),
                    ucfirst($feedback->status),
                    $feedback->content,
                    $feedback->suggestions ? implode('; ', $feedback->suggestions) : 'N/A',
                    $feedback->created_at->format('Y-m-d H:i:s'),
                    $feedback->submitted_at ? $feedback->submitted_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF format
     */
    private function exportToPDF($feedbacks, $request)
    {
        $filename = 'session_feedback_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Create HTML content for PDF
        $html = $this->generatePDFContent($feedbacks, $request);
        
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // For now, return a simple text response
        // In production, you would use a PDF library like DomPDF or TCPDF
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.html"',
        ]);
    }

    /**
     * Generate PDF content
     */
    private function generatePDFContent($feedbacks, $request)
    {
        $dateRange = '';
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $dateRange = 'Period: ';
            if ($request->filled('date_from')) $dateRange .= $request->date_from;
            $dateRange .= ' to ';
            if ($request->filled('date_to')) $dateRange .= $request->date_to;
        }

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Session Feedback Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; text-align: center; }
                h2 { color: #666; border-bottom: 2px solid #ddd; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .summary { background-color: #f9f9f9; padding: 15px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <h1>Session Feedback Report</h1>
            <div class="summary">
                <p><strong>Generated:</strong> ' . now()->format('Y-m-d H:i:s') . '</p>
                <p><strong>Total Feedback:</strong> ' . $feedbacks->count() . '</p>
                <p><strong>Average Rating:</strong> ' . number_format($feedbacks->avg('overall_rating'), 1) . '/5</p>
                ' . ($dateRange ? '<p><strong>' . $dateRange . '</strong></p>' : '') . '
            </div>
            
            <h2>Feedback Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Teacher</th>
                        <th>Overall Rating</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($feedbacks as $feedback) {
            $html .= '<tr>
                <td>' . ($feedback->session->title ?? 'N/A') . '</td>
                <td>' . ($feedback->teacher->name ?? 'N/A') . '</td>
                <td>' . $feedback->overall_rating . '/5</td>
                <td>' . ucfirst($feedback->feedback_type) . '</td>
                <td>' . ucfirst($feedback->status) . '</td>
                <td>' . $feedback->created_at->format('Y-m-d') . '</td>
            </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        return $html;
    }
}