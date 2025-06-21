<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
    <div class="w-full max-w-sm mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
        <div class="flex flex-col items-center mb-6">
            <h1 class="text-3xl font-extrabold text-indigo-600 dark:text-white mb-2">Edu<span class="text-gray-900 dark:text-indigo-300">Smart</span></h1>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Forgot your password?</h2>
            <p class="text-gray-500 dark:text-gray-300 text-sm">Enter your email address and we'll send you a link to reset your password.</p>
        </div>
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                <div class="mt-1 relative">
                    <input id="email" name="email" type="email" autocomplete="username" required autofocus class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white" value="{{ old('email') }}">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><i class="fas fa-envelope text-gray-400"></i></span>
                </div>
                @error('email')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-center">
                <button type="submit" class="w-auto flex justify-center items-center py-3 px-6 border border-transparent text-lg font-bold rounded-lg text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    <i class="fas fa-paper-plane mr-2"></i> <span>Send reset link</span>
                </button>
            </div>
        </form>
        <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition"><i class="fas fa-arrow-left mr-1"></i>Back to login</a>
        </div>
    </div>
</body>
</html> 