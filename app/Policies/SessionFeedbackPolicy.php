<?php

namespace App\Policies;

use App\Models\SessionFeedback;
use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionFeedbackPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any session feedbacks
     */
    public function viewAny(User $user)
    {
        return in_array($user->user_role, ['admin', 'facilitator', 'teacher']);
    }

    /**
     * Determine whether the user can view the session feedback
     */
    public function view(User $user, SessionFeedback $sessionFeedback)
    {
        // Admin can view all feedback
        if ($user->user_role === 'admin') {
            return true;
        }

        // Facilitator can view their own feedback
        if ($user->user_role === 'facilitator' && $sessionFeedback->facilitator_id === $user->id) {
            return true;
        }

        // Teacher can view feedback about them
        if ($user->user_role === 'teacher' && $sessionFeedback->teacher_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create session feedback
     */
    public function create(User $user, Session $session)
    {
        // Only facilitators can create feedback
        if ($user->user_role !== 'facilitator') {
            return false;
        }

        // Check if the facilitator is assigned to manage the club for this session
        $managedClubs = $user->managedClubs()->pluck('id')->toArray();
        
        return in_array($session->club_id, $managedClubs);
    }

    /**
     * Determine whether the user can update the session feedback
     */
    public function update(User $user, SessionFeedback $sessionFeedback)
    {
        // Admin can update any feedback
        if ($user->user_role === 'admin') {
            return true;
        }

        // Facilitator can only update their own feedback if it's still draft
        if ($user->user_role === 'facilitator' && 
            $sessionFeedback->facilitator_id === $user->id && 
            $sessionFeedback->status === 'draft') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the session feedback
     */
    public function delete(User $user, SessionFeedback $sessionFeedback)
    {
        // Admin can delete any feedback
        if ($user->user_role === 'admin') {
            return true;
        }

        // Facilitator can only delete their own feedback if it's still draft
        if ($user->user_role === 'facilitator' && 
            $sessionFeedback->facilitator_id === $user->id && 
            $sessionFeedback->status === 'draft') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view analytics
     */
    public function viewAnalytics(User $user)
    {
        return $user->user_role === 'admin';
    }

    /**
     * Determine whether the user can export feedback data
     */
    public function export(User $user)
    {
        return $user->user_role === 'admin';
    }
}