@extends('layouts.admin')
@section('title', 'Upload Proof')

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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-emerald-900 to-teal-900 dark:from-white dark:via-emerald-100 dark:to-teal-100 bg-clip-text text-transparent">
                                Upload Proof
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Upload session proof documentation</p>
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
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Proof Information</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Upload and configure session proof documentation</p>
                    </div>

                    <div class="p-8">
                        <form method="POST" action="{{ request()->routeIs('facilitator.*') ? route('facilitator.proofs.store') : route('admin.proofs.store') }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            
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
                                                <option value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>
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

                            <!-- File Upload -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">
                                    Proof File
                                </h3>
                                
                                <div>
                                    <label for="proof_url" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Upload Proof File <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-slate-600 dark:text-slate-400">
                                                <label for="proof_url" class="relative cursor-pointer bg-white dark:bg-slate-800 rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                                    <span>Upload a file</span>
                                                    <input id="proof_url" name="proof_url" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required>
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
                                              placeholder="Add any additional details about this proof...">{{ old('description') }}</textarea>
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
                                    Upload Proof
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
