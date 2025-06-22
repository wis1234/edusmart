<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evaluation;
use App\Models\User;
use App\Models\Teacher;

class CheckEvaluationData extends Command
{
    protected $signature = 'check:evaluation-data {evaluation_id?}';
    protected $description = 'Check evaluation data to understand teacher_id vs created_by';

    public function handle()
    {
        $this->info('Checking Evaluation Data...');
        $this->newLine();

        $evaluationId = $this->argument('evaluation_id');
        
        if ($evaluationId) {
            $evaluations = Evaluation::where('id', $evaluationId)->get();
        } else {
            $evaluations = Evaluation::latest()->take(5)->get();
        }

        foreach ($evaluations as $evaluation) {
            $this->info("=== EVALUATION ID: {$evaluation->id} ===");
            $this->info("Subject: " . ($evaluation->subject->name ?? 'N/A'));
            $this->info("Class: " . ($evaluation->classRoom->name ?? 'N/A'));
            $this->info("School: " . ($evaluation->subject->school->name ?? 'N/A'));
            $this->info("teacher_id: {$evaluation->teacher_id}");
            $this->info("created_by: " . ($evaluation->created_by ?? 'NULL'));
            
            if ($evaluation->teacher) {
                $this->info("Teacher (teacher_id): {$evaluation->teacher->first_name} {$evaluation->teacher->last_name} (User ID: {$evaluation->teacher->id})");
            } else {
                $this->error("Teacher not found for teacher_id: {$evaluation->teacher_id}");
            }
            
            if ($evaluation->creator) {
                $this->info("Creator (created_by): {$evaluation->creator->first_name} {$evaluation->creator->last_name} (User ID: {$evaluation->creator->id})");
            } else {
                $this->warn("Creator not found for created_by: " . ($evaluation->created_by ?? 'NULL'));
            }
            
            // Vérifier si l'enseignant a un profil
            if ($evaluation->teacher) {
                $teacherProfile = Teacher::where('user_id', $evaluation->teacher->id)->first();
                if ($teacherProfile) {
                    $this->info("Teacher Profile: {$teacherProfile->teacher_firstname} {$teacherProfile->teacher_lastname} (School: {$teacherProfile->school_id})");
                } else {
                    $this->warn("No teacher profile found for user ID: {$evaluation->teacher->id}");
                }
            }
            
            $this->newLine();
        }
        
        return 0;
    }
} 