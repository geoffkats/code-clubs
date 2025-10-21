<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportAwaitingApproval;
use App\Notifications\ReportApproved;
use App\Notifications\ReportRejected;
use App\Notifications\ReportRevisionRequested;
use Illuminate\Support\Facades\Cache;

class ReportApprovalWorkflow extends Component
{
    use WithPagination;

    public $userRole;
    public $selectedReport = null;
    public $showReportModal = false;
    public $reportFeedback = '';
    public $reportAction = '';
    public $filterStatus = 'all';
    public $searchTerm = '';

    protected $listeners = [
        'refreshReports' => '$refresh',
        'reportApproved' => 'handleReportApproved',
        'reportRejected' => 'handleReportRejected'
    ];

    public function mount()
    {
        $this->userRole = auth()->user()->user_role;
    }

    public function getReportsQuery()
    {
        $query = Report::with(['club', 'student', 'facilitator', 'admin']);

        if ($this->userRole === 'facilitator') {
            $query->where('facilitator_id', auth()->id());
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('report_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('student', function($studentQuery) {
                      $studentQuery->where('student_first_name', 'like', '%' . $this->searchTerm . '%')
                                  ->orWhere('student_last_name', 'like', '%' . $this->searchTerm . '%');
                  })
                  ->orWhereHas('club', function($clubQuery) {
                      $clubQuery->where('club_name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        return $query->latest();
    }

    public function filterByStatus($status)
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    public function searchReports()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function approveReport($reportId)
    {
        $report = Report::findOrFail($reportId);
        
        if ($this->userRole === 'facilitator') {
            $report->update([
                'status' => 'facilitator_approved',
                'facilitator_approved_at' => now(),
            ]);

            // Notify admin if there's an admin for this school
            $admin = User::where('user_role', 'admin')
                ->where('school_id', $report->club->school_id)
                ->first();
            
            if ($admin) {
                $admin->notify(new ReportAwaitingApproval($report, 'admin'));
            }

            // Notify teacher (if teacher relationship exists)
            // $report->teacher->notify(new ReportApproved($report, 'facilitator'));
        } elseif ($this->userRole === 'admin') {
            $report->update([
                'status' => 'completed',
                'admin_id' => auth()->id(),
                'admin_approved_at' => now(),
            ]);

            // Notify teacher (if teacher relationship exists)
            // $report->teacher->notify(new ReportApproved($report, 'admin'));
        }

        $this->dispatch('reportApproved', $reportId);
        
        session()->flash('success', 'Report approved successfully!');
    }

    public function rejectReport($reportId)
    {
        $this->selectedReport = Report::findOrFail($reportId);
        $this->reportAction = 'reject';
        $this->showReportModal = true;
    }

    public function requestRevision($reportId)
    {
        $this->selectedReport = Report::findOrFail($reportId);
        $this->reportAction = 'revision';
        $this->showReportModal = true;
    }

    public function submitReportAction()
    {
        if (!$this->selectedReport || !$this->reportFeedback) {
            return;
        }

        $status = $this->reportAction === 'reject' ? 'rejected' : 'revision_requested';
        
        if ($this->userRole === 'facilitator') {
            $this->selectedReport->update([
                'status' => $status,
                'facilitator_feedback' => $this->reportFeedback,
            ]);
        } elseif ($this->userRole === 'admin') {
            $this->selectedReport->update([
                'status' => $status,
                'admin_feedback' => $this->reportFeedback,
            ]);
        }

        // Notify teacher (if teacher relationship exists)
        // if ($status === 'rejected') {
        //     $this->selectedReport->teacher->notify(new ReportRejected($this->selectedReport, $this->userRole));
        // } else {
        //     $this->selectedReport->teacher->notify(new ReportRevisionRequested($this->selectedReport, $this->userRole));
        // }

        $this->closeReportModal();
        
        session()->flash('success', 'Report action completed successfully!');
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->selectedReport = null;
        $this->reportFeedback = '';
        $this->reportAction = '';
    }

    public function viewReport($reportId)
    {
        $this->selectedReport = Report::with(['teacher', 'club', 'student', 'facilitator', 'admin'])->findOrFail($reportId);
        $this->showReportModal = true;
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'facilitator_approved' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'admin_approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'revision_requested' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200'
        };
    }

    public function getStatusActions($report)
    {
        $actions = [];
        
        if ($this->userRole === 'facilitator' && $report->status === 'pending') {
            $actions = ['approve', 'reject', 'revision'];
        } elseif ($this->userRole === 'admin' && $report->status === 'facilitator_approved') {
            $actions = ['approve', 'reject', 'revision'];
        }
        
        return $actions;
    }

    public function render()
    {
        $reports = $this->getReportsQuery()->paginate(10);
        
        return view('livewire.report-approval-workflow', [
            'reports' => $reports
        ])->layout('layouts.admin');
    }
}