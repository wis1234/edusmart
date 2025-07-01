// WebRTC Configuration
const rtcConfig = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
        { urls: 'stun:stun2.l.google.com:19302' },
        { urls: 'stun:stun3.l.google.com:19302' },
        { urls: 'stun:stun4.l.google.com:19302' },
        {
            urls: 'turn:openrelay.metered.ca:80',
            username: 'openrelayproject',
            credential: 'openrelayproject'
        }
    ],
    iceCandidatePoolSize: 10,
    bundlePolicy: 'max-bundle',
    rtcpMuxPolicy: 'require',
    iceTransportPolicy: 'all'
};

// Configuration pour les contraintes vidéo haute qualité
const videoConstraints = {
    width: { ideal: 1920, min: 1280 },
    height: { ideal: 1080, min: 720 },
    frameRate: { ideal: 30, min: 24 },
    aspectRatio: { ideal: 16/9 },
    facingMode: 'user'
};

// Configuration pour les contraintes audio haute qualité
const audioConstraints = {
    echoCancellation: true,
    noiseSuppression: true,
    autoGainControl: true,
    sampleRate: { ideal: 48000 },
    channelCount: { ideal: 2 },
    latency: { ideal: 0.01 },
    googEchoCancellation: true,
    googAutoGainControl: true,
    googNoiseSuppression: true,
    googHighpassFilter: true,
    googTypingNoiseDetection: true,
    googAudioMirroring: false
};

// Global variables
let socket;
let localStream;
let screenStream;
let remoteStreams = {};
let peerConnections = {};
let isMuted = false;
let isVideoOff = false;
let isScreenSharing = false;
let callStartTime;
let callTimer;
let currentTab = 'participants';
let participants = {};
let focusedSocketId = null;
let manualFocusSocketId = null;
let screenShareStream;

// Initialize the application
async function init() {
    try {
        await getUserMedia();
        await connectToSignalServer();
        setupEventListeners();
        setupTabs();
        await loadMessages();
        await loadActivities();
        startCallTimer();
        updateLocalVideoBadges(); // Initialize badges
        
        // Initialize badges for all participants
        Object.entries(participants).forEach(([socketId, participant]) => {
            updateRemoteVideoBadges(socketId, participant);
        });
    } catch (error) {
        console.error('Failed to initialize:', error);
        alert('Erreur lors de l\'initialisation de l\'appel : ' + error.message + '\nVérifiez que votre caméra et micro sont bien branchés et autorisés.');
    }
}

// Get user media
async function getUserMedia() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({
            video: videoConstraints,
            audio: audioConstraints
        });
        
        const localVideo = document.getElementById('local-video');
        localVideo.srcObject = localStream;
        
        // Améliorer la qualité de rendu vidéo
        localVideo.style.imageRendering = 'crisp-edges';
        localVideo.style.objectFit = 'cover';
        
        // Optimisations supplémentaires pour la qualité
        localVideo.style.transform = 'translateZ(0)';
        localVideo.style.backfaceVisibility = 'hidden';
        localVideo.style.perspective = '1000px';
        
        // Add id to local video container for animation
        const localContainer = localVideo.closest('.relative');
        if (localContainer) localContainer.id = 'video-container-local';
        
        // Setup voice detection for local stream (focus mode)
        setupVoiceDetection('video-container-local', localStream, 'local');

        // Voice wave animation
        const waveDiv = document.createElement('div');
        waveDiv.className = 'voice-wave';
        for (let i = 0; i < 5; i++) { // Augmenté de 4 à 5 barres
            const bar = document.createElement('div');
            bar.className = 'voice-bar';
            waveDiv.appendChild(bar);
        }
        if (localContainer) localContainer.appendChild(waveDiv);

        // Add badges for local video
        setTimeout(() => {
            const videoActive = localStream.getVideoTracks().length > 0 && localStream.getVideoTracks()[0].enabled;
            const audioActive = localStream.getAudioTracks().length > 0 && localStream.getAudioTracks()[0].enabled;
            const badgeContainer = document.createElement('div');
            badgeContainer.className = 'absolute top-2 left-2 flex space-x-2 z-10';
            
            // Mic badge
            const micBadge = document.createElement('span');
            micBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs';
            if (audioActive) {
                micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
                micBadge.title = 'Microphone on';
            } else {
                micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
                micBadge.title = 'Microphone off';
            }
            badgeContainer.appendChild(micBadge);
            
            // Camera badge
            if (!videoActive) {
                const camBadge = document.createElement('span');
                camBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs ml-1';
                camBadge.innerHTML = `<i class="fas fa-video-slash text-yellow-400"></i>`;
                camBadge.title = 'Camera off';
                badgeContainer.appendChild(camBadge);
            }
            
            // Screen share badge (if sharing)
            if (window.isScreenSharing) {
                const screenBadge = document.createElement('span');
                screenBadge.className = 'inline-flex items-center justify-center rounded-full bg-blue-600 text-white px-2 py-1 text-xs';
                screenBadge.innerHTML = `<i class="fas fa-desktop"></i>`;
                screenBadge.title = 'Screen sharing';
                badgeContainer.appendChild(screenBadge);
            }
            
            // Pin/focus button for local video
            const pinBtn = document.createElement('button');
            pinBtn.className = 'ml-2 bg-gray-800 bg-opacity-80 hover:bg-blue-600 text-white rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-blue-400';
            pinBtn.title = manualFocusSocketId === 'local' ? 'Unpin' : 'Pin';
            pinBtn.innerHTML = `<i class="fas fa-thumbtack"></i>`;
            pinBtn.onclick = (e) => {
                e.stopPropagation();
                handlePin('local');
            };
            badgeContainer.appendChild(pinBtn);
            
            if (localContainer) localContainer.appendChild(badgeContainer);
        }, 500);
        
    } catch (error) {
        console.error('Error accessing media devices:', error);
        showMediaError(error);
        throw new Error('Impossible d\'accéder à la caméra et au microphone');
    }
}

