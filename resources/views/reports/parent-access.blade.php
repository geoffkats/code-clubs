<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Access - Coding Club Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl mb-4">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Parent Access</h1>
                <p class="text-gray-600">Enter your access code to view your child's coding club report</p>
            </div>

            <!-- Access Form -->
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-white/20">
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                            <p class="text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-3"></i>
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('reports.verify-parent-access') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="access_code" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-key text-blue-600 mr-2"></i>
                            Access Code
                        </label>
                        <input type="text" 
                               id="access_code" 
                               name="access_code" 
                               value="{{ old('access_code') }}"
                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm font-mono text-lg text-center"
                               placeholder="Enter access code (e.g., john-1234)"
                               required
                               autofocus>
                        <p class="text-sm text-gray-500 mt-2 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Check your email or contact the teacher for your access code
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-unlock mr-2"></i>
                        View Report
                    </button>
                </form>

                <!-- Help Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                        Need Help?
                    </h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><i class="fas fa-envelope mr-2"></i>Check your email for the access code</p>
                        <p><i class="fas fa-user-tie mr-2"></i>Contact your child's teacher if you need assistance</p>
                        <p><i class="fas fa-clock mr-2"></i>Access codes expire after 30 days</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-shield-alt text-green-600 mr-1"></i>
                    Secure access to your child's coding journey
                </p>
            </div>
        </div>
    </div>

    <!-- Background Animation -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
    </div>
</body>
</html>
