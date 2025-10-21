<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.resources.index') }}" class="group p-2 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-200">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-emerald-900 to-teal-900 dark:from-white dark:via-emerald-100 dark:to-teal-100 bg-clip-text text-transparent">
                                Create Resource
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Add new lesson materials and educational resources</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div id="success-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50">
                    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700/30 p-8 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Success!</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-6">{{ session('success') }}</p>
                            <button onclick="closeSuccessModal()" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl backdrop-blur-sm">
                                Continue
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div id="error-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50">
                    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700/30 p-8 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-full flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Error!</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-6">{{ session('error') }}</p>
                            <button onclick="closeErrorModal()" class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl backdrop-blur-sm">
                                Continue
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="max-w-4xl mx-auto">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-slate-700 dark:to-slate-700">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Resource Information</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Upload and configure new educational resources</p>
                    </div>

                    <div class="p-8">
                        <form method="POST" action="{{ route('admin.resources.store') }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            
                            <!-- Basic Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    Basic Information
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Resource Title <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="title" required
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md"
                                                   placeholder="Enter resource title">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Club <span class="text-red-500">*</span>
                                        </label>
                                        <select name="club_id" required
                                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                            <option value="">Select a club</option>
                                            @foreach($clubs as $club)
                                                <option value="{{ $club->id }}">{{ $club->club_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        Description
                                    </label>
                                    <textarea name="description" rows="4"
                                              class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md"
                                              placeholder="Describe the resource content and learning objectives"></textarea>
                                </div>
                            </div>

                            <!-- Resource Type & Content -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    Resource Type & Content
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Resource Type <span class="text-red-500">*</span>
                                        </label>
                                        <select name="attachment_type" id="resource-type" required onchange="toggleInputFields()"
                                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                            <option value="">Select resource type</option>
                                            <option value="video">Video Content</option>
                                            <option value="document">Document (PDF, Word, PowerPoint)</option>
                                            <option value="image">Image/Gallery</option>
                                            <option value="link">External Link/Website</option>
                                            <option value="audio">Audio/Podcast</option>
                                            <option value="code">Code Repository/Project</option>
                                            <option value="quiz">Interactive Quiz</option>
                                            <option value="assignment">Assignment Template</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Visibility <span class="text-red-500">*</span>
                                        </label>
                                        <select name="visibility" required
                                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                            <option value="">Select visibility</option>
                                            <option value="all">All Users</option>
                                            <option value="teachers_only">Teachers Only</option>
                                            <option value="students_only">Students Only</option>
                                            <option value="private">Private (Creator Only)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Dynamic Input Fields -->
                                <div id="input-fields-container" class="space-y-4 hidden">
                                    <!-- Video URL Input -->
                                    <div id="video-url-input" class="space-y-2 hidden">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Video URL <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="url" name="video_url" placeholder="https://youtube.com/watch?v=... or https://vimeo.com/..."
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Supports YouTube, Vimeo, and direct video URLs</p>
                                    </div>

                                    <!-- External Link Input -->
                                    <div id="external-link-input" class="space-y-2 hidden">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            External Link <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="url" name="external_url" placeholder="https://example.com"
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-xs shadow-sm hover:shadow-md">
                                            <input type="text" name="link_title" placeholder="Link title (optional)"
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                        </p>
                                    </div>

                                    <!-- Code Repository Input -->
                                    <div id="code-repo-input" class="space-y-2 hidden">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Code Repository <span class="text-red-500">*</span>
                                        </label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <input type="url" name="code_url" placeholder="https://github.com/user/repo or https://gitlab.com/user/repo"
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                            <input type="text" name="code_branch" placeholder="Branch (optional, defaults to main)"
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Supports GitHub, GitLab, Bitbucket, and other Git repositories</p>
                                    </div>

                                    <!-- Audio URL Input -->
                                    <div id="audio-url-input" class="space-y-2 hidden">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Audio URL <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="url" name="audio_url" placeholder="https://soundcloud.com/... or direct audio URL"
                                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Supports SoundCloud, Spotify, and direct audio URLs</p>
                                    </div>

                                    <!-- File Upload for Documents, Images, etc. -->
                                    <div id="file-upload-input" class="space-y-2 hidden">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            Upload File <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <!-- Drag and Drop Area -->
                                            <div id="drop-zone" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center hover:border-emerald-500 transition-colors duration-200 cursor-pointer">
                                                <div class="space-y-4">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white mx-auto">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-lg font-medium text-slate-900 dark:text-white">Drop files here or click to upload</p>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400">Supports: PDF, DOC, DOCX, PPT, PPTX, JPG, PNG, GIF, MP4, AVI, MOV, ZIP (Max: 50MB)</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="file" name="attachment" id="file-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.zip" class="hidden">
                                        </div>
                                        
                                        <!-- File Preview -->
                                        <div id="file-preview" class="hidden mt-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center text-white">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-medium text-slate-900 dark:text-white" id="file-name"></div>
                                                    <div class="text-sm text-slate-500 dark:text-slate-400" id="file-size"></div>
                                                </div>
                                                <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resource Tags -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        Tags (Optional)
                                    </label>
                                    <input type="text" name="tags" placeholder="Enter tags separated by commas (e.g., beginner, python, tutorial)"
                                           class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Add tags to help categorize and search for this resource</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ route('admin.resources.index') }}" 
                                   class="px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span>Create Resource</span>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dynamic Input Fields Handler
        function toggleInputFields() {
            const resourceType = document.getElementById('resource-type').value;
            const container = document.getElementById('input-fields-container');
            
            // Hide all input fields
            const inputFields = ['video-url-input', 'external-link-input', 'code-repo-input', 'audio-url-input', 'file-upload-input'];
            inputFields.forEach(field => {
                document.getElementById(field).classList.add('hidden');
            });
            
            // Show container and appropriate field
            if (resourceType) {
                container.classList.remove('hidden');
                
                switch(resourceType) {
                    case 'video':
                        document.getElementById('video-url-input').classList.remove('hidden');
                        break;
                    case 'link':
                        document.getElementById('external-link-input').classList.remove('hidden');
                        break;
                    case 'code':
                        document.getElementById('code-repo-input').classList.remove('hidden');
                        break;
                    case 'audio':
                        document.getElementById('audio-url-input').classList.remove('hidden');
                        break;
                    case 'document':
                    case 'image':
                    case 'quiz':
                    case 'assignment':
                    case 'other':
                        document.getElementById('file-upload-input').classList.remove('hidden');
                        break;
                }
            } else {
                container.classList.add('hidden');
            }
        }

        // Drag and Drop File Upload
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        // Click to upload
        dropZone.addEventListener('click', () => fileInput.click());

        // Drag and drop events
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        function handleFile(file) {
            // Validate file size (50MB limit)
            if (file.size > 50 * 1024 * 1024) {
                alert('File size must be less than 50MB');
                return;
            }

            // Validate file type
            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'image/jpeg',
                'image/png',
                'image/gif',
                'video/mp4',
                'video/avi',
                'video/quicktime',
                'application/zip'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('File type not supported. Please upload a PDF, DOC, DOCX, PPT, PPTX, JPG, PNG, GIF, MP4, AVI, MOV, or ZIP file.');
                return;
            }

            // Update file input
            fileInput.files = new DataTransfer().files;
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;

            // Show preview
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.classList.remove('hidden');
        }

        function removeFile() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Modal functions
        function closeSuccessModal() {
            const modal = document.getElementById('success-modal');
            if (modal) {
                modal.style.display = 'none';
                modal.remove(); // Remove the modal from DOM completely
            }
        }

        function closeErrorModal() {
            const modal = document.getElementById('error-modal');
            if (modal) {
                modal.style.display = 'none';
                modal.remove(); // Remove the modal from DOM completely
            }
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

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const resourceType = document.getElementById('resource-type').value;
            let isValid = true;
            let errorMessage = '';

            if (!resourceType) {
                isValid = false;
                errorMessage = 'Please select a resource type.';
            } else {
                switch(resourceType) {
                    case 'video':
                        if (!document.querySelector('input[name="video_url"]').value) {
                            isValid = false;
                            errorMessage = 'Please enter a video URL.';
                        }
                        break;
                    case 'link':
                        if (!document.querySelector('input[name="external_url"]').value) {
                            isValid = false;
                            errorMessage = 'Please enter an external link.';
                        }
                        break;
                    case 'code':
                        if (!document.querySelector('input[name="code_url"]').value) {
                            isValid = false;
                            errorMessage = 'Please enter a code repository URL.';
                        }
                        break;
                    case 'audio':
                        if (!document.querySelector('input[name="audio_url"]').value) {
                            isValid = false;
                            errorMessage = 'Please enter an audio URL.';
                        }
                        break;
                    default:
                        if (!fileInput.files.length) {
                            isValid = false;
                            errorMessage = 'Please upload a file.';
                        }
                        break;
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
            }
        });
    </script>
</x-layouts.app>