// Connect to signal server
async function connectToSignalServer() {
    const config = window.videoCallConfig;
    
    try {
        socket = io(config.signalServerUrl, {
            path: '/socket.io',
            transports: ['websocket', 'polling'],
            auth: {
                token: config.csrfToken // Token simple pour l'authentification
            }
        });

    socket.on('connect', () => {
        console.log('Connected to signal server');
        updateConnectionStatus(true);
            
            // Rejoindre la salle avec les paramètres attendus par le serveur
            const user = window.currentUser || { name: 'User', profile_photo: null };
            socket.emit('join-room', config.roomId, user.name, user.profile_photo);
    });

        socket.on('participants-list', (data) => {
            console.log('Participants list received:', data);
            participants = {};
            data.forEach(p => {
                participants[p.socketId] = {
                    userName: p.userName,
                    profilePhoto: p.profilePhoto,
                    isMuted: p.isMuted || false,
                    isVideoOff: p.isVideoOff || false,
                    isScreenSharing: p.isScreenSharing || false,
                    isHost: false, // Le serveur ne gère pas les rôles
                    isSpeaking: false
                };
                // Correction : Créer une connexion WebRTC avec chaque autre participant
                if (p.socketId !== socket.id && !peerConnections[p.socketId]) {
                    createPeerConnection(p.socketId);
                }
            });
            renderParticipantsList();
            updateParticipantsCount();
        });

    socket.on('user-joined', (data) => {
        console.log('User joined:', data);
        // Ajouter le nouveau participant
        participants[data.socketId] = {
                userName: data.userName,
                profilePhoto: data.profilePhoto,
                isMuted: false,
                isVideoOff: false,
                isScreenSharing: false,
                isHost: false,
                isSpeaking: false
        };
        createPeerConnection(data.socketId);
        renderParticipantsList();
        updateParticipantsCount();
        
        // Add system message
        addMessage({
            user: { name: 'System' },
                message: `${data.userName} has joined the call`,
            timestamp: new Date().toISOString()
        });
    });

    socket.on('user-left', (data) => {
        console.log('User left:', data);
        if (participants[data.socketId]) {
            const userName = participants[data.socketId].userName;
            delete participants[data.socketId];
            removeRemoteVideo(data.socketId);
            renderParticipantsList();
            updateParticipantsCount();
            
            // Add system message
            addMessage({
                user: { name: 'System' },
                message: `${userName} has left the call`,
                timestamp: new Date().toISOString()
            });
        }
    });

    // Gestion des messages reçus
    socket.on('chat-message', (data) => {
        console.log('Chat message received:', data);
        addMessage({
            user: { name: data.fromName || 'User' },
            message: data.message,
            timestamp: new Date().toISOString()
        });
    });

        socket.on('signal', async (data) => {
            console.log('Signal received:', data);
            
            switch (data.type) {
                case 'offer':
        await handleOffer(data);
                    break;
                case 'answer':
        await handleAnswer(data);
                    break;
                case 'ice-candidate':
        await handleIceCandidate(data);
                    break;
                default:
                    console.warn('Unknown signal type:', data.type);
            }
    });

    // Gestion du partage d'écran
    socket.on('screen-share-started', (data) => {
        console.log('Screen share started:', data);
            if (participants[data.socketId]) {
                participants[data.socketId].isScreenSharing = true;
            renderParticipantsList();
                updateRemoteVideoBadges(data.socketId, participants[data.socketId]);
        }
    });

    socket.on('screen-share-stopped', (data) => {
        console.log('Screen share stopped:', data);
            if (participants[data.socketId]) {
                participants[data.socketId].isScreenSharing = false;
            renderParticipantsList();
                updateRemoteVideoBadges(data.socketId, participants[data.socketId]);
        }
    });

    socket.on('disconnect', (reason) => {
        console.log('Disconnected from signal server:', reason);
        updateConnectionStatus(false);
        
        if (reason === 'io server disconnect') {
            console.log('Server disconnected client, attempting to reconnect...');
            socket.connect();
        }
    });

    socket.on('connect_error', (error) => {
        console.error('Connection error:', error);
        updateConnectionStatus(false);
        
        const errorMessage = document.getElementById('connection-error');
        if (errorMessage) {
            errorMessage.textContent = `Connection error: ${error.message}`;
            errorMessage.classList.remove('hidden');
        }
    });

    socket.on('reconnect', (attemptNumber) => {
        console.log('Reconnected to signal server after', attemptNumber, 'attempts');
        updateConnectionStatus(true);
        
        const errorMessage = document.getElementById('connection-error');
        if (errorMessage) {
            errorMessage.classList.add('hidden');
        }
        
        // Rejoindre la salle après reconnexion
            const user = window.currentUser || { name: 'User', profile_photo: null };
            socket.emit('join-room', config.roomId, user.name, user.profile_photo);
    });

    socket.on('reconnect_error', (error) => {
        console.error('Reconnection error:', error);
        updateConnectionStatus(false);
    });

    socket.on('reconnect_failed', () => {
        console.error('Failed to reconnect to signal server');
        updateConnectionStatus(false);
        alert('Impossible de se reconnecter au serveur. Veuillez rafraîchir la page.');
    });

    } catch (error) {
        console.error('Failed to connect to signal server:', error);
        throw new Error('Impossible de se connecter au serveur de signalisation');
    }
}

// Setup event listeners
function setupEventListeners() {
    // Control buttons
    document.getElementById('mute-btn').addEventListener('click', toggleMute);
    document.getElementById('video-btn').addEventListener('click', toggleVideo);
    document.getElementById('screen-share-btn').addEventListener('click', toggleScreenShare);
    document.getElementById('end-call-btn').addEventListener('click', endCall);
    
    // Chat input
    const chatInput = document.getElementById('chat-input');
    const sendMessageBtn = document.getElementById('send-message-btn');
    
    sendMessageBtn.addEventListener('click', sendMessage);
    
    // Send message on Enter key
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Auto-resize chat input
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
}

