<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'description' => 'Mathematics subject', 'credits' => 4, 'level' => 'Intermediate', 'hours_per_week' => 5, 'is_active' => true],
            ['name' => 'English', 'code' => 'ENG', 'description' => 'English Language', 'credits' => 3, 'level' => 'Intermediate', 'hours_per_week' => 4, 'is_active' => true],
            ['name' => 'Science', 'code' => 'SCI', 'description' => 'General Science', 'credits' => 4, 'level' => 'Intermediate', 'hours_per_week' => 5, 'is_active' => true],
            ['name' => 'History', 'code' => 'HIST', 'description' => 'History subject', 'credits' => 3, 'level' => 'Intermediate', 'hours_per_week' => 3, 'is_active' => true],
            ['name' => 'Geography', 'code' => 'GEO', 'description' => 'Geography subject', 'credits' => 3, 'level' => 'Intermediate', 'hours_per_week' => 3, 'is_active' => true],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
