#!/bin/bash

echo "🚀 Début du déploiement EduSmart..."

# Variables
PROJECT_NAME="eduSmart"
REMOTE_HOST="votre-serveur.com"
REMOTE_USER="votre-username"
REMOTE_PATH="/home/$REMOTE_USER/public_html"
BACKUP_PATH="/home/$REMOTE_USER/backups"

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# 1. Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    print_error "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

print_message "Vérification de l'environnement..."

# 2. Installer les dépendances de production
print_message "Installation des dépendances de production..."
composer install --no-dev --optimize-autoloader --no-interaction

if [ $? -ne 0 ]; then
    print_error "Erreur lors de l'installation des dépendances Composer"
    exit 1
fi

# 3. Compiler les assets
print_message "Compilation des assets..."
npm run build

if [ $? -ne 0 ]; then
    print_error "Erreur lors de la compilation des assets"
    exit 1
fi

# 4. Optimiser l'application
print_message "Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Créer un backup sur le serveur distant
print_message "Création d'un backup sur le serveur distant..."
ssh $REMOTE_USER@$REMOTE_HOST "mkdir -p $BACKUP_PATH && cp -r $REMOTE_PATH $BACKUP_PATH/backup_$(date +%Y%m%d_%H%M%S)"

# 6. Synchroniser les fichiers
print_message "Synchronisation des fichiers..."
rsync -avz --exclude='.git' \
    --exclude='node_modules' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.env' \
    --exclude='.env.local' \
    --exclude='.env.production' \
    ./ $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/

if [ $? -ne 0 ]; then
    print_error "Erreur lors de la synchronisation des fichiers"
    exit 1
fi

# 7. Configuration sur le serveur distant
print_message "Configuration sur le serveur distant..."

ssh $REMOTE_USER@$REMOTE_HOST << 'EOF'
    cd /home/$USER/public_html
    
    # Définir les permissions
    chmod -R 755 storage
    chmod -R 755 bootstrap/cache
    
    # Vider les caches existants
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    # Recréer les caches optimisés
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Exécuter les migrations si nécessaire
    php artisan migrate --force
    
    # Optimiser l'autoloader
    composer dump-autoload --optimize
    
    echo "Configuration terminée sur le serveur distant"
EOF

if [ $? -ne 0 ]; then
    print_error "Erreur lors de la configuration sur le serveur distant"
    exit 1
fi

print_message "✅ Déploiement terminé avec succès!"
print_message "🌐 Votre application est maintenant accessible sur https://votre-domaine.com"

# 8. Tests de vérification
print_message "Tests de vérification..."

# Test de connectivité
if curl -s -o /dev/null -w "%{http_code}" https://votre-domaine.com | grep -q "200"; then
    print_message "✅ L'application répond correctement"
else
    print_warning "⚠️  L'application ne répond pas comme attendu"
fi

echo ""
print_message "📋 Checklist post-déploiement :"
echo "  1. Vérifier que l'application est accessible"
echo "  2. Tester la connexion à la base de données"
echo "  3. Vérifier les emails SMTP"
echo "  4. Tester les fonctionnalités principales"
echo "  5. Vérifier les logs d'erreur"
echo ""
print_message "🔧 En cas de problème, consultez les logs :"
echo "  - Logs Laravel : storage/logs/laravel.log"
echo "  - Logs serveur : /var/log/apache2/error.log" 