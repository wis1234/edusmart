<x-app-layout>
    <div class="flex items-center justify-center min-h-[70vh] bg-gradient-to-r from-gray-100 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl space-y-6 text-center">
            
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 dark:bg-red-900/20 shadow-lg">
                <i class="fas fa-ban text-red-600 dark:text-red-400 text-3xl"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wide">
                Access Denied
            </h1>

            <!-- Message -->
            <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                You don't have access to this resource.<br>
                If you believe you should, please contact the administration.
            </p>

            <!-- Info Box -->
            <div class="bg-yellow-100 dark:bg-yellow-900/10 border-l-4 border-yellow-500 dark:border-yellow-600 p-4 rounded-md shadow-md">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-shield-alt text-yellow-600 dark:text-yellow-400 text-lg mt-1"></i>
                    <div class="text-left">
                        <p class="font-medium text-yellow-800 dark:text-yellow-200 text-sm">Restricted Access</p>
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                            This page is restricted to authorized users only.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modern Button -->
            <div class="flex justify-center">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2 text-white"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
