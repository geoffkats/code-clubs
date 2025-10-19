<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal - Code Club Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-slow {
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full floating"></div>
        <div class="absolute top-32 right-20 w-24 h-24 bg-white/5 rounded-full floating" style="animation-delay: -1s;"></div>
        <div class="absolute bottom-20 left-1/4 w-40 h-40 bg-white/5 rounded-full floating" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-32 right-1/3 w-28 h-28 bg-white/10 rounded-full floating" style="animation-delay: -0.5s;"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="mb-8">
                    <div class="inline-block p-6 glass-effect rounded-3xl mb-6">
                        <i class="fas fa-graduation-cap text-6xl text-white pulse-slow"></i>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
                        Parent Portal
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 mb-2">
                        View Your Child's Coding Journey
                    </p>
                    <p class="text-lg text-white/80">
                        Access detailed reports and track your child's progress in coding
                    </p>
                </div>
            </div>

            <!-- Access Code Form -->
            <div class="max-w-md mx-auto mb-12">
                <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                    <div class="text-center mb-6">
                        <div class="inline-block p-4 bg-white/20 rounded-2xl mb-4">
                            <i class="fas fa-key text-3xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">Enter Access Code</h2>
                        <p class="text-white/80 text-sm">
                            Enter the access code provided in your email to view your child's report
                        </p>
                        
                        @if(session('error'))
                            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-3 mt-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-red-300 mr-2"></i>
                                    <span class="text-red-200 text-sm">{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <form id="accessCodeForm" action="{{ route('parent.verify-access') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="accessCode" class="block text-white font-medium mb-2">
                                Access Code
                            </label>
                            <input type="text" 
                                   id="accessCode" 
                                   name="access_code"
                                   class="w-full px-4 py-3 rounded-xl border-2 border-white/20 bg-white/10 text-white placeholder-white/60 focus:border-white/40 focus:bg-white/20 transition-all duration-300"
                                   placeholder="e.g., john-2500"
                                   required>
                            <p class="text-white/70 text-xs mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                The access code was sent to your email address
                            </p>
                        </div>

                        <button type="submit" 
                                id="submitBtn"
                                class="w-full bg-white text-gray-800 py-3 px-6 rounded-xl font-semibold hover:bg-white/90 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <span id="btnText">
                                <i class="fas fa-unlock mr-2"></i>
                                View Report
                            </span>
                            <span id="loadingText" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Loading...
                            </span>
                        </button>
                    </form>

                    <!-- Help Section -->
                    <div class="mt-6 pt-6 border-t border-white/20">
                        <div class="text-center">
                            <p class="text-white/70 text-sm mb-3">Need help?</p>
                            <div class="flex flex-col space-y-2 text-xs text-white/60">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <span>Check your email for the access code</span>
                                </div>
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-phone mr-2"></i>
                                    <span>Contact your child's coding club instructor</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="glass-effect rounded-2xl p-6 text-center">
                    <div class="inline-block p-3 bg-white/20 rounded-xl mb-4">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Progress Tracking</h3>
                    <p class="text-white/80 text-sm">Monitor your child's coding skills development and achievements</p>
                </div>

                <div class="glass-effect rounded-2xl p-6 text-center">
                    <div class="inline-block p-3 bg-white/20 rounded-xl mb-4">
                        <i class="fas fa-project-diagram text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Creative Projects</h3>
                    <p class="text-white/80 text-sm">View and interact with your child's Scratch coding projects</p>
                </div>

                <div class="glass-effect rounded-2xl p-6 text-center">
                    <div class="inline-block p-3 bg-white/20 rounded-xl mb-4">
                        <i class="fas fa-user-graduate text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Teacher Feedback</h3>
                    <p class="text-white/80 text-sm">Read detailed feedback from coding instructors</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <div class="glass-effect rounded-2xl p-6 inline-block">
                    <p class="text-white/80 text-sm mb-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Secure & Private - Your child's data is protected
                    </p>
                    <p class="text-white/60 text-xs">
                        © {{ date('Y') }} Code Club System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Form submission handler
        document.getElementById('accessCodeForm').addEventListener('submit', function(e) {
            const accessCode = document.getElementById('accessCode').value.trim();
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loadingText = document.getElementById('loadingText');
            
            if (!accessCode) {
                e.preventDefault();
                showToast('Please enter an access code', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            
            // Form will submit normally to server
        });

        // Reset form function
        function resetForm() {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loadingText = document.getElementById('loadingText');
            
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const container = document.getElementById('toastContainer');
            
            toast.className = `px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-green-500 text-white' 
                    : 'bg-red-500 text-white'
            }`;
            
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 100);
            
            // Auto remove
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    if (container.contains(toast)) {
                        container.removeChild(toast);
                    }
                }, 300);
            }, 4000);
        }

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus effects to input
            const accessCodeInput = document.getElementById('accessCode');
            accessCodeInput.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-white/30');
            });
            
            accessCodeInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-white/30');
            });

            // Add enter key support
            accessCodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('accessCodeForm').dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>
</body>
</html>
