<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can manage facilitators
     */
    public function manageFacilitators(User $user)
    {
        return $user->user_role === 'admin';
    }

    /**
     * Determine if the user can manage teachers
     */
    public function manageTeachers(User $user, User $teacher)
    {
        return $user->user_role === 'admin' || 
               ($user->user_role === 'facilitator' && $teacher->facilitator_id === $user->id);
    }

    /**
     * Determine if the user can view a teacher
     */
    public function viewTeacher(User $user, User $teacher)
    {
        return $user->user_role === 'admin' || 
               ($user->user_role === 'facilitator' && $teacher->facilitator_id === $user->id);
    }

    /**
     * Determine if the user can create teachers
     */
    public function createTeacher(User $user)
    {
        return $user->user_role === 'admin' || $user->user_role === 'facilitator';
    }

    /**
     * Determine if the user can update a teacher
     */
    public function updateTeacher(User $user, User $teacher)
    {
        return $user->user_role === 'admin' || 
               ($user->user_role === 'facilitator' && $teacher->facilitator_id === $user->id);
    }

    /**
     * Determine if the user can delete a teacher
     */
    public function deleteTeacher(User $user, User $teacher)
    {
        return $user->user_role === 'admin' || 
               ($user->user_role === 'facilitator' && $teacher->facilitator_id === $user->id);
    }

    /**
     * Determine if the user can manage students
     */
    public function manageStudents(User $user)
    {
        return in_array($user->user_role, ['admin', 'facilitator', 'teacher']);
    }

    /**
     * Determine if the user can view a student
     */
    public function viewStudent(User $user, $student)
    {
        if ($user->user_role === 'admin') {
            return true;
        }

        if ($user->user_role === 'facilitator') {
            // Facilitator can view students in clubs they manage
            return $user->managedClubs()->whereHas('students', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })->exists();
        }

        if ($user->user_role === 'teacher') {
            // Teacher can view students in clubs they teach
            return $user->clubsAsTeacher()->whereHas('students', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })->exists();
        }

        return false;
    }
}
