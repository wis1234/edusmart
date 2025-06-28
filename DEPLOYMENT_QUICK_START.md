# Guide de Déploiement Rapide - EduSmart

## 🚀 Déploiement du Serveur de Signalisation sur Render

### 1. Configuration Render

1. **Connectez-vous à Render** : https://dashboard.render.com
2. **Créez un nouveau Web Service**
3. **Connectez votre repository GitHub**
4. **Configuration du service** :
   - **Name** : `edusmart-signal-server`
   - **Environment** : `Node`
   - **Build Command** : `npm install`
   - **Start Command** : `node server.js`
   - **Plan** : `Free` (ou `Starter` pour plus de ressources)

### 2. Variables d'Environnement

Ajoutez ces variables d'environnement dans Render :

```bash
FRONTEND_URL=https://edusmart.erequest.net
LARAVEL_URL=https://edusmart.erequest.net
JWT_SECRET=votre-secret-jwt-securise
NODE_ENV=production
SIGNAL_PORT=3001
```

**Note importante** : Assurez-vous que le serveur de signalisation sur Render est configuré pour accepter les connexions depuis `https://edusmart.erequest.net` dans les paramètres CORS.

### 3. Déploiement de l'Application Laravel

#### Option A : Déploiement sur le même serveur

1. **Uploadez le code Laravel** sur votre serveur
2. **Exécutez le script de déploiement** :
   ```bash
   chmod +x deploy-production.sh
   ./deploy-production.sh
   ```

#### Option B : Déploiement séparé

1. **Installez les dépendances** :
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install --production
   npm run build
   ```

2. **Configurez l'environnement** :
   ```bash
   cp .env.example .env
   # Éditez .env avec vos paramètres de production
   ```

3. **Optimisez l'application** :
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Mettez à jour la base de données** :
   ```bash
   php artisan migrate --force
   ```

### 4. Configuration Nginx

#### Pour le serveur de signalisation (optionnel)

Si vous voulez un sous-domaine dédié :

```nginx
# /etc/nginx/sites-available/signal-server
server {
    listen 80;
    server_name signal.edusmart.erequest.net;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name signal.edusmart.erequest.net;
    
    ssl_certificate /etc/letsencrypt/live/signal.edusmart.erequest.net/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/signal.edusmart.erequest.net/privkey.pem;
    
    location / {
        proxy_pass https://edusmart-signal-server.onrender.com;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        
        proxy_read_timeout 86400;
        proxy_send_timeout 86400;
        proxy_connect_timeout 60;
    }
}
```

### 5. Test de Connexion

1. **Testez le serveur de signalisation** :
   ```bash
   curl https://edusmart-signal-server.onrender.com/health
   ```

2. **Vérifiez les logs** :
   - **Render** : Dashboard Render → Logs
   - **Laravel** : `tail -f storage/logs/laravel.log`

### 6. URLs de Production

- **Application Laravel** : `https://edusmart.erequest.net`
- **Serveur de Signalisation** : `https://node-whatsapp-1.onrender.com`
- **Health Check** : `https://node-whatsapp-1.onrender.com/health`

### 7. Commandes Utiles

```bash
# Redémarrer le serveur de signalisation (Render)
# Via le dashboard Render

# Vérifier les logs Laravel
tail -f storage/logs/laravel.log

# Vider les caches Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Vérifier la santé de l'application
curl https://edusmart.erequest.net/health
```

### 8. Dépannage

#### Problème de connexion WebSocket
- Vérifiez que l'URL du serveur de signalisation est correcte dans `resources/views/video_calls/show.blade.php`
- Assurez-vous que les variables d'environnement sont configurées sur Render
- Vérifiez les logs Render pour les erreurs

#### Problème d'authentification
- Vérifiez que `JWT_SECRET` est identique entre Laravel et le serveur de signalisation
- Assurez-vous que les tokens d'authentification sont valides

#### Problème de CORS
- Vérifiez que `FRONTEND_URL` pointe vers le bon domaine
- Assurez-vous que le protocole (http/https) correspond

### 9. Sécurité

- Changez `JWT_SECRET` pour une valeur sécurisée
- Utilisez HTTPS partout
- Configurez les certificats SSL
- Surveillez les logs pour les tentatives d'intrusion

### 10. Monitoring

- **Render** : Dashboard avec métriques automatiques
- **Laravel** : Logs dans `storage/logs/`
- **Base de données** : Surveillez les performances
- **Réseau** : Surveillez la bande passante WebSocket

---

## ✅ Checklist de Déploiement

- [ ] Serveur de signalisation déployé sur Render
- [ ] Variables d'environnement configurées
- [ ] Application Laravel déployée
- [ ] Base de données migrée
- [ ] Assets compilés
- [ ] Caches optimisés
- [ ] SSL configuré
- [ ] Tests de connexion réussis
- [ ] Logs surveillés
- [ ] Sauvegarde configurée 