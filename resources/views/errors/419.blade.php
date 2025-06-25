<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduSmart') }} - Page Expired</title>

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
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-orange-100 dark:bg-orange-900/20 shadow-lg">
                <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-3xl"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wide">
                Page Expired
            </h1>

            <!-- Message -->
            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                Your session has expired or the page has timed out.<br>
                Please refresh the page and try again.
            </p>

            <!-- Info Box -->
            <div class="bg-blue-100 dark:bg-blue-900/10 border-l-4 border-blue-500 dark:border-blue-600 p-4 rounded-md shadow-md">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-lg mt-1"></i>
                    <div class="text-left">
                        <p class="font-medium text-blue-800 dark:text-blue-200 text-sm">Session Timeout</p>
                        <p class="text-blue-700 dark:text-blue-300 text-sm">
                            This usually happens when you've been inactive for too long or when the CSRF token has expired.
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
                    Refresh Page
                </button>
                
                <!-- Back to Login -->
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2 text-white"></i>
                    Back to Login
                </a>
            </div>

            <!-- Additional Help -->
            <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Need Help?</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p>• Make sure you're logged in to your account</p>
                    <p>• Try refreshing the page or going back to the previous page</p>
                    <p>• If the problem persists, contact support</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh after 30 seconds if user doesn't take action
        setTimeout(function() {
            if (confirm('Would you like to refresh the page automatically?')) {
                window.location.reload();
            }
        }, 30000);
    </script>
</body>
</html> 