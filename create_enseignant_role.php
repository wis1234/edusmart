<?php

// Script pour créer le rôle enseignant et ses permissions
// À exécuter avec: php artisan tinker < create_enseignant_role.php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "Création du rôle enseignant...\n";

// Créer le rôle enseignant s'il n'existe pas
$enseignantRole = Role::firstOrCreate(['name' => 'enseignant']);
echo "Rôle enseignant créé/récupéré.\n";

// Permissions nécessaires pour les enseignants
$permissions = [
    'view teachers',
    'view students', 
    'view class_rooms',
    'view subjects',
    'view evaluations',
    'create evaluations',
    'edit evaluations',
    'delete evaluations',
    'view grades',
    'create grades',
    'edit grades',
    'delete grades'
];

echo "Vérification et création des permissions...\n";

// Vérifier et créer les permissions si elles n'existent pas
foreach ($permissions as $permissionName) {
    $permission = Permission::firstOrCreate(['name' => $permissionName]);
    echo "Permission '{$permissionName}' vérifiée/créée.\n";
}

// Donner toutes les permissions au rôle enseignant
$enseignantRole->syncPermissions($permissions);

echo "\nPermissions assignées au rôle enseignant:\n";
foreach ($permissions as $permission) {
    echo "- {$permission}\n";
}

echo "\nRôle enseignant créé avec succès!\n";
echo "ID du rôle: " . $enseignantRole->id . "\n";
echo "Nom du rôle: " . $enseignantRole->name . "\n"; 