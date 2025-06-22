<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class TestEvaluationShow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:evaluation-show {evaluation_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test evaluation show view data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Evaluation Show View...');
        $this->newLine();

        // Get evaluation ID from argument or find the latest one
        $evaluationId = $this->argument('evaluation_id');
        
        if ($evaluationId) {
            $evaluation = Evaluation::find($evaluationId);
        } else {
            $evaluation = Evaluation::latest()->first();
        }
        
        if (!$evaluation) {
            $this->error('No evaluation found in the system.');
            return 1;
        }

        $this->info("Testing with evaluation ID: {$evaluation->id}");
        $this->newLine();

        // Load all necessary relationships
        $evaluation->load([
            'subject.school', 
            'classRoom.school', 
            'evaluationType', 
            'teacher', 
            'creator',
            'studentGrades.student'
        ]);

        // Display evaluation details
        $this->info('=== EVALUATION DETAILS ===');
        $this->info("ID: {$evaluation->id}");
        $this->info("Academic Year: {$evaluation->academic_year}");
        $this->info("Term: {$evaluation->term}");
        $this->info("Date: {$evaluation->evaluation_date->format('d M, Y')}");
        $this->info("Total Marks: {$evaluation->total_marks}");
        $this->info("Passing Marks: {$evaluation->passing_marks}");
        $this->info("Notes: " . ($evaluation->notes ?? 'N/A'));
        
        $this->newLine();

        // Display subject information
        $this->info('=== SUBJECT INFORMATION ===');
        if ($evaluation->subject) {
            $this->info("Subject ID: {$evaluation->subject->id}");
            $this->info("Subject Name: {$evaluation->subject->name}");
            if ($evaluation->subject->school) {
                $this->info("School: {$evaluation->subject->school->name} (ID: {$evaluation->subject->school->id})");
            } else {
                $this->info("School: N/A");
            }
        } else {
            $this->error("Subject: N/A");
        }
        
        $this->newLine();

        // Display classroom information
        $this->info('=== CLASSROOM INFORMATION ===');
        if ($evaluation->classRoom) {
            $this->info("Classroom ID: {$evaluation->classRoom->id}");
            $this->info("Classroom Name: {$evaluation->classRoom->name}");
            if ($evaluation->classRoom->school) {
                $this->info("School: {$evaluation->classRoom->school->name} (ID: {$evaluation->classRoom->school->id})");
            } else {
                $this->info("School: N/A");
            }
        } else {
            $this->error("Classroom: N/A");
        }
        
        $this->newLine();

        // Display teacher information
        $this->info('=== TEACHER INFORMATION ===');
        if ($evaluation->teacher) {
            $this->info("Teacher User ID: {$evaluation->teacher->id}");
            $this->info("Teacher Name: {$evaluation->teacher->first_name} {$evaluation->teacher->last_name}");
            $this->info("Teacher Email: {$evaluation->teacher->email}");
            
            // Get teacher profile
            $teacherProfile = Teacher::where('user_id', $evaluation->teacher->id)->first();
            if ($teacherProfile) {
                $this->info("Teacher Profile ID: {$teacherProfile->id}");
                $this->info("Specialization: " . ($teacherProfile->specialization ?? 'N/A'));
                $this->info("Teacher School ID: {$teacherProfile->school_id}");
            } else {
                $this->warn("No teacher profile found");
            }
        } else {
            $this->error("Teacher: N/A");
        }
        
        $this->newLine();

        // Display evaluation type
        $this->info('=== EVALUATION TYPE ===');
        if ($evaluation->evaluationType) {
            $this->info("Type ID: {$evaluation->evaluationType->id}");
            $this->info("Type Name: {$evaluation->evaluationType->name}");
        } else {
            $this->error("Evaluation Type: N/A");
        }
        
        $this->newLine();

        // Display creator information
        $this->info('=== CREATOR INFORMATION ===');
        if ($evaluation->creator) {
            $this->info("Creator ID: {$evaluation->creator->id}");
            $this->info("Creator Name: {$evaluation->creator->first_name} {$evaluation->creator->last_name}");
            $this->info("Creator Email: {$evaluation->creator->email}");
        } else {
            $this->error("Creator: N/A");
        }
        
        $this->newLine();

        // Display student grades statistics
        $this->info('=== STUDENT GRADES STATISTICS ===');
        $totalStudents = $evaluation->studentGrades()->count();
        $gradedStudents = $evaluation->studentGrades()->whereNotNull('marks_obtained')->count();
        $passedStudents = $evaluation->studentGrades()->where('marks_obtained', '>=', $evaluation->passing_marks)->count();
        
        $this->info("Total Students: {$totalStudents}");
        $this->info("Graded Students: {$gradedStudents}");
        $this->info("Passed Students: {$passedStudents}");
        
        if ($gradedStudents > 0) {
            $passRate = round(($passedStudents / $gradedStudents) * 100, 1);
            $this->info("Pass Rate: {$passRate}%");
        }
        
        $this->newLine();
        $this->info('Test completed!');
        
        return 0;
    }
} 