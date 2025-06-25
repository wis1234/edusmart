# Guide de dépannage - Redirection d'évaluation

## Problème
Quand un enseignant crée une évaluation, les données sont bien enregistrées en base, mais il est redirigé vers une page 404 au lieu de la page de détails de l'évaluation.

## Solutions appliquées

### 1. Correction du contrôleur
- ✅ Correction de la méthode `store` pour utiliser `teacher->user_id` au lieu de `teacher->id`
- ✅ Correction de la méthode `show` pour charger les relations avant les vérifications
- ✅ Amélioration des vérifications d'accès pour les enseignants
- ✅ Redirection robuste avec fallback vers URL relative

### 2. Correction de la vue
- ✅ Correction de l'affichage des informations de l'enseignant
- ✅ Utilisation des bonnes relations User/Teacher

## Vérifications à faire

### 1. Configuration de l'URL de base
Vérifiez votre fichier `.env` :
```env
APP_URL=http://votre-domaine.com
```

### 2. Configuration du serveur web

#### Apache (.htaccess)
Assurez-vous que le fichier `public/.htaccess` existe et contient :
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### Nginx
Assurez-vous que votre configuration Nginx redirige vers `public/index.php` :
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 3. Vérification des routes
Exécutez cette commande pour vérifier que les routes sont bien enregistrées :
```bash
php artisan route:list --name=evaluations
```

### 4. Test de la redirection
Pour tester si la redirection fonctionne, créez une évaluation via l'interface web et vérifiez :
1. L'URL de redirection dans la barre d'adresse
2. Les logs d'erreur du serveur web
3. Les logs Laravel dans `storage/logs/laravel.log`

### 5. Cache et configuration
Videz les caches Laravel :
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## Diagnostic

Si le problème persiste, vérifiez :

1. **URL de redirection** : L'URL générée est-elle correcte ?
2. **Serveur web** : Les requêtes arrivent-elles bien à Laravel ?
3. **Permissions** : L'utilisateur a-t-il les bonnes permissions ?
4. **Session** : La session est-elle maintenue ?

## Test manuel

Pour tester manuellement, accédez directement à l'URL :
```
http://votre-domaine.com/evaluations/{id}
```

Si cette URL fonctionne mais pas la redirection, le problème vient de la génération d'URL.

## Solution alternative

Si le problème persiste, vous pouvez temporairement rediriger vers la liste des évaluations :
```php
return redirect()->route('evaluations.index')->with('success', 'Évaluation créée avec succès.');
```

## Support

Si le problème persiste après ces vérifications, fournissez :
1. L'URL exacte de redirection
2. Les logs d'erreur du serveur web
3. La configuration de votre serveur web
4. L'URL de base de votre application 