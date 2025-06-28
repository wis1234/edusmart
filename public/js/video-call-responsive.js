// Video Call Responsive JavaScript
// Optimisé pour tous les écrans (mobile, tablette, desktop)

class VideoCallResponsive {
    constructor() {
        this.socket = null;
        this.localStream = null;
        this.peers = new Map();
        this.localVideo = document.getElementById('local-video');
        this.videoGrid = document.getElementById('video-grid');
        this.screenShareStream = null;
        this.isScreenSharing = false;
        this.callStartTime = Date.now();
        this.timerInterval = null;
        
        // Responsive breakpoints
        this.breakpoints = {
            mobile: 640,
            tablet: 1024,
            desktop: 1280
        };
        
        this.init();
    }

    async init() {
        try {
            await this.setupMedia();
            this.setupSocket();
            this.setupEventListeners();
            this.setupResponsiveFeatures();
            this.startTimer();
            this.loadInitialData();
        } catch (error) {
            console.error('Initialization error:', error);
            this.showError('Erreur d\'initialisation: ' + error.message);
        }
    }

    async setupMedia() {
        try {
            this.localStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: this.getOptimalVideoWidth() },
                    height: { ideal: this.getOptimalVideoHeight() },
                    frameRate: { ideal: 30 }
                },
                audio: {
                    echoCancellation: true,
                    noiseSuppression: true,
                    autoGainControl: true
                }
            });
            
            this.localVideo.srcObject = this.localStream;
        } catch (error) {
            console.error('Media setup error:', error);
            this.showError('Impossible d\'accéder à la caméra/microphone');
        }
    }

    getOptimalVideoWidth() {
        const screenWidth = window.innerWidth;
        if (screenWidth < this.breakpoints.mobile) return 640;
        if (screenWidth < this.breakpoints.tablet) return 1280;
        return 1920;
    }

    getOptimalVideoHeight() {
        const screenWidth = window.innerWidth;
        if (screenWidth < this.breakpoints.mobile) return 480;
        if (screenWidth < this.breakpoints.tablet) return 720;
        return 1080;
    }

    setupSocket() {
        this.socket = io(window.videoCallConfig.signalServerUrl, {
            transports: ['websocket', 'polling'],
            timeout: 20000,
            reconnection: true,
            reconnectionAttempts: 5,
            reconnectionDelay: 1000
        });

        this.socket.on('connect', () => {
            console.log('Connected to signal server');
            this.updateConnectionStatus(true);
            this.socket.emit('join-room', 
                window.videoCallConfig.roomId, 
                window.currentUser.name, 
                window.currentUser.profile_photo
            );
        });

        this.socket.on('disconnect', () => {
            console.log('Disconnected from signal server');
            this.updateConnectionStatus(false);
        });

        this.socket.on('user-joined', (userData) => {
            console.log('User joined:', userData);
            this.addParticipant(userData);
            this.createPeerConnection(userData.userId);
        });

        this.socket.on('user-left', (userId) => {
            console.log('User left:', userId);
            this.removeParticipant(userId);
            this.removePeerConnection(userId);
        });

        this.socket.on('signal', (data) => {
            this.handleSignal(data);
        });

        this.socket.on('participants-list', (participants) => {
            console.log('Participants list received:', participants);
            this.updateParticipantsList(participants);
        });

        this.socket.on('chat-message', (message) => {
            this.addChatMessage(message);
        });

        this.socket.on('screen-share-started', (data) => {
            this.handleScreenShareStarted(data);
        });

        this.socket.on('screen-share-stopped', () => {
            this.handleScreenShareStopped();
        });
    }

    setupEventListeners() {
        // Responsive controls
        const muteBtn = document.getElementById('mute-btn');
        const videoBtn = document.getElementById('video-btn');
        const screenShareBtn = document.getElementById('screen-share-btn');
        const endCallBtn = document.getElementById('end-call-btn');

        muteBtn.addEventListener('click', () => this.toggleMute());
        videoBtn.addEventListener('click', () => this.toggleVideo());
        screenShareBtn.addEventListener('click', () => this.toggleScreenShare());
        endCallBtn.addEventListener('click', () => this.endCall());

        // Responsive tabs
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.switchTab(e.target.id));
        });

        // Responsive chat
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-message-btn');

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
        sendBtn.addEventListener('click', () => this.sendMessage());

        // Responsive window resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Touch events for mobile
        if ('ontouchstart' in window) {
            this.setupTouchEvents();
        }
    }

    setupResponsiveFeatures() {
        // Auto-hide controls on mobile after inactivity
        if (window.innerWidth < this.breakpoints.mobile) {
            this.setupAutoHideControls();
        }

        // Optimize video quality based on screen size
        this.optimizeVideoQuality();

        // Setup responsive grid
        this.updateVideoGrid();
    }

    setupAutoHideControls() {
        let controlsTimeout;
        const controls = document.querySelector('.flex.items-center.justify-center.space-x-2');
        
        const showControls = () => {
            controls.style.opacity = '1';
            clearTimeout(controlsTimeout);
            controlsTimeout = setTimeout(() => {
                controls.style.opacity = '0.3';
            }, 3000);
        };

        document.addEventListener('mousemove', showControls);
        document.addEventListener('touchstart', showControls);
        showControls();
    }

    optimizeVideoQuality() {
        const screenWidth = window.innerWidth;
        const constraints = {
            video: {
                width: { ideal: this.getOptimalVideoWidth() },
                height: { ideal: this.getOptimalVideoHeight() },
                frameRate: screenWidth < this.breakpoints.mobile ? 15 : 30
            }
        };

        if (this.localStream) {
            this.localStream.getVideoTracks().forEach(track => {
                track.applyConstraints(constraints.video);
            });
        }
    }

    updateVideoGrid() {
        const screenWidth = window.innerWidth;
        let columns = 1;

        if (screenWidth >= this.breakpoints.desktop) {
            columns = 3;
        } else if (screenWidth >= this.breakpoints.tablet) {
            columns = 2;
        }

        this.videoGrid.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;
    }

    handleResize() {
        this.updateVideoGrid();
        this.optimizeVideoQuality();
        
        // Recalculate optimal video dimensions
        if (this.localStream) {
            const constraints = {
                width: { ideal: this.getOptimalVideoWidth() },
                height: { ideal: this.getOptimalVideoHeight() }
            };
            
            this.localStream.getVideoTracks().forEach(track => {
                track.applyConstraints(constraints);
            });
        }
    }

    setupTouchEvents() {
        // Double tap to focus on video
        let lastTap = 0;
        this.videoGrid.addEventListener('touchend', (e) => {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            
            if (tapLength < 500 && tapLength > 0) {
                const videoElement = e.target.closest('.relative');
                if (videoElement) {
                    this.focusVideo(videoElement);
                }
            }
            lastTap = currentTime;
        });
    }

    focusVideo(videoElement) {
        // Remove focus from all videos
        document.querySelectorAll('.relative').forEach(el => {
            el.classList.remove('focused');
        });
        
        // Add focus to selected video
        videoElement.classList.add('focused');
        
        // Auto-remove focus after 5 seconds
        setTimeout(() => {
            videoElement.classList.remove('focused');
        }, 5000);
    }

    async toggleMute() {
        if (!this.localStream) return;

        const audioTrack = this.localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = !audioTrack.enabled;
            const muteBtn = document.getElementById('mute-btn');
            const muteIndicator = document.getElementById('local-mute-indicator');
            
            if (audioTrack.enabled) {
                muteBtn.classList.remove('bg-red-600');
                muteBtn.classList.add('bg-gray-700');
                muteIndicator.classList.add('hidden');
            } else {
                muteBtn.classList.remove('bg-gray-700');
                muteBtn.classList.add('bg-red-600');
                muteIndicator.classList.remove('hidden');
            }
        }
    }

    async toggleVideo() {
        if (!this.localStream) return;

        const videoTrack = this.localStream.getVideoTracks()[0];
        if (videoTrack) {
            videoTrack.enabled = !videoTrack.enabled;
            const videoBtn = document.getElementById('video-btn');
            
            if (videoTrack.enabled) {
                videoBtn.classList.remove('bg-red-600');
                videoBtn.classList.add('bg-gray-700');
                this.localVideo.style.display = 'block';
            } else {
                videoBtn.classList.remove('bg-gray-700');
                videoBtn.classList.add('bg-red-600');
                this.localVideo.style.display = 'none';
            }
        }
    }

    async toggleScreenShare() {
        if (this.isScreenSharing) {
            await this.stopScreenShare();
        } else {
            await this.startScreenShare();
        }
    }

    async startScreenShare() {
        try {
            this.screenShareStream = await navigator.mediaDevices.getDisplayMedia({
                video: {
                    cursor: 'always',
                    displaySurface: 'monitor'
                }
            });

            const screenShareVideo = document.getElementById('screen-share-video');
            screenShareVideo.srcObject = this.screenShareStream;
            document.getElementById('screen-share-area').classList.remove('hidden');
            document.getElementById('screen-share-user').textContent = window.currentUser.name;

            this.socket.emit('screen-share-started', {
                roomId: window.videoCallConfig.roomId,
                userName: window.currentUser.name
            });

            this.isScreenSharing = true;
            document.getElementById('screen-share-btn').classList.add('bg-blue-600');

            // Handle screen share stop
            this.screenShareStream.getVideoTracks()[0].onended = () => {
                this.stopScreenShare();
            };

        } catch (error) {
            console.error('Screen share error:', error);
            this.showError('Impossible de partager l\'écran');
        }
    }

    async stopScreenShare() {
        if (this.screenShareStream) {
            this.screenShareStream.getTracks().forEach(track => track.stop());
            this.screenShareStream = null;
        }

        document.getElementById('screen-share-area').classList.add('hidden');
        this.isScreenSharing = false;
        document.getElementById('screen-share-btn').classList.remove('bg-blue-600');

        this.socket.emit('screen-share-stopped', {
            roomId: window.videoCallConfig.roomId
        });
    }

    handleScreenShareStarted(data) {
        document.getElementById('screen-share-area').classList.remove('hidden');
        document.getElementById('screen-share-user').textContent = data.userName;
    }

    handleScreenShareStopped() {
        document.getElementById('screen-share-area').classList.add('hidden');
    }

    createPeerConnection(userId) {
        const peerConnection = new RTCPeerConnection({
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' }
            ]
        });

        this.peers.set(userId, peerConnection);

        // Add local stream
        if (this.localStream) {
            this.localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, this.localStream);
            });
        }

        // Handle incoming streams
        peerConnection.ontrack = (event) => {
            this.addRemoteVideo(userId, event.streams[0]);
        };

        // Handle ICE candidates
        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                this.socket.emit('signal', {
                    type: 'ice-candidate',
                    candidate: event.candidate,
                    to: userId
                });
            }
        };

        return peerConnection;
    }

    removePeerConnection(userId) {
        const peerConnection = this.peers.get(userId);
        if (peerConnection) {
            peerConnection.close();
            this.peers.delete(userId);
        }
    }

    async handleSignal(data) {
        const peerConnection = this.peers.get(data.from);
        if (!peerConnection) return;

        try {
            if (data.type === 'offer') {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(data.offer));
                const answer = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answer);
                
                this.socket.emit('signal', {
                    type: 'answer',
                    answer: answer,
                    to: data.from
                });
            } else if (data.type === 'answer') {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(data.answer));
            } else if (data.type === 'ice-candidate') {
                await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
            }
        } catch (error) {
            console.error('Signal handling error:', error);
        }
    }

    addRemoteVideo(userId, stream) {
        const videoElement = document.createElement('video');
        videoElement.id = `video-${userId}`;
        videoElement.autoplay = true;
        videoElement.playsInline = true;
        videoElement.className = 'w-full h-full object-cover rounded-lg';

        const videoContainer = document.createElement('div');
        videoContainer.className = 'relative bg-gray-700 rounded-lg overflow-hidden aspect-video shadow-lg flex items-center justify-center';
        videoContainer.appendChild(videoElement);

        this.videoGrid.appendChild(videoContainer);
        videoElement.srcObject = stream;
    }

    removeRemoteVideo(userId) {
        const videoElement = document.getElementById(`video-${userId}`);
        if (videoElement) {
            videoElement.parentElement.remove();
        }
    }

    updateConnectionStatus(connected) {
        const statusElement = document.getElementById('connection-status');
        const statusDot = statusElement.querySelector('div');
        const statusText = statusElement.querySelector('span');

        if (connected) {
            statusDot.className = 'w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full';
            if (statusText) statusText.textContent = 'Connecté';
        } else {
            statusDot.className = 'w-2 h-2 sm:w-3 sm:h-3 bg-red-500 rounded-full';
            if (statusText) statusText.textContent = 'Déconnecté';
        }
    }

    showError(message) {
        const errorElement = document.getElementById('connection-error');
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        
        setTimeout(() => {
            errorElement.classList.add('hidden');
        }, 5000);
    }

    startTimer() {
        this.timerInterval = setInterval(() => {
            const elapsed = Date.now() - this.callStartTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            document.getElementById('call-timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    switchTab(tabId) {
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
        const contentId = tabId.replace('-tab', '-content');
        document.getElementById(contentId).classList.remove('hidden');

        // Add active class to selected tab
        const selectedTab = document.getElementById(tabId);
        selectedTab.classList.add('active', 'text-white', 'border-blue-500');
        selectedTab.classList.remove('text-gray-400');
    }

    async loadInitialData() {
        await this.loadParticipants();
        await this.loadChatMessages();
        await this.loadActivities();
    }

    async loadParticipants() {
        try {
            const response = await fetch(window.videoCallConfig.messagesUrl, {
                headers: {
                    'X-CSRF-TOKEN': window.videoCallConfig.csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.updateParticipantsList(data.participants || []);
            }
        } catch (error) {
            console.error('Error loading participants:', error);
        }
    }

    async loadChatMessages() {
        try {
            const response = await fetch(window.videoCallConfig.messagesUrl, {
                headers: {
                    'X-CSRF-TOKEN': window.videoCallConfig.csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const messages = Array.isArray(data) ? data : (data.data || data.messages || []);
                messages.forEach(message => this.addChatMessage(message));
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    async loadActivities() {
        try {
            const response = await fetch(window.videoCallConfig.activitiesUrl, {
                headers: {
                    'X-CSRF-TOKEN': window.videoCallConfig.csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const activities = Array.isArray(data) ? data : (data.data || data.activities || []);
                activities.forEach(activity => this.addActivity(activity));
            }
        } catch (error) {
            console.error('Error loading activities:', error);
        }
    }

    updateParticipantsList(participants) {
        const participantsList = document.getElementById('participants-list');
        participantsList.innerHTML = '';

        participants.forEach(participant => {
            this.addParticipant(participant);
        });
    }

    addParticipant(participant) {
        const participantsList = document.getElementById('participants-list');
        
        const participantElement = document.createElement('div');
        participantElement.className = 'flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 bg-gray-700 rounded-lg';
        participantElement.id = `participant-${participant.userId || participant.id}`;
        
        const avatar = document.createElement('img');
        avatar.src = participant.profile_photo || '/default-avatar.png';
        avatar.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover';
        avatar.alt = participant.name;
        
        const info = document.createElement('div');
        info.className = 'flex-1 min-w-0';
        
        const name = document.createElement('div');
        name.className = 'text-sm sm:text-base font-medium text-white truncate';
        name.textContent = participant.name;
        
        const status = document.createElement('div');
        status.className = 'text-xs sm:text-sm text-gray-400';
        status.textContent = 'En ligne';
        
        info.appendChild(name);
        info.appendChild(status);
        
        participantElement.appendChild(avatar);
        participantElement.appendChild(info);
        
        participantsList.appendChild(participantElement);
    }

    removeParticipant(userId) {
        const participantElement = document.getElementById(`participant-${userId}`);
        if (participantElement) {
            participantElement.remove();
        }
    }

    addChatMessage(message) {
        const chatMessages = document.getElementById('chat-messages');
        
        const messageElement = document.createElement('div');
        messageElement.className = 'flex space-x-2 sm:space-x-3';
        
        const avatar = document.createElement('img');
        avatar.src = message.user?.profile_photo_url || '/default-avatar.png';
        avatar.className = 'w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover flex-shrink-0';
        avatar.alt = message.user?.name || 'Utilisateur';
        
        const content = document.createElement('div');
        content.className = 'flex-1 min-w-0';
        
        const header = document.createElement('div');
        header.className = 'flex items-center space-x-2 mb-1';
        
        const name = document.createElement('span');
        name.className = 'text-xs sm:text-sm font-medium text-white';
        name.textContent = message.user?.name || 'Utilisateur';
        
        const time = document.createElement('span');
        time.className = 'text-xs text-gray-400';
        time.textContent = new Date(message.created_at || message.timestamp).toLocaleTimeString();
        
        header.appendChild(name);
        header.appendChild(time);
        
        const text = document.createElement('div');
        text.className = 'text-xs sm:text-sm text-gray-300 break-words';
        text.textContent = message.content || message.message;
        
        content.appendChild(header);
        content.appendChild(text);
        
        messageElement.appendChild(avatar);
        messageElement.appendChild(content);
        
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    addActivity(activity) {
        const activitiesList = document.getElementById('activities-list');
        
        const activityElement = document.createElement('div');
        activityElement.className = 'flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 bg-gray-700 rounded-lg';
        
        const icon = document.createElement('i');
        icon.className = 'fas fa-circle text-xs text-blue-400';
        
        const content = document.createElement('div');
        content.className = 'flex-1 min-w-0';
        
        const text = document.createElement('div');
        text.className = 'text-xs sm:text-sm text-gray-300';
        text.textContent = activity.description || activity.message;
        
        const time = document.createElement('div');
        time.className = 'text-xs text-gray-500';
        time.textContent = new Date(activity.created_at || activity.timestamp).toLocaleTimeString();
        
        content.appendChild(text);
        content.appendChild(time);
        
        activityElement.appendChild(icon);
        activityElement.appendChild(content);
        
        activitiesList.appendChild(activityElement);
    }

    async sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        
        if (!message) return;
        
        try {
            const response = await fetch(window.videoCallConfig.messagesUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.videoCallConfig.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content: message })
            });
            
            if (response.ok) {
                input.value = '';
                const data = await response.json();
                this.addChatMessage(data);
                
                // Emit to socket for real-time
                this.socket.emit('chat-message', {
                    roomId: window.videoCallConfig.roomId,
                    content: message,
                    userId: window.videoCallConfig.userId,
                    userName: window.videoCallConfig.userName
                });
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Erreur lors de l\'envoi du message');
        }
    }

    endCall() {
        if (confirm('Êtes-vous sûr de vouloir terminer l\'appel ?')) {
            if (this.socket) {
                this.socket.disconnect();
            }
            
            if (this.localStream) {
                this.localStream.getTracks().forEach(track => track.stop());
            }
            
            if (this.screenShareStream) {
                this.screenShareStream.getTracks().forEach(track => track.stop());
            }
            
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
            }
            
            window.location.href = window.videoCallConfig.indexUrl;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new VideoCallResponsive();
});

// Handle page visibility changes for mobile optimization
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Pause video when page is not visible (mobile optimization)
        const videos = document.querySelectorAll('video');
        videos.forEach(video => {
            if (!video.paused) video.pause();
        });
    } else {
        // Resume video when page becomes visible
        const videos = document.querySelectorAll('video');
        videos.forEach(video => {
            if (video.paused) video.play();
        });
    }
}); 