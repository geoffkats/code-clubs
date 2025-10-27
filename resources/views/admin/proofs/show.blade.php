@extends(request()->routeIs('facilitator.*') ? 'layouts.facilitator' : (request()->routeIs('teacher.*') ? 'layouts.teacher' : 'layouts.admin'))
@section('title', 'Proof Review')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.index') : (request()->routeIs('teacher.*') ? route('teacher.proofs.index') : route('admin.proofs.index')) }}" 
                               class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white mr-4">
                                ← Back to Proofs
                            </a>
                        </div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">
                            📸 Proof Review
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400">
                            Review teacher session proof for {{ $proof->session->club->club_name ?? 'Unknown Club' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        @if($proof->status === 'pending' && !request()->routeIs('teacher.*'))
                            <button onclick="approveProof()" 
                                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                                ✅ Approve Proof
                            </button>
                            <button onclick="rejectProof()" 
                                    class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                                ❌ Reject Proof
                            </button>
                        @else
                            <span class="inline-flex px-4 py-3 text-sm font-semibold rounded-xl
                                @if($proof->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($proof->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                {{ ucfirst(str_replace('_', ' ', $proof->status)) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Proof Display -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Proof Content</h3>
                        
                        @if($proof->isImage())
                            <div class="text-center">
                                <img src="{{ $proof->file_url }}" 
                                     alt="Session Proof" 
                                     class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                                     style="max-height: 500px;">
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                                    📷 Photo - {{ $proof->formatted_file_size }}
                                </p>
                            </div>
                        @elseif($proof->isVideo())
                            <div class="text-center">
                                <video controls class="max-w-full h-auto rounded-lg shadow-lg mx-auto" style="max-height: 500px;">
                                    <source src="{{ $proof->file_url }}" type="{{ $proof->mime_type }}">
                                    Your browser does not support the video tag.
                                </video>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                                    🎥 Video - {{ $proof->formatted_file_size }}
                                </p>
                            </div>
                        @elseif($proof->isDocument())
                            <div class="text-center">
                                <div class="bg-slate-100 dark:bg-slate-700 rounded-lg p-8 mx-auto max-w-md">
                                    <div class="text-6xl mb-4">
                                        @if(str_contains($proof->mime_type, 'pdf'))
                                            📄
                                        @elseif(str_contains($proof->mime_type, 'word') || str_contains($proof->mime_type, 'document'))
                                            📝
                                        @else
                                            📁
                                        @endif
                                    </div>
                                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
                                        {{ basename($proof->proof_url) }}
                                    </h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                                        {{ $proof->formatted_file_size }}
                                    </p>
                                    <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.download', $proof) : (request()->routeIs('teacher.*') ? route('teacher.proofs.download', $proof) : route('admin.proofs.download', $proof)) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Document
                                    </a>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                                    📄 Document - {{ $proof->formatted_file_size }}
                                </p>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="bg-slate-100 dark:bg-slate-700 rounded-lg p-8 mx-auto max-w-md">
                                    <div class="text-6xl mb-4">📁</div>
                                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
                                        {{ basename($proof->proof_url) }}
                                    </h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                                        {{ $proof->formatted_file_size }}
                                    </p>
                                    <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.download', $proof) : (request()->routeIs('teacher.*') ? route('teacher.proofs.download', $proof) : route('admin.proofs.download', $proof)) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download File
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 flex justify-center">
                            <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.download', $proof) : (request()->routeIs('teacher.*') ? route('teacher.proofs.download', $proof) : route('admin.proofs.download', $proof)) }}" 
                               class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                                📥 Download Proof
                            </a>
                        </div>
                    </div>

                    <!-- Proof Description -->
                    @if($proof->description)
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Teacher's Description</h3>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-slate-700 dark:text-slate-300">{{ $proof->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Admin Comments -->
                    @if($proof->admin_comments)
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Admin Comments</h3>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-slate-700 dark:text-slate-300">{{ $proof->admin_comments }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Rejection Reason -->
                    @if($proof->rejection_reason)
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Rejection Reason</h3>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-red-600 dark:text-red-400">{{ $proof->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Proof Details -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Proof Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full mt-1
                                    @if($proof->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($proof->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($proof->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $proof->status)) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Type</label>
                                <p class="text-slate-900 dark:text-white mt-1">
                                    @if($proof->isImage())
                                        📷 Photo
                                    @elseif($proof->isVideo())
                                        🎥 Video
                                    @elseif($proof->isDocument())
                                        📄 Document
                                    @else
                                        📁 File
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">File Size</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ $proof->formatted_file_size }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Submitted</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ $proof->created_at->format('M d, Y h:i A') }}</p>
                            </div>

                            @if($proof->reviewed_at)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reviewed</label>
                                    <p class="text-slate-900 dark:text-white mt-1">{{ $proof->reviewed_at ? $proof->reviewed_at->format('M d, Y h:i A') : 'Not reviewed' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Session Details -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-系统中">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Session Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Club</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ $proof->session && $proof->session->club ? $proof->session->club->club_name : 'Unknown Club' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Session Date</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ $proof->session && $proof->session->session_date ? \Carbon\Carbon::parse($proof->session->session_date)->format('M d, Y') : 'Unknown Date' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Session Time</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ $proof->session ? $proof->session->session_time : 'Unknown Time' }}</p>
                            </div>

                            @if($proof->session && $proof->session->session_title)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Title</label>
                                    <p class="text-slate-900 dark:text-white mt-1">{{ $proof->session->session_title }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Teacher Details -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Teacher Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ $proof->uploader->name ?? 'Unknown User' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ $proof->uploader->email ?? 'Unknown Email' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Role</label>
                                <p class="text-slate-900 dark:text-white mt-1">{{ ucfirst($proof->uploader->user_role ?? 'Unknown') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reviewer Details -->
                    @if($proof->reviewer)
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Reviewer Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                                    <p class="text-slate-900 dark:text-white mt-1">{{ $proof->reviewer->name ?? 'Unknown Reviewer' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                                    <p class="text-slate-900 dark:text-white mt-1">{{ $proof->reviewer->email ?? 'Unknown Email' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reviewed At</label>
                                    <p class="text-slate-900 dark:text-white mt-1">{{ $proof->reviewed_at ? $proof->reviewed_at->format('M d, Y h:i A') : 'Not reviewed' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl backdrop-blur-sm border border-slate-200 dark:border-slate-700">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Approve Proof</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6">Are you sure you want to approve this proof?</p>
                <form method="POST" action="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.approve', $proof) : route('admin.proofs.approve', $proof) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Comments (Optional)</label>
                        <textarea name="admin_comments" rows="3" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Add any comments about this proof..."></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeApproveModal()" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
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

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl backdrop-blur-sm border border-slate-200 dark:border-slate-700">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Reject Proof</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6">Please provide a reason for rejecting this proof.</p>
                <form method="POST" action="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.reject', $proof) : route('admin.proofs.reject', $proof) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Rejection Reason *</label>
                        <textarea name="rejection_reason" rows="3" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Enter reason for rejection..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Additional Comments (Optional)</label>
                        <textarea name="admin_comments" rows="3" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Add any additional comments..."></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeRejectModal()" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
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
        function approveProof() {
            document.getElementById('approve-modal').classList.remove('hidden');
        }

        function rejectProof() {
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approve-modal').classList.add('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }
    </script>
@endsection
