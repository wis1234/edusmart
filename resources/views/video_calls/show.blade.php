<x-app-layout>
    <div class="min-h-screen bg-gray-900 text-white">
        <!-- Header -->
        <div class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('video-calls.index') }}" class="text-gray-300 hover:text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-lg font-semibold">{{ $videoCall->title ?: 'Appel sans titre' }}</h1>
                            <p class="text-sm text-gray-400">
                                {{ $videoCall->type === 'video' ? 'Vidéo' : ($videoCall->type === 'audio' ? 'Audio' : 'Vidéo/Audio') }} • 
                                <span id="participants-count">{{ $videoCall->participants()->count() }}</span> participant(s)
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div id="call-timer" class="text-sm text-gray-300">00:00</div>
                        <div id="connection-status" class="flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm">Déconnecté</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Video Area -->
                <div class="lg:col-span-3">
                    <div class="bg-gray-800 rounded-lg p-4">
                        <!-- Video Grid -->
                        <div id="video-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-4 auto-rows-fr">
                            <!-- Local Video -->
                            <div class="relative bg-gray-700 rounded-lg overflow-hidden aspect-video shadow-lg flex items-center justify-center">
                                <video id="local-video" autoplay muted playsinline class="w-full h-full object-cover rounded-lg"></video>
                                <div class="absolute bottom-2 left-2 bg-black bg-opacity-60 px-3 py-1 rounded text-base font-semibold flex items-center space-x-2">
                                    <img src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('default-avatar.png') }}" class="w-7 h-7 rounded-full object-cover border-2 border-blue-500" alt="Votre photo">
                                    <span>Vous ({{ Auth::user()->name }})</span>
                                </div>
                                <div id="local-mute-indicator" class="absolute top-2 right-2 bg-red-500 rounded-full p-1 hidden">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Screen Share Area -->
                        <div id="screen-share-area" class="hidden mb-4">
                            <div class="relative bg-gray-700 rounded-lg overflow-hidden aspect-video">
                                <video id="screen-share-video" autoplay playsinline class="w-full h-full object-cover"></video>
                                <div class="absolute top-2 left-2 bg-black bg-opacity-50 px-2 py-1 rounded text-sm">
                                    Partage d'écran - <span id="screen-share-user"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <div class="flex items-center justify-center space-x-4">
                            <!-- Mute/Unmute -->
                            <button id="mute-btn" class="bg-gray-700 hover:bg-gray-600 p-3 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </button>

                            <!-- Video On/Off -->
                            <button id="video-btn" class="bg-gray-700 hover:bg-gray-600 p-3 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>

                            <!-- Screen Share -->
                            <button id="screen-share-btn" class="bg-gray-700 hover:bg-gray-600 p-3 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </button>

                            <!-- End Call -->
                            <button id="end-call-btn" class="bg-red-600 hover:bg-red-700 p-3 rounded-full transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Tabs -->
                    <div class="bg-gray-800 rounded-lg p-4 mb-4">
                        <div class="flex space-x-4 border-b border-gray-700">
                            <button id="participants-tab" class="tab-btn active px-3 py-2 text-sm font-medium text-white border-b-2 border-blue-500">
                                Participants
                            </button>
                            <button id="chat-tab" class="tab-btn px-3 py-2 text-sm font-medium text-gray-400 hover:text-white">
                                Chat
                            </button>
                            <button id="history-tab" class="tab-btn px-3 py-2 text-sm font-medium text-gray-400 hover:text-white truncate max-w-[100px]">
                                History
                            </button>
                        </div>
                    </div>

                    <!-- Participants Tab -->
                    <div id="participants-content" class="tab-content">
                        <div class="bg-gray-800 rounded-lg p-4 mb-4 shadow-lg">
                            <h3 class="text-lg font-semibold mb-3">Participants</h3>
                            <div id="participants-list" class="space-y-3">
                                <!-- Participants will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Chat Tab -->
                    <div id="chat-content" class="tab-content hidden">
                        <div class="bg-gray-800 rounded-lg p-4 h-96 flex flex-col">
                            <h3 class="text-lg font-semibold mb-3">Chat</h3>
                            <div id="chat-messages" class="flex-1 overflow-y-auto mb-3 space-y-2 min-h-0"></div>
                            <div class="flex items-center space-x-2 mt-auto w-full">
                                <input type="text" id="chat-input" placeholder="Tapez votre message..." class="flex-1 bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 text-sm max-w-full" style="min-width:0;" />
                                <button id="send-message-btn" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- History Tab -->
                    <div id="history-content" class="tab-content hidden">
                        <div class="bg-gray-800 rounded-lg p-4">
                            <h3 class="text-lg font-semibold mb-3">History</h3>
                            <div id="activities-list" class="h-64 overflow-y-auto space-y-2">
                                <!-- Activities will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration data for JavaScript -->
    <script>
        window.videoCallConfig = {
            signalServerUrl: 'https://edusmart-signal-server.onrender.com',
            roomId: '{{ $videoCall->room_id }}',
            userId: {{ Auth::id() }},
            userName: '{{ Auth::user()->name }}',
            isHost: {{ $isHost ? 'true' : 'false' }},
            csrfToken: '{{ csrf_token() }}',
            indexUrl: '{{ route("video-calls.index") }}',
            messagesUrl: '{{ route("video-calls.messages.index", $videoCall) }}',
            activitiesUrl: '{{ route("video-calls.activities.index", $videoCall) }}',
            recordActivityUrl: '{{ route("video-calls.activities.store", $videoCall) }}',
            callId: '{{ $videoCall->id }}'
        };
    </script>

    @push('scripts')
    <script src="https://cdn.socket.io/4.7.4/socket.io.min.js"></script>
    <script src="{{ asset('js/video-call.js') }}"></script>
    @endpush

    <!-- FontAwesome for user icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-papm6Q+..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Styles pour le chat */
        #chat-messages {
            scrollbar-width: thin;
            scrollbar-color: #4B5563 #1F2937;
        }
        
        #chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        #chat-messages::-webkit-scrollbar-track {
            background: #1F2937;
        }
        
        #chat-messages::-webkit-scrollbar-thumb {
            background: #4B5563;
            border-radius: 3px;
        }
        
        /* Voice wave animation */
        .voice-wave {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 3px;
            z-index: 20;
            height: 60px;
        }
        
        .voice-bar {
            width: 4px;
            height: 20px;
            background: linear-gradient(to top, #3B82F6, #60A5FA);
            border-radius: 2px;
            transition: height 0.1s ease, opacity 0.1s ease;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
        }
        
        .speaking .voice-bar {
            animation: none; /* Désactiver l'animation CSS, utiliser JavaScript */
        }
        
        /* Animation de base pour les barres inactives */
        .voice-bar:nth-child(1) { animation-delay: 0s; }
        .voice-bar:nth-child(2) { animation-delay: 0.1s; }
        .voice-bar:nth-child(3) { animation-delay: 0.2s; }
        .voice-bar:nth-child(4) { animation-delay: 0.3s; }
        .voice-bar:nth-child(5) { animation-delay: 0.4s; }
        
        /* Amélioration de l'effet speaking */
        .speaking {
            box-shadow: 0 0 0 3px #3b82f6, 0 0 20px 5px rgba(59, 130, 246, 0.3);
            border: 2px solid #3b82f6 !important;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        /* Animation de pulsation pour les barres actives */
        @keyframes voicePulse {
            0%, 100% { 
                transform: scaleY(1);
                opacity: 0.8;
            }
            50% { 
                transform: scaleY(1.1);
                opacity: 1;
            }
        }
        
        .speaking .voice-bar {
            animation: voicePulse 0.6s ease-in-out infinite;
        }
        
        /* Amélioration de la qualité vidéo */
        video {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
        }
        
        /* Optimisation pour les vidéos haute qualité */
        .aspect-video video {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        
        /* Focus mode */
        .focused {
            grid-column: span 2;
            grid-row: span 2;
        }
        
        /* Tab styles */
        .tab-btn.active {
            color: white;
            border-bottom-color: #3B82F6;
        }
        
        .tab-btn:not(.active) {
            color: #9CA3AF;
            border-bottom-color: transparent;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Video grid responsive */
        @media (max-width: 640px) {
            #video-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (min-width: 641px) and (max-width: 1024px) {
            #video-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1025px) {
            #video-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</x-app-layout> 