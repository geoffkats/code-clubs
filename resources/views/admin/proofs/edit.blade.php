@extends('layouts.admin')
@section('title', 'Edit Proof')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Header Section -->
        <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-slate-900/80 border-b border-slate-200/60 dark:border-slate-700/60">
            <div class="px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.index') : route('admin.proofs.index') }}" class="group p-2 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-200">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-emerald-900 to-teal-900 dark:from-white dark:via-emerald-100 dark:to-teal-100 bg-clip-text text-transparent">
                                Edit Proof
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Update proof information and documentation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Form Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Update Proof Information</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Modify the details for this proof</p>
                    </div>

                    <div class="p-8">
                        <form method="POST" action="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.update', $proof) : route('admin.proofs.update', $proof) }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            @method('PUT')
                            
                            <!-- Session Selection -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    Session Details
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="session_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            Select Session <span class="text-red-500">*</span>
                                        </label>
                                        <select name="session_id" id="session_id" required
                                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                            <option value="">Choose a session...</option>
                                            @foreach($sessions as $session)
                                                <option value="{{ $session->id }}" {{ (old('session_id', $proof->session_id) == $session->id) ? 'selected' : '' }}>
                                                    {{ $session->club->club_name }} - {{ $session->session_date }} {{ $session->session_time }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('session_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Current File -->
                            @if($proof->proof_url)
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    Current File
                                </h3>
                                
                                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                                {{ basename($proof->proof_url) }}
                                            </p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                                Current proof file
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.download', $proof) : route('admin.proofs.download', $proof) }}" 
                                               class="inline-flex items-center px-3 py-2 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-sm font-medium rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-800 transition-colors">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- File Upload -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    {{ $proof->proof_url ? 'Replace Proof File' : 'Proof File' }}
                                </h3>
                                
                                <div>
                                    <label for="proof_url" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        {{ $proof->proof_url ? 'Upload New File (Optional)' : 'Upload Proof File' }} <span class="text-red-500">{{ $proof->proof_url ? '' : '*' }}</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-slate-600 dark:text-slate-400">
                                                <label for="proof_url" class="relative cursor-pointer bg-white dark:bg-slate-800 rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                                    <span>{{ $proof->proof_url ? 'Upload a new file' : 'Upload a file' }}</span>
                                                    <input id="proof_url" name="proof_url" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" {{ $proof->proof_url ? '' : 'required' }}>
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                PNG, JPG, PDF, DOC, DOCX up to 10MB
                                            </p>
                                        </div>
                                    </div>
                                    @error('proof_url')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    Additional Information
                                </h3>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Description (Optional)
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                              class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
                                              placeholder="Add any additional details about this proof...">{{ old('description', $proof->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.index') : route('admin.proofs.index') }}" 
                                   class="px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                                    Update Proof
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
