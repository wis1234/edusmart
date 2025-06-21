<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
    <div class="w-full max-w-md mx-auto p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
        <div class="flex flex-col items-center mb-6">
            <h1 class="text-3xl font-extrabold text-indigo-600 dark:text-white mb-2">Edu<span class="text-gray-900 dark:text-indigo-300">Smart</span></h1>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Create an account</h2>
            <p class="text-gray-500 dark:text-gray-300 text-sm">Fill in the form to join EduSmart</p>
        </div>
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full name</label>
                <div class="mt-1 relative">
                    <input id="name" name="name" type="text" autocomplete="name" required autofocus class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white" value="{{ old('name') }}">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><i class="fas fa-user text-gray-400"></i></span>
                </div>
                @error('name')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                <div class="mt-1 relative">
                    <input id="email" name="email" type="email" autocomplete="username" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white" value="{{ old('email') }}">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><i class="fas fa-envelope text-gray-400"></i></span>
                </div>
                @error('email')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <div class="mt-1 relative">
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><i class="fas fa-lock text-gray-400"></i></span>
                </div>
                @error('password')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm password</label>
                <div class="mt-1 relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><i class="fas fa-lock text-gray-400"></i></span>
                </div>
                @error('password_confirmation')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-lg font-bold rounded-lg text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <i class="fas fa-user-plus mr-2"></i> Register
            </button>
        </form>
        <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition">Sign in</a>
        </div>
    </div>
</body>
</html> 