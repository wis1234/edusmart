<x-app-layout>
    <div class="min-h-screen bg-gray-900 text-white">
        <!-- Header - Super Responsive -->
        <div class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                <div class="flex items-center justify-between h-12 sm:h-16">
                    <!-- Left side - Back button and title -->
                    <div class="flex items-center min-w-0 flex-1">
                        <a href="{{ route('video-calls.index') }}" class="text-gray-300 hover:text-white mr-2 sm:mr-4 flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-sm sm:text-lg font-semibold truncate">{{ $videoCall->title ?: 'Appel sans titre' }}</h1>
                            <p class="text-xs sm:text-sm text-gray-400 truncate">
                                {{ $videoCall->type === 'video' ? 'Vidéo' : ($videoCall->type === 'audio' ? 'Audio' : 'Vidéo/Audio') }} • 
                                <span id="participants-count">{{ $videoCall->participants()->count() }}</span> participant(s)
                            </p>
                        </div>
                    </div>
                    
                    <!-- Right side - Connection Status -->
                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                        <!-- Connection Status -->
                        <div id="connection-status" class="flex items-center space-x-1 sm:space-x-2">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-red-500 rounded-full"></div>
                            <span class="text-xs sm:text-sm text-gray-300 hidden sm:inline">Déconnecté</span>
                        </div>
                        
                        <!-- Connection Error -->
                        <div id="connection-error" class="text-xs sm:text-sm text-red-400 hidden max-w-32 sm:max-w-none truncate"></div>
                        
                        <!-- Call Timer -->
                        <div class="flex items-center space-x-1 sm:space-x-2">
                            <i class="fas fa-clock text-gray-400 text-xs sm:text-sm"></i>
                            <span id="call-timer" class="text-xs sm:text-sm text-gray-300">00:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content - Super Responsive -->
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-2 sm:py-4 lg:py-6">
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-2 sm:gap-4 lg:gap-6">
                <!-- Video Area -->
                <div class="xl:col-span-3 order-2 xl:order-1">
                    <div class="bg-gray-800 rounded-lg p-2 sm:p-4">
                        <!-- Video Grid -->
                        <div id="video-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 lg:gap-6 mb-2 sm:mb-4 auto-rows-fr">
                            <!-- Local Video -->
                            <div class="relative bg-gray-700 rounded-lg overflow-hidden aspect-video shadow-lg flex items-center justify-center">
                                <video id="local-video" autoplay muted playsinline class="w-full h-full object-cover rounded-lg"></video>
                                <div class="absolute bottom-1 sm:bottom-2 left-1 sm:left-2 bg-black bg-opacity-60 px-2 sm:px-3 py-1 rounded text-xs sm:text-sm font-semibold flex items-center space-x-1 sm:space-x-2">
                                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('default-avatar.png') }}" class="w-5 h-5 sm:w-7 sm:h-7 rounded-full object-cover border-2 border-blue-500" alt="Votre photo">
                                    <span class="hidden sm:inline">Vous ({{ Auth::user()->name }})</span>
                                    <span class="sm:hidden">Vous</span>
                                </div>
                                <div id="local-mute-indicator" class="absolute top-1 sm:top-2 right-1 sm:right-2 bg-red-500 rounded-full p-1 hidden">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Screen Share Area -->
                        <div id="screen-share-area" class="hidden mb-2 sm:mb-4">
                            <div class="relative bg-gray-700 rounded-lg overflow-hidden aspect-video">
                                <video id="screen-share-video" autoplay playsinline class="w-full h-full object-cover"></video>
                                <div class="absolute top-1 sm:top-2 left-1 sm:left-2 bg-black bg-opacity-50 px-2 py-1 rounded text-xs sm:text-sm">
                                    Partage d'écran - <span id="screen-share-user"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Controls - Super Responsive -->
                        <div class="flex items-center justify-center space-x-2 sm:space-x-4">
                            <!-- Mute/Unmute -->
                            <button id="mute-btn" class="bg-gray-700 hover:bg-gray-600 p-2 sm:p-3 rounded-full transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </button>

                            <!-- Video On/Off -->
                            <button id="video-btn" class="bg-gray-700 hover:bg-gray-600 p-2 sm:p-3 rounded-full transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>

                            <!-- Screen Share -->
                            <button id="screen-share-btn" class="bg-gray-700 hover:bg-gray-600 p-2 sm:p-3 rounded-full transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </button>

                            <!-- End Call -->
                            <button id="end-call-btn" class="bg-red-600 hover:bg-red-700 p-2 sm:p-3 rounded-full transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Super Responsive -->
                <div class="xl:col-span-1 order-1 xl:order-2">
                    <!-- Tabs - Mobile First -->
                    <div class="bg-gray-800 rounded-lg p-2 sm:p-4 mb-2 sm:mb-4">
                        <div class="flex space-x-1 sm:space-x-4 border-b border-gray-700 overflow-x-auto">
                            <button id="participants-tab" class="tab-btn active px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium text-white border-b-2 border-blue-500 whitespace-nowrap flex-shrink-0">
                                <i class="fas fa-users mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Participants</span>
                                <span class="sm:hidden">Part.</span>
                            </button>
                            <button id="chat-tab" class="tab-btn px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium text-gray-400 hover:text-white whitespace-nowrap flex-shrink-0">
                                <i class="fas fa-comments mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Chat</span>
                                <span class="sm:hidden">Chat</span>
                            </button>
                            <button id="history-tab" class="tab-btn px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium text-gray-400 hover:text-white whitespace-nowrap flex-shrink-0">
                                <i class="fas fa-history mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">History</span>
                                <span class="sm:hidden">Hist.</span>
                            </button>
                        </div>
                    </div>

                    <!-- Participants Tab -->
                    <div id="participants-content" class="tab-content">
                        <div class="bg-gray-800 rounded-lg p-2 sm:p-4 mb-2 sm:mb-4 shadow-lg">
                            <h3 class="text-sm sm:text-lg font-semibold mb-2 sm:mb-3 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-400"></i>
                                <span class="hidden sm:inline">Participants</span>
                                <span class="sm:hidden">Part.</span>
                            </h3>
                            <div id="participants-list" class="space-y-2 sm:space-y-3">
                                <!-- Participants will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Chat Tab -->
                    <div id="chat-content" class="tab-content hidden">
                        <div class="bg-gray-800 rounded-lg p-2 sm:p-4 h-64 sm:h-80 lg:h-96 flex flex-col">
                            <h3 class="text-sm sm:text-lg font-semibold mb-2 sm:mb-3 flex items-center">
                                <i class="fas fa-comments mr-2 text-blue-400"></i>
                                <span class="hidden sm:inline">Chat</span>
                                <span class="sm:hidden">Chat</span>
                            </h3>
                            <div id="chat-messages" class="flex-1 overflow-y-auto mb-2 sm:mb-3 space-y-1 sm:space-y-2 min-h-0"></div>
                            <div class="flex items-center space-x-1 sm:space-x-2 mt-auto w-full">
                                <input type="text" id="chat-input" placeholder="Message..." class="flex-1 bg-gray-700 border border-gray-600 rounded px-2 sm:px-3 py-1 sm:py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 text-xs sm:text-sm max-w-full" style="min-width:0;" />
                                <button id="send-message-btn" class="bg-blue-600 hover:bg-blue-700 px-2 sm:px-3 py-1 sm:py-2 rounded transition-colors flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- History Tab -->
                    <div id="history-content" class="tab-content hidden">
                        <div class="bg-gray-800 rounded-lg p-2 sm:p-4 h-64 sm:h-80 lg:h-96 flex flex-col">
                            <h3 class="text-sm sm:text-lg font-semibold mb-2 sm:mb-3 flex items-center">
                                <i class="fas fa-history mr-2 text-blue-400"></i>
                                <span class="hidden sm:inline">History</span>
                                <span class="sm:hidden">Hist.</span>
                            </h3>
                            <div id="activities-list" class="flex-1 overflow-y-auto space-y-1 sm:space-y-2 min-h-0">
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
            signalServerUrl: 'https://node-whatsapp-1.onrender.com',
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
        
        // Données utilisateur pour le serveur de signalisation
        window.currentUser = {
            name: '{{ Auth::user()->name }}',
            profile_photo: '{{ Auth::user()->profile_photo_url }}'
        };
    </script>

    @push('scripts')
    <script src="https://cdn.socket.io/4.7.4/socket.io.min.js"></script>
    <script src="{{ asset('js/video-call.js') }}"></script>
    @endpush

    <!-- FontAwesome for user icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-papm6Q+..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Responsive Design Improvements */
        
        /* Mobile First Approach */
        @media (max-width: 640px) {
            .min-h-screen {
                min-height: 100vh;
            }
            
            /* Compact header for mobile */
            .h-12 {
                height: 3rem;
            }
            
            /* Smaller text and spacing */
            .text-xs {
                font-size: 0.75rem;
                line-height: 1rem;
            }
            
            /* Compact controls */
            .p-2 {
                padding: 0.5rem;
            }
            
            /* Smaller video grid */
            #video-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            /* Compact sidebar */
            .h-64 {
                height: 16rem;
            }
        }
        
        /* Tablet Optimizations */
        @media (min-width: 641px) and (max-width: 1024px) {
            #video-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .h-80 {
                height: 20rem;
            }
        }
        
        /* Desktop Optimizations */
        @media (min-width: 1025px) {
            #video-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .h-96 {
                height: 24rem;
            }
        }
        
        /* Extra Large Screens */
        @media (min-width: 1280px) {
            .xl\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
            
            .xl\:col-span-3 {
                grid-column: span 3 / span 3;
            }
            
            .xl\:col-span-1 {
                grid-column: span 1 / span 1;
            }
        }
        
        /* Chat and History Responsive */
        #chat-messages, #activities-list {
            scrollbar-width: thin;
            scrollbar-color: #4B5563 #1F2937;
        }
        
        #chat-messages::-webkit-scrollbar,
        #activities-list::-webkit-scrollbar {
            width: 4px;
        }
        
        #chat-messages::-webkit-scrollbar-track,
        #activities-list::-webkit-scrollbar-track {
            background: #1F2937;
        }
        
        #chat-messages::-webkit-scrollbar-thumb,
        #activities-list::-webkit-scrollbar-thumb {
            background: #4B5563;
            border-radius: 2px;
        }
        
        /* Voice wave animation - Responsive */
        .voice-wave {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 2px;
            z-index: 20;
            height: 40px;
        }
        
        @media (min-width: 640px) {
            .voice-wave {
                bottom: 60px;
                gap: 3px;
                height: 60px;
            }
        }
        
        .voice-bar {
            width: 3px;
            height: 15px;
            background: linear-gradient(to top, #3B82F6, #60A5FA);
            border-radius: 2px;
            transition: height 0.1s ease, opacity 0.1s ease;
            box-shadow: 0 0 6px rgba(59, 130, 246, 0.5);
        }
        
        @media (min-width: 640px) {
            .voice-bar {
                width: 4px;
                height: 20px;
                box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
            }
        }
        
        /* Tab styles - Responsive */
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
        
        /* Video quality optimizations */
        video {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
        }
        
        /* Focus mode responsive */
        .focused {
            grid-column: span 1;
            grid-row: span 1;
        }
        
        @media (min-width: 640px) {
            .focused {
                grid-column: span 2;
                grid-row: span 2;
            }
        }
        
        /* Participant list responsive */
        #participants-list .flex {
            padding: 0.5rem;
        }
        
        @media (min-width: 640px) {
            #participants-list .flex {
                padding: 0.75rem;
            }
        }
        
        /* Input responsive */
        #chat-input {
            font-size: 0.75rem;
        }
        
        @media (min-width: 640px) {
            #chat-input {
                font-size: 0.875rem;
            }
        }
        
        /* Button responsive */
        button {
            transition: all 0.2s ease;
        }
        
        button:active {
            transform: scale(0.95);
        }
        
        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Error states */
        .error {
            border-color: #EF4444;
            background-color: rgba(239, 68, 68, 0.1);
        }
        
        /* Success states */
        .success {
            border-color: #10B981;
            background-color: rgba(16, 185, 129, 0.1);
        }
    </style>
</x-app-layout> 