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
        Permission::firstOrCreate(['name' => 'view students']);
        Permission::firstOrCreate(['name' => 'create students']);
        Permission::firstOrCreate(['name' => 'edit students']);
        Permission::firstOrCreate(['name' => 'delete students']);

        // Teacher permissions
        Permission::firstOrCreate(['name' => 'view teachers']);
        Permission::firstOrCreate(['name' => 'create teachers']);
        Permission::firstOrCreate(['name' => 'edit teachers']);
        Permission::firstOrCreate(['name' => 'delete teachers']);

        // School permissions
        Permission::firstOrCreate(['name' => 'view schools']);
        Permission::firstOrCreate(['name' => 'create schools']);
        Permission::firstOrCreate(['name' => 'edit schools']);
        Permission::firstOrCreate(['name' => 'delete schools']);

        // Class Room permissions
        Permission::firstOrCreate(['name' => 'view class_rooms']);
        Permission::firstOrCreate(['name' => 'create class_rooms']);
        Permission::firstOrCreate(['name' => 'edit class_rooms']);
        Permission::firstOrCreate(['name' => 'delete class_rooms']);

        // Subject permissions
        Permission::firstOrCreate(['name' => 'view subjects']);
        Permission::firstOrCreate(['name' => 'create subjects']);
        Permission::firstOrCreate(['name' => 'edit subjects']);
        Permission::firstOrCreate(['name' => 'delete subjects']);

        // Evaluation permissions
        Permission::firstOrCreate(['name' => 'view evaluations']);
        Permission::firstOrCreate(['name' => 'create evaluations']);
        Permission::firstOrCreate(['name' => 'edit evaluations']);
        Permission::firstOrCreate(['name' => 'delete evaluations']);

        // Grade permissions
        Permission::firstOrCreate(['name' => 'view grades']);
        Permission::firstOrCreate(['name' => 'create grades']);
        Permission::firstOrCreate(['name' => 'edit grades']);
        Permission::firstOrCreate(['name' => 'delete grades']);

        // User permissions
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'edit users']);
        Permission::firstOrCreate(['name' => 'delete users']);
    }
}
