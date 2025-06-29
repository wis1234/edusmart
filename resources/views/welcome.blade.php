<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduSmart - Your Complete School Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in-up.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .carousel-slide {
            opacity: 0;
            transform: scale(0.95);
            transition: all 1s ease-in-out;
        }

        .carousel-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .text-slide {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease-in-out;
        }

        .text-slide.active {
            opacity: 1;
            transform: translateY(0);
        }

        .hero-gradient {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2) 0%, rgba(147, 51, 234, 0.2) 100%);
        }

        .card-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .card-hover-effect {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover-effect:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .icon-glow {
            transition: all 0.3s ease;
        }

        .card-hover-effect:hover .icon-glow {
            transform: scale(1.1);
            filter: drop-shadow(0 0 8px rgba(79, 70, 229, 0.3));
        }

        .loading-dots {
            display: inline-block;
        }

        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(5, end) infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .zoom-in {
            animation: zoomIn 0.8s ease-out;
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 640px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800" x-data>

    <div x-data="{ 
        mobileMenuOpen: false,
        currentSlide: 0,
        isLoading: true,
        slides: [
            {
                image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                title: 'The Smart Way to Manage Your School',
                subtitle: 'EduSmart is an all-in-one platform that connects administrators, teachers, students, and parents for a seamless educational experience.',
                cta: 'Create Your School',
                animation: 'slide-in-left'
            },
            {
                image: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                title: 'Empower Your Teachers',
                subtitle: 'Give your educators the tools they need to create engaging lessons, track progress, and communicate effectively with parents.',
                cta: 'Start Teaching',
                animation: 'slide-in-right'
            },
            {
                image: 'https://images.unsplash.com/photo-1503676382389-4809596d5290?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                title: 'Connect with Parents',
                subtitle: 'Keep parents informed and engaged with real-time updates on their child\'s progress, attendance, and school activities.',
                cta: 'Join Now',
                animation: 'zoom-in'
            },
            {
                image: 'https://images.unsplash.com/photo-1513258496099-48168024aec0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                title: 'Track Student Success',
                subtitle: 'Monitor academic performance, attendance, and engagement with comprehensive analytics and detailed reports.',
                cta: 'Learn More',
                animation: 'slide-in-left'
            }
        ]
    }" x-init="setTimeout(() => { isLoading = false }, 1000); setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 6000)">

        <!-- Loading Screen -->
        <div x-show="isLoading" x-transition class="fixed inset-0 bg-gradient-to-br from-indigo-600 to-purple-700 z-50 flex items-center justify-center">
            <div class="text-center text-white">
                <div class="w-16 h-16 border-4 border-white border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <h2 class="text-2xl font-bold mb-2">Welcome to EduSmart</h2>
                <p class="text-lg opacity-90">Loading amazing features<span class="loading-dots"></span></p>
            </div>
        </div>

        <!-- Header -->
        <header class="bg-white/95 backdrop-blur-lg shadow-lg fixed top-0 left-0 right-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="/" class="text-2xl sm:text-3xl font-bold">
                            <span class="text-indigo-600">Edu</span><span class="text-black">Smart</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-gray-600 hover:text-indigo-600 transition font-medium">Features</a>
                        <a href="#how-it-works" class="text-gray-600 hover:text-indigo-600 transition font-medium">How It Works</a>
                        <a href="#contact" class="text-gray-600 hover:text-indigo-600 transition font-medium">Contact</a>
                    </nav>

                    <!-- Desktop Auth Links -->
                    <div class="hidden md:flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-block bg-indigo-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-indigo-700 transition-transform transform hover:scale-105">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition font-medium">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-block bg-indigo-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-indigo-700 transition-transform transform hover:scale-105">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-indigo-600 focus:outline-none p-2">
                            <i class="fas text-xl" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-white border-t border-gray-200">
                <nav class="flex flex-col space-y-2 p-4">
                    <a href="#features" @click="mobileMenuOpen = false" class="block text-gray-600 hover:text-indigo-600 transition font-medium py-2">Features</a>
                    <a href="#how-it-works" @click="mobileMenuOpen = false" class="block text-gray-600 hover:text-indigo-600 transition font-medium py-2">How It Works</a>
                    <a href="#contact" @click="mobileMenuOpen = false" class="block text-gray-600 hover:text-indigo-600 transition font-medium py-2">Contact</a>
                    <div class="border-t pt-4 mt-2 space-y-2">
                        @if (Route::has('login'))
            @auth
                                <a href="{{ url('/dashboard') }}" class="block w-full text-center bg-indigo-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-indigo-700 transition">Dashboard</a>
            @else
                                <a href="{{ route('login') }}" class="block w-full text-center text-gray-600 hover:text-indigo-600 transition font-medium py-2">Log in</a>
                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block w-full text-center bg-indigo-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-indigo-700 transition">Register</a>
                @endif
            @endauth
                        @endif
        </div>
    </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="pt-16">
            <!-- Hero Section with Carousel -->
            <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
                <!-- Carousel Background Images -->
                <template x-for="(slide, index) in slides" :key="index">
                    <div class="absolute inset-0 transition-opacity duration-1500"
                         :class="currentSlide === index ? 'opacity-100' : 'opacity-0'">
                        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transform scale-105"
                             :style="`background-image: url('${slide.image}')`"></div>
                        <div class="absolute inset-0 hero-gradient"></div>
                    </div>
                </template>

                <!-- Content -->
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="text-slide"
                             :class="currentSlide === index ? 'active' : ''"
                             x-show="currentSlide === index">
                            <h1 class="hero-title font-extrabold leading-tight mb-6 drop-shadow-lg" :class="slide.animation">
                                <span x-text="slide.title"></span>
                </h1>
                            <p class="hero-subtitle max-w-3xl mx-auto mb-8 drop-shadow-md leading-relaxed" :class="slide.animation">
                                <span x-text="slide.subtitle"></span>
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center" :class="slide.animation">
                                <a href="{{ route('register') }}" 
                                   class="inline-block bg-white text-indigo-600 font-bold text-lg px-8 py-4 rounded-lg shadow-xl hover:bg-gray-100 transition-all transform hover:scale-105 hover:shadow-2xl">
                                    <span x-text="slide.cta"></span>
                                </a>
                                @auth
                                    <a href="{{ route('dashboard') }}" 
                                       class="inline-block border-2 border-white text-white font-bold text-lg px-8 py-4 rounded-lg hover:bg-white hover:text-indigo-600 transition-all transform hover:scale-105">
                                        Go to Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="inline-block border-2 border-white text-white font-bold text-lg px-8 py-4 rounded-lg hover:bg-white hover:text-indigo-600 transition-all transform hover:scale-105">
                                        Sign In
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </template>

                    <!-- Carousel Indicators -->
                    <div class="flex justify-center space-x-3 mt-12">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="currentSlide = index" 
                                    class="w-4 h-4 rounded-full transition-all duration-300 hover:scale-125"
                                    :class="currentSlide === index ? 'bg-white scale-125 shadow-lg' : 'bg-white/50 hover:bg-white/75'">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Scroll Indicator -->
                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                    <i class="fas fa-chevron-down text-white text-2xl drop-shadow-lg"></i>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-20 bg-gradient-to-br from-gray-50 to-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16 fade-in-up">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">A Feature for Every Need</h2>
                        <p class="max-w-2xl mx-auto text-lg text-gray-600">From administration to the classroom, EduSmart has you covered.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="card-modern p-8 rounded-2xl card-hover-effect fade-in-up">
                            <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white mb-6 mx-auto icon-glow">
                                <i class="fas fa-users text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center mb-4">User & School Management</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Manage teachers, parents, and students with distinct roles and permissions in a centralized system.</p>
                        </div>
                        
                        <div class="card-modern p-8 rounded-2xl card-hover-effect fade-in-up">
                            <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white mb-6 mx-auto icon-glow">
                                <i class="fas fa-chalkboard-teacher text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Academic Tools</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Organize classrooms, manage subjects, and schedule events with our integrated calendar system.</p>
                        </div>
                        
                        <div class="card-modern p-8 rounded-2xl card-hover-effect fade-in-up">
                            <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white mb-6 mx-auto icon-glow">
                                <i class="fas fa-file-signature text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Evaluations & Grading</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Create detailed evaluations, record student answers, and automatically calculate grades and reports.</p>
                        </div>
                        
                        <div class="card-modern p-8 rounded-2xl card-hover-effect fade-in-up">
                            <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 text-white mb-6 mx-auto icon-glow">
                                <i class="fas fa-video text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Live Video Calls</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Engage in real-time with secure, integrated video calls for virtual classrooms and meetings.</p>
                        </div>
                        
                        <div class="card-modern p-8 rounded-2xl card-hover-effect fade-in-up">
                            <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-yellow-500 to-yellow-600 text-white mb-6 mx-auto icon-glow">
                                <i class="fas fa-bell text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Real-Time Notifications</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Keep everyone informed with instant notifications for announcements, grades, and upcoming events.</p>
                        </div>
                        
                        <div class="card-modern p-8 rounded-2xl card-hover-effect fade-in-up">
                            <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mb-6 mx-auto icon-glow">
                                <i class="fas fa-store text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center mb-4">E-commerce Ready</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Manage a school store with product listings, cart functionality, and order processing.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How it Works Section -->
            <section id="how-it-works" class="py-20 bg-gradient-to-br from-indigo-50 to-purple-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16 fade-in-up">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Get Started in 3 Easy Steps</h2>
                        <p class="max-w-2xl mx-auto text-lg text-gray-600">Launching your digital campus is simple and fast.</p>
                    </div>
                    
                    <div class="relative">
                        <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-300 to-transparent -translate-y-1/2"></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 relative">
                            <div class="card-modern p-8 rounded-2xl text-center z-10 card-hover-effect fade-in-up">
                                <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white mb-6 mx-auto text-3xl font-bold shadow-lg">1</div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Register Your School</h3>
                                <p class="text-gray-600 leading-relaxed">Create an account for your institution and set up your school's profile in minutes.</p>
                            </div>
                            
                            <div class="card-modern p-8 rounded-2xl text-center z-10 card-hover-effect fade-in-up">
                                <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white mb-6 mx-auto text-3xl font-bold shadow-lg">2</div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Onboard Your Users</h3>
                                <p class="text-gray-600 leading-relaxed">Easily add your teachers, students, and parents, or invite them to join your new platform.</p>
                            </div>
                            
                            <div class="card-modern p-8 rounded-2xl text-center z-10 card-hover-effect fade-in-up">
                                <div class="flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white mb-6 mx-auto text-3xl font-bold shadow-lg">3</div>
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Start Teaching & Managing</h3>
                                <p class="text-gray-600 leading-relaxed">Utilize all the powerful features to enhance learning, communication, and administration.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer id="contact" class="bg-gradient-to-br from-gray-800 to-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <h3 class="text-2xl font-bold mb-4">
                            <span class="text-indigo-400">Edu</span><span class="text-white">Smart</span>
                        </h3>
                        <p class="text-gray-400 max-w-md mb-6 leading-relaxed">The future of education is here. Join us in revolutionizing the classroom experience with a powerful, intuitive, and centralized platform.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-12 h-12 bg-gray-700 hover:bg-indigo-600 rounded-xl flex items-center justify-center transition-all transform hover:scale-110">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-gray-700 hover:bg-indigo-600 rounded-xl flex items-center justify-center transition-all transform hover:scale-110">
                                <i class="fab fa-linkedin text-lg"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-gray-700 hover:bg-indigo-600 rounded-xl flex items-center justify-center transition-all transform hover:scale-110">
                                <i class="fab fa-facebook text-lg"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Register</a></li>
                        </ul>
            </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-4">Contact Us</h3>
                        <p class="text-gray-400 mb-3 leading-relaxed">123 Education Lane<br>Knowledge City, 54321</p>
                        <p class="text-gray-400">Email: <a href="mailto:support@edusmart.com" class="hover:text-white transition-colors">support@edusmart.com</a></p>
                    </div>
                </div>
                
                <div class="mt-12 border-t border-gray-700 pt-8 text-center text-gray-500">
                    <p>&copy; {{ date('Y') }} EduSmart. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

                <script>
        function setupScrollAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.fade-in-up').forEach(el => {
                observer.observe(el);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            setupScrollAnimations();
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html> 