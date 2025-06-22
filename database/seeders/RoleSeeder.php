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
        $enseignant = Role::firstOrCreate(['name' => 'enseignant']);
        $student = Role::firstOrCreate(['name' => 'student']);
        $parent = Role::firstOrCreate(['name' => 'parent']);
        $school_admin = Role::firstOrCreate(['name' => 'school_admin']);

        // Clear existing permissions
        $admin->syncPermissions([]);
        $teacher->syncPermissions([]);
        $enseignant->syncPermissions([]);
        $student->syncPermissions([]);
        $parent->syncPermissions([]);
        $school_admin->syncPermissions([]);

        // Admin can do everything
        $admin->givePermissionTo(Permission::all());

        // Teacher permissions
        $teacherPermissions = [
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
        ];
        
        $teacher->givePermissionTo($teacherPermissions);
        $enseignant->givePermissionTo($teacherPermissions);
        
        // School Admin permissions
        $school_admin->givePermissionTo([
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',
            'view students',
            'create students',
            'edit students',
            'delete students',
            'view class_rooms',
            'create class_rooms',
            'edit class_rooms',
            'delete class_rooms',
            'view schools',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view subjects',
            'create subjects',
            'edit subjects',
            'delete subjects',
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
