import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import cors from 'cors';
import dotenv from 'dotenv';

// Charger les variables d'environnement
dotenv.config();

const app = express();
const httpServer = createServer(app);

// Configuration CORS améliorée
const allowedOrigins = [
  'https://edusmart.erequest.net',
  'http://localhost:3000',
  'http://localhost:8000'
];

app.use(cors({
  origin: function (origin, callback) {
    // Permettre les requêtes sans origin (comme les apps mobiles)
    if (!origin) return callback(null, true);
    
    if (allowedOrigins.indexOf(origin) !== -1) {
      callback(null, true);
    } else {
      callback(new Error('Not allowed by CORS'));
    }
  },
  credentials: true
}));

// Configuration Socket.IO
const io = new Server(httpServer, {
  path: '/socket.io',
  cors: {
    origin: allowedOrigins,
    methods: ['GET', 'POST'],
    credentials: true
  },
  transports: ['websocket', 'polling'],
  pingInterval: 25000,
  pingTimeout: 20000,
  maxHttpBufferSize: 1e8, // 100MB pour les gros fichiers
  allowEIO3: true // Compatibilité avec les anciennes versions
});

// Gestion des participants par salle
// Chaque participant : { socketId, userName, profilePhoto, isMuted, isVideoOff, isScreenSharing, joinedAt, lastActivity }
const participants = {}; // { roomId: { socketId: { userName, profilePhoto, isMuted, isVideoOff, isScreenSharing, joinedAt, lastActivity } } }

// Limites de sécurité
const MAX_PARTICIPANTS_PER_ROOM = 50;
const MAX_ROOM_NAME_LENGTH = 100;
const MAX_USER_NAME_LENGTH = 50;

// Fonction utilitaire pour nettoyer les salles vides
const cleanupEmptyRooms = () => {
  Object.keys(participants).forEach(roomId => {
    if (Object.keys(participants[roomId]).length === 0) {
      delete participants[roomId];
      console.log(`🗑️ Salle ${roomId} supprimée (vide)`);
    }
  });
};

// Fonction utilitaire pour obtenir la liste des participants
const getParticipantsList = (roomId) => {
  if (!participants[roomId]) return [];
  
  return Object.entries(participants[roomId]).map(([socketId, user]) => ({
    socketId,
    ...user
  }));
};

// Fonction pour nettoyer les participants inactifs
const cleanupInactiveParticipants = () => {
  const now = new Date();
  const inactiveThreshold = 5 * 60 * 1000; // 5 minutes
  
  Object.keys(participants).forEach(roomId => {
    Object.keys(participants[roomId]).forEach(socketId => {
      const participant = participants[roomId][socketId];
      const lastActivity = new Date(participant.lastActivity || participant.joinedAt);
      
      if (now - lastActivity > inactiveThreshold) {
        console.log(`⏰ Participant inactif supprimé: ${participant.userName} (${socketId})`);
        delete participants[roomId][socketId];
      }
    });
  });
  
  cleanupEmptyRooms();
};

// Middleware d'authentification amélioré
io.use(async (socket, next) => {
  try {
    const token = socket.handshake.auth.token;
    const roomId = socket.handshake.auth.roomId;
    
    // Validation basique du token
    if (!token) {
      return next(new Error('Token manquant'));
    }
    
    // Validation basique de la roomId
    if (!roomId || typeof roomId !== 'string') {
      return next(new Error('RoomId invalide'));
    }
    
    // Ici vous pouvez ajouter une validation plus poussée avec Laravel
    // const user = await validateTokenWithLaravel(token);
    // if (!user) return next(new Error('Token invalide'));
    
    // Stocker les infos dans le socket pour usage ultérieur
    socket.userData = { token, roomId };
    
    next();
  } catch (err) {
    console.error('Erreur d\'authentification:', err.message);
    next(new Error('Authentification échouée'));
  }
});

