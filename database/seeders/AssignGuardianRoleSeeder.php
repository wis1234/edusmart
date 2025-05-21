<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignGuardianRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign 'guardian' role to all users who should have it
        $guardianUsers = User::where('email', 'ronaldoagbohou@gmail.com')->get();

        foreach ($guardianUsers as $user) {
            if (!$user->hasRole('guardian')) {
                $user->assignRole('guardian');
                $this->command->info("Assigned 'guardian' role to user: {$user->email}");
            }
        }
    }
}
