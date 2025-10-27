@extends('layouts.teacher')

@section('title', 'Session Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('teacher.sessions') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Session Details</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $session->club->club_name ?? 'Unknown Club' }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openUploadModal()" class="btn btn-primary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Upload Proof
            </button>
            <a href="{{ route('teacher.sessions.attendance', $session) }}" class="btn btn-secondary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Attendance
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Session Info -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Session Information</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Date</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-white">
                            {{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('F d, Y') : 'Unknown' }}
                        </dd>
                    </div>
                    @if($session->session_time)
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Time</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($session->session_time)->format('h:i A') }}
                        </dd>
                    </div>
                    @endif
                    @if($session->session_notes)
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Notes</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-white">
                            {{ $session->session_notes }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Attending Students -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Attending Students ({{ $session->students->count() }})</h2>
                @if($session->students->count() > 0)
                    <div class="space-y-2">
                        @foreach($session->students as $student)
                            <div class="flex items-center justify-between py-2 border-b border-slate-200 dark:border-slate-700 last:border-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                        {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </div>
                                        @if($student->pivot->attended_at)
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                Attended: {{ \Carbon\Carbon::parse($student->pivot->attended_at)->format('M d, Y h:i A') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">No students attended this session.</p>
                @endif
            </div>

            <!-- Uploaded Proofs -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Uploaded Proofs ({{ $session->proofs->count() }})</h2>
                @if($session->proofs->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($session->proofs as $proof)
                            <div class="relative group">
                                @if($proof->proof_type === 'image')
                                    <img src="{{ Storage::url($proof->proof_path) }}" alt="Proof" class="w-full h-32 object-cover rounded-lg">
                                @else
                                    <div class="w-full h-32 bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-lg transition-opacity">
                                    <a href="{{ Storage::url($proof->proof_path) }}" target="_blank" class="text-white hover:text-blue-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">No proofs uploaded yet.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Statistics</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Attendees</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $session->students->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Proofs</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $session->proofs->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Proof Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeUploadModal()">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full mx-4 p-6" onclick="event.stopPropagation()">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Upload Proof</h3>
        <form action="{{ route('teacher.sessions.proof', $session) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Files (Images or Videos)
                </label>
                <input type="file" name="files[]" multiple accept="image/*,video/*" required
                       class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white">
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Upload multiple images or videos</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeUploadModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
}
</script>
@endsection
