<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\School;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all students without student_id_number
        $studentsWithoutIds = Student::whereNull('student_id_number')
            ->orWhere('student_id_number', '')
            ->get();

        foreach ($studentsWithoutIds as $student) {
            // Generate student ID based on school
            $studentId = $this->generateStudentId($student->school_id);
            $student->update(['student_id_number' => $studentId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration doesn't need to be reversed
        // as it only adds missing data
    }

    /**
     * Generate unique student ID based on school
     */
    private function generateStudentId($schoolId)
    {
        // Get school abbreviation (first 3 letters of school name)
        $school = School::find($schoolId);
        if (!$school) {
            $schoolAbbr = 'STU'; // Default fallback
        } else {
            $schoolAbbr = strtoupper(substr($school->school_name, 0, 3));
        }
        
        // Get the last student ID for this school
        $lastStudent = Student::where('school_id', $schoolId)
            ->where('student_id_number', 'like', $schoolAbbr . '%')
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
};