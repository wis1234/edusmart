// WebRTC Configuration
const rtcConfig = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
        { urls: 'stun:stun2.l.google.com:19302' },
        { urls: 'stun:stun3.l.google.com:19302' },
        { urls: 'stun:stun4.l.google.com:19302' }
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
            auth: {
                token: config.csrfToken
            },
            transports: ['websocket', 'polling'],
            reconnectionAttempts: 5,
            reconnectionDelay: 1000
        });
    } catch (e) {
        alert('Impossible de se connecter au serveur de signalisation. Veuillez réessayer plus tard.');
        throw e;
    }

    socket.on('connect', () => {
        console.log('Connected to signal server');
        updateConnectionStatus(true);
        // Rejoindre la salle avec le nom d'utilisateur et la photo de profil
        socket.emit('join-room', config.roomId, config.userName, config.userProfilePhoto || null);
        recordActivity('joined');
    });

    socket.on('participants-list', (data) => {
        console.log('Participants list received:', data);
        participants = {};
        data.forEach(p => {
            participants[p.socketId] = {
                userName: p.userName,
                profilePhoto: p.profilePhoto,
                isMuted: p.isMuted,
                isVideoOff: p.isVideoOff,
                isScreenSharing: p.isScreenSharing
            };
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
            isScreenSharing: false
        };
        createPeerConnection(data.socketId);
        renderParticipantsList();
        updateParticipantsCount();
    });

    socket.on('signal', async (data) => {
        console.log('Signal received:', data);
        const { type, from, ...signalData } = data;
        
        switch (type) {
            case 'offer':
                await handleOffer({ fromSocketId: from, offer: signalData.offer });
                break;
            case 'answer':
                await handleAnswer({ fromSocketId: from, answer: signalData.answer });
                break;
            case 'ice-candidate':
                await handleIceCandidate({ fromSocketId: from, candidate: signalData.candidate });
                break;
        }
    });

    socket.on('screen-share-started', (data) => {
        console.log('Screen share started:', data);
        showScreenShare(data);
        if (participants[data.socketId]) {
            participants[data.socketId].isScreenSharing = true;
            renderParticipantsList();
        }
    });

    socket.on('screen-share-stopped', (data) => {
        console.log('Screen share stopped:', data);
        hideScreenShare();
        if (participants[data.socketId]) {
            participants[data.socketId].isScreenSharing = false;
            renderParticipantsList();
        }
    });

    socket.on('disconnect', () => {
        console.log('Disconnected from signal server');
        updateConnectionStatus(false);
    });

    socket.on('connect_error', (error) => {
        console.error('Connection error:', error);
        updateConnectionStatus(false);
    });
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
    if (localStream) {
        const audioTrack = localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = !audioTrack.enabled;
            isMuted = !audioTrack.enabled;
            
            // Update button appearance
            const muteBtn = document.getElementById('mute-btn');
            if (isMuted) {
                muteBtn.classList.add('bg-red-600');
                muteBtn.classList.remove('bg-gray-700');
                muteBtn.innerHTML = `<i class="fas fa-microphone-slash"></i>`;
            } else {
                muteBtn.classList.remove('bg-red-600');
                muteBtn.classList.add('bg-gray-700');
                muteBtn.innerHTML = `<i class="fas fa-microphone"></i>`;
            }
            
            // Update local video badge
            updateLocalVideoBadges();
            
            // Send status update to server
            socket.emit('update-status', window.videoCallConfig.roomId, { isMuted: isMuted });
            
            // Record activity
            recordActivity(isMuted ? 'muted' : 'unmuted');
        }
    }
}

