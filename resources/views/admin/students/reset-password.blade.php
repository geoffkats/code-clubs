@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reset Student Password</h1>
                    <p class="mt-2 text-gray-600">Set a new password for {{ $student->full_name }}</p>
                </div>
                <a href="{{ route('admin.students.show', $student) }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Student
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Student Information</h2>
                <div class="mt-4 flex items-center space-x-4">
                    <img class="h-12 w-12 rounded-full" 
                         src="{{ $student->profile_image_url }}" 
                         alt="{{ $student->full_name }}">
                    <div>
                        <p class="text-lg font-medium text-gray-900">{{ $student->full_name }}</p>
                        <p class="text-sm text-gray-500">{{ $student->email }}</p>
                        <p class="text-sm text-gray-500">ID: {{ $student->student_id_number }}</p>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.students.reset-password.post', $student) }}" class="p-6">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password *
                        </label>
                        <div class="flex">
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('new_password') border-red-300 @enderror"
                                   placeholder="Enter new password (min 8 characters)">
                            <button type="button" 
                                    onclick="generatePassword()"
                                    class="ml-2 bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-random"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password *
                        </label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('confirm_password') border-red-300 @enderror"
                               placeholder="Confirm the new password">
                        @error('confirm_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8">
                    <a href="{{ route('admin.students.show', $student) }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-yellow-500 to-red-600 text-white px-6 py-3 rounded-lg hover:from-yellow-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-key mr-2"></i>Reset Password
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
    
    document.getElementById('new_password').value = password;
    document.getElementById('confirm_password').value = password;
    
    document.getElementById('new_password').type = 'text';
    document.getElementById('confirm_password').type = 'text';
    
    setTimeout(() => {
        document.getElementById('new_password').type = 'password';
        document.getElementById('confirm_password').type = 'password';
    }, 2000);
}
</script>
@endsection
