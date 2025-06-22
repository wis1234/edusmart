<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-red-500 to-yellow-500 shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">403 - Unauthorized</h1>
                    <p class="text-gray-500 dark:text-gray-300">You are not authorized to access this page.</p>
                </div>
            </div>
        </div>
        <!-- Error Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col items-center">
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-4">You do not have permission to view this resource.</p>
            @if(auth()->check() && auth()->user()->hasRole('enseignant'))
                <p class="text-md text-gray-500 dark:text-gray-400 mb-4">If you believe this is an error, please contact your school administration.</p>
            @endif
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
        </div>
    </div>
</x-app-layout> 