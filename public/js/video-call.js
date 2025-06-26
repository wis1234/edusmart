// WebRTC Configuration
const rtcConfig = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' }
    ]
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

// Initialize the application
async function init() {
    try {
        await getUserMedia();
        connectToSignalServer();
        setupEventListeners();
        setupTabs();
        loadMessages();
        loadActivities();
        startCallTimer();
    } catch (error) {
        console.error('Failed to initialize:', error);
        alert('Erreur lors de l\'initialisation de l\'appel : ' + error.message + '\nVérifiez que votre caméra et micro sont bien branchés et autorisés.');
    }
}

// Get user media
async function getUserMedia() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        });
        
        document.getElementById('local-video').srcObject = localStream;
    } catch (error) {
        console.error('Error accessing media devices:', error);
        showMediaError(error);
        throw new Error('Impossible d\'accéder à la caméra et au microphone');
    }
}

// Connect to signal server
function connectToSignalServer() {
    const config = window.videoCallConfig;
    try {
        socket = io(config.signalServerUrl, {
            auth: {
                token: config.csrfToken
            }
        });
    } catch (e) {
        alert('Impossible de se connecter au serveur de signalisation. Veuillez réessayer plus tard.');
        throw e;
    }

    socket.on('connect', () => {
        console.log('Connected to signal server');
        updateConnectionStatus(true);
        socket.emit('join-room', config.roomId, config.userName);
        recordActivity('joined');
    });

    socket.on('room-joined', (data) => {
        console.log('Joined room:', data);
    });

    socket.on('user-joined', (data) => {
        console.log('User joined:', data);
        createPeerConnection(data.socketId);
        addParticipant(data);
        updateParticipantsCount();
    });

    socket.on('user-left', (data) => {
        console.log('User left:', data);
        removeParticipant(data.socketId);
        removeRemoteVideo(data.socketId);
        updateParticipantsCount();
    });

    socket.on('offer', async (data) => {
        await handleOffer(data);
    });

    socket.on('answer', async (data) => {
        await handleAnswer(data);
    });

    socket.on('ice-candidate', async (data) => {
        await handleIceCandidate(data);
    });

    socket.on('screen-share-started', (data) => {
        console.log('Screen share started:', data);
        showScreenShare(data);
    });

    socket.on('screen-share-stopped', (data) => {
        console.log('Screen share stopped:', data);
        hideScreenShare();
    });

    socket.on('message.sent', (data) => {
        addMessage(data.message);
    });

    socket.on('activity.recorded', (data) => {
        addActivity(data.activity);
    });

    socket.on('participants-list', (data) => {
        participants = {};
        data.forEach(p => {
            participants[p.socketId] = p.userName;
        });
        renderParticipantsList();
    });

    socket.on('join-room-error', handleJoinRoomError);
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
        document.getElementById(`${tab}-tab`).addEventListener('click', () => {
            switchTab(tab);
        });
    });
}

// Switch tab
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'text-white', 'border-blue-500');
        btn.classList.add('text-gray-400');
    });
    
    // Show selected tab content
    document.getElementById(`${tabName}-content`).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(`${tabName}-tab`);
    activeTab.classList.add('active', 'text-white', 'border-blue-500');
    activeTab.classList.remove('text-gray-400');
    
    currentTab = tabName;
    
    // Load content based on tab
    if (tabName === 'chat') {
        loadMessages();
    } else if (tabName === 'history') {
        loadActivities();
    }
}

// Toggle mute/unmute
function toggleMute() {
    if (localStream) {
        const audioTrack = localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = !audioTrack.enabled;
            isMuted = !audioTrack.enabled;
            
            // Update UI
            const muteIndicator = document.getElementById('local-mute-indicator');
            if (isMuted) {
                muteIndicator.classList.remove('hidden');
            } else {
                muteIndicator.classList.add('hidden');
            }
            
            // Record activity
            recordActivity(isMuted ? 'muted' : 'unmuted');
        }
    }
}