// Setup tabs
function setupTabs() {
    const tabs = ['participants', 'chat', 'history'];
    
    tabs.forEach(tab => {
        const tabBtn = document.getElementById(`${tab}-tab`);
        const tabContent = document.getElementById(`${tab}-content`);
        
        if (tabBtn && tabContent) {
            tabBtn.addEventListener('click', () => {
            switchTab(tab);
        });
        }
    });
}

// Switch tab
function switchTab(tabName) {
    const tabs = ['participants', 'chat', 'history'];
    
    // Update tab buttons
    tabs.forEach(tab => {
        const tabBtn = document.getElementById(`${tab}-tab`);
        const tabContent = document.getElementById(`${tab}-content`);
        
        if (tabBtn && tabContent) {
            if (tab === tabName) {
                tabBtn.classList.add('active');
                tabBtn.classList.remove('text-gray-400');
                tabBtn.classList.add('text-white');
                tabBtn.classList.add('border-blue-500');
                tabBtn.classList.remove('border-transparent');
                tabContent.classList.remove('hidden');
                tabContent.classList.add('active');
            } else {
                tabBtn.classList.remove('active');
                tabBtn.classList.add('text-gray-400');
                tabBtn.classList.remove('text-white');
                tabBtn.classList.remove('border-blue-500');
                tabBtn.classList.add('border-transparent');
                tabContent.classList.add('hidden');
                tabContent.classList.remove('active');
            }
        }
    });
    
    currentTab = tabName;
}

// Toggle mute/unmute
function toggleMute() {
    isMuted = !isMuted;
    
    if (localStream) {
        localStream.getAudioTracks().forEach(track => {
            track.enabled = !isMuted;
        });
    }
    
    // Update UI
            const muteBtn = document.getElementById('mute-btn');
    const muteIndicator = document.getElementById('local-mute-indicator');
    
            if (isMuted) {
        muteBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path></svg>';
        muteIndicator.classList.remove('hidden');
            } else {
        muteBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>';
        muteIndicator.classList.add('hidden');
            }
            
    // Notify server
    socket.emit('update-status', window.videoCallConfig.roomId, { isMuted });
            
    // Update local participant
    if (participants[socket.id]) {
        participants[socket.id].isMuted = isMuted;
        renderParticipantsList();
    }
}

// Update local video badges
function updateLocalVideoBadges() {
    const localContainer = document.getElementById('video-container-local');
    if (!localContainer) return;
    
    // Supprimer les anciens badges
    const existingBadges = localContainer.querySelector('.badge-container');
    if (existingBadges) {
        existingBadges.remove();
    }
    
    // Créer les nouveaux badges
    const badgeContainer = document.createElement('div');
    badgeContainer.className = 'absolute top-2 left-2 flex space-x-2 z-10 badge-container';
    
    // Badge microphone
    const micBadge = document.createElement('span');
    micBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs';
    if (isMuted) {
        micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
        micBadge.title = 'Microphone off';
    } else {
        micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
        micBadge.title = 'Microphone on';
    }
    badgeContainer.appendChild(micBadge);
    
    // Badge caméra
    if (isVideoOff) {
        const camBadge = document.createElement('span');
        camBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs ml-1';
        camBadge.innerHTML = `<i class="fas fa-video-slash text-yellow-400"></i>`;
        camBadge.title = 'Camera off';
        badgeContainer.appendChild(camBadge);
    }
    
    // Badge partage d'écran
    if (isScreenSharing) {
        const screenBadge = document.createElement('span');
        screenBadge.className = 'inline-flex items-center justify-center rounded-full bg-blue-600 text-white px-2 py-1 text-xs';
        screenBadge.innerHTML = `<i class="fas fa-desktop"></i>`;
        screenBadge.title = 'Screen sharing';
        badgeContainer.appendChild(screenBadge);
    }
    
    // Bouton pin/focus
    const pinBtn = document.createElement('button');
    pinBtn.className = 'ml-2 bg-gray-800 bg-opacity-80 hover:bg-blue-600 text-white rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-blue-400';
    pinBtn.title = manualFocusSocketId === 'local' ? 'Unpin' : 'Pin';
    pinBtn.innerHTML = `<i class="fas fa-thumbtack"></i>`;
    pinBtn.onclick = (e) => {
        e.stopPropagation();
        handlePin('local');
    };
    badgeContainer.appendChild(pinBtn);
    
    localContainer.appendChild(badgeContainer);
}

// Toggle video on/off
function toggleVideo() {
    isVideoOff = !isVideoOff;
    
    if (localStream) {
        localStream.getVideoTracks().forEach(track => {
            track.enabled = !isVideoOff;
        });
    }
    
    // Update UI
            const videoBtn = document.getElementById('video-btn');
    
            if (isVideoOff) {
        videoBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path></svg>';
            } else {
        videoBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
            }
            
    // Notify server
    socket.emit('update-status', window.videoCallConfig.roomId, { isVideoOff });
            
    // Update local participant
    if (participants[socket.id]) {
        participants[socket.id].isVideoOff = isVideoOff;
        renderParticipantsList();
    }
}

// Toggle screen sharing
async function toggleScreenShare() {
    if (isScreenSharing) {
        stopScreenSharing();
    } else {
        await startScreenSharing();
    }
}