// Update local video badges
function updateLocalVideoBadges() {
    const localContainer = document.getElementById('video-container-local');
    if (!localContainer) return;
    
    // Remove existing badge container
    const existingBadgeContainer = localContainer.querySelector('.absolute.top-2.left-2');
    if (existingBadgeContainer) {
        existingBadgeContainer.remove();
    }
    
    // Create new badge container
    const badgeContainer = document.createElement('div');
    badgeContainer.className = 'absolute top-2 left-2 flex space-x-2 z-10';
    
    // Get current stream status
    const videoActive = localStream && localStream.getVideoTracks().length > 0 && localStream.getVideoTracks()[0].enabled;
    const audioActive = localStream && localStream.getAudioTracks().length > 0 && localStream.getAudioTracks()[0].enabled;
    
    // Mic badge
    const micBadge = document.createElement('span');
    micBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs';
    if (audioActive && !isMuted) {
        micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
        micBadge.title = 'Microphone on';
    } else {
        micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
        micBadge.title = 'Microphone off';
    }
    badgeContainer.appendChild(micBadge);
    
    // Camera badge
    if (!videoActive || isVideoOff) {
        const camBadge = document.createElement('span');
        camBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs ml-1';
        camBadge.innerHTML = `<i class="fas fa-video-slash text-yellow-400"></i>`;
        camBadge.title = 'Camera off';
        badgeContainer.appendChild(camBadge);
    }
    
    // Screen share badge (if sharing)
    if (isScreenSharing) {
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
    
    localContainer.appendChild(badgeContainer);
}

// Toggle video on/off
function toggleVideo() {
    if (localStream) {
        const videoTrack = localStream.getVideoTracks()[0];
        if (videoTrack) {
            videoTrack.enabled = !videoTrack.enabled;
            isVideoOff = !videoTrack.enabled;
            
            // Update button appearance
            const videoBtn = document.getElementById('video-btn');
            if (isVideoOff) {
                videoBtn.classList.add('bg-red-600');
                videoBtn.classList.remove('bg-gray-700');
                videoBtn.innerHTML = `<i class="fas fa-video-slash"></i>`;
            } else {
                videoBtn.classList.remove('bg-red-600');
                videoBtn.classList.add('bg-gray-700');
                videoBtn.innerHTML = `<i class="fas fa-video"></i>`;
            }
            
            // Update local video badge
            updateLocalVideoBadges();
            
            // Send status update to server
            socket.emit('update-status', window.videoCallConfig.roomId, { isVideoOff: isVideoOff });
            
            // Record activity
            recordActivity(isVideoOff ? 'video_off' : 'video_on');
        }
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

// Start screen sharing
async function startScreenSharing() {
    try {
        const screenStream = await navigator.mediaDevices.getDisplayMedia({
            video: {
                cursor: 'always',
                displaySurface: 'monitor'
            },
            audio: false
        });
        
        // Notify server about screen sharing start
        socket.emit('screen-share-start', window.videoCallConfig.roomId);

        // Replace video track in all peer connections
        const videoTrack = screenStream.getVideoTracks()[0];
        Object.values(peerConnections).forEach(pc => {
            const sender = pc.getSenders().find(s => s.track && s.track.kind === 'video');
            if (sender) {
                sender.replaceTrack(videoTrack);
            }
        });

        // Store screen stream
        screenShareStream = screenStream;
        isScreenSharing = true;

        // Update UI
        const screenShareBtn = document.getElementById('screen-share-btn');
        screenShareBtn.classList.add('bg-red-600');
        screenShareBtn.classList.remove('bg-gray-600');
        screenShareBtn.innerHTML = '<i class="fas fa-stop"></i>';

        // Handle screen share stop
        videoTrack.onended = () => {
            stopScreenSharing();
        };

        recordActivity('screen_share_started');
    } catch (error) {
        console.error('Error starting screen share:', error);
        alert('Impossible de démarrer le partage d\'écran. Veuillez réessayer.');
    }
}

// Stop screen sharing
function stopScreenSharing() {
    if (screenShareStream) {
        screenShareStream.getTracks().forEach(track => track.stop());
        screenShareStream = null;
    }

    // Notify server about screen sharing stop
    socket.emit('screen-share-stop', window.videoCallConfig.roomId);

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

    // Update UI
    const screenShareBtn = document.getElementById('screen-share-btn');
    screenShareBtn.classList.remove('bg-red-600');
    screenShareBtn.classList.add('bg-gray-600');
    screenShareBtn.innerHTML = '<i class="fas fa-desktop"></i>';

    recordActivity('screen_share_stopped');
}

// End call
function endCall() {
    // Stop all media streams
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
    }
    if (screenShareStream) {
        screenShareStream.getTracks().forEach(track => track.stop());
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
        text.textContent = 'Connecté';
    } else {
            indicator.classList.remove('bg-green-500');
            indicator.classList.add('bg-red-500');
        text.textContent = 'Déconnecté';
        }
    }
}

// Load messages
async function loadMessages() {
    if (!window.videoCallConfig.callId) {
        console.error('videoCallConfig.callId is undefined!');
        addMessage({
            user: { name: window.videoCallConfig.userName },
            message: 'Erreur: ID de l\'appel vidéo manquant.',
            timestamp: new Date().toISOString()
        });
        return;
    }
    try {
        const response = await fetch(`/video-calls/${window.videoCallConfig.callId}/messages`);
        if (!response.ok) throw new Error(`HTTP error ${response.status}`);
        const messages = await response.json();
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) chatMessages.innerHTML = '';
        if (messages.length === 0) {
            addMessage({
                user: { name: 'Système' },
                message: 'Bienvenue dans le chat ! Tapez votre message ci-dessous.',
                timestamp: new Date().toISOString()
            });
        } else {
            messages.forEach(msg => addMessage({
                user: { name: msg.user?.name || 'Utilisateur' },
                message: msg.message,
                timestamp: msg.created_at
            }));
        }
    } catch (error) {
        console.error('Error loading messages:', error);
        addMessage({
            user: { name: window.videoCallConfig.userName },
            message: 'Erreur lors du chargement des messages.',
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
        if (!response.ok) throw new Error(`HTTP error ${response.status}`);
        const activities = await response.json();
        const activitiesContainer = document.getElementById('activities-list');
        if (activitiesContainer) activitiesContainer.innerHTML = '';
        activities.forEach(activity => addActivity(activity));
    } catch (error) {
        console.error('Error loading activities:', error);
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
    socket.emit('chat-message', {
        roomId: window.videoCallConfig.roomId,
        message
    });

    input.value = '';
}

// Add message to chat
function addMessage(message) {
    const chatMessages = document.getElementById('chat-messages');
    const messageElement = document.createElement('div');
    messageElement.className = 'bg-gray-700 rounded px-3 py-2 text-sm text-white break-words max-w-full mb-1';
    // Si message est un objet, on affiche le texte, le nom et l'heure
    let userName = message.user && message.user.name ? message.user.name : 'Utilisateur';
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
        await fetch(`/video-calls/${window.videoCallConfig.callId}/activities`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.videoCallConfig.csrfToken
            },
            body: JSON.stringify({ action, metadata })
        });
    } catch (error) {
        console.error('Error recording activity:', error);
    }
}

