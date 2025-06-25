<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduSmart') }} - Page Not Found</title>

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
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-purple-100 dark:bg-purple-900/20 shadow-lg">
                <i class="fas fa-search text-purple-600 dark:text-purple-400 text-3xl"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wide">
                Page Not Found
            </h1>

            <!-- Message -->
            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                The page you're looking for doesn't exist or has been moved.<br>
                Please check the URL and try again.
            </p>

            <!-- Info Box -->
            <div class="bg-purple-100 dark:bg-purple-900/10 border-l-4 border-purple-500 dark:border-purple-600 p-4 rounded-md shadow-md">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-triangle text-purple-600 dark:text-purple-400 text-lg mt-1"></i>
                    <div class="text-left">
                        <p class="font-medium text-purple-800 dark:text-purple-200 text-sm">Page Not Found</p>
                        <p class="text-purple-700 dark:text-purple-300 text-sm">
                            The requested resource could not be found on this server.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <!-- Go Back Button -->
                <button onclick="history.back()"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white text-sm font-semibold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2 text-white"></i>
                    Go Back
                </button>
                
                <!-- Back to Login -->
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2 text-white"></i>
                    Back to Login
                </a>
            </div>

            <!-- Search Box -->
            <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Looking for something?</h3>
                <form action="{{ route('search.global') }}" method="GET" class="flex gap-2">
                    <input type="text" name="q" placeholder="Search the platform..." 
                           class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Additional Help -->
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Need Help?</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p>• Check if the URL is spelled correctly</p>
                    <p>• Use the search box above to find what you're looking for</p>
                    <p>• Navigate using the menu or go back to the login page</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 