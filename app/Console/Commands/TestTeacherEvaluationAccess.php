<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class TestTeacherEvaluationAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-evaluation-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test teacher access to evaluations from their assigned school, class and subject';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Teacher Evaluation Access (CRUD)...');
        $this->newLine();

        // Get a teacher user
        $teacher = User::where('role', 'enseignant')->first();
        
        if (!$teacher) {
            $this->error('No teacher found in the system.');
            return 1;
        }

        // S'assurer que l'enseignant a le rôle assigné via Spatie
        if (!$teacher->hasRole('enseignant')) {
            $teacher->assignRole('enseignant');
            $this->info("Assigned 'enseignant' role to teacher");
        }

        $this->info("Testing with teacher: {$teacher->name} (ID: {$teacher->id})");
        
        // Récupérer le profil enseignant
        $teacherProfile = Teacher::where('user_id', $teacher->id)->first();
        if ($teacherProfile) {
            $this->info("Teacher's school ID: {$teacherProfile->school_id}");
            $this->info("Teacher's subjects: " . $teacherProfile->subjects()->pluck('subjects.name')->implode(', '));
            $this->info("Teacher's classes: " . $teacherProfile->classRooms()->pluck('class_rooms.name')->implode(', '));
        } else {
            $this->error('No teacher profile found.');
            return 1;
        }
        
        $this->newLine();

        // Get evaluations from teacher's school
        $schoolEvaluations = Evaluation::whereHas('subject', function($q) use ($teacherProfile) {
            $q->where('school_id', $teacherProfile->school_id);
        })->get();
        
        $otherEvaluations = Evaluation::whereHas('subject', function($q) use ($teacherProfile) {
            $q->where('school_id', '!=', $teacherProfile->school_id);
        })->get();

        $this->info("Evaluations in teacher's school: {$schoolEvaluations->count()}");
        $this->info("Evaluations in other schools: {$otherEvaluations->count()}");
        $this->newLine();

        // Test access to evaluations from teacher's school
        $this->info('Testing access to evaluations from teacher\'s school:');
        foreach ($schoolEvaluations->take(3) as $evaluation) {
            $this->testEvaluationAccess($teacher, $evaluation, 'should have access');
        }

        // Test access to evaluations from other schools
        $this->newLine();
        $this->info('Testing access to evaluations from other schools:');
        foreach ($otherEvaluations->take(3) as $evaluation) {
            $this->testEvaluationAccess($teacher, $evaluation, 'should NOT have access');
        }

        // Test policy methods
        $this->newLine();
        $this->info('Testing Policy Methods:');
        
        Auth::login($teacher);
        
        $policy = app(\App\Policies\EvaluationPolicy::class);
        
        // Test viewAny
        $canViewAny = $policy->viewAny($teacher);
        $this->info("Can view any evaluations: " . ($canViewAny ? 'YES' : 'NO'));
        
        // Test create
        $canCreate = $policy->create($teacher);
        $this->info("Can create evaluations: " . ($canCreate ? 'YES' : 'NO'));
        
        // Test update
        if ($schoolEvaluations->isNotEmpty()) {
            $evaluation = $schoolEvaluations->first();
            $canUpdate = $policy->update($teacher, $evaluation);
            $this->info("Can update evaluations: " . ($canUpdate ? 'YES' : 'NO'));
        }
        
        // Test delete
        if ($schoolEvaluations->isNotEmpty()) {
            $evaluation = $schoolEvaluations->first();
            $canDelete = $policy->delete($teacher, $evaluation);
            $this->info("Can delete evaluations: " . ($canDelete ? 'YES' : 'NO'));
        }

        $this->newLine();
        $this->info('Test completed!');
        
        return 0;
    }

    private function testEvaluationAccess($teacher, $evaluation, $expected)
    {
        $this->line("  - Evaluation: {$evaluation->subject->name} - {$evaluation->classRoom->name} (School: {$evaluation->subject->school_id})");
        
        // Forcer l'authentification de l'utilisateur
        \Illuminate\Support\Facades\Auth::login($teacher);
        
        // Test view permission
        $policy = app(\App\Policies\EvaluationPolicy::class);
        $canView = $policy->view($teacher, $evaluation);
        
        $status = $canView ? '✓ ACCESS' : '✗ NO ACCESS';
        $this->line("    View: {$status} ({$expected})");
        
        // Test update permission
        $canUpdate = $policy->update($teacher, $evaluation);
        $updateStatus = $canUpdate ? '✓ CAN UPDATE' : '✗ NO UPDATE';
        $this->line("    Update: {$updateStatus}");
        
        // Test delete permission
        $canDelete = $policy->delete($teacher, $evaluation);
        $deleteStatus = $canDelete ? '✓ CAN DELETE' : '✗ NO DELETE';
        $this->line("    Delete: {$deleteStatus}");
    }
} 