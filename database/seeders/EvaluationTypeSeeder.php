<?php

namespace Database\Seeders;

use App\Models\EvaluationType;
use Illuminate\Database\Seeder;

class EvaluationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Midterm Exam',
                'code' => 'MID',
                'weight' => 30,
                'description' => 'Midterm examination conducted halfway through the term'
            ],
            [
                'name' => 'Final Exam',
                'code' => 'FIN',
                'weight' => 40,
                'description' => 'Final examination conducted at the end of the term'
            ],
            [
                'name' => 'Quiz',
                'code' => 'QUZ',
                'weight' => 10,
                'description' => 'Short tests conducted throughout the term'
            ],
            [
                'name' => 'Assignment',
                'code' => 'ASG',
                'weight' => 10,
                'description' => 'Homework and take-home assignments'
            ],
            [
                'name' => 'Project',
                'code' => 'PRJ',
                'weight' => 10,
                'description' => 'Individual or group projects'
            ]
        ];

        foreach ($types as $type) {
            EvaluationType::create($type);
        }
    }
}
