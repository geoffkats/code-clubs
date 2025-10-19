@extends('layouts.student')

@section('page-title', 'My Profile')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="mt-2 text-gray-600">Manage your account information and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                        <p class="text-gray-600">Update your personal details</p>
                    </div>
                    
                    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Profile Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <div class="flex items-center space-x-4">
                                <img src="{{ $student->profile_image_url }}" 
                                     alt="{{ $student->full_name }}" 
                                     class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <input type="file" 
                                           name="profile_image" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF (max 2MB)</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="student_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name
                                </label>
                                <input type="text" 
                                       id="student_first_name" 
                                       name="student_first_name" 
                                       value="{{ old('student_first_name', $student->student_first_name) }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('student_first_name') border-red-300 @enderror">
                                @error('student_first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="student_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name
                                </label>
                                <input type="text" 
                                       id="student_last_name" 
                                       name="student_last_name" 
                                       value="{{ old('student_last_name', $student->student_last_name) }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('student_last_name') border-red-300 @enderror">
                                @error('student_last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $student->email) }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Grade Level -->
                        <div class="mb-6">
                            <label for="student_grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                                Grade Level
                            </label>
                            <select id="student_grade_level" 
                                    name="student_grade_level" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('student_grade_level') border-red-300 @enderror">
                                <option value="">Select Grade</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('student_grade_level', $student->student_grade_level) == $i ? 'selected' : '' }}>
                                        Grade {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('student_grade_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Account Summary -->
            <div class="space-y-6">
                <!-- Account Info -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Account Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Student ID:</span>
                            <span class="font-medium">{{ $student->student_id_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Member Since:</span>
                            <span class="font-medium">{{ $student->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Clubs Enrolled:</span>
                            <span class="font-medium">{{ $student->clubs->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Assessments Completed:</span>
                            <span class="font-medium">{{ $student->assessment_scores->count() }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Enrolled Clubs -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Enrolled Clubs</h3>
                    @if($student->clubs->count() > 0)
                        <div class="space-y-3">
                            @foreach($student->clubs as $club)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($club->club_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="font-medium text-gray-900">{{ $club->club_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $club->club_type ?? 'Coding Club' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Not enrolled in any clubs yet.</p>
                    @endif
                </div>
                
                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Average Score:</span>
                            <span class="font-medium text-green-600">{{ number_format($student->getAverageAssessmentScore(), 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Attendance:</span>
                            <span class="font-medium text-blue-600">{{ number_format($student->getAttendancePercentage(), 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reports Generated:</span>
                            <span class="font-medium text-purple-600">{{ $student->reports->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
