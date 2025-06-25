<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success - EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        .animate-scale-in {
            animation: scaleIn 0.5s ease-out;
        }
        .success-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-blue-50 to-purple-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8 animate-fade-in-up">
                <!-- Success Icon -->
                <div class="success-icon w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center animate-scale-in">
                    <i class="fas fa-check text-white text-3xl"></i>
                </div>
                
                <!-- Success Message -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Registration Successful!</h2>
                    <div class="space-y-3 text-gray-600">
                        <p class="text-lg">
                            Welcome to EduSmart! 🎉
                        </p>
                        <p class="text-sm">
                            Your account has been created successfully and is now pending validation by an administrator.
                        </p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                <p class="text-sm text-blue-700">
                                    You will receive an email notification once your account has been approved.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 space-y-3">
                    <a href="{{ route('login') }}" 
                       class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Back to Login
                    </a>
                    <a href="{{ route('welcome') }}" 
                       class="w-full flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Go to Homepage
                    </a>
                </div>
                
                <!-- Additional Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="text-center text-sm text-gray-500">
                        <p>Need help? Contact our support team</p>
                        <div class="flex justify-center space-x-4 mt-2">
                            <a href="#" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-phone"></i>
                            </a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-question-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 