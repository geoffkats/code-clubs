<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Code Club System') }} - Student Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .uganda-text {
            color: #FFD700;
            font-weight: 700;
        }
        
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23667eea' fill-opacity='0.08'%3E%3Ccircle cx='30' cy='30' r='3'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        @keyframes pulseGlow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
            }
            50% { 
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.5);
            }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 hero-pattern" x-data="{ isLoading: false }">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
    </div>

    <div class="relative min-h-full flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6 sm:space-y-8 fade-in">
            <!-- Header -->
            <div class="text-center slide-up">
                <div class="mx-auto h-24 w-24 bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center floating pulse-glow shadow-2xl">
                    <i class="fas fa-code text-white text-3xl"></i>
                </div>
                <div class="mt-6 sm:mt-8">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold gradient-text mb-2 sm:mb-3">
                        Code Club System
                    </h1>
                    <p class="text-lg sm:text-xl text-white/90 mb-2">
                        <span class="uganda-text">Student Portal</span>
                    </p>
                    <p class="text-xs sm:text-sm text-white/70 max-w-sm mx-auto leading-relaxed px-4 sm:px-0">
                        Access your coding assignments, track your progress, and continue your journey to becoming a skilled developer
                    </p>
                </div>
            </div>

            <!-- Login Form -->
            <div class="glass-effect py-6 sm:py-8 lg:py-10 px-4 sm:px-6 lg:px-8 shadow-2xl rounded-2xl sm:rounded-3xl slide-up">
                <form class="space-y-6 sm:space-y-8" method="POST" action="{{ route('student.login.post') }}" @submit="isLoading = true">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-800">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>
                            Email Address
                        </label>
                        <div class="relative">
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required 
                                   value="{{ old('email') }}"
                                   class="input-focus appearance-none block w-full pl-12 pr-4 py-3 sm:py-4 border-2 border-gray-300 rounded-xl placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 text-sm sm:text-sm transition-all duration-300 @error('email') border-red-400 focus:ring-red-500 @enderror"
                                   placeholder="Enter your email address">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-800">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="current-password" 
                                   required
                                   class="input-focus appearance-none block w-full pl-12 pr-12 py-3 sm:py-4 border-2 border-gray-300 rounded-xl placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 text-sm sm:text-sm transition-all duration-300 @error('password') border-red-400 focus:ring-red-500 @enderror"
                                   placeholder="Enter your password">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-500"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <button type="button" onclick="togglePassword()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <i id="password-toggle" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-lg transition-colors">
                            <label for="remember" class="ml-3 block text-sm font-medium text-gray-800">
                                Remember me for 30 days
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                :disabled="isLoading"
                                class="btn-hover group relative w-full flex justify-center py-3 sm:py-4 px-4 sm:px-6 border border-transparent text-sm sm:text-base font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 hover:from-blue-700 hover:via-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <i class="fas fa-sign-in-alt text-blue-200 group-hover:text-blue-100 transition-colors"></i>
                            </span>
                            <span x-show="!isLoading" class="flex items-center">
                                <i class="fas fa-code mr-2"></i>
                                Access Student Portal
                            </span>
                            <span x-show="isLoading" class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Signing In...
                            </span>
                        </button>
                    </div>

                    <!-- Account Information -->
                    <div class="text-center space-y-3 pt-4 border-t border-gray-300">
                        <p class="text-sm text-gray-700">
                            Don't have an account? 
                            <span class="font-semibold text-blue-700 hover:text-blue-800 cursor-pointer transition-colors">Contact your instructor</span>
                        </p>
                        <div class="flex items-center justify-center space-x-2 text-xs text-gray-600">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span>Student accounts are created by administrators only</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Back to Main Site -->
            <div class="text-center slide-up">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-medium text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg sm:rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/20 hover:border-white/30">
                    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                    <span class="hidden sm:inline">Back to main site</span>
                    <span class="sm:hidden">Back</span>
                </a>
            </div>
            
            <!-- Footer -->
            <div class="text-center text-xs text-white/60 slide-up px-4">
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-4 mb-2">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-code text-blue-400"></i>
                        <span>Code Academy Uganda</span>
                    </div>
                    <div class="hidden sm:block w-1 h-1 bg-white/40 rounded-full"></div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-building text-purple-400"></i>
                        <span>Synthilogic Enterprise</span>
                    </div>
                </div>
                <p class="text-xs sm:text-xs">© {{ date('Y') }} All rights reserved. Empowering the next generation of developers.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript for password toggle -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
