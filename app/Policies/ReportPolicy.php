<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report;

class ReportPolicy
{
    /**
     * Determine if the user can view a report
     */
    public function view(User $user, Report $report)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator' && $report->facilitator_id === $user->id) {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $report->teacher_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine if the user can create reports
     */
    public function create(User $user)
    {
        return $user->user_role === 'teacher';
    }

    /**
     * Determine if the user can update a report
     */
    public function update(User $user, Report $report)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $report->teacher_id === $user->id) {
            // Only allow updates if report is not yet approved
            return in_array($report->status, ['pending', 'revision_requested']);
        }
        
        return false;
    }

    /**
     * Determine if the user can delete a report
     */
    public function delete(User $user, Report $report)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'teacher' && $report->teacher_id === $user->id) {
            // Only allow deletion if report is not yet approved
            return in_array($report->status, ['pending', 'revision_requested']);
        }
        
        return false;
    }

    /**
     * Determine if the user can approve reports as a facilitator
     */
    public function approveFacilitator(User $user, Report $report)
    {
        return $user->user_role === 'facilitator' && 
               $report->facilitator_id === $user->id &&
               $report->status === 'pending';
    }

    /**
     * Determine if the user can approve reports as an admin
     */
    public function approveAdmin(User $user, Report $report)
    {
        return $user->user_role === 'admin' &&
               $report->status === 'facilitator_approved';
    }

    /**
     * Determine if the user can request revision for a report
     */
    public function requestRevision(User $user, Report $report)
    {
        if ($user->user_role === 'facilitator' && $report->facilitator_id === $user->id) {
            return in_array($report->status, ['pending', 'facilitator_approved']);
        }
        
        if ($user->user_role === 'admin') {
            return in_array($report->status, ['pending', 'facilitator_approved']);
        }
        
        return false;
    }

    /**
     * Determine if the user can reject a report
     */
    public function reject(User $user, Report $report)
    {
        if ($user->user_role === 'facilitator' && $report->facilitator_id === $user->id) {
            return in_array($report->status, ['pending', 'facilitator_approved']);
        }
        
        if ($user->user_role === 'admin') {
            return in_array($report->status, ['pending', 'facilitator_approved']);
        }
        
        return false;
    }

    /**
     * Determine if the user can view all reports
     */
    public function viewAll(User $user)
    {
        return in_array($user->user_role, ['admin', 'facilitator']);
    }

    /**
     * Determine if the user can view pending reports
     */
    public function viewPending(User $user)
    {
        if ($user->user_role === 'admin') {
            return true;
        }
        
        if ($user->user_role === 'facilitator') {
            return true;
        }
        
        return false;
    }
}