// Gestion des connexions
io.on('connection', (socket) => {
  console.log('Nouvelle connexion:', socket.id, 'pour la salle:', socket.userData?.roomId);

  // Quand un utilisateur rejoint une salle
  socket.on('join-room', (roomId, userName, profilePhoto = null) => {
    try {
      // Validation des données
      if (!roomId || !userName) {
        socket.emit('error', { message: 'RoomId et userName requis' });
        return;
      }

      socket.join(roomId);

      // Initialise la salle si besoin
      if (!participants[roomId]) {
        participants[roomId] = {};
        console.log(`Nouvelle salle créée: ${roomId}`);
      }

      // Ajoute le participant avec ses infos de base
      participants[roomId][socket.id] = {
        userName: userName.trim(),
        profilePhoto,
        isMuted: false,
        isVideoOff: false,
        isScreenSharing: false,
        joinedAt: new Date().toISOString(),
        lastActivity: new Date().toISOString()
      };

      // Envoie la liste à tous les clients de la salle
      const participantsList = getParticipantsList(roomId);
      io.to(roomId).emit('participants-list', participantsList);

      // Notifie les autres participants
      socket.to(roomId).emit('user-joined', { 
        socketId: socket.id, 
        userName: userName.trim(), 
        profilePhoto 
      });

      // Confirmation au participant qui vient de rejoindre
      socket.emit('room-joined', {
        roomId,
        participants: participantsList,
        yourSocketId: socket.id
      });

      console.log(`Socket ${socket.id} (${userName}) a rejoint la salle ${roomId}`);
      console.log(`Participants dans ${roomId}:`, participantsList.length);
      
    } catch (error) {
      console.error('Erreur lors de la jointure de salle:', error);
      socket.emit('error', { message: 'Erreur lors de la jointure de salle' });
    }
  });

  // Mise à jour du statut (mute/unmute, vidéo on/off)
  socket.on('update-status', (roomId, status) => {
    try {
      if (!participants[roomId] || !participants[roomId][socket.id]) {
        socket.emit('error', { message: 'Participant non trouvé dans cette salle' });
        return;
      }

      const participant = participants[roomId][socket.id];
      
      // Mise à jour des statuts
      if ('isMuted' in status) participant.isMuted = status.isMuted;
      if ('isVideoOff' in status) participant.isVideoOff = status.isVideoOff;
      
      // Envoie la liste mise à jour
      const participantsList = getParticipantsList(roomId);
      io.to(roomId).emit('participants-list', participantsList);
      
      // Notifie les autres du changement de statut
      socket.to(roomId).emit('user-status-updated', {
        socketId: socket.id,
        status: {
          isMuted: participant.isMuted,
          isVideoOff: participant.isVideoOff
        }
      });

      console.log(`Statut mis à jour pour ${socket.id} dans ${roomId}:`, status);
      
    } catch (error) {
      console.error('Erreur lors de la mise à jour du statut:', error);
      socket.emit('error', { message: 'Erreur lors de la mise à jour du statut' });
    }
  });

  // Partage d'écran
  socket.on('screen-share-start', (roomId) => {
    try {
      if (!participants[roomId] || !participants[roomId][socket.id]) {
        socket.emit('error', { message: 'Participant non trouvé dans cette salle' });
        return;
      }

      participants[roomId][socket.id].isScreenSharing = true;
      
      const participantsList = getParticipantsList(roomId);
      io.to(roomId).emit('participants-list', participantsList);
      
      socket.to(roomId).emit('screen-share-started', { 
        socketId: socket.id,
        userName: participants[roomId][socket.id].userName
      });

      console.log(`Partage d'écran démarré par ${socket.id} dans ${roomId}`);
      
    } catch (error) {
      console.error('Erreur lors du démarrage du partage d\'écran:', error);
      socket.emit('error', { message: 'Erreur lors du démarrage du partage d\'écran' });
    }
  });

  socket.on('screen-share-stop', (roomId) => {
    try {
      if (!participants[roomId] || !participants[roomId][socket.id]) {
        socket.emit('error', { message: 'Participant non trouvé dans cette salle' });
        return;
      }

      participants[roomId][socket.id].isScreenSharing = false;
      
      const participantsList = getParticipantsList(roomId);
      io.to(roomId).emit('participants-list', participantsList);
      
      socket.to(roomId).emit('screen-share-stopped', { 
        socketId: socket.id,
        userName: participants[roomId][socket.id].userName
      });

      console.log(`Partage d'écran arrêté par ${socket.id} dans ${roomId}`);
      
    } catch (error) {
      console.error('Erreur lors de l\'arrêt du partage d\'écran:', error);
      socket.emit('error', { message: 'Erreur lors de l\'arrêt du partage d\'écran' });
    }
  });

  // Signalisation WebRTC
  socket.on('signal', (data) => {
    try {
      if (!data.roomId || !data.to) {
        socket.emit('error', { message: 'Données de signalisation invalides' });
        return;
      }

      socket.to(data.roomId).emit('signal', {
        ...data,
        from: socket.id
      });

      console.log(`Signal envoyé de ${socket.id} vers ${data.to} dans ${data.roomId}`);
      
    } catch (error) {
      console.error('Erreur lors de l\'envoi du signal:', error);
      socket.emit('error', { message: 'Erreur lors de l\'envoi du signal' });
    }
  });

  // Message de chat
  socket.on('chat-message', (roomId, message) => {
    try {
      if (!participants[roomId] || !participants[roomId][socket.id]) {
        socket.emit('error', { message: 'Participant non trouvé dans cette salle' });
        return;
      }

      const participant = participants[roomId][socket.id];
      
      io.to(roomId).emit('chat-message', {
        socketId: socket.id,
        userName: participant.userName,
        message: message.trim(),
        timestamp: new Date().toISOString()
      });

      console.log(`Message de ${participant.userName} dans ${roomId}: ${message}`);
      
    } catch (error) {
      console.error('Erreur lors de l\'envoi du message:', error);
      socket.emit('error', { message: 'Erreur lors de l\'envoi du message' });
    }
  });

  // Quand un utilisateur quitte (déconnexion)
  socket.on('disconnecting', () => {
    try {
      for (const roomId of socket.rooms) {
        if (roomId !== socket.id && participants[roomId]) {
          const participant = participants[roomId][socket.id];
          
          if (participant) {
            delete participants[roomId][socket.id];
            
            // Met à jour la liste pour tous
            const participantsList = getParticipantsList(roomId);
            io.to(roomId).emit('participants-list', participantsList);
            
            // Notifie les autres de la déconnexion
            socket.to(roomId).emit('user-left', { 
              socketId: socket.id,
              userName: participant.userName
            });

            console.log(`Socket ${socket.id} (${participant.userName}) a quitté la salle ${roomId}`);
          }
        }
      }
      
      // Nettoyer les salles vides
      cleanupEmptyRooms();
      
    } catch (error) {
      console.error('Erreur lors de la déconnexion:', error);
    }
  });

  // Gestion des erreurs de socket
  socket.on('error', (error) => {
    console.error('Erreur de socket:', error);
  });
});

