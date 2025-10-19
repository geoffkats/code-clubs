@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Student Details</h1>
                    <p class="mt-2 text-gray-600">View and manage student account information</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.students.edit', $student) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Student
                    </a>
                    <a href="{{ route('admin.students.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Students
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Student Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Basic Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-6">
                            <img class="h-16 w-16 rounded-full object-cover" 
                                 src="{{ $student->profile_image_url }}" 
                                 alt="{{ $student->full_name }}"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&background=random'">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $student->full_name }}</h3>
                                <p class="text-gray-600">Student ID: {{ $student->student_id_number }}</p>
                                <p class="text-gray-600">Grade {{ $student->student_grade_level }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <p class="text-gray-900">{{ $student->email }}</p>
                                @if($student->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                        <i class="fas fa-check-circle mr-1"></i>Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                        <i class="fas fa-clock mr-1"></i>Pending Verification
                                    </span>
                                @endif
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Account Status</label>
                                @if($student->password)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-key mr-1"></i>Account Ready
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Setup Needed
                                    </span>
                                @endif
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                                <p class="text-gray-900">{{ $student->school->school_name ?? 'No School' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                                <p class="text-gray-900">{{ $student->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Parent/Guardian Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name</label>
                                <p class="text-gray-900">{{ $student->student_parent_name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Email</label>
                                <p class="text-gray-900">{{ $student->student_parent_email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clubs -->
                @if($student->clubs->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Enrolled Clubs</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($student->clubs as $club)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h3 class="font-medium text-gray-900">{{ $club->club_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $club->club_type ?? 'Coding Club' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $club->club_description ?? 'No description' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Quick Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.students.edit', $student) }}" 
                           class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center block">
                            <i class="fas fa-edit mr-2"></i>Edit Student
                        </a>
                        
                        <a href="{{ route('admin.students.reset-password', $student) }}" 
                           class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-center block">
                            <i class="fas fa-key mr-2"></i>Reset Password
                        </a>
                        
                        <form method="POST" action="{{ route('admin.students.destroy', $student) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this student account?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Account
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Statistics</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Clubs Enrolled:</span>
                            <span class="font-medium">{{ $student->clubs->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Assessments Taken:</span>
                            <span class="font-medium">{{ $student->assessment_scores->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Average Score:</span>
                            <span class="font-medium">{{ number_format($student->getAverageAssessmentScore(), 1) }}%</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Attendance Rate:</span>
                            <span class="font-medium">{{ number_format($student->getAttendancePercentage(), 1) }}%</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reports Generated:</span>
                            <span class="font-medium">{{ $student->reports->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
