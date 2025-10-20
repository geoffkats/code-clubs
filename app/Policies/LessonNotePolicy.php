<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LessonNote;

class LessonNotePolicy
{
    /**
     * Determine if the user can view a resource
     */
    public function view(User $user, LessonNote $resource)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator') {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $user->clubsAsTeacher->contains($resource->club_id)) {
            return true;
        }
        
        if ($user->user_role === 'student' && $resource->visibility === 'all') {
            // This would need to be implemented based on the existing club_enrollments table
            // For now, we'll return false as students use the existing V1 system
            return false;
        }
        
        return false;
    }

    /**
     * Determine if the user can create resources
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
     * Determine if the user can update a resource
     */
    public function update(User $user, LessonNote $resource)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator') {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $user->clubsAsTeacher->contains($resource->club_id)) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can delete a resource
     */
    public function delete(User $user, LessonNote $resource)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator') {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $user->clubsAsTeacher->contains($resource->club_id)) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can download a resource
     */
    public function download(User $user, LessonNote $resource)
    {
        return $this->view($user, $resource);
    }
}