async function startScreenSharing() {
    try {
        screenStream = await navigator.mediaDevices.getDisplayMedia({
            video: {
                cursor: 'always',
                displaySurface: 'monitor'
            },
            audio: false
        });

        // Replace video track in all peer connections
        const videoTrack = screenStream.getVideoTracks()[0];
        Object.values(peerConnections).forEach(pc => {
            const sender = pc.getSenders().find(s => s.track && s.track.kind === 'video');
            if (sender) {
                sender.replaceTrack(videoTrack);
            }
        });

        isScreenSharing = true;
        
        // Notify server
        socket.emit('screen-share-start', window.videoCallConfig.roomId);

        // Update UI
        const screenShareBtn = document.getElementById('screen-share-btn');
        screenShareBtn.classList.add('bg-blue-600');
        screenShareBtn.classList.remove('bg-gray-700');
        
        // Show screen share area
        const screenShareArea = document.getElementById('screen-share-area');
        const screenShareVideo = document.getElementById('screen-share-video');
        screenShareArea.classList.remove('hidden');
        screenShareVideo.srcObject = screenStream;

        // Handle screen share stop
        videoTrack.onended = () => {
            stopScreenSharing();
        };

    } catch (error) {
        console.error('Error starting screen share:', error);
        alert('Impossible de démarrer le partage d\'écran');
    }
}

function stopScreenSharing() {
    if (screenStream) {
        screenStream.getTracks().forEach(track => track.stop());
        screenStream = null;
    }

    // Restore original video track
    if (localStream) {
        const videoTrack = localStream.getVideoTracks()[0];
        Object.values(peerConnections).forEach(pc => {
            const sender = pc.getSenders().find(s => s.track && s.track.kind === 'video');
            if (sender && videoTrack) {
                sender.replaceTrack(videoTrack);
            }
        });
    }

    isScreenSharing = false;
    
    // Notify server
    socket.emit('screen-share-stop', window.videoCallConfig.roomId);

    // Update UI
    const screenShareBtn = document.getElementById('screen-share-btn');
    screenShareBtn.classList.remove('bg-blue-600');
    screenShareBtn.classList.add('bg-gray-700');
    
    // Hide screen share area
    const screenShareArea = document.getElementById('screen-share-area');
    screenShareArea.classList.add('hidden');
}

// End call
function endCall() {
    // Stop all media streams
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
    }
    if (screenStream) {
        screenStream.getTracks().forEach(track => track.stop());
    }
    
    // Close all peer connections
    Object.values(peerConnections).forEach(pc => pc.close());
    peerConnections = {};
    remoteStreams = {};
    
    // Disconnect from signal server
    if (socket) {
        socket.disconnect();
    }
    
    // Record activity
    recordActivity('call_ended');
    
    // Redirect to video calls index
    window.location.href = window.videoCallConfig.indexUrl;
}

// Start call timer
function startCallTimer() {
    callStartTime = Date.now();
    callTimer = setInterval(updateCallTimer, 1000);
}

// Update call timer
function updateCallTimer() {
    const elapsed = Math.floor((Date.now() - callStartTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    document.getElementById('call-timer').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Update connection status
function updateConnectionStatus(connected) {
    const statusElement = document.getElementById('connection-status');
    if (statusElement) {
    const indicator = statusElement.querySelector('div');
    const text = statusElement.querySelector('span');
    
    if (connected) {
            indicator.classList.remove('bg-red-500');
            indicator.classList.add('bg-green-500');
        text.textContent = 'Connected';
    } else {
            indicator.classList.remove('bg-green-500');
            indicator.classList.add('bg-red-500');
        text.textContent = 'Disconnected';
        }
    }
}

// Load messages
async function loadMessages() {
    if (!window.videoCallConfig.callId) {
        console.error('videoCallConfig.callId is undefined!');
        addMessage({
            user: { name: window.videoCallConfig.userName },
            message: 'Error: Video call ID missing.',
            timestamp: new Date().toISOString()
        });
        return;
    }
    try {
        const response = await fetch(`/video-calls/${window.videoCallConfig.callId}/messages`);
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
        }
        const data = await response.json();
        console.log('Messages API response:', data); // Debug
        
        // Handle different response formats
        let messages = data;
        if (data && typeof data === 'object' && data.data) {
            messages = data.data; // Laravel pagination format
        } else if (Array.isArray(data)) {
            messages = data; // Direct array
        } else {
            console.error('Unexpected messages response format:', data);
            messages = [];
        }
        
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.innerHTML = '';
            if (messages.length === 0) {
                addMessage({
                    user: { name: 'System' },
                    message: 'Welcome to the chat! Type your message below.',
                    timestamp: new Date().toISOString()
                });
            } else {
                messages.forEach(msg => {
                    const userName = msg.user ? 
                        [msg.user.first_name, msg.user.last_name].filter(Boolean).join(' ').trim() || 'User' : 
                        'User';
                    addMessage({
                        user: { name: userName },
                        message: msg.message,
                        timestamp: msg.created_at
                    });
                });
            }
        }
    } catch (error) {
        console.error('Error loading messages:', error);
        addMessage({
            user: { name: 'System' },
            message: `Error loading messages: ${error.message}`,
            timestamp: new Date().toISOString()
        });
    }
}

// Load activities
async function loadActivities() {
    if (!window.videoCallConfig.callId) {
        console.error('videoCallConfig.callId is undefined!');
        return;
    }
    try {
        const response = await fetch(`/video-calls/${window.videoCallConfig.callId}/activities`);
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
        }
        const activities = await response.json();
        const activitiesContainer = document.getElementById('activities-list');
        if (activitiesContainer) {
            activitiesContainer.innerHTML = '';
            
            if (activities.length === 0) {
                const noActivityElement = document.createElement('div');
                noActivityElement.className = 'text-gray-400 text-center py-4';
                noActivityElement.textContent = 'No activities yet';
                activitiesContainer.appendChild(noActivityElement);
            } else {
                activities.forEach(activity => addActivity(activity));
            }
        }
    } catch (error) {
        console.error('Error loading activities:', error);
        const activitiesContainer = document.getElementById('activities-list');
        if (activitiesContainer) {
            activitiesContainer.innerHTML = `
                <div class="text-red-400 text-center py-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Error loading history: ${error.message}
                </div>
            `;
        }
    }
}

