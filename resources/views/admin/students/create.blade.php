@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Student Account</h1>
                    <p class="mt-2 text-gray-600">Set up a new student account with login credentials</p>
                </div>
                <a href="{{ route('admin.students.index') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Students
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-dark rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Student Information</h2>
                <p class="text-white-600 mt-1">Fill in the student's details and set their login credentials</p>
            </div>
            
            <form method="POST" action="{{ route('admin.students.store') }}" class="p-6">
                @csrf
                
                <!-- Student Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="student_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name *
                        </label>
                        <input type="text" 
                               id="student_first_name" 
                               name="student_first_name" 
                               value="{{ old('student_first_name') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('student_first_name') border-red-300 @enderror"
                               placeholder="Enter first name">
                        @error('student_first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="student_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name *
                        </label>
                        <input type="text" 
                               id="student_last_name" 
                               name="student_last_name" 
                               value="{{ old('student_last_name') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('student_last_name') border-red-300 @enderror"
                               placeholder="Enter last name">
                        @error('student_last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Login Credentials -->
                <div class="bg-orange border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">Login Credentials</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-3 py-2 bg-dark border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                                   placeholder="student@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Student ID (Auto-Generated)
                            </label>
                            <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-600">
                                <i class="fas fa-magic mr-2"></i>Will be generated automatically based on school
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Format: [School Code] + [Number] (e.g., CAU001, CAU002)</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password *
                        </label>
                        <div class="flex">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror"
                                   placeholder="Enter password (min 8 characters)">
                            <button type="button" 
                                    onclick="generatePassword()"
                                    class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-random"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Click the random button to generate a secure password</p>
                    </div>
                </div>
                
                <!-- Academic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="student_grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Grade Level *
                        </label>
                        <select id="student_grade_level" 
                                name="student_grade_level" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('student_grade_level') border-red-300 @enderror">
                            <option value="">Select Grade</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('student_grade_level') == $i ? 'selected' : '' }}>
                                    Grade {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('student_grade_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">
                            School *
                        </label>
                        <select id="school_id" 
                                name="school_id" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('school_id') border-red-300 @enderror">
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->school_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Parent Information -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Parent/Guardian Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="student_parent_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent/Guardian Name *
                            </label>
                            <input type="text" 
                                   id="student_parent_name" 
                                   name="student_parent_name" 
                                   value="{{ old('student_parent_name') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('student_parent_name') border-red-300 @enderror"
                                   placeholder="Enter parent/guardian name">
                            @error('student_parent_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="student_parent_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent/Guardian Email *
                            </label>
                            <input type="email" 
                                   id="student_parent_email" 
                                   name="student_parent_email" 
                                   value="{{ old('student_parent_email') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('student_parent_email') border-red-300 @enderror"
                                   placeholder="parent@example.com">
                            @error('student_parent_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.students.index') }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-yellow-500 to-red-600 text-white px-6 py-3 rounded-lg hover:from-yellow-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Create Student Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generatePassword() {
    const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    let password = '';
    
    for (let i = 0; i < 12; i++) {
        password += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    
    document.getElementById('password').value = password;
    document.getElementById('password').type = 'text';
    
    setTimeout(() => {
        document.getElementById('password').type = 'password';
    }, 2000);
}
</script>
@endsection
