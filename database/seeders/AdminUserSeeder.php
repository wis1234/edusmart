<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'first_name' => 'Ronaldo',
            'last_name' => 'Agbohou',
            'email' => 'ronaldoagbohou@gmail.com',
            'password' => Hash::make('passpass'),
            'status' => 'active'
        ]);

        $admin->assignRole('admin');
    }
}
