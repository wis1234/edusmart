# Checklist de déploiement EduSmart sur Hostinger

## 📋 Préparation locale

### 1. Optimisation de l'application
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `php artisan key:generate`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `npm run build`

### 2. Fichiers à préparer
- [ ] Créer `.env.production` avec les bonnes configurations
- [ ] Vérifier que tous les fichiers sont prêts pour l'upload

## 🌐 Configuration Hostinger

### 3. Panneau de contrôle
- [ ] Accéder au panneau de contrôle Hostinger
- [ ] Vérifier la version PHP (minimum 8.1)
- [ ] Activer les extensions PHP nécessaires :
  - [ ] `fileinfo`
  - [ ] `openssl`
  - [ ] `pdo_mysql`
  - [ ] `mbstring`
  - [ ] `tokenizer`
  - [ ] `xml`
  - [ ] `ctype`
  - [ ] `json`

### 4. Base de données
- [ ] Créer une base de données MySQL
- [ ] Noter les informations de connexion :
  - [ ] Nom de la base de données
  - [ ] Nom d'utilisateur
  - [ ] Mot de passe
  - [ ] Hôte (généralement localhost)

### 5. Email SMTP
- [ ] Configurer les emails dans le panneau Hostinger
- [ ] Noter les paramètres SMTP :
  - [ ] Serveur SMTP
  - [ ] Port
  - [ ] Nom d'utilisateur
  - [ ] Mot de passe
  - [ ] Chiffrement (TLS/SSL)

## 📤 Upload des fichiers

### 6. Structure des dossiers
- [ ] Uploader tous les fichiers dans `public_html/`
- [ ] Vérifier que la structure est correcte :
  ```
  public_html/
  ├── app/
  ├── bootstrap/
  ├── config/
  ├── database/
  ├── public/
  ├── resources/
  ├── routes/
  ├── storage/
  ├── vendor/
  ├── .env
  ├── artisan
  └── composer.json
  ```

### 7. Fichiers .htaccess
- [ ] Vérifier que `.htaccess` est présent dans `public_html/`
- [ ] Vérifier que `.htaccess` est présent dans `public_html/public/`

## ⚙️ Configuration sur le serveur

### 8. Variables d'environnement
- [ ] Créer le fichier `.env` sur le serveur avec :
  ```env
  APP_NAME="EduSmart"
  APP_ENV=production
  APP_KEY=base64:votre_clé_générée
  APP_DEBUG=false
  APP_URL=https://votre-domaine.com
  
  DB_CONNECTION=mysql
  DB_HOST=localhost
  DB_PORT=3306
  DB_DATABASE=votre_nom_db
  DB_USERNAME=votre_username_db
  DB_PASSWORD=votre_password_db
  
  MAIL_MAILER=smtp
  MAIL_HOST=mail.votre-domaine.com
  MAIL_PORT=587
  MAIL_USERNAME=info@votre-domaine.com
  MAIL_PASSWORD=votre_password_email
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS="info@votre-domaine.com"
  MAIL_FROM_NAME="EduSmart"
  ```

### 9. Permissions des dossiers
- [ ] `chmod -R 755 storage/`
- [ ] `chmod -R 755 bootstrap/cache/`
- [ ] `chmod 644 .env`

### 10. Commandes Laravel
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `composer dump-autoload --optimize`

## 🧪 Tests post-déploiement

### 11. Tests de base
- [ ] L'application est accessible sur https://votre-domaine.com
- [ ] Pas d'erreurs 500 ou 404
- [ ] Les assets CSS/JS se chargent correctement
- [ ] Le logo et les images s'affichent

### 12. Tests de fonctionnalités
- [ ] Connexion utilisateur fonctionne
- [ ] Création d'un compte fonctionne
- [ ] Dashboard s'affiche correctement
- [ ] Navigation entre les pages fonctionne
- [ ] Formulaires fonctionnent (création d'écoles, classes, etc.)
- [ ] Upload de fichiers fonctionne
- [ ] Envoi d'emails fonctionne

### 13. Tests spécifiques EduSmart
- [ ] Création d'écoles
- [ ] Création de classes
- [ ] Ajout d'enseignants
- [ ] Ajout d'étudiants
- [ ] Création d'appels vidéo
- [ ] Système de notifications
- [ ] Gestion des rôles et permissions

## 🔧 Dépannage

### 14. Logs à vérifier
- [ ] `storage/logs/laravel.log`
- [ ] Logs d'erreur du serveur web
- [ ] Logs de la base de données

### 15. Problèmes courants
- [ ] Erreur 500 : Vérifier les permissions et les logs
- [ ] Erreur de base de données : Vérifier les paramètres de connexion
- [ ] Assets non chargés : Vérifier le fichier .htaccess
- [ ] Emails non envoyés : Vérifier la configuration SMTP

## 📞 Support

### 16. En cas de problème
- [ ] Consulter les logs d'erreur
- [ ] Vérifier la documentation Laravel
- [ ] Contacter le support Hostinger si nécessaire
- [ ] Vérifier les forums Laravel pour des solutions

## ✅ Validation finale

- [ ] L'application est entièrement fonctionnelle
- [ ] Toutes les fonctionnalités ont été testées
- [ ] Les performances sont acceptables
- [ ] La sécurité est en place
- [ ] Les sauvegardes sont configurées
- [ ] Le monitoring est en place

---

**Note :** Cette checklist doit être adaptée selon vos besoins spécifiques et la configuration de votre serveur Hostinger. 