// Send message
async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;

    // Ajouter le message localement immédiatement
    addMessage({
        user: { name: window.videoCallConfig.userName },
        message,
        timestamp: new Date().toISOString()
    });

    // Envoyer au serveur de signalisation (WebRTC)
    if (socket) {
        socket.emit('chat-message', {
            roomId: window.videoCallConfig.roomId,
            message
        });
    }

    // Sauvegarder le message côté serveur Laravel
    try {
        const response = await fetch(`/video-calls/${window.videoCallConfig.callId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.videoCallConfig.csrfToken
            },
            body: JSON.stringify({ message })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
        }
        
        const savedMessage = await response.json();
        console.log('Message saved successfully:', savedMessage);
    } catch (error) {
        console.error('Error saving message:', error);
        // Afficher l'erreur dans le chat
        addMessage({
            user: { name: 'System' },
            message: `Error saving message: ${error.message}`,
            timestamp: new Date().toISOString()
        });
    }

    input.value = '';
}

// Add message to chat
function addMessage(message) {
    const chatMessages = document.getElementById('chat-messages');
    const messageElement = document.createElement('div');
    messageElement.className = 'bg-gray-700 rounded px-3 py-2 text-sm text-white break-words max-w-full mb-1';
    // Si message est un objet, on affiche le texte, le nom et l'heure
    let userName = message.user && message.user.name ? message.user.name : 'User';
    let text = typeof message === 'string' ? message : message.message;
    let time = message.timestamp ? new Date(message.timestamp).toLocaleTimeString() : '';
    messageElement.innerHTML = `<span class="font-semibold text-blue-300 mr-2">${userName}</span> <span>${text}</span> <span class="text-xs text-gray-400 float-right ml-2">${time}</span>`;
    chatMessages.appendChild(messageElement);
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Record activity
async function recordActivity(action, metadata = {}) {
    if (!window.videoCallConfig.callId) {
        console.error('videoCallConfig.callId is undefined!');
        return;
    }
    try {
        const response = await fetch(`/video-calls/${window.videoCallConfig.callId}/activities`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.videoCallConfig.csrfToken
            },
            body: JSON.stringify({ action, metadata })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
        }
        
        const activity = await response.json();
        addActivity(activity);
    } catch (error) {
        console.error('Error recording activity:', error);
        // Display error in history
        const activitiesContainer = document.getElementById('activities-list');
        if (activitiesContainer) {
            const errorElement = document.createElement('div');
            errorElement.className = 'text-red-400 text-sm p-2 bg-red-900 bg-opacity-20 rounded mb-2';
            errorElement.innerHTML = `
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Error recording activity: ${error.message}
            `;
            activitiesContainer.insertBefore(errorElement, activitiesContainer.firstChild);
        }
    }
}

// Add activity to list
function addActivity(activity) {
    const activitiesContainer = document.getElementById('activities-list');
    if (!activitiesContainer) return;
    
    const activityElement = document.createElement('div');
    activityElement.className = 'flex items-center space-x-2 p-2 bg-gray-700 rounded text-sm mb-2';
    
    const icon = document.createElement('i');
    icon.className = 'fas fa-circle text-blue-400 text-xs flex-shrink-0';
    
    const text = document.createElement('span');
    text.className = 'text-gray-300 flex-1 min-w-0';
    
    // Build text with user name
    let userName = 'User';
    if (activity.user && activity.user.first_name) {
        userName = activity.user.first_name;
        if (activity.user.last_name) {
            userName += ' ' + activity.user.last_name;
        }
    }
    
    // Action translations in English
    const actionTranslations = {
        'joined': 'joined the call',
        'left': 'left the call',
        'muted': 'muted their microphone',
        'unmuted': 'unmuted their microphone',
        'video_off': 'turned off their camera',
        'video_on': 'turned on their camera',
        'screen_share_started': 'started screen sharing',
        'screen_share_stopped': 'stopped screen sharing',
        'call_ended': 'ended the call'
    };
    
    const actionText = actionTranslations[activity.action] || activity.action;
    text.textContent = `${userName} ${actionText}`;
    
    // Add time if available
    if (activity.created_at) {
        const timeElement = document.createElement('span');
        timeElement.className = 'text-xs text-gray-500 flex-shrink-0';
        timeElement.textContent = new Date(activity.created_at).toLocaleTimeString();
        activityElement.appendChild(timeElement);
    }
    
    activityElement.appendChild(icon);
    activityElement.appendChild(text);
    
    // Add to beginning of list (most recent at top)
    activitiesContainer.insertBefore(activityElement, activitiesContainer.firstChild);
}

function renderParticipantsList() {
    const participantsList = document.getElementById('participants-list');
    participantsList.innerHTML = '';
    Object.entries(participants).forEach(([socketId, participant]) => {
        const participantElement = document.createElement('div');
        participantElement.id = `participant-${socketId}`;
        participantElement.className = 'flex items-center space-x-3 p-2 bg-gray-700 rounded shadow';
        
        let avatarHtml;
        if (participant && participant.profilePhoto) {
            avatarHtml = `<img src="${participant.profilePhoto}" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500" alt="Profile photo">`;
        } else {
            avatarHtml = `<span class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-600 border-2 border-blue-500 text-white text-2xl"><i class='fas fa-user-circle'></i></span>`;
        }
        
        // Status badges
        let statusBadges = '';
        if (participant.isMuted) {
            statusBadges += '<i class="fas fa-microphone-slash text-red-500 ml-1" title="Microphone off"></i>';
        }
        if (participant.isVideoOff) {
            statusBadges += '<i class="fas fa-video-slash text-yellow-400 ml-1" title="Camera off"></i>';
        }
        if (participant.isScreenSharing) {
            statusBadges += '<i class="fas fa-desktop text-blue-400 ml-1" title="Screen sharing"></i>';
        }
        
        participantElement.innerHTML = `
            ${avatarHtml}
            <div class="flex-1 min-w-0">
                <div class="text-base font-semibold text-gray-100">${participant && participant.userName ? participant.userName : 'Unknown'}</div>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                ${statusBadges}
            </div>
        `;
        participantsList.appendChild(participantElement);
    });
    updateParticipantsCount();
}

