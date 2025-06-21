<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Locked - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
    <div class="w-full max-w-md mx-auto p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
        <div class="flex flex-col items-center text-center">
            <div class="p-4 bg-red-100 dark:bg-red-900/50 rounded-full mb-4">
                <i class="fas fa-lock text-5xl text-red-500 dark:text-red-400"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Account Locked</h1>
            <p class="text-gray-500 dark:text-gray-300 mb-6">For your security, your account has been temporarily locked due to multiple failed login attempts.</p>
            
            <div class="w-full bg-yellow-100/50 dark:bg-yellow-400/10 border-l-4 border-yellow-400 p-4 text-left rounded-md mb-6">
                <div class="flex">
                    <div class="py-1">
                        <i class="fas fa-envelope-open-text text-yellow-500 dark:text-yellow-300 text-2xl mr-4"></i>
                    </div>
                    <div>
                        <p class="font-bold text-yellow-800 dark:text-yellow-200">Check Your Email</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">We have sent a message with a password reset link to unlock your account.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('login') }}" class="w-full flex justify-center items-center py-3 px-6 border border-transparent text-lg font-bold rounded-lg text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Back to Login</span>
            </a>
        </div>
    </div>
</body>
</html> 