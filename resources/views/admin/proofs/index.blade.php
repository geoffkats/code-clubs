@extends(request()->routeIs('facilitator.*') ? 'layouts.facilitator' : (request()->routeIs('teacher.*') ? 'layouts.teacher' : 'layouts.admin'))
@section('title', 'Teacher Proofs Management')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            📸 Teacher Proofs Management
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400">
                            Review and manage teacher session proofs (images and videos)
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        @if(!request()->routeIs('facilitator.*') && !request()->routeIs('teacher.*'))
                        <a href="{{ route('admin.proofs.archived') }}" 
                           class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            📦 Archived Proofs
                        </a>
                        <a href="{{ route('admin.proofs.analytics') }}" 
                           class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            📊 Analytics
                        </a>
                        @endif
                        <a href="@if(request()->routeIs('facilitator.*')){{ route('facilitator.proofs.create') }}@elseif(request()->routeIs('teacher.*')){{ route('teacher.proofs.create') }}@else{{ route('admin.proofs.create') }}@endif" 
                           class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            📤 Upload Proof
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Proofs</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending Review</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Approved</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['approved'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Rejected</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['rejected'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 mb-6">
                <form method="GET" action="@if(request()->routeIs('facilitator.*')){{ route('facilitator.proofs.index') }}@elseif(request()->routeIs('teacher.*')){{ route('teacher.proofs.index') }}@else{{ route('admin.proofs.index') }}@endif" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Club</label>
                        <select name="club_id" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Clubs</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                    {{ $club->club_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teacher</label>
                        <select name="teacher_id" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            🔍 Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            @if(!request()->routeIs('teacher.*'))
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Bulk Actions</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Range Selection:</label>
                            <button onclick="selectRange()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                                Select Range
                            </button>
                            <button onclick="selectAll()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                                Select All
                            </button>
                            <button onclick="clearSelection()" class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button onclick="bulkApprove()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Approve Selected</span>
                    </button>
                    <button onclick="bulkReject()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Reject Selected</span>
                    </button>
                    <button onclick="bulkArchive()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6"></path>
                        </svg>
                        <span>Archive Selected</span>
                    </button>
                    @if(!request()->routeIs('facilitator.*'))
                    <button onclick="bulkDelete()" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Delete Selected</span>
                    </button>
                    @endif
                    <button onclick="bulkExport()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Export Selected</span>
                    </button>
                </div>
            </div>
            @endif

            <!-- Proofs Table -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Proof</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Club</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Session Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($proofs as $proof)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="proof_ids[]" value="{{ $proof->id }}" class="proof-checkbox rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($proof->isImage())
                                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @elseif($proof->isVideo())
                                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @elseif($proof->isDocument())
                                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                    @if($proof->isImage())
                                                        Photo
                                                    @elseif($proof->isVideo())
                                                        Video
                                                    @elseif($proof->isDocument())
                                                        Document
                                                    @else
                                                        File
                                                    @endif
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $proof->formatted_file_size }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $proof->uploader->name }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $proof->uploader->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $proof->session && $proof->session->club ? $proof->session->club->club_name : 'Unknown Club' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $proof->session && $proof->session->session_date ? \Carbon\Carbon::parse($proof->session->session_date)->format('M d, Y') : 'Unknown Date' }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $proof->session ? $proof->session->session_time : 'Unknown Time' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($proof->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($proof->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($proof->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $proof->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $proof->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $proof->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-1">
                                            <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.show', $proof) : (request()->routeIs('teacher.*') ? route('teacher.proofs.show', $proof) : route('admin.proofs.show', $proof)) }}" 
                                               class="p-2 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors duration-200"
                                               title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            @if($proof->status === 'pending' && !request()->routeIs('teacher.*'))
                                                <button onclick="approveProof({{ $proof->id }})" 
                                                        class="p-2 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors duration-200"
                                                        title="Approve Proof">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="rejectProof({{ $proof->id }})" 
                                                        class="p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200"
                                                        title="Reject Proof">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            @if(!request()->routeIs('teacher.*'))
                                            <button onclick="archiveProof({{ $proof->id }})" 
                                                    class="p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/20 rounded-lg transition-colors duration-200"
                                                    title="Archive Proof">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6"></path>
                                                </svg>
                                            </button>
                                            @endif
                                            <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.download', $proof) : (request()->routeIs('teacher.*') ? route('teacher.proofs.download', $proof) : route('admin.proofs.download', $proof)) }}" 
                                               class="p-2 text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors duration-200"
                                               title="Download Proof">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                            @if(!request()->routeIs('teacher.*'))
                                            <button onclick="deleteProof({{ $proof->id }})" 
                                                    class="p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200"
                                                    title="Delete Proof">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-slate-500 dark:text-slate-400">
                                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium mb-2">No proofs found</p>
                                            <p class="text-sm">No teacher proofs match your current filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($proofs->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $proofs->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    @if(session('success'))
        <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl backdrop-blur-sm border border-slate-200 dark:border-slate-700">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Success!</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-6">{{ session('success') }}</p>
                    <button onclick="closeSuccessModal()" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Modal -->
    @if(session('error'))
        <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl backdrop-blur-sm border border-slate-200 dark:border-slate-700">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Error!</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-6">{{ session('error') }}</p>
                    <button onclick="closeErrorModal()" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Bulk Approve Modal -->
    <div id="bulk-approve-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl backdrop-blur-sm border border-slate-200 dark:border-slate-700">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Bulk Approve Proofs</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6">Are you sure you want to approve the selected proofs?</p>
                <form id="bulk-approve-form" method="POST" action="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.bulk-approve') : route('admin.proofs.bulk-approve') }}">
                    @csrf
                    <div id="bulk-approve-proofs"></div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeBulkApproveModal()" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            Cancel
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Reject Modal -->
    <div id="bulk-reject-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl backdrop-blur-sm border border-slate-200 dark:border-slate-700">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Bulk Reject Proofs</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6">Are you sure you want to reject the selected proofs?</p>
                <form id="bulk-reject-form" method="POST" action="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.bulk-reject') : route('admin.proofs.bulk-reject') }}">
                    @csrf
                    <div id="bulk-reject-proofs"></div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Rejection Reason</label>
                        <textarea name="rejection_reason" rows="3" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Enter reason for rejection..."></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeBulkRejectModal()" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            Cancel
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function closeSuccessModal() {
            const modal = document.getElementById('success-modal');
            if (modal) {
                modal.style.display = 'none';
                modal.remove();
            }
        }

        function closeErrorModal() {
            const modal = document.getElementById('error-modal');
            if (modal) {
                modal.style.display = 'none';
                modal.remove();
            }
        }

        function closeBulkApproveModal() {
            document.getElementById('bulk-approve-modal').classList.add('hidden');
        }

        function closeBulkRejectModal() {
            document.getElementById('bulk-reject-modal').classList.add('hidden');
        }

        // Auto-close success/error modals after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const successModal = document.getElementById('success-modal');
                const errorModal = document.getElementById('error-modal');
                if (successModal) {
                    successModal.style.display = 'none';
                    successModal.remove();
                }
                if (errorModal) {
                    errorModal.style.display = 'none';
                    errorModal.remove();
                }
            }, 5000);
        });

        // Select all functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.proof-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        // Bulk actions
        function bulkApprove() {
            const selectedIds = getSelectedProofIds();
            if (selectedIds.length === 0) {
                showErrorMessage('Please select proofs to approve.');
                return;
            }

            showConfirmModal(
                'Approve Selected Proofs',
                `Are you sure you want to approve ${selectedIds.length} selected proof(s)?`,
                'Approve All',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.bulk-approve') : route('admin.proofs.bulk-approve') }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'proof_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        function bulkReject() {
            const selectedIds = getSelectedProofIds();
            if (selectedIds.length === 0) {
                showErrorMessage('Please select proofs to reject.');
                return;
            }

            const rejectionReason = prompt(`Please provide a reason for rejecting ${selectedIds.length} proof(s):`);
            if (!rejectionReason || rejectionReason.trim() === '') {
                showErrorMessage('Rejection reason is required.');
                return;
            }

            showConfirmModal(
                'Reject Selected Proofs',
                `Are you sure you want to reject ${selectedIds.length} selected proof(s) with reason: "${rejectionReason}"?`,
                'Reject All',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.bulk-reject') : route('admin.proofs.bulk-reject') }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'rejection_reason';
                    reasonInput.value = rejectionReason;
                    form.appendChild(reasonInput);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'proof_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                },
                'danger'
            );
        }

        // Individual actions
        function approveProof(proofId) {
            showConfirmModal(
                'Approve Proof',
                'Are you sure you want to approve this proof?',
                'Approve',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/proofs/${proofId}/approve`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        function rejectProof(proofId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (!reason || reason.trim() === '') {
                showErrorMessage('Rejection reason is required.');
                return;
            }

            showConfirmModal(
                'Reject Proof',
                `Are you sure you want to reject this proof with reason: "${reason}"?`,
                'Reject',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/proofs/${proofId}/reject`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'rejection_reason';
                    reasonInput.value = reason;
                    form.appendChild(reasonInput);

                    document.body.appendChild(form);
                    form.submit();
                },
                'danger'
            );
        }

        function bulkArchive() {
            const selectedIds = getSelectedProofIds();
            if (selectedIds.length === 0) {
                showErrorMessage('Please select proofs to archive.');
                return;
            }

            showConfirmModal(
                'Archive Selected Proofs',
                `Are you sure you want to archive ${selectedIds.length} selected proof(s)?`,
                'Archive All',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.bulk-archive') : route('admin.proofs.bulk-archive') }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'proof_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        function archiveProof(proofId) {
            showConfirmModal(
                'Archive Proof',
                'Are you sure you want to archive this proof?',
                'Archive',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/proofs/${proofId}/archive`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        function deleteProof(proofId) {
            showConfirmModal(
                'Delete Proof',
                'Are you sure you want to permanently delete this proof? This action cannot be undone.',
                'Delete',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/proofs/${proofId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                },
                'danger'
            );
        }

        // Range selection functions
        function selectRange() {
            const start = prompt('Enter start row number (1-based):');
            const end = prompt('Enter end row number (1-based):');
            
            if (start && end && !isNaN(start) && !isNaN(end)) {
                const checkboxes = document.querySelectorAll('.proof-checkbox');
                const startIdx = parseInt(start) - 1;
                const endIdx = parseInt(end) - 1;
                
                if (startIdx >= 0 && endIdx < checkboxes.length && startIdx <= endIdx) {
                    for (let i = startIdx; i <= endIdx; i++) {
                        if (checkboxes[i]) {
                            checkboxes[i].checked = true;
                        }
                    }
                    updateSelectAllState();
                    showSuccessMessage(`Selected rows ${start} to ${end}`);
                } else {
                    showErrorMessage('Invalid range. Please check your row numbers.');
                }
            }
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('.proof-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            document.getElementById('selectAll').checked = true;
            showSuccessMessage(`Selected all ${checkboxes.length} proofs`);
        }

        function clearSelection() {
            const checkboxes = document.querySelectorAll('.proof-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            showSuccessMessage('Selection cleared');
        }

        function updateSelectAllState() {
            const checkboxes = document.querySelectorAll('.proof-checkbox');
            const checkedBoxes = document.querySelectorAll('.proof-checkbox:checked');
            const selectAll = document.getElementById('selectAll');
            
            selectAll.checked = checkboxes.length > 0 && checkedBoxes.length === checkboxes.length;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
        }

        // Helper function to get selected proof IDs
        function getSelectedProofIds() {
            const checkboxes = document.querySelectorAll('.proof-checkbox:checked');
            return Array.from(checkboxes).map(checkbox => checkbox.value);
        }

        // Enhanced bulk operations
        function bulkDelete() {
            // Prevent facilitators from deleting proofs (malpractice prevention)
            @if(request()->routeIs('facilitator.*'))
                showErrorMessage('Facilitators cannot delete proofs. This action is restricted to prevent malpractice.');
                return;
            @endif

            const selectedIds = getSelectedProofIds();
            if (selectedIds.length === 0) {
                showErrorMessage('Please select proofs to delete.');
                return;
            }

            showConfirmModal(
                'Delete Selected Proofs',
                `Are you sure you want to permanently delete ${selectedIds.length} selected proof(s)? This action cannot be undone.`,
                'Delete All',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('admin.proofs.bulk-delete') }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'proof_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                },
                'danger'
            );
        }

        function bulkExport() {
            const selectedIds = getSelectedProofIds();
            if (selectedIds.length === 0) {
                showErrorMessage('Please select proofs to export.');
                return;
            }

            showConfirmModal(
                'Export Selected Proofs',
                `Export ${selectedIds.length} selected proof(s) as a ZIP file?`,
                'Export',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.bulk-export') : route('admin.proofs.bulk-export') }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'proof_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        // Enhanced modal functions
        function showConfirmModal(title, message, confirmText, onConfirm, type = 'warning') {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-[9999]';
            modal.innerHTML = `
                <div class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-md rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl border border-slate-200/50 dark:border-slate-700/50">
                    <div class="text-center">
                        <div class="w-16 h-16 ${type === 'danger' ? 'bg-gradient-to-br from-red-500 to-red-600' : 'bg-gradient-to-br from-yellow-500 to-orange-500'} rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${type === 'danger' ? 
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>' :
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>'
                                }
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">${title}</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">${message}</p>
                        <div class="flex space-x-3">
                            <button onclick="closeConfirmModal()" class="bg-slate-500/80 hover:bg-slate-600/80 backdrop-blur-sm text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                                Cancel
                            </button>
                            <button onclick="executeConfirmAction()" class="${type === 'danger' ? 'bg-gradient-to-r from-red-500/80 to-red-600/80 hover:from-red-600/80 hover:to-red-700/80' : 'bg-gradient-to-r from-yellow-500/80 to-orange-500/80 hover:from-yellow-600/80 hover:to-orange-600/80'} backdrop-blur-sm text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                                ${confirmText}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            window.executeConfirmAction = onConfirm;
            window.closeConfirmModal = () => {
                modal.style.opacity = '0';
                modal.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    if (document.body.contains(modal)) {
                        document.body.removeChild(modal);
                    }
                    delete window.executeConfirmAction;
                    delete window.closeConfirmModal;
                }, 200);
            };
        }

        function showSuccessMessage(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-3 rounded-xl shadow-xl z-50 flex items-center space-x-2';
            notification.innerHTML = `
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-medium">${message}</span>
            `;
            document.body.appendChild(notification);
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }
            }, 3000);
        }

        function showErrorMessage(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-6 py-3 rounded-xl shadow-xl z-50 flex items-center space-x-2';
            notification.innerHTML = `
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span class="font-medium">${message}</span>
            `;
            document.body.appendChild(notification);
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Update select all functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.proof-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            if (selectAll.checked) {
                showSuccessMessage(`Selected all ${checkboxes.length} proofs`);
            } else {
                showSuccessMessage('Selection cleared');
            }
        }

        // Add event listeners for individual checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.proof-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectAllState);
            });
        });
    </script>
@endsection

