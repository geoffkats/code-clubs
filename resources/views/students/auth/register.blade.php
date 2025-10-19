<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Code Club System') }} - Student Registration</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-xl">CC</span>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Create Student Account
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Join the coding club and start your learning journey
                </p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white py-8 px-6 shadow-xl rounded-lg">
                <form class="space-y-6" method="POST" action="{{ route('student.register.post') }}">
                    @csrf

                    <!-- Student Name -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="student_first_name" class="block text-sm font-medium text-gray-700">
                                First Name
                            </label>
                            <div class="mt-1">
                                <input id="student_first_name" 
                                       name="student_first_name" 
                                       type="text" 
                                       required 
                                       value="{{ old('student_first_name') }}"
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('student_first_name') border-red-300 @enderror"
                                       placeholder="First name">
                            </div>
                            @error('student_first_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="student_last_name" class="block text-sm font-medium text-gray-700">
                                Last Name
                            </label>
                            <div class="mt-1">
                                <input id="student_last_name" 
                                       name="student_last_name" 
                                       type="text" 
                                       required 
                                       value="{{ old('student_last_name') }}"
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('student_last_name') border-red-300 @enderror"
                                       placeholder="Last name">
                            </div>
                            @error('student_last_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <div class="mt-1">
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required 
                                   value="{{ old('email') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror"
                                   placeholder="Enter your email">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student ID -->
                    <div>
                        <label for="student_id_number" class="block text-sm font-medium text-gray-700">
                            Student ID Number
                        </label>
                        <div class="mt-1">
                            <input id="student_id_number" 
                                   name="student_id_number" 
                                   type="text" 
                                   required 
                                   value="{{ old('student_id_number') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('student_id_number') border-red-300 @enderror"
                                   placeholder="Enter your student ID">
                        </div>
                        @error('student_id_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grade Level -->
                    <div>
                        <label for="student_grade_level" class="block text-sm font-medium text-gray-700">
                            Grade Level
                        </label>
                        <div class="mt-1">
                            <select id="student_grade_level" 
                                    name="student_grade_level" 
                                    required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('student_grade_level') border-red-300 @enderror">
                                <option value="">Select Grade</option>
                                <option value="1" {{ old('student_grade_level') == '1' ? 'selected' : '' }}>Grade 1</option>
                                <option value="2" {{ old('student_grade_level') == '2' ? 'selected' : '' }}>Grade 2</option>
                                <option value="3" {{ old('student_grade_level') == '3' ? 'selected' : '' }}>Grade 3</option>
                                <option value="4" {{ old('student_grade_level') == '4' ? 'selected' : '' }}>Grade 4</option>
                                <option value="5" {{ old('student_grade_level') == '5' ? 'selected' : '' }}>Grade 5</option>
                                <option value="6" {{ old('student_grade_level') == '6' ? 'selected' : '' }}>Grade 6</option>
                                <option value="7" {{ old('student_grade_level') == '7' ? 'selected' : '' }}>Grade 7</option>
                                <option value="8" {{ old('student_grade_level') == '8' ? 'selected' : '' }}>Grade 8</option>
                                <option value="9" {{ old('student_grade_level') == '9' ? 'selected' : '' }}>Grade 9</option>
                                <option value="10" {{ old('student_grade_level') == '10' ? 'selected' : '' }}>Grade 10</option>
                                <option value="11" {{ old('student_grade_level') == '11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="12" {{ old('student_grade_level') == '12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                        </div>
                        @error('student_grade_level')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Information -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Parent/Guardian Information</h3>
                        
                        <div>
                            <label for="student_parent_name" class="block text-sm font-medium text-gray-700">
                                Parent/Guardian Name
                            </label>
                            <div class="mt-1">
                                <input id="student_parent_name" 
                                       name="student_parent_name" 
                                       type="text" 
                                       required 
                                       value="{{ old('student_parent_name') }}"
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('student_parent_name') border-red-300 @enderror"
                                       placeholder="Parent/Guardian full name">
                            </div>
                            @error('student_parent_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="student_parent_email" class="block text-sm font-medium text-gray-700">
                                Parent/Guardian Email
                            </label>
                            <div class="mt-1">
                                <input id="student_parent_email" 
                                       name="student_parent_email" 
                                       type="email" 
                                       required 
                                       value="{{ old('student_parent_email') }}"
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('student_parent_email') border-red-300 @enderror"
                                       placeholder="Parent/Guardian email">
                            </div>
                            @error('student_parent_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="new-password" 
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror"
                                   placeholder="Create a password (min. 8 characters)">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm Password
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation" 
                                   name="password_confirmation" 
                                   type="password" 
                                   autocomplete="new-password" 
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Confirm your password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-blue-300 group-hover:text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                </svg>
                            </span>
                            Create Account
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{ route('student.login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Back to Main Site -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to main site
                </a>
            </div>
        </div>
    </div>
</body>
</html>
