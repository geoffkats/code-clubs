<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Club;

class ClubPolicy
{
    /**
     * Determine if the user can view a club
     */
    public function viewClub(User $user, Club $club)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $user->clubsAsTeacher->contains($club->id)) {
            return true;
        }
        
        // Students can view clubs they're enrolled in (through existing V1 system)
        if ($user->user_role === 'student') {
            // This would need to be implemented based on the existing club_enrollments table
            // For now, we'll return false as students use the existing V1 system
            return false;
        }
        
        return false;
    }

    /**
     * Determine if the user can create clubs
     */
    public function create(User $user)
    {
        return in_array($user->user_role, ['admin', 'facilitator']);
    }

    /**
     * Determine if the user can update a club
     */
    public function update(User $user, Club $club)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $club->facilitator_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can delete a club
     */
    public function delete(User $user, Club $club)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $club->facilitator_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can manage teachers for a club
     */
    public function manageTeachers(User $user, Club $club)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $club->facilitator_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can view club resources
     */
    public function viewResources(User $user, Club $club)
    {
        return $this->viewClub($user, $club);
    }

    /**
     * Determine if the user can create resources for a club
     */
    public function createResources(User $user, Club $club)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $user->clubsAsTeacher->contains($club->id)) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can view club sessions
     */
    public function viewSessions(User $user, Club $club)
    {
        return $this->viewClub($user, $club);
    }

    /**
     * Determine if the user can create sessions for a club
     */
    public function createSessions(User $user, Club $club)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $club->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $user->clubsAsTeacher->contains($club->id)) {
            return true;
        }
        
        return false;
    }
}
