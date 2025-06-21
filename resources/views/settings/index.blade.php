<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-cog text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Settings</h1>
                    <p class="text-gray-500 dark:text-gray-300">Manage your account preferences and security</p>
                </div>
            </div>
        </div>

        <!-- Settings Navigation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <a href="#" data-tab="two-factor" 
                       class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 {{ $activeTab === 'two-factor' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Two-Factor Authentication
                    </a>
                    <a href="#" data-tab="profile" 
                       class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 {{ $activeTab === 'profile' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-user mr-2"></i>
                        Profile Settings
                    </a>
                    <a href="#" data-tab="security" 
                       class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 {{ $activeTab === 'security' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-lock mr-2"></i>
                        Security
                    </a>
                    <a href="#" data-tab="notifications" 
                       class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 {{ $activeTab === 'notifications' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-bell mr-2"></i>
                        Notifications
                    </a>
                </nav>
            </div>

            <!-- Settings Content -->
            <div class="p-6 relative">
                <!-- Loading Overlay for Tab Content -->
                <div id="tab-loading" class="hidden absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-75 z-10 flex items-center justify-center rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Loading...</span>
                    </div>
                </div>
                
                <!-- Content Container -->
                <div id="tab-content" class="transition-all duration-300">
                    @if($activeTab === 'two-factor')
                        @include('settings.partials.two-factor')
                    @elseif($activeTab === 'profile')
                        @include('settings.partials.profile')
                    @elseif($activeTab === 'security')
                        @include('settings.partials.security')
                    @elseif($activeTab === 'notifications')
                        @include('settings.partials.notifications')
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContent = document.getElementById('tab-content');
            const tabLoading = document.getElementById('tab-loading');
            
            // Store current tab
            let currentTab = '{{ $activeTab }}';
            
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const tabName = this.getAttribute('data-tab');
                    
                    // Don't reload if it's the same tab
                    if (tabName === currentTab) return;
                    
                    // Update active tab styling
                    updateActiveTab(this);
                    
                    // Show loading
                    showTabLoading();
                    
                    // Load tab content via AJAX
                    loadTabContent(tabName);
                });
            });
            
            function updateActiveTab(activeLink) {
                // Remove active state from all tabs
                tabLinks.forEach(link => {
                    link.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                    link.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Add active state to clicked tab
                activeLink.classList.remove('border-transparent', 'text-gray-500');
                activeLink.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
            }
            
            function showTabLoading() {
                tabLoading.classList.remove('hidden');
                tabContent.style.opacity = '0.5';
            }
            
            function hideTabLoading() {
                tabLoading.classList.add('hidden');
                tabContent.style.opacity = '1';
            }
            
            function loadTabContent(tabName) {
                // Simulate loading time for better UX
                setTimeout(() => {
                    fetch(`{{ route('settings.index') }}?tab=${tabName}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Extract only the tab content from the response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.querySelector('#tab-content');
                        
                        if (newContent) {
                            // Fade out current content
                            tabContent.style.opacity = '0';
                            
                            setTimeout(() => {
                                // Update content
                                tabContent.innerHTML = newContent.innerHTML;
                                
                                // Fade in new content
                                tabContent.style.opacity = '1';
                                hideTabLoading();
                                
                                // Update current tab
                                currentTab = tabName;
                                
                                // Update URL without page reload
                                const url = new URL(window.location);
                                url.searchParams.set('tab', tabName);
                                window.history.pushState({}, '', url);
                                
                                // Reinitialize any scripts in the new content
                                initializeTabScripts();
                            }, 150);
                        } else {
                            hideTabLoading();
                            showNotification('Failed to load tab content', 'error');
                        }
                    })
                    .catch(error => {
                        hideTabLoading();
                        console.error('Error loading tab:', error);
                        showNotification('Failed to load tab content', 'error');
                    });
                }, 300); // Minimum loading time for better UX
            }
            
            function initializeTabScripts() {
                // Re-initialize any scripts that need to be run after content load
                // This is especially important for the two-factor tab
                if (currentTab === 'two-factor') {
                    const toggle = document.getElementById('two-factor-toggle');
                    if (toggle) {
                        toggle.addEventListener('change', function() {
                            const enabled = this.checked;
                            toggleTwoFactor(enabled);
                        });
                    }
                }
            }
            
            function showNotification(message, type) {
                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.notification-toast');
                existingNotifications.forEach(notification => notification.remove());
                
                // Create notification element
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
                }, 3000);
            }
        });
    </script>
    @endpush
</x-app-layout> 