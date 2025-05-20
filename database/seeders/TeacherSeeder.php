<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first school
        $school = School::first();
        if (!$school) {
            return;
        }

        // Get subjects and classrooms
        $subjects = Subject::take(3)->get();
        if ($subjects->isEmpty()) {
            return;
        }

        $classRooms = ClassRoom::take(2)->get();
        if ($classRooms->isEmpty()) {
            return;
        }

        // Create a Math teacher user
        $mathTeacherUser = User::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john.smith@school.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'address' => '123 Teacher St, School City',
            'date_of_birth' => '1980-01-01',
            'gender' => 'male',
            'status' => 'active',
            'school_id' => $school->id
        ]);

        // Assign teacher role
        $mathTeacherUser->assignRole('teacher');

        // Create Math teacher profile
        $mathTeacher = Teacher::create([
            'teacher_firstname' => $mathTeacherUser->first_name,
            'teacher_lastname' => $mathTeacherUser->last_name,
            'teacher_email' => $mathTeacherUser->email,
            'teacher_phone' => $mathTeacherUser->phone,
            'date_of_birth' => $mathTeacherUser->date_of_birth,
            'gender' => $mathTeacherUser->gender,
            'address' => $mathTeacherUser->address,
            'grade' => 'Senior',
            'speciality' => 'Mathematics',
            'subject_title' => 'Advanced Mathematics',
            'status' => 'active',
            'profile_photo' => $mathTeacherUser->profile_photo,
            'user_id' => $mathTeacherUser->id,
            'school_id' => $school->id
        ]);

        // Create a Science teacher user
        $scienceTeacherUser = User::create([
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'email' => 'sarah.johnson@school.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => '456 Teacher St, School City',
            'date_of_birth' => '1985-05-15',
            'gender' => 'female',
            'status' => 'active',
            'school_id' => $school->id
        ]);

        // Assign teacher role
        $scienceTeacherUser->assignRole('teacher');

        // Create Science teacher profile
        $scienceTeacher = Teacher::create([
            'teacher_firstname' => $scienceTeacherUser->first_name,
            'teacher_lastname' => $scienceTeacherUser->last_name,
            'teacher_email' => $scienceTeacherUser->email,
            'teacher_phone' => $scienceTeacherUser->phone,
            'date_of_birth' => $scienceTeacherUser->date_of_birth,
            'gender' => $scienceTeacherUser->gender,
            'address' => $scienceTeacherUser->address,
            'grade' => 'Senior',
            'speciality' => 'Science',
            'subject_title' => 'Physics',
            'status' => 'active',
            'profile_photo' => $scienceTeacherUser->profile_photo,
            'user_id' => $scienceTeacherUser->id,
            'school_id' => $school->id
        ]);

        $currentYear = date('Y');

        // Assign subjects and classrooms to math teacher
        foreach ($subjects->take(2) as $index => $subject) {
            if (isset($classRooms[$index])) {
                // Attach subject
                $mathTeacher->subjects()->attach($subject->id, [
                    'year' => $currentYear
                ]);

                // Attach classroom with subject
                $mathTeacher->classRooms()->attach($classRooms[$index]->id, [
                    'subject_id' => $subject->id,
                    'year' => $currentYear,
                    'start_time' => '08:00:00',
                    'end_time' => '09:30:00',
                    'days_of_week' => json_encode(['Monday', 'Wednesday'])
                ]);
            }
        }

        // Assign subject and classroom to science teacher
        if ($subjects->last() && $classRooms->first()) {
            $scienceTeacher->subjects()->attach($subjects->last()->id, [
                'year' => $currentYear
            ]);

            $scienceTeacher->classRooms()->attach($classRooms->first()->id, [
                'subject_id' => $subjects->last()->id,
                'year' => $currentYear,
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'days_of_week' => json_encode(['Tuesday', 'Thursday'])
            ]);
        }
    }
}