// Add activity to list
function addActivity(activity) {
    const activitiesContainer = document.getElementById('activities-list');
    const activityElement = document.createElement('div');
    activityElement.className = 'flex items-center space-x-2 p-2 bg-gray-700 rounded text-sm';
    
    const icon = document.createElement('i');
    icon.className = 'fas fa-circle text-blue-400 text-xs';
    
    const text = document.createElement('span');
    text.className = 'text-gray-300';
    text.textContent = activity.description || activity.action;
    
    activityElement.appendChild(icon);
    activityElement.appendChild(text);
    
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
                type: 'ice-candidate',
                roomId: window.videoCallConfig.roomId,
                candidate: event.candidate
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
                type: 'offer',
                roomId: window.videoCallConfig.roomId,
                offer: peerConnection.localDescription
            });
        })
        .catch(error => console.error('Error creating offer:', error));
}

// Handle incoming offer
async function handleOffer(data) {
    const peerConnection = new RTCPeerConnection(rtcConfig);
    peerConnections[data.fromSocketId] = peerConnection;
    
    if (localStream) {
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });
    }
    
    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            socket.emit('signal', {
                type: 'ice-candidate',
                roomId: window.videoCallConfig.roomId,
                candidate: event.candidate
            });
        }
    };
    
    peerConnection.ontrack = (event) => {
        const remoteStream = event.streams[0];
        remoteStreams[data.fromSocketId] = remoteStream;
        const participant = participants[data.fromSocketId];
        addRemoteVideo(data.fromSocketId, remoteStream, participant);
    };
    
    await peerConnection.setRemoteDescription(data.offer);
    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);
    
    socket.emit('signal', {
        type: 'answer',
        roomId: window.videoCallConfig.roomId,
        answer: answer
    });
}

