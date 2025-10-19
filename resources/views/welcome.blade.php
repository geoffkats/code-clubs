<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Code Club Management System - Empowering Young Coders Across Uganda</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            }
            
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
        
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
            
            .slide-in-left {
                animation: slideInLeft 1s ease-out;
            }
            
            @keyframes slideInLeft {
                from { opacity: 0; transform: translateX(-50px); }
                to { opacity: 1; transform: translateX(0); }
            }
            
            .slide-in-right {
                animation: slideInRight 1s ease-out;
            }
            
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(50px); }
                to { opacity: 1; transform: translateX(0); }
            }
            
    </style>
    </head>
<body class="font-sans antialiased bg-white">
    
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-code text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold uganda-text">Code Club System</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-blue-600 transition-colors">Features</a>
                    <a href="#about" class="text-gray-600 hover:text-blue-600 transition-colors">About</a>
                    <a href="#contact" class="text-gray-600 hover:text-blue-600 transition-colors">Contact</a>
                    <a href="{{ route('login') }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:from-yellow-600 hover:to-red-600 transition-all transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
                </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center hero-pattern overflow-hidden">
        <div class="absolute inset-0 bg-gray-50"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-yellow-200 rounded-full opacity-20 floating" style="animation-delay: 0s;"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-red-200 rounded-full opacity-20 floating" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-20 w-12 h-12 bg-black rounded-full opacity-20 floating" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 right-10 w-24 h-24 bg-yellow-200 rounded-full opacity-10 floating" style="animation-delay: 3s;"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Column - Content -->
                <div class="text-center lg:text-left fade-in">
                    <div class="mb-8">
                        <span class="inline-block px-4 py-2 bg-yellow-100 text-gray-800 rounded-full text-sm font-semibold mb-4">
                            🇺🇬 Made in Uganda, For Africa
                        </span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                        Managing Code Clubs
                        <span class="uganda-text">Across Uganda</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-2xl">
                        The comprehensive platform for managing coding clubs, tracking student progress, generating reports, and connecting parents with their children's coding journey.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="bg-yellow-500 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-yellow-600 transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>Access Club Portal
                        </a>
                        <a href="#features" class="border-2 border-gray-600 text-gray-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-50 transition-all">
                            <i class="fas fa-play mr-2"></i>Learn More
                        </a>
                    </div>
                </div>
                
                <!-- Right Column - Dashboard Mockup -->
                <div class="relative slide-in-right">
                    <!-- Dashboard Mockup -->
                    <div class="bg-white rounded-2xl shadow-2xl p-6 border border-gray-200">
                        <!-- Mockup Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-code text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Code Club Dashboard</h3>
                                    <p class="text-sm text-gray-500">Live System Preview</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                        </div>
                        
                        <!-- Mockup Content -->
                        <div class="space-y-4">
                            <!-- Stats Cards -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-blue-600">24</div>
                                    <div class="text-xs text-gray-600">Active Clubs</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-green-600">156</div>
                                    <div class="text-xs text-gray-600">Students</div>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-purple-600">89%</div>
                                    <div class="text-xs text-gray-600">Attendance</div>
                                </div>
                            </div>
                            
                            <!-- Recent Activity -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Recent Activity</h4>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                        <span class="text-sm text-gray-600">New student enrolled in Python Club</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                        <span class="text-sm text-gray-600">Report generated for Sarah M.</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                        <span class="text-sm text-gray-600">Session scheduled for Web Dev Club</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="grid grid-cols-2 gap-3">
                                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors">
                                    <i class="fas fa-plus mr-1"></i>New Club
                                </button>
                                <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                                    <i class="fas fa-chart-bar mr-1"></i>Reports
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-200 rounded-full opacity-50 floating"></div>
                    <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-red-200 rounded-full opacity-50 floating" style="animation-delay: 1s;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Why Choose Our <span class="uganda-text">Code Club System?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We provide comprehensive club management tools designed specifically for coding education and student tracking across Uganda.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 slide-in-left">
                    <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-laptop-code text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Club Management</h3>
                    <p class="text-gray-600">Efficiently manage multiple coding clubs with comprehensive tools for tracking sessions, attendance, and student progress.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 fade-in">
                    <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Parent Engagement</h3>
                    <p class="text-gray-600">Keep parents informed with detailed progress reports, project showcases, and direct communication channels.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 slide-in-right">
                    <div class="w-16 h-16 bg-yellow-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Assessment Tools</h3>
                    <p class="text-gray-600">Create and manage assessments, track student performance, and generate comprehensive progress reports.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 slide-in-left">
                    <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mobile Optimized</h3>
                    <p class="text-gray-600">Access the club management system from any device, optimized for Uganda's internet conditions.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 fade-in">
                    <div class="w-16 h-16 bg-red-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Report Generation</h3>
                    <p class="text-gray-600">Generate comprehensive student reports with AI-powered insights and automated parent notifications.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 slide-in-right">
                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-globe-africa text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Uganda Focus</h3>
                    <p class="text-gray-600">Designed specifically for Uganda's education system, supporting local coding clubs and student development.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="slide-in-left">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Empowering <span class="uganda-text">Uganda's Code Clubs</span>
                </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Our Code Club Management System helps educators and administrators efficiently manage coding clubs across Uganda, track student progress, and connect with parents through comprehensive reporting tools.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Multiple coding clubs managed across Uganda</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Comprehensive student progress tracking</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Automated parent communication system</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">AI-powered report generation</span>
                        </div>
            </div>
                </div>
                
                <div class="slide-in-right">
                    <div class="relative">
                        <div class="bg-yellow-500 rounded-3xl p-8 text-white">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="text-center">
                                    <div class="text-4xl font-bold mb-2">100%</div>
                                    <div class="text-yellow-100">Cloud Based</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-4xl font-bold mb-2">24/7</div>
                                    <div class="text-yellow-100">System Access</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-4xl font-bold mb-2">AI</div>
                                    <div class="text-yellow-100">Report Generation</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-4xl font-bold mb-2">100%</div>
                                    <div class="text-yellow-100">Secure & Private</div>
                                </div>
                            </div>
                </div>
                        <!-- Floating elements around the stats -->
                        <div class="absolute -top-4 -left-4 w-8 h-8 bg-yellow-200 rounded-full opacity-50 floating"></div>
                        <div class="absolute -bottom-4 -right-4 w-6 h-6 bg-red-200 rounded-full opacity-50 floating" style="animation-delay: 1s;"></div>
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Our <span class="uganda-text">System Features</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Comprehensive tools for managing coding clubs, tracking progress, and engaging parents.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Beginner Program -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-seedling text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Club Management</h3>
                        <p class="text-gray-600">Essential tools for organizing and managing coding clubs</p>
                        </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Session Scheduling
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Attendance Tracking
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Student Enrollment
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Progress Monitoring
                        </li>
                    </ul>
                    <div class="text-center">
                        <span class="text-3xl font-bold text-gray-900">Core</span>
                        <span class="text-gray-600 ml-2">Features</span>
                    </div>
                </div>
                
                <!-- Intermediate Program -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-yellow-400">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-yellow-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-rocket text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Assessment & Reports</h3>
                        <p class="text-gray-600">Advanced tools for evaluation and reporting</p>
                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mt-2">Most Popular</span>
                        </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Assessment Creation
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            AI Report Generation
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Parent Communication
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Progress Analytics
                        </li>
                    </ul>
                    <div class="text-center">
                        <span class="text-3xl font-bold text-gray-900">Premium</span>
                        <span class="text-gray-600 ml-2">Features</span>
                    </div>
                </div>
                
                <!-- Advanced Program -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-crown text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Integration & Support</h3>
                        <p class="text-gray-600">Enterprise-level features and support</p>
                        </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            API Integration
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Custom Branding
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Priority Support
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Data Export
                        </li>
                    </ul>
                    <div class="text-center">
                        <span class="text-3xl font-bold text-gray-900">Enterprise</span>
                        <span class="text-gray-600 ml-2">Features</span>
                    </div>
                </div>
            </div>
        </div>
            </section>

    <!-- CTA Section -->
    <section class="py-20 bg-yellow-500">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Manage Your <span class="text-yellow-300">Code Clubs?</span>
            </h2>
            <p class="text-xl text-yellow-100 mb-8">
                Join educators across Uganda who are using our system to efficiently manage their coding clubs and track student progress.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="bg-white text-gray-900 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Access System
                </a>
                <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/10 transition-all">
                    <i class="fas fa-envelope mr-2"></i>Contact Us
                </a>
            </div>
        </div>
            </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-code text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold uganda-text">Code Club System</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        Comprehensive Code Club Management System designed for Uganda's education sector. Streamlining club operations, student tracking, and parent communication.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Features</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Club Management</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Assessment Tools</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Report Generation</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Parent Portal</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-yellow-500"></i>
                            Kampala, Uganda
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-yellow-500"></i>
                            +256 XXX XXX XXX
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-yellow-500"></i>
                            info@codeacademyuganda.com
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} Code Club System. All rights reserved. 🇺🇬
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Developed by <span class="text-yellow-400 font-semibold">Synthilogic Enterprise</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Smooth scrolling script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    </body>
</html>