// Update participants count
function updateParticipantsCount() {
    const count = Object.keys(participants).length + 1; // +1 for local user
    const countElement = document.getElementById('participants-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

// Create peer connection
function createPeerConnection(socketId) {
    const peerConnection = new RTCPeerConnection(rtcConfig);
    peerConnections[socketId] = peerConnection;
    
    if (localStream) {
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });
    }
    
    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            socket.emit('signal', {
                roomId: window.videoCallConfig.roomId,
                type: 'ice-candidate',
                candidate: event.candidate,
                to: socketId
            });
        }
    };
    
    peerConnection.ontrack = (event) => {
        const remoteStream = event.streams[0];
        remoteStreams[socketId] = remoteStream;
        const participant = participants[socketId];
        addRemoteVideo(socketId, remoteStream, participant);
    };
    
    peerConnection.createOffer()
        .then(offer => peerConnection.setLocalDescription(offer))
        .then(() => {
            socket.emit('signal', {
                roomId: window.videoCallConfig.roomId,
                type: 'offer',
                offer: peerConnection.localDescription,
                to: socketId
            });
        })
        .catch(error => console.error('Error creating offer:', error));
}

// Handle incoming offer
async function handleOffer(data) {
    const peerConnection = new RTCPeerConnection(rtcConfig);
    peerConnections[data.from] = peerConnection;
    
    if (localStream) {
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });
    }
    
    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            socket.emit('signal', {
                roomId: window.videoCallConfig.roomId,
                type: 'ice-candidate',
                candidate: event.candidate,
                to: data.from
            });
        }
    };
    
    peerConnection.ontrack = (event) => {
        const remoteStream = event.streams[0];
        remoteStreams[data.from] = remoteStream;
        const participant = participants[data.from];
        addRemoteVideo(data.from, remoteStream, participant);
    };
    
    await peerConnection.setRemoteDescription(data.offer);
    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);
    
    socket.emit('signal', {
        roomId: window.videoCallConfig.roomId,
        type: 'answer',
        answer: answer,
        to: data.from
    });
}

// Handle incoming answer
async function handleAnswer(data) {
    const peerConnection = peerConnections[data.from];
    if (peerConnection) {
        await peerConnection.setRemoteDescription(data.answer);
    }
}

// Handle ICE candidate
async function handleIceCandidate(data) {
    const peerConnection = peerConnections[data.from];
    if (peerConnection) {
        await peerConnection.addIceCandidate(data.candidate);
    }
}

// Add remote video with badges, wave, and pin
function addRemoteVideo(socketId, stream, participant) {
    const videoGrid = document.getElementById('video-grid');
    const videoContainer = document.createElement('div');
    videoContainer.className = 'relative bg-gray-700 rounded-lg overflow-hidden aspect-video video-container';
    videoContainer.id = `video-container-${socketId}`;

    // Show video if active
    let videoActive = false;
    let audioActive = false;
    if (stream) {
        const videoTracks = stream.getVideoTracks();
        const audioTracks = stream.getAudioTracks();
        videoActive = videoTracks.length > 0 && videoTracks[0].enabled;
        audioActive = audioTracks.length > 0 && audioTracks[0].enabled;
    }

    if (videoActive) {
        const videoElement = document.createElement('video');
        videoElement.id = `remote-video-${socketId}`;
        videoElement.autoplay = true;
        videoElement.playsinline = true;
        videoElement.className = 'w-full h-full object-cover';
        videoElement.srcObject = stream;
        videoElement.muted = false;
        
        // Optimisations de qualité pour les vidéos distantes
        videoElement.style.imageRendering = 'crisp-edges';
        videoElement.style.transform = 'translateZ(0)';
        videoElement.style.backfaceVisibility = 'hidden';
        videoElement.style.perspective = '1000px';
        
        videoContainer.appendChild(videoElement);
    } else {
        // Show FontAwesome avatar if no profile photo
        if (participant && participant.profilePhoto) {
            const img = document.createElement('img');
            img.src = participant.profilePhoto;
            img.className = 'w-full h-full object-cover';
            videoContainer.appendChild(img);
        } else {
            const avatarDiv = document.createElement('div');
            avatarDiv.className = 'w-full h-full flex items-center justify-center bg-gray-600';
            avatarDiv.innerHTML = `<i class='fas fa-user-circle text-7xl text-gray-400'></i>`;
            videoContainer.appendChild(avatarDiv);
        }
    }

    // Voice wave animation
    const waveDiv = document.createElement('div');
    waveDiv.className = 'voice-wave';
    for (let i = 0; i < 5; i++) { // 5 barres pour tous les participants
        const bar = document.createElement('div');
        bar.className = 'voice-bar';
        waveDiv.appendChild(bar);
    }
    videoContainer.appendChild(waveDiv);

    // Badges container
    const badgeContainer = document.createElement('div');
    badgeContainer.className = 'absolute top-2 left-2 flex space-x-2 z-10';

    // Mic badge
    const micBadge = document.createElement('span');
    micBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs cursor-pointer hover:bg-opacity-80';
    micBadge.onclick = (e) => {
        e.stopPropagation();
        toggleRemoteAudio(socketId);
    };
    
    // Check if local user has muted this participant
    const videoElement = document.getElementById(`remote-video-${socketId}`);
    const isLocallyMuted = videoElement && videoElement.muted;
    
    if (isLocallyMuted) {
        micBadge.innerHTML = `<i class="fas fa-volume-mute text-red-500"></i>`;
        micBadge.title = 'Audio muted (click to unmute)';
    } else if (!participant.isMuted) {
        micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
        micBadge.title = 'Microphone on (click to mute)';
    } else {
        micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
        micBadge.title = 'Microphone off (click to mute)';
    }
    badgeContainer.appendChild(micBadge);

    // Camera badge
    if (participant.isVideoOff) {
        const camBadge = document.createElement('span');
        camBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs';
        camBadge.innerHTML = `<i class="fas fa-video-slash text-yellow-400"></i>`;
        camBadge.title = 'Camera off';
        badgeContainer.appendChild(camBadge);
    }

    // Screen share badge
    if (participant.isScreenSharing) {
        const screenBadge = document.createElement('span');
        screenBadge.className = 'inline-flex items-center justify-center rounded-full bg-blue-600 text-white px-2 py-1 text-xs';
        screenBadge.innerHTML = `<i class="fas fa-desktop"></i>`;
        screenBadge.title = 'Screen sharing';
        badgeContainer.appendChild(screenBadge);
    }

    // Host badge
    if (participant.isHost) {
        const hostBadge = document.createElement('span');
        hostBadge.className = 'inline-flex items-center justify-center rounded-full bg-green-600 text-white px-2 py-1 text-xs';
        hostBadge.innerHTML = `<i class="fas fa-crown"></i>`;
        hostBadge.title = 'Host';
        badgeContainer.appendChild(hostBadge);
    }

    // Speaking indicator
    if (participant.isSpeaking) {
        videoContainer.classList.add('speaking');
    } else {
        videoContainer.classList.remove('speaking');
    }

    // Pin/focus button
    const pinBtn = document.createElement('button');
    pinBtn.className = 'ml-2 bg-gray-800 bg-opacity-80 hover:bg-blue-600 text-white rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-blue-400 pin-button';
    pinBtn.setAttribute('data-socket-id', socketId);
    pinBtn.title = manualFocusSocketId === socketId ? 'Unpin' : 'Pin';
    pinBtn.innerHTML = `<i class="fas fa-thumbtack"></i>`;
    pinBtn.onclick = (e) => {
        e.stopPropagation();
        handlePin(socketId);
    };
    badgeContainer.appendChild(pinBtn);

    videoContainer.appendChild(badgeContainer);

    // Add participant name
    const nameDiv = document.createElement('div');
    nameDiv.className = 'absolute bottom-2 left-2 bg-black bg-opacity-60 text-white px-2 py-1 rounded text-sm';
    nameDiv.textContent = participant ? participant.userName : 'Unknown';
    videoContainer.appendChild(nameDiv);

    // Add to grid
    videoGrid.appendChild(videoContainer);

    // Setup voice detection for this remote stream
    if (stream && stream.getAudioTracks().length > 0) {
        setupVoiceDetection(`video-container-${socketId}`, stream, socketId);
    }

    // Update badges based on current participant status
    updateRemoteVideoBadges(socketId, participant);
}

