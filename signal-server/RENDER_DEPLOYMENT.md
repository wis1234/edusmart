# Déploiement sur Render

Ce guide vous explique comment déployer le serveur de signalisation EduSmart sur Render.

## 🚀 Déploiement automatique avec render.yaml

### 1. **Préparer le repository**

Assurez-vous que votre code est dans un repository Git (GitHub, GitLab, etc.) avec la structure suivante :

```
signal-server/
├── server.js
├── package.json
├── render.yaml
├── .gitignore
└── README.md
```

### 2. **Connecter à Render**

1. Allez sur [render.com](https://render.com)
2. Créez un compte ou connectez-vous
3. Cliquez sur "New +" → "Blueprint"
4. Connectez votre repository Git
5. Render détectera automatiquement le fichier `render.yaml`

### 3. **Configuration automatique**

Le fichier `render.yaml` configure automatiquement :
- ✅ Type de service : Web Service
- ✅ Environnement : Node.js
- ✅ Plan : Starter (gratuit)
- ✅ Variables d'environnement
- ✅ Health check
- ✅ Auto-déploiement

## 🔧 Déploiement manuel

Si vous préférez déployer manuellement :

### 1. **Créer un nouveau Web Service**

1. Dans Render Dashboard → "New +" → "Web Service"
2. Connectez votre repository Git
3. Configurez :
   - **Name** : `edusmart-signal-server`
   - **Environment** : `Node`
   - **Build Command** : `npm install`
   - **Start Command** : `npm start`

### 2. **Variables d'environnement**

Ajoutez ces variables dans Render :

| Variable | Valeur |
|----------|--------|
| `NODE_ENV` | `production` |
| `PORT` | `10000` (Render définit automatiquement) |
| `FRONTEND_URL` | `https://edusmart.erequest.net` |
| `LARAVEL_URL` | `https://edusmart.erequest.net` |
| `JWT_SECRET` | `votre-clé-secrète-générée` |

### 3. **Health Check**

Configurez le health check :
- **Path** : `/health`
- **Timeout** : `5s`

## 🌐 Configuration du frontend

Une fois déployé, vous obtiendrez une URL comme :
`https://edusmart-signal-server.onrender.com`

### Mettre à jour le frontend Laravel

Dans votre application Laravel, mettez à jour la configuration WebSocket :

```javascript
// Dans resources/js/video-call.js
const socket = io('https://edusmart-signal-server.onrender.com', {
    auth: {
        token: userToken
    }
});
```

Ou ajoutez une variable d'environnement dans Laravel :

```env
# .env Laravel
SIGNAL_SERVER_URL=https://edusmart-signal-server.onrender.com
```

## 📊 Monitoring

### Logs Render
- Accédez aux logs via le dashboard Render
- Logs en temps réel disponibles
- Historique des déploiements

### Health Check
Testez votre serveur :
```bash
curl https://edusmart-signal-server.onrender.com/health
```

Réponse attendue :
```json
{
  "status": "ok",
  "timestamp": "2024-01-01T12:00:00.000Z",
  "activeRooms": 0,
  "activeConnections": 0
}
```

## 🔄 Mise à jour

### Auto-déploiement
- Render déploie automatiquement à chaque push sur la branche principale
- Vous pouvez configurer des branches spécifiques

### Déploiement manuel
1. Push sur votre repository
2. Render détecte automatiquement les changements
3. Déploiement automatique en quelques minutes

## 💰 Coûts

### Plan Starter (Gratuit)
- ✅ 750 heures/mois
- ✅ 512 MB RAM
- ✅ 0.1 CPU
- ✅ Sleep après 15 minutes d'inactivité

### Plan Pro (Payant)
- ✅ Pas de limite d'heures
- ✅ Plus de RAM/CPU
- ✅ Pas de sleep

## 🛠️ Dépannage

### Problèmes courants

1. **Build échoue**
   - Vérifiez les logs de build
   - Assurez-vous que `package.json` est correct

2. **Service ne démarre pas**
   - Vérifiez les logs de runtime
   - Testez localement avec `npm start`

3. **CORS errors**
   - Vérifiez `FRONTEND_URL` dans les variables d'environnement
   - Assurez-vous que l'URL est exacte

4. **WebSocket ne se connecte pas**
   - Vérifiez l'URL du serveur dans le frontend
   - Testez la connexion avec un client WebSocket

### Support
- Logs détaillés dans Render Dashboard
- Documentation Render : [docs.render.com](https://docs.render.com)
- Support Render disponible pour les comptes payants

## ✅ Checklist de déploiement

- [ ] Repository Git configuré
- [ ] `render.yaml` présent
- [ ] Variables d'environnement configurées
- [ ] Health check fonctionne
- [ ] Frontend mis à jour avec la nouvelle URL
- [ ] Test de connexion WebSocket réussi
- [ ] Test d'appel vidéo réussi 