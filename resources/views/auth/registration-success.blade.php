<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success - EduSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .content-item {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .content-item:nth-child(1) { animation-delay: 0.2s; }
        .content-item:nth-child(2) { animation-delay: 0.4s; }
        .content-item:nth-child(3) { animation-delay: 0.6s; }
        .content-item:nth-child(4) { animation-delay: 0.8s; }
        .content-item:nth-child(5) { animation-delay: 1.0s; }
        
        .btn-primary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .success-icon {
            animation: scaleIn 0.6s ease-out 0.3s both;
        }
        
        .progress-bar {
            width: 0%;
            transition: width 2s ease-in-out;
        }
        
        .progress-bar.animate {
            width: 100%;
        }
        
        .card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-security {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <!-- Main Content -->
    <div class="w-full max-w-4xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header Section -->
            <div class="bg-blue-600 p-8 md:p-10 text-center text-white">
                <div class="content-item">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full shadow-lg success-icon mb-6">
                        <i class="fas fa-check text-white text-xl"></i>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-semibold mb-3">
                        Registration Successful
                    </h1>
                    <p class="text-blue-100 text-base max-w-2xl mx-auto leading-relaxed">
                        Your account has been created successfully. Welcome to EduSmart!
                    </p>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8 md:p-10">
                <!-- Progress Indicator -->
                <div class="mb-8 content-item">
                    <div class="bg-gray-200 rounded-full h-2 mb-3">
                        <div class="progress-bar bg-blue-600 h-2 rounded-full"></div>
                    </div>
                    <p class="text-gray-600 text-center text-sm font-medium">Account validation in progress</p>
                </div>

                <!-- Information Cards -->
                <div class="grid md:grid-cols-2 gap-6 mb-8 content-item">
                    <!-- Status Card -->
                    <div class="card bg-white rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-amber-600"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold text-lg">Account Status</h3>
                                <span class="status-badge status-pending">Under Review</span>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Your account is currently being reviewed by our team. This process typically takes <span class="font-medium text-gray-900">24-48 hours</span>.
                        </p>
                    </div>

                    <!-- Security Card -->
                    <div class="card bg-white rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-shield-alt text-red-600"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold text-lg">Security Notice</h3>
                                <span class="status-badge status-security">3 Login Attempts</span>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            For your security, you have <span class="font-medium text-gray-900">3 login attempts</span>. After failed attempts, your account will be temporarily locked.
                        </p>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="card bg-gray-50 rounded-lg p-6 mb-8 content-item">
                    <h3 class="text-gray-900 font-semibold text-lg mb-6 flex items-center">
                        <i class="fas fa-list-check mr-3 text-blue-600"></i>
                        Next Steps
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-xs font-semibold">1</span>
                            </div>
                            <div>
                                <p class="text-gray-900 font-medium text-sm">Check your email</p>
                                <p class="text-gray-500 text-sm">Look for validation confirmation in your inbox</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-xs font-semibold">2</span>
                            </div>
                            <div>
                                <p class="text-gray-900 font-medium text-sm">Wait for validation</p>
                                <p class="text-gray-500 text-sm">Our team will review your account (24-48 hours)</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-xs font-semibold">3</span>
                            </div>
                            <div>
                                <p class="text-gray-900 font-medium text-sm">Start learning</p>
                                <p class="text-gray-500 text-sm">Login with your credentials once validated</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 content-item">
                    <a href="/login" class="btn-primary px-6 py-3 bg-blue-600 text-white rounded-lg font-medium text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Go to Login
                    </a>
                    <a href="/" class="btn-secondary px-6 py-3 bg-gray-600 text-white rounded-lg font-medium text-center">
                        <i class="fas fa-home mr-2"></i>
                        Back to Home
                    </a>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 pt-6 border-t border-gray-200 content-item">
                    <p class="text-gray-500 text-sm">
                        Need help? Contact our support team at 
                        <a href="mailto:support@edusmart.com" class="text-blue-600 hover:text-blue-700 font-medium">support@edusmart.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bar
            setTimeout(() => {
                document.querySelector('.progress-bar').classList.add('animate');
            }, 800);

            // Add interactive success icon
            document.querySelector('.success-icon').addEventListener('click', function() {
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = 'scaleIn 0.6s ease-out both';
                }, 10);
            });
        });
    </script>
</body>
</html> 