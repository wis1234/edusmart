<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
            }

            body {
                min-height: 100vh;
                background-color: #f3f4f6;
                font-family: 'Nunito', sans-serif;
                padding-top: var(--header-height);
            }

            /* Layout Structure */
            .app-wrapper {
                display: flex;
                min-height: calc(100vh - var(--header-height));
            }

            /* Sidebar */
            .sidebar {
                width: var(--sidebar-width);
                background: white;
                position: fixed;
                top: var(--header-height);
                left: 0;
                height: calc(100vh - var(--header-height));
                z-index: 1000;
                transition: all 0.3s ease-in-out;
                box-shadow: 0 0 15px rgba(0,0,0,0.05);
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
                background: white;
                border-bottom: 1px solid #e5e7eb;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1100;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }

            /* Content Area */
            .content-wrapper {
                flex: 1;
                padding: 1.5rem;
                background: #f3f4f6;
                margin-top: 0;
            }

            /* Card Styles */
            .card {
                border: none;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
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
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #555;
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
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            }

            .text-gradient {
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .hover-lift {
                transition: transform 0.2s ease;
            }

            .hover-lift:hover {
                transform: translateY(-2px);
            }
        </style>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body>
        <!-- Header -->
        @include('layouts.header')

        <div class="app-wrapper">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="main-content pt-10">
                <!-- Content -->
                <div class="content-wrapper">
                    {{ $slot }}
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
                let isSidebarCollapsed = false;

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

                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        if (sidebarOverlay) {
                            sidebarOverlay.classList.remove('show');
                        }
                    }
                });

                // Add hover effects to cards
                document.querySelectorAll('.card').forEach(card => {
                    card.classList.add('hover-lift');
                });
            });
        </script>
        @stack('scripts')
    </body>
</html> 