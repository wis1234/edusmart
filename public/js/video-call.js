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
let remoteStreams = {};
let peerConnections = {};
let isMuted = false;
let isVideoOff = false;

// Initialize the application
async function init() {
    try {
        await getUserMedia();
        connectToSignalServer();
        setupEventListeners();
    } catch (error) {
        console.error('Failed to initialize:', error);
        alert('Erreur lors de l\'initialisation de l\'appel: ' + error.message);
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
        throw new Error('Impossible d\'accéder à la caméra et au microphone');
    }
}

// Connect to signal server
function connectToSignalServer() {
    const config = window.videoCallConfig;
    
    socket = io(config.signalServerUrl, {
        auth: {
            token: config.csrfToken
        }
    });

    socket.on('connect', () => {
        console.log('Connected to signal server');
        socket.emit('join-room', config.roomId);
    });

    socket.on('room-joined', (data) => {
        console.log('Joined room:', data);
    });

    socket.on('user-joined', (data) => {
        console.log('User joined:', data);
        createPeerConnection(data.socketId);
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
}

// Setup event listeners
function setupEventListeners() {
    document.getElementById('mute-btn').addEventListener('click', toggleMute);
    document.getElementById('video-btn').addEventListener('click', toggleVideo);
    document.getElementById('end-call-btn').addEventListener('click', endCall);
}

// Toggle mute/unmute
function toggleMute() {
    if (localStream) {
        const audioTrack = localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = !audioTrack.enabled;
            isMuted = !audioTrack.enabled;
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
        }
    }
}

// End call
function endCall() {
    if (confirm('Êtes-vous sûr de vouloir terminer l\'appel ?')) {
        Object.values(peerConnections).forEach(pc => pc.close());
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }
        if (socket) {
            socket.emit('leave-room', window.videoCallConfig.roomId);
            socket.disconnect();
        }
        window.location.href = window.videoCallConfig.indexUrl;
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

// Initialize when page loads
document.addEventListener('DOMContentLoaded', init); 