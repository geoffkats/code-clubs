@extends(request()->routeIs('facilitator.*') ? 'layouts.facilitator' : (request()->routeIs('teacher.*') ? 'layouts.teacher' : 'layouts.admin'))

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Upload Proof</h1>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">Upload a file as proof for a session</p>
                </div>
                <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.index') : (request()->routeIs('teacher.*') ? route('teacher.proofs.index') : route('admin.proofs.index')) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Proofs
                </a>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
            <div class="p-8">
                <form id="proofUploadForm" method="POST" action="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.store') : (request()->routeIs('teacher.*') ? route('teacher.proofs.store') : route('admin.proofs.store')) }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Session Selection -->
                    <div>
                        <label for="session_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Select Session <span class="text-red-500">*</span>
                        </label>
                        <select name="session_id" id="session_id" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white">
                            <option value="">Choose a session...</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">
                                    {{ $session->club->club_name ?? 'Unknown Club' }} - 
                                    {{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('M d, Y') : 'No Date' }}
                                </option>
                            @endforeach
                        </select>
                        @error('session_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label for="proof_url" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Upload Proof File <span class="text-red-500">*</span>
                        </label>
                        <div id="file-upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 dark:text-slate-400">
                                    <label for="proof_url" class="relative cursor-pointer bg-white dark:bg-slate-800 rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                        <span>Upload a file</span>
                                        <input id="proof_url" name="proof_url" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.mp4,.mov,.avi,.webm" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Images: PNG, JPG | Videos: MP4, MOV, AVI, WEBM | Documents: PDF, DOC, DOCX | Max 50MB
                                </p>
                            </div>
                        </div>
                        @error('proof_url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Description (Optional)
                        </label>
                        <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white" placeholder="Add a description for this proof..."></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload Proof
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('proof_url');
    const fileUploadArea = document.getElementById('file-upload-area');
    
    // File selection handling
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });
    
    // Drag and drop handling
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900');
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900');
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    function handleFileSelect(file) {
        if (!file) return;

        // Validate file size (50MB max)
        if (file.size > 50 * 1024 * 1024) {
            alert('File size must be less than 50MB. Your file is ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
            return;
        }

        // Validate file type
        const allowedTypes = [
            'image/jpeg', 'image/jpg', 'image/png',
            'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm',
            'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!allowedTypes.includes(file.type)) {
            alert('File type not supported. Please upload an image, video, or document.');
            return;
        }

        // Update UI to show selected file
        fileUploadArea.innerHTML = `
            <div class="text-center">
                <div class="text-4xl mb-2">${getFileIcon(file.type)}</div>
                <div class="text-sm font-medium text-slate-900 dark:text-white">${file.name}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">${formatFileSize(file.size)}</div>
            </div>
        `;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function getFileIcon(mimeType) {
        if (mimeType.includes('pdf')) return '📄';
        if (mimeType.includes('word') || mimeType.includes('document')) return '📝';
        if (mimeType.includes('video')) return '🎥';
        if (mimeType.includes('image')) return '🖼️';
        return '📁';
    }
});
</script>
@endsection
