<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom Styles -->
        <style>
            :root {
                --primary: #4f46e5;
                --primary-light: #818cf8;
                --primary-dark: #3730a3;
                --success: #10b981;
                --success-light: #34d399;
                --success-dark: #059669;
                --danger: #ef4444;
                --danger-light: #f87171;
                --danger-dark: #dc2626;
                --warning: #f59e0b;
                --warning-light: #fbbf24;
                --warning-dark: #d97706;
                --info: #3b82f6;
                --info-light: #60a5fa;
                --info-dark: #2563eb;
                --sidebar-width: 250px;
                --header-height: 60px;
                
                /* Light mode colors */
                --bg-primary: #ffffff;
                --bg-secondary: #f3f4f6;
                --bg-tertiary: #f9fafb;
                --text-primary: #111827;
                --text-secondary: #6b7280;
                --text-tertiary: #9ca3af;
                --border-color: #e5e7eb;
                --shadow-color: rgba(0, 0, 0, 0.05);
            }

            /* Dark mode colors */
            html.dark {
                --bg-primary: #1f2937;
                --bg-secondary: #111827;
                --bg-tertiary: #374151;
                --text-primary: #f9fafb;
                --text-secondary: #d1d5db;
                --text-tertiary: #9ca3af;
                --border-color: #374151;
                --shadow-color: rgba(0, 0, 0, 0.3);
            }

            body {
                min-height: 100vh;
                background-color: var(--bg-secondary);
                color: var(--text-primary);
                font-family: 'Nunito', sans-serif;
                padding-top: 0;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            /* Layout Structure */
            .app-wrapper {
                display: flex;
                min-height: calc(100vh - var(--header-height));
            }

            /* Sidebar */
            .sidebar {
                width: var(--sidebar-width);
                background: var(--bg-primary);
                position: fixed;
                top: var(--header-height);
                left: 0;
                height: calc(100vh - var(--header-height));
                z-index: 1000;
                transition: all 0.3s ease-in-out;
                box-shadow: 0 0 15px var(--shadow-color);
                border-right: 1px solid var(--border-color);
            }

            .sidebar.collapsed {
                transform: translateX(-250px);
            }

            /* Main Content Area */
            .main-content {
                flex: 1;
                margin-left: var(--sidebar-width);
                transition: all 0.3s ease-in-out;
                min-height: calc(100vh - var(--header-height));
                display: flex;
                flex-direction: column;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            /* Header */
            .main-header {
                height: var(--header-height);
                background: var(--bg-primary);
                border-bottom: 1px solid var(--border-color);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1100;
                box-shadow: 0 1px 3px var(--shadow-color);
            }

            /* Content Area */
            .content-wrapper {
                flex: 1;
                padding: 0;
                background: var(--bg-secondary);
                margin-top: 0;
                position: relative;
                min-height: 200px;
            }

            /* Card Styles */
            .card {
                border: none;
                background: var(--bg-primary);
                color: var(--text-primary);
                box-shadow: 0 1px 3px var(--shadow-color);
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 4px 6px -1px var(--shadow-color), 0 2px 4px -1px var(--shadow-color);
            }

            /* Button Styles */
            .btn {
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .btn-primary {
                background-color: var(--primary);
                border-color: var(--primary);
            }

            .btn-primary:hover {
                background-color: var(--primary-dark);
                border-color: var(--primary-dark);
            }

            /* Form Controls */
            .form-control {
                background-color: var(--bg-primary);
                border-color: var(--border-color);
                color: var(--text-primary);
            }

            .form-control:focus {
                background-color: var(--bg-primary);
                border-color: var(--primary);
                color: var(--text-primary);
                box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
            }

            /* Table Styles */
            .table {
                color: var(--text-primary);
            }

            .table th {
                background-color: var(--bg-tertiary);
                border-color: var(--border-color);
            }

            .table td {
                border-color: var(--border-color);
            }

            /* Navigation */
            .nav-link {
                color: var(--text-secondary);
            }

            .nav-link:hover {
                color: var(--text-primary);
            }

            .nav-link.active {
                color: var(--primary);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-250px);
                }
                
                .sidebar.show {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                }

                .sidebar-overlay.show {
                    display: block;
                }
            }

            /* Scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
            }

            ::-webkit-scrollbar-track {
                background: var(--bg-tertiary);
            }

            ::-webkit-scrollbar-thumb {
                background: var(--text-tertiary);
                border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: var(--text-secondary);
            }

            /* Overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: var(--header-height);
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                backdrop-filter: blur(2px);
            }

            .sidebar-overlay.show {
                display: block;
            }

            /* Animations */
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            /* Custom Utilities */
            .bg-gradient-primary {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            }

            /* Theme Toggle Button */
            .theme-toggle-btn {
                transition: all 0.3s ease;
            }

            .theme-toggle-btn:hover {
                transform: scale(1.1);
            }

            /* Dark mode specific overrides */
            html.dark .text-muted {
                color: var(--text-tertiary) !important;
            }

            html.dark .bg-light {
                background-color: var(--bg-tertiary) !important;
            }

            html.dark .border {
                border-color: var(--border-color) !important;
            }
        </style>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/theme.js'])
        @stack('styles')
        
        <!-- Global Theme Management - Load early -->
        <!-- (Removed inline theme script, now loaded from resources/js/theme.js) -->
    </head>
    <body>
        <!-- Header -->
        @include('layouts.header')

        <div class="app-wrapper">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <!-- Content -->
                <div class="content-wrapper page-transition" id="mainContent">
                    <!-- Loading Spinner -->
                    <div class="loading-spinner" id="loadingSpinner">
                        <div class="spinner-container">
                            <div class="spinner"></div>
                            <div class="spinner-text">Chargement en cours...</div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

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