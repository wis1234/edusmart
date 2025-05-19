<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::create([
            'name' => 'EduSmart Academy',
            'code' => 'ESA-001',
            'description' => 'A leading educational institution focused on innovative learning',
            'address' => '123 Education Street',
            'city' => 'Cotonou',
            'state' => 'Littoral',
            'country' => 'Benin',
            'postal_code' => '12345',
            'phone' => '+229 12345678',
            'email' => 'contact@edusmartacademy.com',
            'website' => 'www.edusmartacademy.com',
            'principal_name' => 'Dr. John Doe',
            'type' => 'private',
            'capacity' => 1000,
            'status' => 'active'
        ]);

        // Add a second school
        School::create([
            'name' => 'EduSmart International School',
            'code' => 'ESI-002',
            'description' => 'International school offering world-class education',
            'address' => '456 Learning Avenue',
            'city' => 'Porto-Novo',
            'state' => 'Ouémé',
            'country' => 'Benin',
            'postal_code' => '23456',
            'phone' => '+229 87654321',
            'email' => 'contact@edusmartinternational.com',
            'website' => 'www.edusmartinternational.com',
            'principal_name' => 'Dr. Jane Smith',
            'type' => 'international',
            'capacity' => 800,
            'status' => 'active'
        ]);
    }
}