// Handle incoming answer
async function handleAnswer(data) {
    const peerConnection = peerConnections[data.fromSocketId];
    if (peerConnection) {
        await peerConnection.setRemoteDescription(data.answer);
    }
}

// Handle ICE candidate
async function handleIceCandidate(data) {
    const peerConnection = peerConnections[data.fromSocketId];
    if (peerConnection) {
        await peerConnection.addIceCandidate(data.candidate);
    }
}

// Add remote video with badges, wave, and pin
function addRemoteVideo(socketId, stream, participant) {
    const videoGrid = document.getElementById('video-grid');
    const videoContainer = document.createElement('div');
    videoContainer.className = 'relative bg-gray-700 rounded-lg overflow-hidden aspect-video';
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
    } else if (audioActive && !participant.isMuted) {
        micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
        micBadge.title = 'Microphone on (click to mute)';
    } else {
        micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
        micBadge.title = 'Microphone off (click to mute)';
    }
    badgeContainer.appendChild(micBadge);

    // Camera badge
    if (!videoActive || participant.isVideoOff) {
        const camBadge = document.createElement('span');
        camBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs ml-1';
        camBadge.innerHTML = `<i class="fas fa-video-slash text-yellow-400"></i>`;
        camBadge.title = 'Camera off';
        badgeContainer.appendChild(camBadge);
    }

    // Screen share badge (if user is sharing screen)
    if (participant && participant.isScreenSharing) {
        const screenBadge = document.createElement('span');
        screenBadge.className = 'inline-flex items-center justify-center rounded-full bg-blue-600 text-white px-2 py-1 text-xs';
        screenBadge.innerHTML = `<i class="fas fa-desktop"></i>`;
        screenBadge.title = 'Screen sharing';
        badgeContainer.appendChild(screenBadge);
    }

    // Host badge
    if (participant && participant.isHost) {
        const hostBadge = document.createElement('span');
        hostBadge.className = 'inline-flex items-center justify-center rounded-full bg-yellow-600 text-white px-2 py-1 text-xs';
        hostBadge.innerHTML = `<i class="fas fa-crown"></i>`;
        hostBadge.title = 'Host';
        badgeContainer.appendChild(hostBadge);
    }

    // Pin/focus button
    const pinBtn = document.createElement('button');
    pinBtn.className = 'ml-2 bg-gray-800 bg-opacity-80 hover:bg-blue-600 text-white rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-blue-400';
    pinBtn.title = manualFocusSocketId === socketId ? 'Unpin' : 'Pin';
    pinBtn.innerHTML = `<i class="fas fa-thumbtack"></i>`;
    pinBtn.onclick = (e) => {
        e.stopPropagation();
        handlePin(socketId);
    };
    badgeContainer.appendChild(pinBtn);

    videoContainer.appendChild(badgeContainer);

    // User name label
    const nameDiv = document.createElement('div');
    nameDiv.className = 'absolute bottom-2 left-2 bg-black bg-opacity-60 px-3 py-1 rounded text-base font-semibold flex items-center space-x-2';
    nameDiv.innerHTML = `<span>${participant && participant.userName ? participant.userName : 'Unknown'}</span>`;
    videoContainer.appendChild(nameDiv);

    videoGrid.appendChild(videoContainer);
    // Setup voice detection for remote stream (focus mode)
    setupVoiceDetection(`video-container-${socketId}`, stream, socketId);
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

