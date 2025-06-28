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
        
        <!-- App Responsive CSS -->
        <link href="{{ asset('css/app-responsive.css') }}" rel="stylesheet">
        
        <!-- Custom Styles -->
        <style>
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
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- App Responsive JS -->
        <script src="{{ asset('js/app-responsive.js') }}"></script>

        @stack('scripts')
    </body>
</html> 