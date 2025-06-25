<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduSmart') }} - Server Error</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-gray-100 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl space-y-6 text-center">
            
            <!-- Logo -->
            <div class="mb-8">
                <span class="text-3xl font-extrabold tracking-tight select-none" style="font-family: 'Inter', 'Segoe UI', Arial, sans-serif;">
                    <span class="bg-gradient-to-tr from-indigo-600 to-purple-600 bg-clip-text text-transparent">Edu</span><span class="text-black dark:text-white">Smart</span>
                </span>
            </div>
            
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 dark:bg-red-900/20 shadow-lg">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-3xl"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wide">
                Server Error
            </h1>

            <!-- Message -->
            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                Something went wrong on our end.<br>
                We're working to fix the problem. Please try again later.
            </p>

            <!-- Info Box -->
            <div class="bg-red-100 dark:bg-red-900/10 border-l-4 border-red-500 dark:border-red-600 p-4 rounded-md shadow-md">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-server text-red-600 dark:text-red-400 text-lg mt-1"></i>
                    <div class="text-left">
                        <p class="font-medium text-red-800 dark:text-red-200 text-sm">Internal Server Error</p>
                        <p class="text-red-700 dark:text-red-300 text-sm">
                            Our technical team has been notified and is working on a solution.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <!-- Refresh Button -->
                <button onclick="window.location.reload()"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-semibold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-redo mr-2 text-white"></i>
                    Try Again
                </button>
                
                <!-- Back to Login -->
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2 text-white"></i>
                    Back to Login
                </a>
            </div>

            <!-- Status Information -->
            <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">What happened?</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p>• An unexpected error occurred on our servers</p>
                    <p>• This is not your fault - it's a technical issue</p>
                    <p>• Our team has been automatically notified</p>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-200 dark:border-blue-700">
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">Still having issues?</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">
                    If this problem persists, please contact our support team.
                </p>
                <a href="mailto:support@edusmart.com" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Support
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh after 60 seconds
        setTimeout(function() {
            if (confirm('Would you like to try again? The page will refresh automatically.')) {
                window.location.reload();
            }
        }, 60000);
    </script>
</body>
</html> 