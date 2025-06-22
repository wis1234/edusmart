<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evaluation;
use App\Models\Teacher;

class TestEvaluationDisplay extends Command
{
    protected $signature = 'test:evaluation-display {evaluation_id?}';
    protected $description = 'Test evaluation display with teacherProfile relation';

    public function handle()
    {
        $this->info('Testing Evaluation Display with TeacherProfile...');
        $this->newLine();

        $evaluationId = $this->argument('evaluation_id');
        
        if ($evaluationId) {
            $evaluations = Evaluation::where('id', $evaluationId)->get();
        } else {
            $evaluations = Evaluation::latest()->take(3)->get();
        }

        foreach ($evaluations as $evaluation) {
            $evaluation->load(['subject.school', 'classRoom.school', 'evaluationType', 'teacher', 'teacherProfile', 'creator']);
            
            $this->info("=== EVALUATION ID: {$evaluation->id} ===");
            $this->info("Subject: " . ($evaluation->subject->name ?? 'N/A'));
            $this->info("Class: " . ($evaluation->classRoom->name ?? 'N/A'));
            $this->info("School: " . ($evaluation->subject->school->name ?? 'N/A'));
            $this->info("teacher_id: {$evaluation->teacher_id}");
            
            // Test teacher relation (users table)
            if ($evaluation->teacher) {
                $this->info("Teacher (users): {$evaluation->teacher->first_name} {$evaluation->teacher->last_name} (ID: {$evaluation->teacher->id})");
            } else {
                $this->error("Teacher (users): Not found");
            }
            
            // Test teacherProfile relation (teachers table)
            if ($evaluation->teacherProfile) {
                $this->info("TeacherProfile (teachers): {$evaluation->teacherProfile->teacher_firstname} {$evaluation->teacherProfile->teacher_lastname} (ID: {$evaluation->teacherProfile->id})");
                $this->info("Teacher Email: {$evaluation->teacherProfile->teacher_email}");
                $this->info("Teacher School: {$evaluation->teacherProfile->school_id}");
                if ($evaluation->teacherProfile->specialization) {
                    $this->info("Specialization: {$evaluation->teacherProfile->specialization}");
                }
            } else {
                $this->warn("TeacherProfile (teachers): Not found");
                
                // Vérifier manuellement
                $manualCheck = Teacher::where('user_id', $evaluation->teacher_id)->first();
                if ($manualCheck) {
                    $this->info("Manual check found: {$manualCheck->teacher_firstname} {$manualCheck->teacher_lastname}");
                } else {
                    $this->error("Manual check: No teacher profile for user_id {$evaluation->teacher_id}");
                }
            }
            
            $this->newLine();
        }
        
        return 0;
    }
} 