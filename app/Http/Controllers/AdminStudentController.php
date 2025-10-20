<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminStudentController extends Controller
{
    /**
     * Display a listing of students with management options
     */
    public function index(Request $request)
    {
        $query = Student::with(['school', 'clubs']);
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('student_first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('student_last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('student_id_number', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by school
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        
        // Filter by account status
        if ($request->filled('status')) {
            if ($request->status === 'with_password') {
                $query->whereNotNull('password');
            } elseif ($request->status === 'no_password') {
                $query->whereNull('password');
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        $students = $query->paginate(25)->withQueryString();
        $schools = \App\Models\School::orderBy('school_name')->get();
        
        return view('admin.students.index', compact('students', 'schools'));
    }

    /**
     * Show the form for creating a new student with credentials
     */
    public function create()
    {
        $schools = School::all();
        return view('admin.students.create', compact('schools'));
    }

    /**
     * Store a newly created student with login credentials
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:students',
            'password' => 'required|string|min:8',
            'student_grade_level' => 'required|string|max:10',
            'student_parent_name' => 'required|string|max:255',
            'student_parent_email' => 'required|string|email|max:255',
            'school_id' => 'required|exists:schools,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Auto-generate Student ID
        $studentId = $this->generateStudentId($request->school_id);

        $student = Student::create([
            'student_first_name' => $request->student_first_name,
            'student_last_name' => $request->student_last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'student_id_number' => $studentId,
            'student_grade_level' => $request->student_grade_level,
            'student_parent_name' => $request->student_parent_name,
            'student_parent_email' => $request->student_parent_email,
            'school_id' => $request->school_id,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', "Student account created successfully! Student ID: {$studentId}");
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['school', 'clubs', 'assessment_scores.assessment', 'reports']);
        
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing student credentials
     */
    public function edit(Student $student)
    {
        $schools = School::all();
        return view('admin.students.edit', compact('student', 'schools'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'student_grade_level' => 'required|string|max:10',
            'student_parent_name' => 'required|string|max:255',
            'student_parent_email' => 'required|string|email|max:255',
            'school_id' => 'required|exists:schools,id',
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'student_first_name',
            'student_last_name',
            'email',
            'student_grade_level',
            'student_parent_name',
            'student_parent_email',
            'school_id'
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student account updated successfully!');
    }

    /**
     * Bulk enroll all students in a school into a club
     */
    public function bulkEnroll(Request $request)
    {
        $data = Validator::make($request->all(), [
            'school_id' => ['required', 'exists:schools,id'],
            'club_id' => ['required', 'exists:clubs,id'],
        ])->validate();

        $schoolId = (int) $data['school_id'];
        $clubId = (int) $data['club_id'];

        // Get student IDs for the school
        $studentIds = \App\Models\Student::where('school_id', $schoolId)->pluck('id');

        if ($studentIds->isEmpty()) {
            return back()->with('error', 'No students found for the selected school.');
        }

        // Insert missing enrollments only
        $now = now();
        $rows = [];
        foreach ($studentIds as $studentId) {
            $rows[] = [
                'club_id' => $clubId,
                'student_id' => $studentId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Avoid duplicates by ignoring existing pairs
        DB::table('club_enrollments')->upsert(
            $rows,
            ['club_id', 'student_id'],
            ['updated_at']
        );

        return back()->with('success', 'Students enrolled into the club successfully.');
    }

    /**
     * Show form to reset student password
     */
    public function showResetPassword(Student $student)
    {
        return view('admin.students.reset-password', compact('student'));
    }

    /**
     * Reset student password
     */
    public function resetPassword(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $student->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student password has been reset successfully!');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student account deleted successfully!');
    }

    /**
     * Generate temporary password for student
     */
    public function generateTempPassword()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        
        for ($i = 0; $i < 8; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    /**
     * Bulk update students without IDs
     */
    public function bulkUpdateIds()
    {
        $studentsWithoutIds = Student::where(function($query) {
            $query->whereNull('student_id_number')
                  ->orWhere('student_id_number', '');
        })->get();

        $updatedCount = 0;
        
        foreach ($studentsWithoutIds as $student) {
            $studentId = $this->generateStudentId($student->school_id);
            $student->update(['student_id_number' => $studentId]);
            $updatedCount++;
        }

        return redirect()->route('admin.students.index')
            ->with('success', "Updated Student IDs for {$updatedCount} students!");
    }

    /**
     * Generate unique student ID based on school
     */
    private function generateStudentId($schoolId)
    {
        // Get school abbreviation (first 3 letters of school name)
        $school = \App\Models\School::find($schoolId);
        if (!$school) {
            $schoolAbbr = 'STU'; // Default fallback
        } else {
            $schoolAbbr = strtoupper(substr($school->school_name, 0, 3));
        }
        
        // Get the last student ID for this school
        $lastStudent = Student::where('school_id', $schoolId)
            ->where('student_id_number', 'like', $schoolAbbr . '%')
            ->whereNotNull('student_id_number')
            ->where('student_id_number', '!=', '')
            ->orderBy('student_id_number', 'desc')
            ->first();
        
        if ($lastStudent) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastStudent->student_id_number, strlen($schoolAbbr));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format with leading zeros (e.g., CAU001, CAU002, etc.)
        return $schoolAbbr . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}