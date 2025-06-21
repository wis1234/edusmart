<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two-Factor Authentication | EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>
        .toast-container { position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem; }
        .toast { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.5rem; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); color: white; opacity: 0; transform: translateX(100%); transition: opacity 0.3s ease, transform 0.3s ease; }
        .toast.show { opacity: 1; transform: translateX(0); }
        .toast.success { background-color: #22c55e; }
        .toast.error { background-color: #ef4444; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
    <div id="toast-container" class="toast-container"></div>
    <div class="w-full max-w-sm mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl">
        <div class="flex flex-col items-center mb-6">
            <h1 class="text-3xl font-extrabold text-indigo-600 dark:text-white mb-2">Edu<span class="text-gray-900 dark:text-indigo-300">Smart</span></h1>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Two-Factor Authentication</h2>
            <div id="timer" class="mt-2 text-lg font-semibold text-indigo-600 dark:text-indigo-300"></div>
            <p class="text-gray-500 dark:text-gray-300 text-sm">Please enter the 6-digit code sent to your email.</p>
        </div>
        <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-6">
            @csrf
            <div>
                <label for="two_factor_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Authentication code</label>
                <div class="mt-1 relative">
                    <input id="two_factor_code" name="two_factor_code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white text-center tracking-widest text-2xl">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><i class="fas fa-shield-alt text-gray-400"></i></span>
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="w-auto flex justify-center items-center py-3 px-6 border border-transparent text-lg font-bold rounded-lg text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    <i class="fas fa-check-circle mr-2"></i> <span>Verify</span>
                </button>
            </div>
        </form>
        <form method="POST" action="{{ route('two-factor.resend') }}" class="mt-4 flex justify-center">
            @csrf
            <button id="resendBtn" type="submit" class="w-auto flex justify-center items-center py-2 px-4 border border-transparent text-base font-bold rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <i class="fas fa-sync-alt mr-2"></i> <span>Resend code</span>
            </button>
        </form>
        <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition"><i class="fas fa-arrow-left mr-1"></i>Back to login</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toast functionality
            const toastContainer = document.getElementById('toast-container');
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
                toast.innerHTML = `<i class="fas ${iconClass}"></i><span>${message}</span>`;
                toastContainer.appendChild(toast);
                setTimeout(() => toast.classList.add('show'), 100);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            }

            @if (session('status'))
                showToast("{{ session('status') }}", 'success');
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast("{{ $error }}", 'error');
                @endforeach
            @endif

            // Timer functionality
            const expiresAt = {{ $expiresAt ?? 'null' }};
            let seconds = expiresAt ? Math.max(0, Math.floor(expiresAt - Date.now() / 1000)) : 0;
            const timerDiv = document.getElementById('timer');
            const verifyBtn = document.querySelector('button[type="submit"]');
            const resendBtn = document.getElementById('resendBtn');
            const originalTimerClasses = timerDiv.className;

            function updateTimerAndButtons() {
                if (seconds > 0) {
                    const min = Math.floor(seconds / 60);
                    const sec = seconds % 60;
                    timerDiv.textContent = `Code expires in ${min}:${String(sec).padStart(2, '0')}`;
                    timerDiv.className = originalTimerClasses;
                    resendBtn.disabled = true;
                    resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    timerDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i><span>The code has expired. Please Try again</span>';
                    timerDiv.className = 'w-full mt-2 text-sm font-medium text-red-600 dark:text-red-400 flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/50 rounded-lg';
                    verifyBtn.disabled = true;
                    verifyBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            if (timerDiv) {
                updateTimerAndButtons();
                const interval = setInterval(() => {
                    seconds--;
                    updateTimerAndButtons();
                    if (seconds <= 0) clearInterval(interval);
                }, 1000);
            }
        });
    </script>
</body>
</html> 