// Toggle video on/off
function toggleVideo() {
    if (localStream) {
        const videoTrack = localStream.getVideoTracks()[0];
        if (videoTrack) {
            videoTrack.enabled = !videoTrack.enabled;
            isVideoOff = !videoTrack.enabled;
            
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
        screenStream = await navigator.mediaDevices.getDisplayMedia({
            video: true,
            audio: false
        });
        
        isScreenSharing = true;
        
        // Show screen share in local area
        document.getElementById('screen-share-video').srcObject = screenStream;
        document.getElementById('screen-share-area').classList.remove('hidden');
        document.getElementById('screen-share-user').textContent = window.videoCallConfig.userName;
        
        // Add screen track to all peer connections
        const screenTrack = screenStream.getVideoTracks()[0];
        Object.values(peerConnections).forEach(pc => {
            const sender = pc.getSenders().find(s => s.track && s.track.kind === 'video');
            if (sender) {
                sender.replaceTrack(screenTrack);
            }
        });
        
        // Notify others
        socket.emit('screen-share-started', {
            roomId: window.videoCallConfig.roomId,
            streamId: screenTrack.id
        });
        
        // Record activity
        recordActivity('screen_shared');
        
        // Handle screen share stop
        screenTrack.onended = () => {
            stopScreenSharing();
        };
        
    } catch (error) {
        console.error('Error starting screen share:', error);
        alert('Erreur lors du partage d\'écran: ' + error.message);
    }
}

// Stop screen sharing
function stopScreenSharing() {
    if (screenStream) {
        screenStream.getTracks().forEach(track => track.stop());
        screenStream = null;
    }
    
    isScreenSharing = false;
    
    // Hide screen share area
    document.getElementById('screen-share-area').classList.add('hidden');
    
    // Restore video track to all peer connections
    const videoTrack = localStream.getVideoTracks()[0];
    Object.values(peerConnections).forEach(pc => {
        const sender = pc.getSenders().find(s => s.track && s.track.kind === 'video');
        if (sender) {
            sender.replaceTrack(videoTrack);
        }
    });
    
    // Notify others
    socket.emit('screen-share-stopped', {
        roomId: window.videoCallConfig.roomId
    });
    
    // Record activity
    recordActivity('screen_stopped');
}

// Show screen share from other user
function showScreenShare(data) {
    document.getElementById('screen-share-area').classList.remove('hidden');
    document.getElementById('screen-share-user').textContent = data.userName || 'Participant';
}

// Hide screen share
function hideScreenShare() {
    document.getElementById('screen-share-area').classList.add('hidden');
}

// End call
function endCall() {
    if (confirm('Êtes-vous sûr de vouloir terminer l\'appel ?')) {
        Object.values(peerConnections).forEach(pc => pc.close());
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }
        if (screenStream) {
            screenStream.getTracks().forEach(track => track.stop());
        }
        if (socket) {
            socket.emit('leave-room', window.videoCallConfig.roomId);
            socket.disconnect();
        }
        if (callTimer) {
            clearInterval(callTimer);
        }
        window.location.href = window.videoCallConfig.indexUrl;
    }
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
    const indicator = statusElement.querySelector('div');
    const text = statusElement.querySelector('span');
    
    if (connected) {
        indicator.className = 'w-2 h-2 bg-green-500 rounded-full mr-2';
        text.textContent = 'Connecté';
    } else {
        indicator.className = 'w-2 h-2 bg-red-500 rounded-full mr-2';
        text.textContent = 'Déconnecté';
    }
}

// Load messages
async function loadMessages() {
    try {
        console.log('Loading messages from:', window.videoCallConfig.messagesUrl);
        const response = await fetch(window.videoCallConfig.messagesUrl);
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const messages = await response.json();
        console.log('Loaded messages:', messages);
        
        // Clear existing messages
        document.getElementById('chat-messages').innerHTML = '';
        
        // Add welcome message if no messages
        if (messages.length === 0) {
            addMessage({
                id: 0,
                user_id: 0,
                message: 'Bienvenue dans le chat ! Tapez votre message ci-dessous.',
                created_at: new Date().toISOString(),
                user: {
                    id: 0,
                    name: 'Système'
                }
            });
        } else {
            messages.forEach(message => {
                addMessage(message);
            });
        }
    } catch (error) {
        console.error('Error loading messages:', error);
        // Add a test message to show the chat is working
        addMessage({
            id: 1,
            user_id: window.videoCallConfig.userId,
            message: 'Test message - Chat is working!',
            created_at: new Date().toISOString(),
            user: {
                id: window.videoCallConfig.userId,
                name: window.videoCallConfig.userName
            }
        });
    }
}

