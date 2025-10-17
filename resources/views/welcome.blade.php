<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CodeClub System') }} - Empowering Young Coders</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
    </style>
</head>
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold gradient-text">CodeClub</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-blue-600 transition-colors">Features</a>
                    <a href="#about" class="text-gray-600 hover:text-blue-600 transition-colors">About</a>
                    <a href="#contact" class="text-gray-600 hover:text-blue-600 transition-colors">Contact</a>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Login</a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="text-gray-600 hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center hero-pattern overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-purple-50"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-blue-200 rounded-full opacity-20 floating" style="animation-delay: 0s;"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-purple-200 rounded-full opacity-20 floating" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-20 w-12 h-12 bg-green-200 rounded-full opacity-20 floating" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="fade-in">
                <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                    Empowering Young
                    <span class="gradient-text">Coders</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    The ultimate platform for coding clubs, where students learn, create, and showcase their programming projects through interactive assessments and collaborative learning.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg">
                        Get Started Today
                    </a>
                    <a href="#features" class="border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-blue-50 transition-all">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Everything You Need for
                    <span class="gradient-text">Coding Education</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our comprehensive platform provides all the tools educators and students need for successful coding club management and learning.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl hover:shadow-xl transition-all transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Interactive Assessments</h3>
                    <p class="text-gray-600 mb-4">Create engaging quizzes, tests, and coding challenges with image uploads and Scratch IDE integration for hands-on learning.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Multiple question types</li>
                        <li>• Image and file uploads</li>
                        <li>• Real-time scoring</li>
                        <li>• Progress tracking</li>
                    </ul>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl hover:shadow-xl transition-all transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Student Management</h3>
                    <p class="text-gray-600 mb-4">Comprehensive student profiles with attendance tracking, progress monitoring, and parent communication tools.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Student portfolios</li>
                        <li>• Attendance tracking</li>
                        <li>• Parent notifications</li>
                        <li>• Progress reports</li>
                    </ul>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl hover:shadow-xl transition-all transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Scratch IDE Integration</h3>
                    <p class="text-gray-600 mb-4">Built-in Scratch programming environment for students to create, share, and collaborate on coding projects.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Visual programming</li>
                        <li>• Project sharing</li>
                        <li>• Collaborative coding</li>
                        <li>• Portfolio building</li>
                    </ul>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-2xl hover:shadow-xl transition-all transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-orange-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Project Showcase</h3>
                    <p class="text-gray-600 mb-4">Students can upload and showcase their coding projects with detailed descriptions and progress tracking.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Project galleries</li>
                        <li>• Progress documentation</li>
                        <li>• Peer feedback</li>
                        <li>• Achievement badges</li>
                    </ul>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-2xl hover:shadow-xl transition-all transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Comprehensive Reports</h3>
                    <p class="text-gray-600 mb-4">Generate detailed reports for parents and administrators with student progress, attendance, and achievement data.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Progress analytics</li>
                        <li>• Attendance summaries</li>
                        <li>• Achievement tracking</li>
                        <li>• Parent notifications</li>
                    </ul>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 rounded-2xl hover:shadow-xl transition-all transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Multi-School Support</h3>
                    <p class="text-gray-600 mb-4">Manage multiple schools and clubs with role-based access control and data isolation for secure operations.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• School management</li>
                        <li>• Role-based access</li>
                        <li>• Data security</li>
                        <li>• Scalable architecture</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Trusted by Educators Worldwide
                </h2>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Join thousands of educators who are transforming coding education with our platform.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-5xl font-bold text-white mb-2">500+</div>
                    <div class="text-blue-100">Schools</div>
                </div>
                <div>
                    <div class="text-5xl font-bold text-white mb-2">10,000+</div>
                    <div class="text-blue-100">Students</div>
                </div>
                <div>
                    <div class="text-5xl font-bold text-white mb-2">50,000+</div>
                    <div class="text-blue-100">Projects Created</div>
                </div>
                <div>
                    <div class="text-5xl font-bold text-white mb-2">98%</div>
                    <div class="text-blue-100">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    What Parents & Educators Say
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-lg">SM</span>
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Sarah Mitchell</div>
                            <div class="text-gray-500">Parent</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"My daughter's coding skills have improved dramatically since joining the club. The platform makes it easy to track her progress and see her projects."</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-bold text-lg">DJ</span>
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">David Johnson</div>
                            <div class="text-gray-500">Computer Science Teacher</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"This platform has revolutionized how I manage my coding club. The assessment tools and Scratch integration are game-changers for student engagement."</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-bold text-lg">LW</span>
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Lisa Wang</div>
                            <div class="text-gray-500">School Administrator</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"The comprehensive reporting features help us demonstrate the value of our coding programs to parents and school board members."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Coding Education?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of educators who are already using our platform to inspire the next generation of coders.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                    Start Free Trial
                </a>
                <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/10 transition-all">
                    Contact Sales
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold">CodeClub</span>
                    </div>
                    <p class="text-gray-400 mb-4">Empowering young coders through innovative education technology.</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Integrations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Status</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: support@codeclub.local</li>
                        <li>Phone: (555) 123-4567</li>
                        <li>Address: 123 Education St</li>
                        <li>City, State 12345</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2024 CodeClub System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>