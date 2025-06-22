<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RonaldoAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Find or create the admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // 2. Create the admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'ronaldoagbohou@gmail.com'],
            [
                'first_name' => 'Ronaldo',
                'password' => Hash::make('password'), // Change this to a secure password
                'email_verified_at' => now(),
            ]
        );

        // 3. Assign the admin role to the user
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }
        
        $this->command->info('Admin user ronaldoagbohou@gmail.com created and assigned admin role successfully.');
    }
} 