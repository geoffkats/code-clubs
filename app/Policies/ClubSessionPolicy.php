<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClubSession;

class ClubSessionPolicy
{
    /**
     * Determine if the user can view a session
     */
    public function view(User $user, ClubSession $session)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $session->club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $session->teacher_id === $user->id) {
            return true;
        }
        
        // Students can view sessions for clubs they're enrolled in (through existing V1 system)
        if ($user->user_role === 'student') {
            // This would need to be implemented based on the existing club_enrollments table
            // For now, we'll return false as students use the existing V1 system
            return false;
        }
        
        return false;
    }

    /**
     * Determine if the user can create sessions
     */
    public function create(User $user, $club = null)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator') {
            return true;
        }
        
        if ($user->user_role === 'teacher') {
            // If club is provided, check if user teaches that club
            if ($club && $user->clubsAsTeacher->contains($club->id)) {
                return true;
            }
            // If no club provided, allow creation (will be validated in controller)
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can update a session
     */
    public function update(User $user, ClubSession $session)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $session->club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $session->teacher_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can delete a session
     */
    public function delete(User $user, ClubSession $session)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $session->club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $session->teacher_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can upload proofs for a session
     */
    public function uploadProof(User $user, ClubSession $session)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $session->club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $session->teacher_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can view attendance for a session
     */
    public function viewAttendance(User $user, ClubSession $session)
    {
        return $this->view($user, $session);
    }

    /**
     * Determine if the user can update attendance for a session
     */
    public function updateAttendance(User $user, ClubSession $session)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $session->club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $session->teacher_id === $user->id) {
            return true;
        }
        
        return false;
    }
}
