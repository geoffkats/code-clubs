<div class="space-y-6" wire:poll.15s>
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Report Approval Workflow</h1>
                <p class="text-purple-100 mt-2">{{ ucfirst($userRole) }} Dashboard</p>
            </div>
            <button wire:click="$refresh" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-lg p-4 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex flex-wrap gap-4 items-center">
            <!-- Status Filter -->
            <div class="flex flex-wrap gap-2">
                <button wire:click="filterByStatus('all')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filterStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                    All Reports
                </button>
                <button wire:click="filterByStatus('pending')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filterStatus === 'pending' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                    Pending
                </button>
                @if($userRole === 'admin')
                <button wire:click="filterByStatus('facilitator_approved')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filterStatus === 'facilitator_approved' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                    Awaiting Admin
                </button>
                @endif
                <button wire:click="filterByStatus('revision_requested')" 
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filterStatus === 'revision_requested' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                    Needs Revision
                </button>
            </div>

            <!-- Search -->
            <div class="flex-1 min-w-64">
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchTerm"
                           wire:keyup="searchReports"
                           placeholder="Search reports, students, or clubs..." 
                           class="w-full px-4 py-2 pl-10 pr-4 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    @if($searchTerm)
                    <button wire:click="clearSearch" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Report</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Club</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Teacher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $report->report_name }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Score: {{ $report->report_overall_score ?? 'N/A' }}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-white">
                                {{ $report->student->student_first_name ?? 'N/A' }} {{ $report->student->student_last_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-white">{{ $report->club->club_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 dark:text-white">{{ $report->teacher->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($report->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                            {{ $report->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="viewReport({{ $report->id }})" 
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    View
                                </button>
                                
                                @foreach($this->getStatusActions($report) as $action)
                                    @if($action === 'approve')
                                        <button wire:click="approveReport({{ $report->id }})" 
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            Approve
                                        </button>
                                    @elseif($action === 'reject')
                                        <button wire:click="rejectReport({{ $report->id }})" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Reject
                                        </button>
                                    @elseif($action === 'revision')
                                        <button wire:click="requestRevision({{ $report->id }})" 
                                                class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            Request Revision
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-slate-500 dark:text-slate-400">No reports found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $reports->links() }}
        </div>
        @endif
    </div>

    <!-- Report Modal -->
    @if($showReportModal && $selectedReport)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click.self="closeReportModal">
        <div class="bg-white dark:bg-slate-800 rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $selectedReport->report_name }}</h3>
                    <button wire:click="closeReportModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Report Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-3">Report Information</h4>
                        <div class="space-y-2">
                            <p><span class="font-medium">Student:</span> {{ $selectedReport->student->student_first_name ?? 'N/A' }} {{ $selectedReport->student->student_last_name ?? 'N/A' }}</p>
                            <p><span class="font-medium">Club:</span> {{ $selectedReport->club->club_name ?? 'N/A' }}</p>
                            <p><span class="font-medium">Teacher:</span> {{ $selectedReport->teacher->name ?? 'N/A' }}</p>
                            <p><span class="font-medium">Score:</span> {{ $selectedReport->report_overall_score ?? 'N/A' }}%</p>
                            <p><span class="font-medium">Status:</span> 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($selectedReport->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $selectedReport->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-3">Assessment Scores</h4>
                        <div class="space-y-2">
                            <p><span class="font-medium">Problem Solving:</span> {{ $selectedReport->problem_solving_score ?? 'N/A' }}/10</p>
                            <p><span class="font-medium">Creativity:</span> {{ $selectedReport->creativity_score ?? 'N/A' }}/10</p>
                            <p><span class="font-medium">Collaboration:</span> {{ $selectedReport->collaboration_score ?? 'N/A' }}/10</p>
                            <p><span class="font-medium">Persistence:</span> {{ $selectedReport->persistence_score ?? 'N/A' }}/10</p>
                        </div>
                    </div>
                </div>

                <!-- Report Summary -->
                @if($selectedReport->report_summary_text)
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-3">Summary</h4>
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                        <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $selectedReport->report_summary_text }}</p>
                    </div>
                </div>
                @endif

                <!-- Feedback Section -->
                @if($selectedReport->facilitator_feedback || $selectedReport->admin_feedback)
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-3">Feedback</h4>
                    <div class="space-y-4">
                        @if($selectedReport->facilitator_feedback)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-2">Facilitator Feedback</p>
                            <p class="text-blue-800 dark:text-blue-300 whitespace-pre-wrap">{{ $selectedReport->facilitator_feedback }}</p>
                        </div>
                        @endif
                        
                        @if($selectedReport->admin_feedback)
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <p class="text-sm font-medium text-green-900 dark:text-green-200 mb-2">Admin Feedback</p>
                            <p class="text-green-800 dark:text-green-300 whitespace-pre-wrap">{{ $selectedReport->admin_feedback }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Form -->
                @if($reportAction)
                <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                    <h4 class="text-lg font-medium text-slate-900 dark:text-white mb-3">
                        {{ $reportAction === 'reject' ? 'Reject Report' : 'Request Revision' }}
                    </h4>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Feedback
                        </label>
                        <textarea wire:model="reportFeedback" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Provide feedback for the teacher..."></textarea>
                        @error('reportFeedback') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
            </div>
            
            <div class="p-6 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-3">
                <button wire:click="closeReportModal" 
                        class="px-4 py-2 bg-slate-300 dark:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-400 dark:hover:bg-slate-500 transition-colors">
                    Close
                </button>
                
                @if($reportAction)
                <button wire:click="submitReportAction" 
                        class="px-4 py-2 {{ $reportAction === 'reject' ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-600 hover:bg-yellow-700' }} text-white rounded-lg transition-colors">
                    {{ $reportAction === 'reject' ? 'Reject Report' : 'Request Revision' }}
                </button>
                @else
                <button wire:click="approveReport({{ $selectedReport->id }})" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    Approve Report
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
    @endif
</div>