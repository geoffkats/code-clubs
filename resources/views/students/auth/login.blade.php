<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Code Club System') }} - Student Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .uganda-text {
            color: #FFD700;
        }
        
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23667eea' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-indigo-100 hero-pattern">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-gradient-to-r from-yellow-500 to-red-600 rounded-full flex items-center justify-center floating">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <div class="mt-6">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                        Code Club System
                    </h1>
                    <p class="text-lg text-gray-600 mb-4">
                        <span class="uganda-text font-semibold">Student Portal</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Sign in to access your assignments, track progress, and continue your coding journey
                    </p>
                </div>
            </div>

            <!-- Login Form -->
            <div class="bg-white py-8 px-6 shadow-xl rounded-lg">
                <form class="space-y-6" method="POST" action="{{ route('student.login.post') }}">
                    @csrf

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

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1 relative">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="current-password" 
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror"
                                   placeholder="Enter your password">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-900">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-yellow-500 to-red-600 hover:from-yellow-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out transform hover:scale-105 shadow-lg">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-sign-in-alt text-yellow-200 group-hover:text-yellow-100"></i>
                            </span>
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Access Student Portal
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account? 
                            <a href="{{ route('student.register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Sign up here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Back to Main Site -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-yellow-600 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>Back to main site
                </a>
            </div>
            
            <!-- Footer -->
            <div class="text-center text-xs text-gray-500">
                <p>© {{ date('Y') }} Code Academy Uganda. Developed by Synthilogic Enterprise.</p>
            </div>
        </div>
    </div>
</body>
</html>
