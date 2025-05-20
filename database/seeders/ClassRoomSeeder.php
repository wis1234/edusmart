<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\School;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();

        if (!$school) {
            return;
        }

        $classRooms = [
            [
                'name' => 'Class 1A',
                'grade_level' => '1',
                'section' => 'A',
                'room_number' => '101',
                'capacity' => 30,
                'building' => 'Main Building',
                'floor' => 1,
                'start_time' => '08:00:00',
                'end_time' => '15:00:00',
                'days_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'academic_year' => '2024',
                'is_active' => true,
                'school_id' => $school->id,
            ],
            [
                'name' => 'Class 1B',
                'grade_level' => '1',
                'section' => 'B',
                'room_number' => '102',
                'capacity' => 30,
                'building' => 'Main Building',
                'floor' => 1,
                'start_time' => '08:00:00',
                'end_time' => '15:00:00',
                'days_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'academic_year' => '2024',
                'is_active' => true,
                'school_id' => $school->id,
            ],
            [
                'name' => 'Class 2A',
                'grade_level' => '2',
                'section' => 'A',
                'room_number' => '201',
                'capacity' => 30,
                'building' => 'Main Building',
                'floor' => 2,
                'start_time' => '08:00:00',
                'end_time' => '15:00:00',
                'days_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'academic_year' => '2024',
                'is_active' => true,
                'school_id' => $school->id,
            ],
            [
                'name' => 'Class 2B',
                'grade_level' => '2',
                'section' => 'B',
                'room_number' => '202',
                'capacity' => 30,
                'building' => 'Main Building',
                'floor' => 2,
                'start_time' => '08:00:00',
                'end_time' => '15:00:00',
                'days_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'academic_year' => '2024',
                'is_active' => true,
                'school_id' => $school->id,
            ],
        ];

        foreach ($classRooms as $classRoom) {
            ClassRoom::create($classRoom);
        }
    }
}
