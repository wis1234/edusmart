#!/bin/bash

# Script de déploiement principal pour EduSmart
# Usage: ./deploy-production.sh

set -e

echo "🚀 Déploiement d'EduSmart en production"

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "composer.json" ]; then
    echo "❌ Erreur: composer.json non trouvé. Assurez-vous d'être dans le répertoire racine d'EduSmart"
    exit 1
fi

# Vérifier les prérequis
echo "🔍 Vérification des prérequis..."
command -v composer >/dev/null 2>&1 || { echo "❌ Composer n'est pas installé"; exit 1; }
command -v npm >/dev/null 2>&1 || { echo "❌ Node.js/npm n'est pas installé"; exit 1; }
command -v php >/dev/null 2>&1 || { echo "❌ PHP n'est pas installé"; exit 1; }

# Sauvegarder la base de données
echo "💾 Sauvegarde de la base de données..."
if [ -f ".env" ] && grep -q "DB_DATABASE" .env; then
    DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2)
    if [ ! -z "$DB_NAME" ]; then
        BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
        mysqldump -u root -p "$DB_NAME" > "backups/$BACKUP_FILE"
        echo "✅ Sauvegarde créée: backups/$BACKUP_FILE"
    fi
fi

# Installer les dépendances PHP
echo "📦 Installation des dépendances PHP..."
composer install --no-dev --optimize-autoloader

# Installer les dépendances Node.js
echo "📦 Installation des dépendances Node.js..."
npm install --production

# Compiler les assets
echo "🔨 Compilation des assets..."
npm run build

# Vider les caches
echo "🧹 Nettoyage des caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser l'application
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Mettre à jour la base de données
echo "🗄️  Mise à jour de la base de données..."
php artisan migrate --force

# Définir les permissions
echo "🔐 Configuration des permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Déployer le serveur de signalisation
echo "📡 Déploiement du serveur de signalisation..."
if [ -d "signal-server" ]; then
    cd signal-server
    if [ -f "deploy.sh" ]; then
        chmod +x deploy.sh
        ./deploy.sh production
    else
        echo "⚠️  Script de déploiement du signal server non trouvé"
    fi
    cd ..
else
    echo "⚠️  Dossier signal-server non trouvé"
fi

# Vérifier la santé de l'application
echo "🏥 Vérification de la santé de l'application..."
if curl -f http://localhost/health >/dev/null 2>&1; then
    echo "✅ Application accessible"
else
    echo "⚠️  Application non accessible via /health"
fi

# Vérifier le serveur de signalisation
echo "📡 Vérification du serveur de signalisation..."
if curl -f http://localhost:3001/health >/dev/null 2>&1; then
    echo "✅ Serveur de signalisation accessible"
else
    echo "❌ Serveur de signalisation non accessible"
fi

echo "🎉 Déploiement terminé avec succès!"
echo ""
echo "📋 Commandes utiles:"
echo "   - Status Laravel: php artisan about"
echo "   - Status Signal Server: sudo systemctl status edusmart-signal-server"
echo "   - Logs Laravel: tail -f storage/logs/laravel.log"
echo "   - Logs Signal Server: sudo journalctl -u edusmart-signal-server -f"
echo ""
echo "🔗 URLs importantes:"
echo "   - Application: https://votre-domaine.com"
echo "   - Signal Server: http://localhost:3001"
echo "   - Health Check: https://votre-domaine.com/health" 