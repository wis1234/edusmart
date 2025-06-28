#!/bin/bash

# Script de déploiement pour le serveur de signalisation EduSmart
# Usage: ./deploy.sh [production|staging]

set -e

ENVIRONMENT=${1:-production}
SIGNAL_SERVER_DIR="signal-server"
SERVICE_NAME="edusmart-signal-server"

echo "🚀 Déploiement du serveur de signalisation EduSmart en mode $ENVIRONMENT"

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "package.json" ]; then
    echo "❌ Erreur: package.json non trouvé. Assurez-vous d'être dans le répertoire signal-server"
    exit 1
fi

# Installer les dépendances
echo "📦 Installation des dépendances..."
npm install

# Vérifier que les variables d'environnement sont configurées
if [ ! -f ".env" ]; then
    echo "⚠️  Attention: Fichier .env non trouvé"
    echo "📝 Création d'un fichier .env basé sur .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "✅ Fichier .env créé. Veuillez configurer les variables d'environnement"
    else
        echo "❌ Fichier .env.example non trouvé"
        exit 1
    fi
fi

# Vérifier la configuration
echo "🔧 Vérification de la configuration..."
if ! grep -q "FRONTEND_URL\|LARAVEL_URL\|JWT_SECRET" .env; then
    echo "❌ Variables d'environnement manquantes dans .env"
    echo "📋 Variables requises:"
    echo "   - FRONTEND_URL=https://votre-domaine.com"
    echo "   - LARAVEL_URL=https://votre-domaine.com"
    echo "   - JWT_SECRET=votre-secret-jwt"
    exit 1
fi

# Tester la compilation
echo "🧪 Test de compilation..."
if ! node -c server.js; then
    echo "❌ Erreur de syntaxe dans server.js"
    exit 1
fi

# Créer le service systemd si nécessaire
if [ "$ENVIRONMENT" = "production" ]; then
    echo "🔧 Configuration du service systemd..."
    
    # Créer le fichier de service
    cat > /etc/systemd/system/$SERVICE_NAME.service << EOF
[Unit]
Description=EduSmart Signal Server
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=$(pwd)
Environment=NODE_ENV=production
ExecStart=/usr/bin/node server.js
Restart=always
RestartSec=10
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF

    # Recharger systemd et activer le service
    systemctl daemon-reload
    systemctl enable $SERVICE_NAME
    
    echo "✅ Service systemd configuré: $SERVICE_NAME"
    echo "📋 Commandes utiles:"
    echo "   - Démarrer: sudo systemctl start $SERVICE_NAME"
    echo "   - Arrêter: sudo systemctl stop $SERVICE_NAME"
    echo "   - Redémarrer: sudo systemctl restart $SERVICE_NAME"
    echo "   - Status: sudo systemctl status $SERVICE_NAME"
    echo "   - Logs: sudo journalctl -u $SERVICE_NAME -f"
fi

# Tester le serveur
echo "🧪 Test du serveur..."
if [ "$ENVIRONMENT" = "production" ]; then
    echo "🔄 Démarrage du service..."
    systemctl start $SERVICE_NAME
    sleep 3
    
    if systemctl is-active --quiet $SERVICE_NAME; then
        echo "✅ Service démarré avec succès"
    else
        echo "❌ Erreur lors du démarrage du service"
        systemctl status $SERVICE_NAME
        exit 1
    fi
else
    echo "🧪 Test en mode développement..."
    timeout 5s node server.js &
    PID=$!
    sleep 2
    
    if kill -0 $PID 2>/dev/null; then
        echo "✅ Serveur de test démarré avec succès"
        kill $PID
    else
        echo "❌ Erreur lors du démarrage du serveur de test"
        exit 1
    fi
fi

echo "🎉 Déploiement terminé avec succès!"
echo ""
echo "📋 Informations importantes:"
echo "   - Port par défaut: 3001"
echo "   - URL de santé: http://localhost:3001/health"
echo "   - Logs: sudo journalctl -u $SERVICE_NAME -f"
echo ""
echo "🔗 Pour tester la connectivité:"
echo "   curl http://localhost:3001/health"
echo ""
echo "🌐 N'oubliez pas de configurer votre reverse proxy (nginx/apache) pour rediriger"
echo "   les requêtes WebSocket vers ce serveur de signalisation." 