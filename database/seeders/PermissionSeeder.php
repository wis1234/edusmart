<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Student permissions
        Permission::create(['name' => 'view students']);
        Permission::create(['name' => 'create students']);
        Permission::create(['name' => 'edit students']);
        Permission::create(['name' => 'delete students']);

        // Teacher permissions
        Permission::create(['name' => 'view teachers']);
        Permission::create(['name' => 'create teachers']);
        Permission::create(['name' => 'edit teachers']);
        Permission::create(['name' => 'delete teachers']);

        // School permissions
        Permission::create(['name' => 'view schools']);
        Permission::create(['name' => 'create schools']);
        Permission::create(['name' => 'edit schools']);
        Permission::create(['name' => 'delete schools']);

        // Class Room permissions
        Permission::create(['name' => 'view class_rooms']);
        Permission::create(['name' => 'create class_rooms']);
        Permission::create(['name' => 'edit class_rooms']);
        Permission::create(['name' => 'delete class_rooms']);

        // Subject permissions
        Permission::create(['name' => 'view subjects']);
        Permission::create(['name' => 'create subjects']);
        Permission::create(['name' => 'edit subjects']);
        Permission::create(['name' => 'delete subjects']);

        // Evaluation permissions
        Permission::create(['name' => 'view evaluations']);
        Permission::create(['name' => 'create evaluations']);
        Permission::create(['name' => 'edit evaluations']);
        Permission::create(['name' => 'delete evaluations']);

        // Grade permissions
        Permission::create(['name' => 'view grades']);
        Permission::create(['name' => 'create grades']);
        Permission::create(['name' => 'edit grades']);
        Permission::create(['name' => 'delete grades']);
    }
}
