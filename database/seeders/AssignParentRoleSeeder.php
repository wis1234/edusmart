<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignParentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign 'parent' role to all users who should have it
        $parentUsers = User::where('email', 'ronaldoagbohou@gmail.com')->get();

        foreach ($parentUsers as $user) {
            if (!$user->hasRole('parent')) {
                $user->assignRole('parent');
                $this->command->info("Assigned 'parent' role to user: {$user->email}");
            }
        }
    }
}
