<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all teachers
        $teachers = Teacher::all();

        foreach ($teachers as $teacher) {
            // Create user account for teacher
            $user = User::create([
                'first_name' => $teacher->teacher_firstname,
                'last_name' => $teacher->teacher_lastname,
                'email' => $teacher->teacher_email,
                'password' => Hash::make('password'), // Default password
            ]);

            // Assign teacher role
            $user->assignRole('teacher');

            // Link user to teacher record (assuming there's a user_id column in teachers table)
            $teacher->user_id = $user->id;
            $teacher->save();
        }

        // Create an admin user if it doesn't exist
        if (!User::where('email', 'admin@school.com')->exists()) {
            $admin = User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@school.com',
                'password' => Hash::make('password'),
            ]);

            $admin->assignRole('admin');
        }

        // Create a demo teacher user if it doesn't exist
        if (!User::where('email', 'teacher@school.com')->exists()) {
            $teacher = User::create([
                'first_name' => 'Demo',
                'last_name' => 'Teacher',
                'email' => 'teacher@school.com',
                'password' => Hash::make('password'),
            ]);

            $teacher->assignRole('teacher');

            // Create and link teacher record
            Teacher::create([
                'teacher_firstname' => 'Demo',
                'teacher_lastname' => 'Teacher',
                'teacher_email' => 'teacher@school.com',
                'teacher_phone' => '1234567890',
                'date_of_birth' => '1990-01-01',
                'gender' => 'other',
                'address' => '123 School St',
                'grade' => 'Senior',
                'speciality' => 'General Education',
                'subject_title' => 'Multiple Subjects',
                'status' => 'active',
                'user_id' => $teacher->id,
            ]);
        }
    }
}