// Voice activity detection and animation
function setupVoiceDetection(containerId, stream, socketId) {
    if (!stream) return;
    
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const analyser = audioContext.createAnalyser();
        const source = audioContext.createMediaStreamSource(stream);
        
        // Configuration améliorée de l'analyseur
        analyser.fftSize = 256; // Augmenté pour plus de précision
        analyser.smoothingTimeConstant = 0.8; // Lissage pour éviter les saccades
        analyser.minDecibels = -90;
        analyser.maxDecibels = -10;
        
        source.connect(analyser);
        
        const bufferLength = analyser.frequencyBinCount;
        const dataArray = new Uint8Array(bufferLength);
        const videoContainer = document.getElementById(containerId);
        
        if (!videoContainer) return;
        
        // Récupérer les barres d'onde
        const waveDiv = videoContainer.querySelector('.voice-wave');
        if (!waveDiv) return;
        
        const bars = waveDiv.querySelectorAll('.voice-bar');
        
        let lastFocus = 0;
        function checkVolume() {
            analyser.getByteFrequencyData(dataArray);
            
            // Calculer le volume moyen sur différentes fréquences
            let sum = 0;
            let count = 0;
            
            // Analyser les fréquences vocales (85Hz - 255Hz)
            const lowFreqStart = Math.floor(85 * bufferLength / audioContext.sampleRate);
            const lowFreqEnd = Math.floor(255 * bufferLength / audioContext.sampleRate);
            
            for (let i = lowFreqStart; i < lowFreqEnd && i < dataArray.length; i++) {
                sum += dataArray[i];
                count++;
            }
            
            const averageVolume = count > 0 ? sum / count : 0;
            const normalizedVolume = averageVolume / 255; // Normaliser entre 0 et 1
            
            // Seuil de détection ajusté
            const speakingThreshold = 0.15; // Réduit pour plus de sensibilité
            const isSpeaking = normalizedVolume > speakingThreshold;
            
            if (videoContainer) {
                if (isSpeaking) {
                    videoContainer.classList.add('speaking');
                    // Mode focus automatique : si ce participant parle, on le met en focus
                    const now = Date.now();
                    if (typeof handlePin === 'function' && (manualFocusSocketId !== socketId || now - lastFocus > 2000)) {
                        handlePin(socketId);
                        lastFocus = now;
                    }
                    // Animation des barres basée sur le volume réel
                    bars.forEach((bar, index) => {
                        const barVolume = Math.min(normalizedVolume * (1 + index * 0.2), 1);
                        const height = Math.max(20, barVolume * 60); // Hauteur entre 20px et 80px
                        bar.style.height = `${height}px`;
                        bar.style.opacity = barVolume;
                    });
                } else {
                    videoContainer.classList.remove('speaking');
                    // Réduire progressivement les barres
                    bars.forEach((bar, index) => {
                        const currentHeight = parseFloat(bar.style.height) || 20;
                        const newHeight = Math.max(20, currentHeight * 0.8);
                        bar.style.height = `${newHeight}px`;
                        bar.style.opacity = Math.max(0.3, newHeight / 60);
                    });
                }
            }
            requestAnimationFrame(checkVolume);
        }
        checkVolume();
    } catch (e) {
        console.error('Voice detection error:', e);
    }
}

// Only manual focus (pin) moves/enlarges a video
function setFocusMode(socketId) {
    const videoGrid = document.getElementById('video-grid');
    Array.from(videoGrid.children).forEach(child => {
        child.classList.remove('focused');
    });
    if (socketId) {
        const focusContainer = document.getElementById(`video-container-${socketId}`) || document.getElementById('video-container-local');
        if (focusContainer) {
            focusContainer.classList.add('focused');
            videoGrid.prepend(focusContainer);
        }
    }
}

