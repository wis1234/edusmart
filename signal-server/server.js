import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import cors from 'cors';
import jwt from 'jsonwebtoken';
import axios from 'axios';

const app = express();
const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: process.env.FRONTEND_URL || "https://edusmart.erequest.net",
        methods: ["GET", "POST"],
        credentials: true
    }
});

// Middleware
app.use(cors({
    origin: process.env.FRONTEND_URL || "https://edusmart.erequest.net",
    credentials: true
}));
app.use(express.json());

// Configuration
const PORT = process.env.PORT || process.env.SIGNAL_PORT || 3001;
const LARAVEL_URL = process.env.LARAVEL_URL || 'https://edusmart.erequest.net';
const JWT_SECRET = process.env.JWT_SECRET || 'your-secret-key';

// Store active rooms and participants
// Each participant: { userId, socketId, name, profile_photo, isMuted, isVideoOff, isScreenSharing, isHost, isSpeaking }
const rooms = new Map();
const userSockets = new Map();

// Middleware pour vérifier l'authentification
const authenticateToken = async (socket, next) => {
    try {
        const token = socket.handshake.auth.token;
        if (!token) {
            return next(new Error('Authentication error'));
        }

        // Vérifier le token avec Laravel
        const response = await axios.get(`${LARAVEL_URL}/api/user`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });

        socket.user = response.data;
        next();
    } catch (error) {
        console.error('Authentication error:', error.message);
        next(new Error('Authentication error'));
    }
};

io.use(authenticateToken);

// Helper to build user display name and photo
function getUserDisplay(socket) {
    if (!socket || !socket.user) return { name: 'Unknown', profile_photo: null };
    const user = socket.user;
    const name = [user.first_name, user.last_name].filter(Boolean).join(' ').trim();
    return {
        name: name || 'Unknown',
        profile_photo: user.profile_photo_url || null
    };
}

