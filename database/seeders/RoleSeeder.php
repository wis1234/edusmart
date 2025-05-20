<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $student = Role::firstOrCreate(['name' => 'student']);
        $parent = Role::firstOrCreate(['name' => 'parent']);

        // Clear existing permissions
        $admin->syncPermissions([]);
        $teacher->syncPermissions([]);
        $student->syncPermissions([]);
        $parent->syncPermissions([]);

        // Admin can do everything
        $admin->givePermissionTo(Permission::all());

        // Teacher permissions
        $teacher->givePermissionTo([
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
            'delete grades',
        ]);

        // Student permissions
        $student->givePermissionTo([
            'view students',
            'view teachers',
            'view class_rooms',
            'view subjects',
            'view evaluations',
            'view grades',
        ]);

        // Parent permissions
        $parent->givePermissionTo([
            'view students',
            'view teachers',
            'view class_rooms',
            'view subjects',
            'view evaluations',
            'view grades',
        ]);
    }
}
