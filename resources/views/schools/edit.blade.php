<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <a href="{{ route('schools.index') }}" 
                       class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-100 dark:to-indigo-100 bg-clip-text text-transparent">
                            Edit School
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Update school information</p>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
                <form method="POST" action="{{ route('schools.update', $school->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- School Name -->
                        <div class="md:col-span-2">
                            <label for="school_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                School Name
                            </label>
                            <input type="text" 
                                   id="school_name" 
                                   name="school_name" 
                                   value="{{ old('school_name', $school->school_name) }}"
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   required>
                            @error('school_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Contact Email
                            </label>
                            <input type="email" 
                                   id="contact_email" 
                                   name="contact_email" 
                                   value="{{ old('contact_email', $school->contact_email) }}"
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Address
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                      placeholder="Enter school address...">{{ old('address', $school->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- School Stats -->
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">School Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $school->clubs_count ?? 0 }}</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Clubs</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $school->students_count ?? 0 }}</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Students</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $school->created_at->format('Y') }}</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">Established</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('schools.index') }}" 
                           class="px-6 py-3 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            Update School
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