// Remove remote video
function removeRemoteVideo(socketId) {
    const videoElement = document.getElementById(`remote-video-${socketId}`);
    if (videoElement) {
        const container = videoElement.closest('.relative');
        if (container) {
            container.remove();
        }
    }
}

// Show media error
function showMediaError(error) {
    console.error('Media error:', error);
    const errorMessage = error.name === 'NotAllowedError' 
        ? 'Accès à la caméra/microphone refusé. Veuillez autoriser l\'accès et recharger la page.'
        : 'Erreur d\'accès aux périphériques média. Vérifiez que votre caméra et microphone sont connectés.';
    alert(errorMessage);
}

// Setup voice detection for a video container
function setupVoiceDetection(containerId, stream, socketId) {
    if (!stream || !stream.getAudioTracks().length) return;
    
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const analyser = audioContext.createAnalyser();
    const microphone = audioContext.createMediaStreamSource(stream);
    
    analyser.smoothingTimeConstant = 0.8;
    analyser.fftSize = 1024;
    
    microphone.connect(analyser);
    
    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);
    
    let isSpeaking = false;
    let speakingStartTime = 0;
    const SPEAKING_THRESHOLD = 30;
    const SPEAKING_DURATION = 1000; // 1 second
    
    function checkVolume() {
        analyser.getByteFrequencyData(dataArray);
        
        // Calculate average volume
        let sum = 0;
        for (let i = 0; i < bufferLength; i++) {
            sum += dataArray[i];
        }
        const average = sum / bufferLength;
        
        // Check if speaking
        const wasSpeaking = isSpeaking;
        if (average > SPEAKING_THRESHOLD) {
            if (!isSpeaking) {
                isSpeaking = true;
                speakingStartTime = Date.now();
            }
        } else {
            if (isSpeaking && (Date.now() - speakingStartTime) > SPEAKING_DURATION) {
                isSpeaking = false;
            }
        }
        
        // Update voice wave animation
        const container = document.getElementById(containerId);
        if (container) {
            const waveDiv = container.querySelector('.voice-wave');
            if (waveDiv) {
                const bars = waveDiv.querySelectorAll('.voice-bar');
                bars.forEach((bar, index) => {
                    const barHeight = isSpeaking ? Math.min(100, average * (index + 1) / 10) : 0;
                    bar.style.height = `${barHeight}%`;
                    bar.style.backgroundColor = isSpeaking ? '#3B82F6' : '#6B7280';
                });
            }
        }
        
        // Auto-focus on speaker (if not manually pinned)
        if (isSpeaking && !wasSpeaking && manualFocusSocketId === null) {
            setFocusMode(socketId);
            
            // Envoyer la mise à jour de statut au serveur
            if (socketId !== 'local' && socket) {
                socket.emit('update-status', {
                    roomId: window.videoCallConfig.roomId,
                    isMuted: isMuted,
                    isVideoOff: isVideoOff,
                    isScreenSharing: isScreenSharing,
                    isSpeaking: true
                });
            }
        } else if (!isSpeaking && wasSpeaking) {
            // Envoyer la mise à jour de statut au serveur
            if (socketId !== 'local' && socket) {
                socket.emit('update-status', {
                    roomId: window.videoCallConfig.roomId,
                    isMuted: isMuted,
                    isVideoOff: isVideoOff,
                    isScreenSharing: isScreenSharing,
                    isSpeaking: false
                });
            }
        }
        
        requestAnimationFrame(checkVolume);
    }
    
    checkVolume();
    
    // Cleanup function
    return () => {
        analyser.disconnect();
        microphone.disconnect();
        audioContext.close();
    };
}

