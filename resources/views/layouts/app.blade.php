<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Content Security Policy -->
        <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' 'wasm-unsafe-eval' 'inline-speculation-rules' https://cdn.socket.io https://cdn.jsdelivr.net https://cdnjs.cloudflare.com chrome-extension:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' ws: wss:;">

        <title>{{ config('app.name', 'EduSmart') }}</title>

        <!-- Alpine.js (chargé en priorité) -->
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        <script src="//unpkg.com/alpinejs" defer></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Global Theme Management - Load early -->
        <!-- (Removed inline theme script, now loaded from resources/js/theme.js) -->

        <!-- Additional CSS -->
        <style>
            .toast-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                max-width: 350px;
            }
            
            .preloader-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }
            
            .preloader-spinner {
                border: 4px solid #f3f3f3;
                border-top: 4px solid #3498db;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body class="h-full font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen">
            @include('layouts.header')

            <!-- Flash Message Popup (Vanilla JS) -->
            @if(session('success') || session('error'))
                <div id="flash-message"
                    class="fixed top-6 right-6 z-50 px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 text-white text-base transition-all duration-300 opacity-0 translate-y-4"
                    style="background-color: {{ session('success') ? '#22c55e' : '#ef4444' }}; min-width: 260px;"
                >
                    <i class="fas {{ session('success') ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    <span>{{ session('success') ?? session('error') }}</span>
                </div>
                <script>
                    window.addEventListener('DOMContentLoaded', function() {
                        var flash = document.getElementById('flash-message');
                        if (flash) {
                            setTimeout(function() {
                                flash.classList.remove('opacity-0', 'translate-y-4');
                                flash.classList.add('opacity-100', 'translate-y-0');
                            }, 100); // animation in
                            setTimeout(function() {
                                flash.classList.remove('opacity-100', 'translate-y-0');
                                flash.classList.add('opacity-0', 'translate-y-4');
                            }, 4000); // animation out
                            setTimeout(function() {
                                if (flash.parentNode) flash.parentNode.removeChild(flash);
                            }, 4300);
                        }
                    });
                </script>
            @endif
            <!-- End Flash Message Popup -->

            <!-- Page Content -->
            <main class="pt-16">
                {{ $slot }}
            </main>
        </div>

        <!-- Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <x-preloader />

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                const sidebarToggle = document.getElementById('sidebarToggle');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                const loadingSpinner = document.getElementById('loadingSpinner');
                const mainContentWrapper = document.getElementById('mainContent');
                let isSidebarCollapsed = false;

                // Show loading spinner
                function showLoading() {
                    loadingSpinner.classList.add('show');
                    mainContentWrapper.classList.remove('show');
                }

                // Hide loading spinner
                function hideLoading() {
                    loadingSpinner.classList.remove('show');
                    mainContentWrapper.classList.add('show');
                }

                // Toggle sidebar
                function toggleSidebar() {
                    isSidebarCollapsed = !isSidebarCollapsed;
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    
                    // Save state
                    localStorage.setItem('sidebarCollapsed', isSidebarCollapsed);
                }

                // Initialize sidebar state
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    isSidebarCollapsed = true;
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                }

                // Event listeners
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        toggleSidebar();
                    });
                }

                // Mobile overlay
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('show');
                            sidebarOverlay.classList.remove('show');
                        }
                    });
                }

                // Handle navigation links
                document.querySelectorAll('a[href]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        // Don't show loading for external links or links with target="_blank"
                        if (this.hostname !== window.location.hostname || this.target === '_blank') {
                            return;
                        }

                        // Don't show loading for links with data-no-loading attribute
                        if (this.hasAttribute('data-no-loading')) {
                            return;
                        }

                        showLoading();
                    });
                });

                // Show content when page is loaded
                hideLoading();

                // Handle browser back/forward buttons
                window.addEventListener('popstate', function() {
                    showLoading();
                });

                // Handle form submissions
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', function() {
                        if (!this.hasAttribute('data-no-loading')) {
                            showLoading();
                        }
                    });
                });
            });
        </script>
    </body>
</html>