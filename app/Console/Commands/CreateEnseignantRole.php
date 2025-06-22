<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateEnseignantRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:create-enseignant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create enseignant role with all necessary permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Création du rôle enseignant...');

        // Créer le rôle enseignant s'il n'existe pas
        $enseignantRole = Role::firstOrCreate(['name' => 'enseignant']);
        $this->info("Rôle enseignant créé/récupéré.");

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

        $this->info('Vérification et création des permissions...');

        // Vérifier et créer les permissions si elles n'existent pas
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $this->line("Permission '{$permissionName}' vérifiée/créée.");
        }

        // Donner toutes les permissions au rôle enseignant
        $enseignantRole->syncPermissions($permissions);

        $this->info('Permissions assignées au rôle enseignant:');
        foreach ($permissions as $permission) {
            $this->line("- {$permission}");
        }

        $this->info('Rôle enseignant créé avec succès!');
        $this->line("ID du rôle: " . $enseignantRole->id);
        $this->line("Nom du rôle: " . $enseignantRole->name);

        return 0;
    }
} 