// Set focus mode for a specific video
function setFocusMode(socketId) {
    if (focusedSocketId === socketId) return;
    
    focusedSocketId = socketId;
    
    // Remove focus from all videos
    const allVideos = document.querySelectorAll('.video-container');
    allVideos.forEach(container => {
        container.classList.remove('focused');
        container.classList.add('unfocused');
    });
    
    // Add focus to selected video
    const targetContainer = socketId === 'local' 
        ? document.getElementById('video-container-local')
        : document.getElementById(`video-container-${socketId}`);
    
    if (targetContainer) {
        targetContainer.classList.remove('unfocused');
        targetContainer.classList.add('focused');
        
        // Scroll to focused video if needed
        targetContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Update pin buttons
    updatePinButtons();
}

// Handle pin/unpin video
function handlePin(socketId) {
    if (manualFocusSocketId === socketId) {
        // Unpin
        manualFocusSocketId = null;
        focusedSocketId = null;
        
        // Remove focus from all videos
        const allVideos = document.querySelectorAll('.video-container');
        allVideos.forEach(container => {
            container.classList.remove('focused', 'unfocused');
        });
    } else {
        // Pin
        manualFocusSocketId = socketId;
        setFocusMode(socketId);
    }
    
    updatePinButtons();
}

// Update pin buttons
function updatePinButtons() {
    const allPinButtons = document.querySelectorAll('.pin-button');
    allPinButtons.forEach(btn => {
        const btnSocketId = btn.getAttribute('data-socket-id');
        if (btnSocketId === manualFocusSocketId) {
            btn.classList.add('bg-blue-600');
            btn.classList.remove('bg-gray-800');
            btn.title = 'Unpin';
        } else {
            btn.classList.remove('bg-blue-600');
            btn.classList.add('bg-gray-800');
            btn.title = 'Pin';
        }
    });
}

// Helper to re-render all remote videos
function renderAllRemoteVideos() {
    const videoGrid = document.getElementById('video-grid');
    // Remove all except the local video
    Array.from(videoGrid.children).forEach(child => {
        if (!child.querySelector('#local-video')) {
            child.remove();
        }
    });
    Object.entries(remoteStreams).forEach(([socketId, stream]) => {
        const participant = participants[socketId];
        if (participant) {
            addRemoteVideo(socketId, stream, participant);
        }
    });
}

// Update remote video badges
function updateRemoteVideoBadges(socketId, participant) {
    const videoContainer = document.getElementById(`video-container-${socketId}`);
    if (!videoContainer) return;
    
    const badgeContainer = videoContainer.querySelector('.absolute.top-2.left-2');
    if (!badgeContainer) return;
    
    // Clear existing badges (except pin button)
    const pinButton = badgeContainer.querySelector('.pin-button');
    badgeContainer.innerHTML = '';
    if (pinButton) badgeContainer.appendChild(pinButton);
    
    // Mic badge
    const micBadge = document.createElement('span');
    micBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs cursor-pointer hover:bg-opacity-80';
    micBadge.onclick = (e) => {
        e.stopPropagation();
        toggleRemoteAudio(socketId);
    };
    
    const videoElement = document.getElementById(`remote-video-${socketId}`);
    const isLocallyMuted = videoElement && videoElement.muted;
    
    if (isLocallyMuted) {
        micBadge.innerHTML = `<i class="fas fa-volume-mute text-red-500"></i>`;
        micBadge.title = 'Audio muted (click to unmute)';
    } else if (!participant.isMuted) {
        micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
        micBadge.title = 'Microphone on (click to mute)';
    } else {
        micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
        micBadge.title = 'Microphone off (click to mute)';
    }
    badgeContainer.appendChild(micBadge);
    
    // Camera badge
    if (participant.isVideoOff) {
        const camBadge = document.createElement('span');
        camBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs';
        camBadge.innerHTML = `<i class="fas fa-video-slash text-yellow-400"></i>`;
        camBadge.title = 'Camera off';
        badgeContainer.appendChild(camBadge);
    }
    
    // Screen share badge
    if (participant.isScreenSharing) {
        const screenBadge = document.createElement('span');
        screenBadge.className = 'inline-flex items-center justify-center rounded-full bg-blue-600 text-white px-2 py-1 text-xs';
        screenBadge.innerHTML = `<i class="fas fa-desktop"></i>`;
        screenBadge.title = 'Screen sharing';
        badgeContainer.appendChild(screenBadge);
    }
    
    // Host badge
    if (participant.isHost) {
        const hostBadge = document.createElement('span');
        hostBadge.className = 'inline-flex items-center justify-center rounded-full bg-green-600 text-white px-2 py-1 text-xs';
        hostBadge.innerHTML = `<i class="fas fa-crown"></i>`;
        hostBadge.title = 'Host';
        badgeContainer.appendChild(hostBadge);
    }
    
    // Speaking indicator
    if (participant.isSpeaking) {
        videoContainer.classList.add('speaking');
    } else {
        videoContainer.classList.remove('speaking');
    }
}

// Toggle remote audio mute/unmute
function toggleRemoteAudio(socketId) {
    const videoElement = document.getElementById(`remote-video-${socketId}`);
    if (videoElement) {
        videoElement.muted = !videoElement.muted;
        updateRemoteVideoBadges(socketId, participants[socketId]);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', init);