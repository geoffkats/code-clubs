<div class="space-y-6" wire:poll.30s>
    <!-- Header with Actions -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Teacher Dashboard</h1>
                <p class="text-green-100 mt-2">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex space-x-3">
                <button wire:click="openCreateSessionModal" 
                        class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Session
                </button>
                <button wire:click="refreshStats" 
                        class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
        <!-- Total Clubs -->
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">My Clubs</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_clubs'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Students</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_students'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Sessions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Sessions</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_sessions'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending Reports</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_reports'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Proofs -->
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Proofs Uploaded</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_proofs'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Recent Activity</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['recent_activity'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Sessions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Upcoming Sessions</h2>
                    <a href="{{ route('teacher.sessions') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($upcomingSessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingSessions as $session)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium text-slate-900 dark:text-white">{{ $session->club->club_name }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                                    @if($session->session_time)
                                        at {{ \Carbon\Carbon::parse($session->session_time)->format('g:i A') }}
                                    @endif
                                </p>
                                @if($session->session_notes)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ Str::limit($session->session_notes, 50) }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button wire:click="openUploadModal({{ $session->id }})" 
                                        class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                    Upload Proof
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-slate-500 dark:text-slate-400">No upcoming sessions</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Recent Reports</h2>
                    <a href="{{ route('teacher.reports') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentReports->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentReports as $report)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium text-slate-900 dark:text-white">{{ $report->report_name }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    {{ $report->student->student_first_name ?? 'N/A' }} {{ $report->student->student_last_name ?? 'N/A' }} • 
                                    {{ $report->club->club_name ?? 'N/A' }}
                                </p>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($report->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($report->status === 'facilitator_approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($report->status === 'revision_requested') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">
                                        {{ $report->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-slate-500 dark:text-slate-400">No reports found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Proofs -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Recent Proofs</h2>
                <a href="{{ route('teacher.sessions') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($recentProofs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recentProofs as $proof)
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($proof->proof_type === 'video') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                {{ ucfirst($proof->proof_type) }}
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $proof->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">
                            {{ $proof->session->club->club_name ?? 'Unknown Club' }}
                        </p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                            {{ \Carbon\Carbon::parse($proof->session->session_date)->format('M d, Y') }}
                        </p>
                        <div class="flex space-x-2 mt-3">
                            <button wire:click="downloadProof({{ $proof->id }})" 
                                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                Download
                            </button>
                            <button wire:click="deleteProof({{ $proof->id }})" 
                                    onclick="return confirm('Are you sure you want to delete this proof?')"
                                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400">No proofs uploaded yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Session Modal -->
    @if($showCreateSessionModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeCreateSessionModal">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Create New Session</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Club
                    </label>
                    <select wire:model="newSession.club_id" 
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Club</option>
                        @foreach($myClubs as $club)
                            <option value="{{ $club->id }}">{{ $club->club_name }}</option>
                        @endforeach
                    </select>
                    @error('newSession.club_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Session Date
                    </label>
                    <input type="date" wire:model="newSession.session_date" 
                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('newSession.session_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Session Time (Optional)
                    </label>
                    <input type="time" wire:model="newSession.session_time" 
                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('newSession.session_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Session Notes (Optional)
                    </label>
                    <textarea wire:model="newSession.session_notes" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Add any notes about this session..."></textarea>
                    @error('newSession.session_notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button wire:click="closeCreateSessionModal" 
                        class="px-4 py-2 bg-slate-300 dark:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-400 dark:hover:bg-slate-500 transition-colors">
                    Cancel
                </button>
                <button wire:click="createSession" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Create Session
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Upload Proof Modal -->
    @if($showUploadModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeUploadModal">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Upload Proof</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Proof Files (Photos/Videos)
                </label>
                <input type="file" wire:model="proofFiles" multiple accept="image/*,video/*" 
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('proofFiles.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Supported formats: JPEG, PNG, MP4, MOV. Max size: 50MB per file.
                </p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button wire:click="closeUploadModal" 
                        class="px-4 py-2 bg-slate-300 dark:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-400 dark:hover:bg-slate-500 transition-colors">
                    Cancel
                </button>
                <button wire:click="uploadProofs" 
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50">
                    <span wire:loading.remove>Upload Proofs</span>
                    <span wire:loading>Uploading...</span>
                </button>
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