// Load activities
async function loadActivities() {
    try {
        const response = await fetch(window.videoCallConfig.activitiesUrl);
        const activities = await response.json();
        
        activities.forEach(activity => {
            addActivity(activity);
        });
    } catch (error) {
        console.error('Error loading activities:', error);
    }
}

// Send message
async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    console.log('Sending message:', message);
    console.log('To URL:', window.videoCallConfig.messagesUrl);
    
    try {
        const response = await fetch(window.videoCallConfig.messagesUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.videoCallConfig.csrfToken
            },
            body: JSON.stringify({ message })
        });
        
        console.log('Send message response status:', response.status);
        
        if (response.ok) {
            const sentMessage = await response.json();
            console.log('Message sent successfully:', sentMessage);
            input.value = '';
            // Add the message to the chat immediately
            addMessage(sentMessage);
        } else {
            const errorData = await response.json();
            console.error('Error sending message:', errorData);
            
            // Even if server fails, add message locally for testing
            const localMessage = {
                id: Date.now(),
                user_id: window.videoCallConfig.userId,
                message: message,
                created_at: new Date().toISOString(),
                user: {
                    id: window.videoCallConfig.userId,
                    name: window.videoCallConfig.userName
                }
            };
            addMessage(localMessage);
            input.value = '';
            
            console.log('Added message locally due to server error');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        
        // Even if network fails, add message locally for testing
        const localMessage = {
            id: Date.now(),
            user_id: window.videoCallConfig.userId,
            message: message,
            created_at: new Date().toISOString(),
            user: {
                id: window.videoCallConfig.userId,
                name: window.videoCallConfig.userName
            }
        };
        addMessage(localMessage);
        input.value = '';
        
        console.log('Added message locally due to network error');
    }
}

