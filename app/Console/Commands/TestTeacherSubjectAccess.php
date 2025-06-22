<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subject;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class TestTeacherSubjectAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-subject-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test teacher access to subjects from their assigned school (read-only)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Teacher Subject Access (Read-only)...');
        $this->newLine();

        // Get a teacher user
        $teacher = User::where('role', 'enseignant')->first();
        
        if (!$teacher) {
            $this->error('No teacher found in the system.');
            return 1;
        }

        $this->info("Testing with teacher: {$teacher->name} (ID: {$teacher->id})");
        $this->info("Teacher's school ID: {$teacher->school_id}");
        $this->newLine();

        // S'assurer que l'enseignant a le rôle assigné via Spatie
        if (!$teacher->hasRole('enseignant')) {
            $teacher->assignRole('enseignant');
            $this->info("Assigned 'enseignant' role to teacher");
        }

        // Get subjects from teacher's school
        $schoolSubjects = Subject::where('school_id', $teacher->school_id)->get();
        $otherSubjects = Subject::where('school_id', '!=', $teacher->school_id)->get();

        $this->info("Subjects in teacher's school: {$schoolSubjects->count()}");
        $this->info("Subjects in other schools: {$otherSubjects->count()}");
        $this->newLine();

        // Test access to subjects from teacher's school
        $this->info('Testing access to subjects from teacher\'s school:');
        foreach ($schoolSubjects->take(3) as $subject) {
            $this->testSubjectAccess($teacher, $subject, 'should have access');
        }

        // Test access to subjects from other schools
        $this->newLine();
        $this->info('Testing access to subjects from other schools:');
        foreach ($otherSubjects->take(3) as $subject) {
            $this->testSubjectAccess($teacher, $subject, 'should NOT have access');
        }

        // Test policy methods
        $this->newLine();
        $this->info('Testing Policy Methods:');
        
        Auth::login($teacher);
        
        $policy = app(\App\Policies\SubjectPolicy::class);
        
        // Test viewAny
        $canViewAny = $policy->viewAny($teacher);
        $this->info("Can view any subjects: " . ($canViewAny ? 'YES' : 'NO'));
        
        // Test create
        $canCreate = $policy->create($teacher);
        $this->info("Can create subjects: " . ($canCreate ? 'YES' : 'NO'));
        
        // Test update
        if ($schoolSubjects->isNotEmpty()) {
            $subject = $schoolSubjects->first();
            $canUpdate = $policy->update($teacher, $subject);
            $this->info("Can update subjects: " . ($canUpdate ? 'YES' : 'NO'));
        }
        
        // Test delete
        if ($schoolSubjects->isNotEmpty()) {
            $subject = $schoolSubjects->first();
            $canDelete = $policy->delete($teacher, $subject);
            $this->info("Can delete subjects: " . ($canDelete ? 'YES' : 'NO'));
        }

        $this->newLine();
        $this->info('Test completed!');
        
        return 0;
    }

    private function testSubjectAccess($teacher, $subject, $expected)
    {
        $this->line("  - Subject: {$subject->name} (School: {$subject->school_id})");
        
        // Forcer l'authentification de l'utilisateur
        \Illuminate\Support\Facades\Auth::login($teacher);
        
        // Test view permission
        $policy = app(\App\Policies\SubjectPolicy::class);
        $canView = $policy->view($teacher, $subject);
        
        $status = $canView ? '✓ ACCESS' : '✗ NO ACCESS';
        $this->line("    View: {$status} ({$expected})");
        
        // Test update permission (should be false for teachers)
        $canUpdate = $policy->update($teacher, $subject);
        $updateStatus = $canUpdate ? '✗ CAN UPDATE' : '✓ READ-ONLY';
        $this->line("    Update: {$updateStatus}");
        
        // Test delete permission (should be false for teachers)
        $canDelete = $policy->delete($teacher, $subject);
        $deleteStatus = $canDelete ? '✗ CAN DELETE' : '✓ READ-ONLY';
        $this->line("    Delete: {$deleteStatus}");
    }
} 