<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to EduSmart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .carousel-slide {
            transition: opacity 1.5s ease-in-out;
        }
        .text-overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.4), rgba(0,0,0,0));
        }
        .btn-primary {
            max-width: 200px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        .logo-text {
            font-weight: 800;
            letter-spacing: -0.025em;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 h-full">
    <div class="flex min-h-full">
        <!-- Left side with Carousel -->
        <div 
            x-data="{
                currentSlide: 0,
                slides: [
                    {
                        image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1280',
                        title: 'Collaborative Learning',
                        subtitle: 'Engage with students and teachers in a dynamic, interactive environment.'
                    },
                    {
                        image: 'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=1280',
                        title: 'Seamless Management',
                        subtitle: 'Streamline administrative tasks and focus on what matters most: education.'
                    },
                    {
                        image: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1280',
                        title: 'Parental Involvement',
                        subtitle: 'Keep parents updated and involved in their child\'s educational journey.'
                    }
                ],
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                }
            }"
            x-init="setInterval(() => nextSlide(), 5000)"
            class="hidden lg:flex lg:flex-col lg:w-3/5 bg-gray-900 justify-center items-center relative overflow-hidden"
        >
            <template x-for="(slide, index) in slides" :key="index">
                <div 
                    x-show="currentSlide === index"
                    class="absolute top-0 left-0 w-full h-full bg-cover bg-center carousel-slide"
                    :style="`background-image: url('${slide.image}');`"
                ></div>
            </template>

            <div class="relative z-10 text-center text-white p-16 flex flex-col justify-between h-full w-full text-overlay">
                <a href="/" class="text-4xl logo-text self-start">
                    <span class="text-indigo-400">Edu</span><span class="text-gray-900">SMART</span>
                </a>
                <div class="text-left">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="currentSlide === index" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform translate-y-5" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <h2 class="text-5xl font-extrabold mb-4" x-text="slide.title"></h2>
                            <p class="text-xl font-light max-w-lg opacity-90" x-text="slide.subtitle"></p>
                        </div>
                    </template>
                </div>
                <div class="flex justify-center space-x-3">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="currentSlide = index" 
                                :class="{'bg-white': currentSlide === index, 'bg-white/40': currentSlide !== index}"
                                class="w-2 h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right side -->
        <div class="flex flex-col justify-center w-full lg:w-2/5 px-4 sm:px-6 lg:px-16 py-12 bg-white">
            <div class="w-full max-w-sm mx-auto h-[90%] flex flex-col">
                <div class="bg-white p-8 rounded-2xl shadow-xl flex-grow flex flex-col justify-center">
                    <div class="text-center mb-10">
                        <a href="/" class="text-3xl font-extrabold lg:hidden mb-8 logo-text">
                            <span class="text-indigo-600">Edu</span><span class="text-gray-900">SMART</span>
                        </a>
                        <h1 class="text-4xl font-bold text-gray-900 mt-4">Welcome Back!</h1>
                        <p class="text-gray-600 mt-3 text-lg">Sign in to continue your journey.</p>
                    </div>

                    @if (Route::has('login'))
                        <div class="space-y-4 mx-auto w-full">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-primary mx-auto block text-center bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-lg shadow-sm hover:bg-indigo-700 transition">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-primary mx-auto block text-center bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-lg shadow-sm hover:bg-indigo-700 transition">
                                    Sign In
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-primary mx-auto block text-center bg-gray-100 text-gray-800 font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition">
                                        Create Account
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <div class="mt-auto pt-8 text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} EduSmart. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>