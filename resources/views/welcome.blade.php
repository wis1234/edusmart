<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])
    <style>
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 flex flex-col">
    <nav class="w-full py-4 px-8 flex justify-between items-center bg-white/80 dark:bg-gray-900/80 shadow-sm fixed top-0 left-0 z-50">
        <div class="flex items-center gap-2">
            <span class="text-2xl font-extrabold text-indigo-600 dark:text-white">Edu<span class="text-gray-900 dark:text-indigo-300">Smart</span></span>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 px-4 py-2 rounded-md text-base font-medium transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 px-4 py-2 rounded-md text-base font-medium transition">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-base font-medium transition">Sign Up</a>
                @endif
            @endauth
        </div>
    </nav>
    <main class="flex-1 flex items-center justify-center pt-24 pb-12">
        <div class="max-w-4xl w-full mx-auto flex flex-col md:flex-row items-center gap-12 px-4">
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight">
                    Manage your school<br>
                    <span class="text-indigo-600 dark:text-indigo-400">simply and smartly</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                    EduSmart helps you manage enrollments, classes, teachers, students, grades, and much more, all in a modern and intuitive interface.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold text-lg shadow-lg hover:from-indigo-600 hover:to-purple-700 transition">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 rounded-lg bg-white dark:bg-gray-900 border border-indigo-500 text-indigo-700 dark:text-indigo-300 font-bold text-lg shadow hover:bg-indigo-50 dark:hover:bg-gray-800 transition">Sign Up</a>
                    @endif
                </div>
            </div>
            <div class="flex-1 flex justify-center items-center min-h-[22rem]">
                <!-- Carousel Section -->
                <div id="carousel" class="relative w-full max-w-xs md:max-w-sm h-72 flex items-center justify-center">
                    <img id="carousel-image" src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80" alt="Education" class="rounded-2xl shadow-2xl w-full h-72 object-cover transition-all duration-700" />
                    <div id="carousel-text" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-center text-lg font-semibold text-indigo-700 dark:text-indigo-300 fade-in">
                        Empowering Students
                    </div>
                </div>
                <script>
                    const images = [
                        {
                            url: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80',
                            text: 'Empowering Students',
                            style: 'text-indigo-700 dark:text-indigo-300 bg-white/80 dark:bg-gray-900/80',
                            animation: 'fade-in'
                        },
                        {
                            url: 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=600&q=80',
                            text: 'Innovative Classrooms',
                            style: 'text-purple-700 dark:text-purple-300 bg-white/90 dark:bg-gray-800/90',
                            animation: 'slide-in'
                        },
                        {
                            url: 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=600&q=80',
                            text: 'Connected Teachers',
                            style: 'text-green-700 dark:text-green-300 bg-white/80 dark:bg-gray-900/80',
                            animation: 'zoom-in'
                        },
                        {
                            url: 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
                            text: 'Engaged Parents',
                            style: 'text-pink-700 dark:text-pink-300 bg-white/90 dark:bg-gray-800/90',
                            animation: 'rotate-in'
                        },
                        {
                            url: 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=600&q=80',
                            text: 'Smart Administration',
                            style: 'text-blue-700 dark:text-blue-300 bg-white/80 dark:bg-gray-900/80',
                            animation: 'bounce-in'
                        }
                    ];
                    let current = 0;
                    setInterval(() => {
                        current = (current + 1) % images.length;
                        const img = document.getElementById('carousel-image');
                        const text = document.getElementById('carousel-text');
                        img.classList.remove('fade-in');
                        text.className = text.className.replace(/fade-in|slide-in|zoom-in|rotate-in|bounce-in/g, '');
                        setTimeout(() => {
                            img.src = images[current].url;
                            text.textContent = images[current].text;
                            text.className = `absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-center text-lg font-semibold ${images[current].style} ${images[current].animation}`;
                            img.classList.add('fade-in');
                        }, 200);
                    }, 3500);
                </script>
                <style>
                    .fade-in {
                        animation: fadeIn 1s ease-in;
                    }
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    .slide-in {
                        animation: slideIn 1s cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    @keyframes slideIn {
                        from { opacity: 0; transform: translateX(-40px); }
                        to { opacity: 1; transform: translateX(0); }
                    }
                    .zoom-in {
                        animation: zoomIn 1s cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    @keyframes zoomIn {
                        from { opacity: 0; transform: scale(0.7); }
                        to { opacity: 1; transform: scale(1); }
                    }
                    .rotate-in {
                        animation: rotateIn 1s cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    @keyframes rotateIn {
                        from { opacity: 0; transform: rotate(-15deg) scale(0.8); }
                        to { opacity: 1; transform: rotate(0deg) scale(1); }
                    }
                    .bounce-in {
                        animation: bounceIn 1s cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    @keyframes bounceIn {
                        0% { opacity: 0; transform: translateY(40px); }
                        60% { opacity: 1; transform: translateY(-10px); }
                        80% { transform: translateY(5px); }
                        100% { transform: translateY(0); }
                    }
                </style>
            </div>
        </div>
    </main>
    <footer class="w-full text-center py-6 text-gray-400 text-sm">
        &copy; {{ date('Y') }} EduSmart. All rights reserved.
    </footer>
</body>
</html> 