<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenue | EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 flex flex-col">
    <nav class="w-full py-4 px-8 flex justify-between items-center bg-white/80 dark:bg-gray-900/80 shadow-sm fixed top-0 left-0 z-50">
        <div class="flex items-center gap-2">
            <span class="text-2xl font-extrabold text-indigo-600 dark:text-white">Edu<span class="text-gray-900 dark:text-indigo-300">Smart</span></span>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 px-4 py-2 rounded-md text-base font-medium transition">Tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-200 hover:text-indigo-600 px-4 py-2 rounded-md text-base font-medium transition">Connexion</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-base font-medium transition">Créer un compte</a>
                @endif
            @endauth
        </div>
    </nav>
    <main class="flex-1 flex items-center justify-center pt-24 pb-12">
        <div class="max-w-4xl w-full mx-auto flex flex-col md:flex-row items-center gap-12 px-4">
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight">
                    Gérez votre établissement scolaire<br>
                    <span class="text-indigo-600 dark:text-indigo-400">simplement et intelligemment</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                    EduSmart vous aide à piloter inscriptions, classes, enseignants, élèves, notes et bien plus, dans une interface moderne et intuitive.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold text-lg shadow-lg hover:from-indigo-600 hover:to-purple-700 transition">Se connecter</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 rounded-lg bg-white dark:bg-gray-900 border border-indigo-500 text-indigo-700 dark:text-indigo-300 font-bold text-lg shadow hover:bg-indigo-50 dark:hover:bg-gray-800 transition">Créer un compte</a>
                    @endif
                </div>
            </div>
            <div class="flex-1 flex justify-center">
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80" alt="Éducation" class="rounded-2xl shadow-2xl w-full max-w-xs md:max-w-sm">
            </div>
        </div>
    </main>
    <footer class="w-full text-center py-6 text-gray-400 text-sm">
        &copy; {{ date('Y') }} EduSmart. Tous droits réservés.
    </footer>
</body>
</html> 