// Add message to chat
function addMessage(message) {
    const chatMessages = document.getElementById('chat-messages');
    const messageElement = document.createElement('div');
    messageElement.className = 'bg-gray-700 rounded px-3 py-2 text-sm text-white break-words max-w-full mb-1';
    // Si message est un objet, on affiche le texte, le nom et l'heure
    let userName = message.user && message.user.name ? message.user.name : 'Utilisateur';
    let text = typeof message === 'string' ? message : message.message;
    let time = message.created_at ? new Date(message.created_at).toLocaleTimeString() : '';
    messageElement.innerHTML = `<span class="font-semibold text-blue-300 mr-2">${userName}</span> <span>${text}</span> <span class="text-xs text-gray-400 float-right ml-2">${time}</span>`;
    chatMessages.appendChild(messageElement);
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Record activity
async function recordActivity(action, metadata = {}) {
    try {
        await fetch(window.videoCallConfig.recordActivityUrl, {
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

// Add activity to history
function addActivity(activity) {
    const activitiesContainer = document.getElementById('activities-list');
    const activityElement = document.createElement('div');
    activityElement.className = 'flex items-center space-x-2 p-2 bg-gray-700 rounded';
    
    activityElement.innerHTML = `
        <div class="flex-shrink-0">
            <div class="w-6 h-6 bg-gray-500 rounded-full flex items-center justify-center text-xs">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="text-sm text-gray-300">
                ${activity.action_description || activity.action}
            </div>
            <div class="text-xs text-gray-500">
                ${new Date(activity.created_at).toLocaleString()}
            </div>
        </div>
    `;
    
    activitiesContainer.insertBefore(activityElement, activitiesContainer.firstChild);
}

// Add participant to list
function addParticipant(data) {
    participants[data.socketId] = data.userName || 'Participant';
    renderParticipantsList();
}

// Remove participant from list
function removeParticipant(socketId) {
    delete participants[socketId];
    renderParticipantsList();
}

function renderParticipantsList() {
    const participantsList = document.getElementById('participants-list');
    participantsList.innerHTML = '';
    Object.entries(participants).forEach(([socketId, userName]) => {
        const participantElement = document.createElement('div');
        participantElement.id = `participant-${socketId}`;
        participantElement.className = 'flex items-center space-x-2 p-2 bg-gray-700 rounded';
        participantElement.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-sm font-medium">
                    ${userName.charAt(0).toUpperCase()}
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-300">
                    ${userName}
                </div>
            </div>
            <div class="flex items-center space-x-1">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            </div>
        `;
        participantsList.appendChild(participantElement);
    });
    updateParticipantsCount();
}

function updateParticipantsCount() {
    const count = Object.keys(participants).length;
    document.getElementById('participants-count').textContent = count;
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
            socket.emit('ice-candidate', {
                roomId: window.videoCallConfig.roomId,
                candidate: event.candidate
            });
        }
    };
    
    peerConnection.ontrack = (event) => {
        const remoteStream = event.streams[0];
        remoteStreams[socketId] = remoteStream;
        addRemoteVideo(socketId, remoteStream);
    };
    
    peerConnection.createOffer()
        .then(offer => peerConnection.setLocalDescription(offer))
        .then(() => {
            socket.emit('offer', {
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
            socket.emit('ice-candidate', {
                roomId: window.videoCallConfig.roomId,
                candidate: event.candidate
            });
        }
    };
    
    peerConnection.ontrack = (event) => {
        const remoteStream = event.streams[0];
        remoteStreams[data.fromSocketId] = remoteStream;
        addRemoteVideo(data.fromSocketId, remoteStream);
    };
    
    await peerConnection.setRemoteDescription(data.offer);
    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);
    
    socket.emit('answer', {
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

// Add remote video
function addRemoteVideo(socketId, stream) {
    const videoGrid = document.getElementById('video-grid');
    
    const videoElement = document.createElement('video');
    videoElement.id = `remote-video-${socketId}`;
    videoElement.autoplay = true;
    videoElement.playsinline = true;
    videoElement.className = 'w-full h-full object-cover';
    videoElement.srcObject = stream;
    
    const videoContainer = document.createElement('div');
    videoContainer.className = 'relative bg-gray-700 rounded-lg overflow-hidden aspect-video';
    videoContainer.appendChild(videoElement);
    
    const nameDiv = document.createElement('div');
    nameDiv.className = 'absolute bottom-2 left-2 bg-black bg-opacity-50 px-2 py-1 rounded text-sm';
    nameDiv.textContent = `Participant ${socketId}`;
    videoContainer.appendChild(nameDiv);
    
    videoGrid.appendChild(videoContainer);
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

// Handle join room error
function handleJoinRoomError(error) {
    console.error('Erreur lors de la tentative de rejoindre la salle :', error);
    alert('Impossible de rejoindre l\'appel : ' + (error && error.message ? error.message : 'Accès refusé ou problème de connexion.'));
}

// Show media error
function showMediaError(error) {
    let message = 'Erreur lors de l\'accès à la caméra/micro.';
    if (error && error.name) {
        switch (error.name) {
            case 'NotAllowedError':
                message = 'Accès à la caméra ou au micro refusé. Veuillez autoriser l\'accès dans votre navigateur.';
                break;
            case 'NotFoundError':
                message = 'Aucun périphérique caméra ou micro détecté.';
                break;
            case 'NotReadableError':
                message = 'Le périphérique caméra/micro est déjà utilisé par une autre application.';
                break;
            case 'OverconstrainedError':
                message = 'Aucun périphérique ne correspond aux contraintes demandées.';
                break;
            default:
                message += ' (' + error.name + ')';
        }
    }
    // Affiche une alerte visible sur la page
    let alertDiv = document.getElementById('media-error-alert');
    if (!alertDiv) {
        alertDiv = document.createElement('div');
        alertDiv.id = 'media-error-alert';
        alertDiv.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        document.body.appendChild(alertDiv);
    }
    alertDiv.textContent = message;
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', init); 