function handlePin(socketId) {
    if (manualFocusSocketId === socketId) {
        manualFocusSocketId = null;
        setFocusMode(null);
    } else {
        manualFocusSocketId = socketId;
        setFocusMode(socketId);
    }
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
    
    // Remove existing badge container
    const existingBadgeContainer = videoContainer.querySelector('.absolute.top-2.left-2');
    if (existingBadgeContainer) {
        existingBadgeContainer.remove();
    }
    
    // Create new badge container
    const badgeContainer = document.createElement('div');
    badgeContainer.className = 'absolute top-2 left-2 flex space-x-2 z-10';
    
    // Get current stream status
    const stream = remoteStreams[socketId];
    let videoActive = false;
    let audioActive = false;
    if (stream) {
        const videoTracks = stream.getVideoTracks();
        const audioTracks = stream.getAudioTracks();
        videoActive = videoTracks.length > 0 && videoTracks[0].enabled;
        audioActive = audioTracks.length > 0 && audioTracks[0].enabled;
    }
    
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
    } else if (audioActive && !participant.isMuted) {
        micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
        micBadge.title = 'Microphone on (click to mute)';
    } else {
        micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
        micBadge.title = 'Microphone off (click to mute)';
    }
    badgeContainer.appendChild(micBadge);
    
    // Camera badge
    if (!videoActive || participant.isVideoOff) {
        const camBadge = document.createElement('span');
        camBadge.className = 'inline-flex items-center justify-center rounded-full bg-black bg-opacity-60 text-white px-2 py-1 text-xs ml-1';
        camBadge.innerHTML = `<i class="fas fa-video-slash text-yellow-400"></i>`;
        camBadge.title = 'Camera off';
        badgeContainer.appendChild(camBadge);
    }
    
    // Screen share badge (if user is sharing screen)
    if (participant && participant.isScreenSharing) {
        const screenBadge = document.createElement('span');
        screenBadge.className = 'inline-flex items-center justify-center rounded-full bg-blue-600 text-white px-2 py-1 text-xs';
        screenBadge.innerHTML = `<i class="fas fa-desktop"></i>`;
        screenBadge.title = 'Screen sharing';
        badgeContainer.appendChild(screenBadge);
    }
    
    // Host badge
    if (participant && participant.isHost) {
        const hostBadge = document.createElement('span');
        hostBadge.className = 'inline-flex items-center justify-center rounded-full bg-yellow-600 text-white px-2 py-1 text-xs';
        hostBadge.innerHTML = `<i class="fas fa-crown"></i>`;
        hostBadge.title = 'Host';
        badgeContainer.appendChild(hostBadge);
    }
    
    // Pin/focus button
    const pinBtn = document.createElement('button');
    pinBtn.className = 'ml-2 bg-gray-800 bg-opacity-80 hover:bg-blue-600 text-white rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-blue-400';
    pinBtn.title = manualFocusSocketId === socketId ? 'Unpin' : 'Pin';
    pinBtn.innerHTML = `<i class="fas fa-thumbtack"></i>`;
    pinBtn.onclick = (e) => {
        e.stopPropagation();
        handlePin(socketId);
    };
    badgeContainer.appendChild(pinBtn);
    
    videoContainer.appendChild(badgeContainer);
}

// Toggle remote audio
function toggleRemoteAudio(socketId) {
    const videoElement = document.getElementById(`remote-video-${socketId}`);
    if (videoElement) {
        videoElement.muted = !videoElement.muted;
        
        // Update badge to show audio state
        const videoContainer = document.getElementById(`video-container-${socketId}`);
        if (videoContainer) {
            const micBadge = videoContainer.querySelector('.absolute.top-2.left-2 .inline-flex:first-child');
            if (micBadge) {
                if (videoElement.muted) {
                    micBadge.innerHTML = `<i class="fas fa-volume-mute text-red-500"></i>`;
                    micBadge.title = 'Audio muted';
                } else {
                    const participant = participants[socketId];
                    if (participant && participant.isMuted) {
                        micBadge.innerHTML = `<i class="fas fa-microphone-slash text-red-500"></i>`;
                        micBadge.title = 'Microphone off';
                    } else {
                        micBadge.innerHTML = `<i class="fas fa-microphone"></i>`;
                        micBadge.title = 'Microphone on';
                    }
                }
            }
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', init); 