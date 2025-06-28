# EduSmart Signal Server

Serveur de signalisation WebRTC pour les appels vidéo d'EduSmart.

## Fonctionnalités

- ✅ Signalisation WebRTC (offer/answer/ICE candidates)
- ✅ Gestion des salles d'appel vidéo
- ✅ Authentification JWT avec Laravel
- ✅ Chat en temps réel
- ✅ Partage d'écran
- ✅ Gestion des statuts (micro, caméra, hôte)
- ✅ Détection de la voix active
- ✅ Focus/Pin des participants
- ✅ CORS configuré pour production

## Prérequis

- Node.js 16+ 
- npm ou yarn
- Accès à l'API Laravel d'EduSmart

## Installation

### 1. Cloner le projet
```bash
cd signal-server
```

### 2. Installer les dépendances
```bash
npm install
```

### 3. Configuration
```bash
cp env.example .env
```

Éditer le fichier `.env` :
```env
SIGNAL_PORT=3001
FRONTEND_URL=https://edusmart.erequest.net
LARAVEL_URL=https://edusmart.erequest.net
JWT_SECRET=your-secret-key-here
NODE_ENV=production
```

### 4. Déploiement automatique
```bash
chmod +x deploy.sh
./deploy.sh
```

## Démarrage manuel

### Mode développement
```bash
npm run dev
```

### Mode production
```bash
npm start
```

### Avec PM2 (recommandé)
```bash
npm install -g pm2
pm2 start server.js --name "edusmart-signal"
pm2 save
pm2 startup
```

## Configuration du serveur web

### Nginx (recommandé)
```nginx
server {
    listen 80;
    server_name your-domain.com;
    
    location / {
        proxy_pass http://localhost:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

### Apache
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    
    ProxyPreserveHost On
    ProxyPass / http://localhost:3001/
    ProxyPassReverse / http://localhost:3001/
    
    RewriteEngine on
    RewriteCond %{HTTP:Upgrade} websocket [NC]
    RewriteCond %{HTTP:Connection} upgrade [NC]
    RewriteRule ^/?(.*) "ws://localhost:3001/$1" [P,L]
</VirtualHost>
```

## Endpoints API

### Health Check
```
GET /health
```

### Participants d'une salle
```
GET /api/rooms/:roomId/participants
```

### Vérifier l'existence d'une salle
```
GET /api/rooms/:roomId/exists
```

## Événements Socket.IO

### Client → Serveur
- `join-room` - Rejoindre une salle
- `offer` - Envoyer une offre WebRTC
- `answer` - Envoyer une réponse WebRTC
- `ice-candidate` - Envoyer un candidat ICE
- `update-status` - Mettre à jour le statut
- `chat-message` - Envoyer un message
- `screen-share-start/stop` - Démarrer/arrêter le partage d'écran
- `focus-participant` - Focus sur un participant
- `leave-room` - Quitter une salle

### Serveur → Client
- `room-joined` - Confirmation de connexion à la salle
- `user-joined` - Nouveau participant
- `user-left` - Participant parti
- `participants-list` - Liste mise à jour des participants
- `offer/answer/ice-candidate` - Signalisation WebRTC
- `chat-message` - Message reçu
- `screen-share-started/stopped` - Partage d'écran
- `focus-participant` - Focus demandé
- `error` - Erreur

## Monitoring

### Logs PM2
```bash
pm2 logs edusmart-signal
```

### Statut PM2
```bash
pm2 status
```

### Redémarrage
```bash
pm2 restart edusmart-signal
```

## Sécurité

- ✅ Authentification JWT obligatoire
- ✅ CORS configuré pour le domaine de production
- ✅ Validation des accès aux salles via Laravel
- ✅ Gestion des erreurs et exceptions

## Support

Pour toute question ou problème, contactez l'équipe EduSmart. 