// Routes API
app.get('/health', (req, res) => {
  const roomsCount = Object.keys(participants).length;
  const totalParticipants = Object.values(participants).reduce((acc, room) => acc + Object.keys(room).length, 0);
  
  res.json({
    status: 'ok',
    timestamp: new Date().toISOString(),
    socketConnections: io.engine.clientsCount,
    roomsCount,
    totalParticipants,
    uptime: process.uptime()
  });
});

app.get('/rooms', (req, res) => {
  const roomsInfo = Object.entries(participants).map(([roomId, participantsInRoom]) => ({
    roomId,
    participantsCount: Object.keys(participantsInRoom).length,
    participants: Object.values(participantsInRoom).map(p => ({
      userName: p.userName,
      isMuted: p.isMuted,
      isVideoOff: p.isVideoOff,
      isScreenSharing: p.isScreenSharing
    }))
  }));
  
  res.json({
    rooms: roomsInfo,
    totalRooms: roomsInfo.length
  });
});

// Gestion des erreurs globales
process.on('uncaughtException', (error) => {
  console.error('Exception non capturée:', error);
});

process.on('unhandledRejection', (reason, promise) => {
  console.error('Promesse rejetée non gérée:', reason);
});

// Démarrer le serveur
const PORT = process.env.PORT || 3001;
const HOST = process.env.HOST || '0.0.0.0';

httpServer.listen(PORT, HOST, () => {
  console.log(`🚀 Serveur Socket.IO démarré sur ${HOST}:${PORT}`);
  console.log(`📡 Chemin d'accès: /socket.io`);
  console.log(`🔧 Mode: ${process.env.NODE_ENV || 'development'}`);
  console.log(`⏰ Démarrage: ${new Date().toISOString()}`);
});

export default app; 