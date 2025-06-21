<div class="space-y-6" id="two-factor-section">
    <!-- Loading Overlay -->
    <div id="two-factor-loading" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-2xl max-w-sm w-full mx-4">
            <div class="flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Updating Settings</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Please wait while we update your two-factor authentication...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Two-Factor Authentication</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Add an extra layer of security to your account</p>
    </div>

    <!-- Current Status -->
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 relative">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-shield-alt text-2xl {{ $user->two_factor_enabled ? 'text-green-500' : 'text-gray-400' }} transition-colors duration-300" id="two-factor-icon"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Two-Factor Authentication</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 transition-all duration-300" id="two-factor-description">
                        @if($user->two_factor_enabled)
                            Your account is protected with two-factor authentication
                        @else
                            Add an extra layer of security to your account
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span id="two-factor-status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all duration-300 {{ $user->two_factor_enabled ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' }}">
                    <span id="two-factor-status-text">{{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}</span>
                </span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="two-factor-toggle" 
                           class="sr-only peer" 
                           {{ $user->two_factor_enabled ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600 transition-all duration-300" id="toggle-slider"></div>
                </label>
            </div>
        </div>
        
        <!-- Loading indicator on the card -->
        <div id="card-loading" class="hidden absolute inset-0 bg-white bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-75 rounded-lg flex items-center justify-center">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Updating...</span>
            </div>
        </div>
    </div>

    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- How it works -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-info-circle text-indigo-500 text-xl mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">How it works</h3>
            </div>
            <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                    <span>When enabled, you'll receive a 6-digit code via email each time you log in</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                    <span>The code expires after 2 minutes for security</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                    <span>You have 3 attempts to enter the correct code</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                    <span>Your account will be locked if you exceed the attempt limit</span>
                </li>
            </ul>
        </div>

        <!-- Security Benefits -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-shield-alt text-green-500 text-xl mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Security Benefits</h3>
            </div>
            <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                <li class="flex items-start">
                    <i class="fas fa-lock text-blue-500 mt-1 mr-2"></i>
                    <span>Protects against unauthorized access even if your password is compromised</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-lock text-blue-500 mt-1 mr-2"></i>
                    <span>Prevents account takeover attacks</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-lock text-blue-500 mt-1 mr-2"></i>
                    <span>Complies with security best practices</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-lock text-blue-500 mt-1 mr-2"></i>
                    <span>Provides peace of mind for your account security</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Warning -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Important</h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <p>When you enable two-factor authentication, you'll receive an email notification. Make sure you have access to your email address before enabling this feature.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('two-factor-toggle');
    
    if (toggle) {
        toggle.addEventListener('change', function() {
            const enabled = this.checked;
            toggleTwoFactor(enabled);
        });
    }
});

function toggleTwoFactor(enabled) {
    // Show loading states
    const toggle = document.getElementById('two-factor-toggle');
    const originalState = toggle.checked;
    toggle.disabled = true;
    
    // Show loading overlay
    const loadingOverlay = document.getElementById('two-factor-loading');
    const cardLoading = document.getElementById('card-loading');
    const toggleSlider = document.getElementById('toggle-slider');
    
    loadingOverlay.classList.remove('hidden');
    cardLoading.classList.remove('hidden');
    toggleSlider.classList.add('opacity-50');
    
    fetch('{{ route("settings.toggle-two-factor") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ enabled: enabled })
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading states
        toggle.disabled = false;
        loadingOverlay.classList.add('hidden');
        cardLoading.classList.add('hidden');
        toggleSlider.classList.remove('opacity-50');
        
        if (data.success) {
            // Show success message with animation
            showNotification(data.message, 'success');
            
            // Update UI with smooth transitions
            const status = document.getElementById('two-factor-status');
            const statusText = document.getElementById('two-factor-status-text');
            const icon = document.getElementById('two-factor-icon');
            const description = document.getElementById('two-factor-description');
            
            if (data.enabled) {
                // Animate the changes with staggered timing
                setTimeout(() => {
                    status.classList.remove('bg-gray-100', 'text-gray-800', 'dark:bg-gray-900', 'dark:text-gray-300');
                    status.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
                    statusText.textContent = 'Enabled';
                }, 100);
                
                setTimeout(() => {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-green-500');
                }, 200);
                
                setTimeout(() => {
                    description.textContent = 'Your account is protected with two-factor authentication';
                }, 300);
            } else {
                // Animate the changes with staggered timing
                setTimeout(() => {
                    status.classList.remove('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
                    status.classList.add('bg-gray-100', 'text-gray-800', 'dark:bg-gray-900', 'dark:text-gray-300');
                    statusText.textContent = 'Disabled';
                }, 100);
                
                setTimeout(() => {
                    icon.classList.remove('text-green-500');
                    icon.classList.add('text-gray-400');
                }, 200);
                
                setTimeout(() => {
                    description.textContent = 'Add an extra layer of security to your account';
                }, 300);
            }
        } else {
            // Revert toggle state on error
            toggle.checked = originalState;
            showNotification(data.message || 'An error occurred. Please try again.', 'error');
            console.error('Error details:', data.error);
        }
    })
    .catch(error => {
        // Hide loading states
        toggle.disabled = false;
        loadingOverlay.classList.add('hidden');
        cardLoading.classList.add('hidden');
        toggleSlider.classList.remove('opacity-50');
        toggle.checked = originalState;
        console.error('Error:', error);
        showNotification('Network error. Please check your connection and try again.', 'error');
    });
}

function showNotification(message, type) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element with modern design
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white' : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-2 opacity-70 hover:opacity-100 transition-opacity">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Auto remove after delay
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, type === 'error' ? 5000 : 3000);
}
</script> 