// Gestion des connexions Socket.IO
io.on('connection', (socket) => {
    console.log(`User ${socket.user.id} connected: ${socket.id}`);

    // Store the user's socket
    userSockets.set(socket.user.id, socket.id);

    // Helper to get or create participant object
    function getOrCreateParticipant(room, socket) {
        let participant = room.participants.get(socket.user.id);
        if (!participant) {
            const display = getUserDisplay(socket);
            participant = {
                userId: socket.user.id,
                socketId: socket.id,
                name: display.name,
                profile_photo: display.profile_photo,
                isMuted: false,
                isVideoOff: false,
                isScreenSharing: false,
                isHost: false,
                isSpeaking: false
            };
            room.participants.set(socket.user.id, participant);
        } else {
            participant.socketId = socket.id;
        }
        return participant;
    }

    // Join a video call room
    socket.on('join-room', async (roomId) => {
        try {
            // Verify access with Laravel
            const response = await axios.get(`${LARAVEL_URL}/api/video-calls/${roomId}/verify-access`, {
                headers: { 'Authorization': `Bearer ${socket.handshake.auth.token}` }
            });

            if (!response.data.canAccess) {
                socket.emit('error', { message: 'Access denied to this room' });
                return;
            }

            socket.join(roomId);

            // Initialize the room if it doesn't exist
            if (!rooms.has(roomId)) {
                rooms.set(roomId, {
                    participants: new Map(), // userId -> participant object
                    host: null
                });
            }

            const room = rooms.get(roomId);
            const participant = getOrCreateParticipant(room, socket);
            
            // Set as host if first participant
            if (room.participants.size === 0) {
                participant.isHost = true;
                room.host = socket.user.id;
            }
            
            room.participants.set(socket.user.id, participant);

            // Notify others in the room
            socket.to(roomId).emit('user-joined', participant);

            // Build the full participants list
            const participants = Array.from(room.participants.values());

            // Send the full list to everyone in the room
            io.to(roomId).emit('participants-list', participants);

            socket.emit('room-joined', {
                roomId,
                participants,
                isHost: participant.isHost
            });

            console.log(`User ${socket.user.id} joined room ${roomId}`);

        } catch (error) {
            console.error('Error joining room:', error);
            socket.emit('error', { message: 'Failed to join room' });
        }
    });

    // Signalisation WebRTC - Offre
    socket.on('offer', (data) => {
        socket.to(data.roomId).emit('offer', {
            offer: data.offer,
            from: socket.user.id,
            fromSocketId: socket.id
        });
    });

    // Signalisation WebRTC - Réponse
    socket.on('answer', (data) => {
        socket.to(data.roomId).emit('answer', {
            answer: data.answer,
            from: socket.user.id,
            fromSocketId: socket.id
        });
    });

    // Signalisation WebRTC - ICE Candidate
    socket.on('ice-candidate', (data) => {
        socket.to(data.roomId).emit('ice-candidate', {
            candidate: data.candidate,
            from: socket.user.id,
            fromSocketId: socket.id
        });
    });

    // Real-time status update (mute/unmute, video on/off, speaking)
    socket.on('update-status', (data) => {
        for (const [roomId, room] of rooms.entries()) {
            if (room.participants.has(socket.user.id)) {
                const participant = room.participants.get(socket.user.id);
                if ('isMuted' in data) participant.isMuted = data.isMuted;
                if ('isVideoOff' in data) participant.isVideoOff = data.isVideoOff;
                if ('isSpeaking' in data) participant.isSpeaking = data.isSpeaking;
                // Broadcast updated participants list
                io.to(roomId).emit('participants-list', Array.from(room.participants.values()));
            }
        }
    });

    // Message de chat
    socket.on('chat-message', (data) => {
        const display = getUserDisplay(socket);
        socket.to(data.roomId).emit('chat-message', {
            message: data.message,
            from: socket.user.id,
            fromName: display.name,
            timestamp: new Date().toISOString()
        });
    });

    // Partager l'écran
    socket.on('screen-share-start', (data) => {
        for (const [roomId, room] of rooms.entries()) {
            if (room.participants.has(socket.user.id)) {
                const participant = room.participants.get(socket.user.id);
                participant.isScreenSharing = true;
                // Broadcast updated participants list
                io.to(roomId).emit('participants-list', Array.from(room.participants.values()));
                socket.to(roomId).emit('screen-share-started', {
                    socketId: socket.id,
                    userId: socket.user.id
                });
            }
        }
    });
    
    socket.on('screen-share-stop', (data) => {
        for (const [roomId, room] of rooms.entries()) {
            if (room.participants.has(socket.user.id)) {
                const participant = room.participants.get(socket.user.id);
                participant.isScreenSharing = false;
                // Broadcast updated participants list
                io.to(roomId).emit('participants-list', Array.from(room.participants.values()));
                socket.to(roomId).emit('screen-share-stopped', {
                    socketId: socket.id,
                    userId: socket.user.id
                });
            }
        }
    });

    // Focus/Pin participant
    socket.on('focus-participant', (data) => {
        socket.to(data.roomId).emit('focus-participant', {
            userId: data.userId,
            focusedBy: socket.user.id
        });
    });

    // Leave room
    socket.on('leave-room', (roomId) => {
        socket.leave(roomId);
        if (rooms.has(roomId)) {
            const room = rooms.get(roomId);
            const wasHost = room.participants.get(socket.user.id)?.isHost;
            room.participants.delete(socket.user.id);
            
            // If host left, assign new host
            if (wasHost && room.participants.size > 0) {
                const newHost = Array.from(room.participants.values())[0];
                newHost.isHost = true;
                room.host = newHost.userId;
            }
            
            // Clean up room if empty
            if (room.participants.size === 0) {
                rooms.delete(roomId);
            } else {
                // Broadcast updated participants list
                io.to(roomId).emit('participants-list', Array.from(room.participants.values()));
            }
        }
        // Notify others
        const display = getUserDisplay(socket);
        socket.to(roomId).emit('user-left', {
            userId: socket.user.id,
            name: display.name,
            profile_photo: display.profile_photo
        });
        console.log(`User ${socket.user.id} left room ${roomId}`);
    });

    // Déconnexion
    socket.on('disconnect', () => {
        console.log(`User ${socket.user.id} disconnected: ${socket.id}`);
        for (const [roomId, room] of rooms.entries()) {
            if (room.participants.has(socket.user.id)) {
                const wasHost = room.participants.get(socket.user.id)?.isHost;
                room.participants.delete(socket.user.id);
                
                // If host disconnected, assign new host
                if (wasHost && room.participants.size > 0) {
                    const newHost = Array.from(room.participants.values())[0];
                    newHost.isHost = true;
                    room.host = newHost.userId;
                }
                
                if (room.participants.size === 0) {
                    rooms.delete(roomId);
                } else {
                    // Broadcast updated participants list
                    io.to(roomId).emit('participants-list', Array.from(room.participants.values()));
                }
            }
        }
        userSockets.delete(socket.user.id);
    });
});

// Routes API pour Laravel
app.get('/api/rooms/:roomId/participants', (req, res) => {
    const { roomId } = req.params;
    const room = rooms.get(roomId);
    
    if (!room) {
        return res.json({ participants: [] });
    }

    const participants = Array.from(room.participants.values()).map(participant => ({
        userId: participant.userId,
        socketId: participant.socketId,
        name: participant.name,
        profile_photo: participant.profile_photo,
        isMuted: participant.isMuted,
        isVideoOff: participant.isVideoOff,
        isScreenSharing: participant.isScreenSharing,
        isHost: participant.isHost,
        isSpeaking: participant.isSpeaking
    }));

    res.json({ participants });
});

app.get('/api/rooms/:roomId/exists', (req, res) => {
    const { roomId } = req.params;
    res.json({ exists: rooms.has(roomId) });
});

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({ 
        status: 'ok', 
        timestamp: new Date().toISOString(),
        activeRooms: rooms.size,
        activeConnections: io.engine.clientsCount
    });
});

// Démarrage du serveur
server.listen(PORT, () => {
    console.log(`Signal server running on port ${PORT}`);
    console.log(`CORS enabled for: ${process.env.FRONTEND_URL || "https://edusmart.erequest.net"}`);
    console.log(`Laravel URL: ${LARAVEL_URL}`);
});

// Gestion des erreurs
process.on('uncaughtException', (error) => {
    console.error('Uncaught Exception:', error);
});

process.on('unhandledRejection', (reason, promise) => {
    console.error('Unhandled Rejection at:', promise, 'reason:', reason);
}); 