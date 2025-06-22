<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subject;
use App\Http\Controllers\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestSubjectIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:subject-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the subject index method to see what subjects are returned for a teacher';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Subject Index Method...');
        $this->newLine();

        // Get a teacher user
        $teacher = User::where('role', 'enseignant')->first();
        
        if (!$teacher) {
            $this->error('No teacher found in the system.');
            return 1;
        }

        $this->info("Testing with teacher: {$teacher->name} (ID: {$teacher->id})");
        $this->info("Teacher's role: {$teacher->role}");
        $this->info("Teacher's school_id (from users table): {$teacher->school_id}");
        
        // Vérifier le school_id dans la table teachers
        $teacherProfile = \App\Models\Teacher::where('user_id', $teacher->id)->first();
        if ($teacherProfile) {
            $this->info("Teacher's school_id (from teachers table): {$teacherProfile->school_id}");
        } else {
            $this->info("No teacher profile found in teachers table");
        }
        
        $this->newLine();

        // Get all subjects in the system
        $allSubjects = Subject::with(['user', 'school'])->get();
        $this->info("Total subjects in system: {$allSubjects->count()}");
        
        foreach ($allSubjects as $subject) {
            $this->line("  - {$subject->name} (School: {$subject->school_id} - {$subject->school->name})");
        }
        
        $this->newLine();

        // Get subjects from teacher's school
        $teacherSchoolId = $teacherProfile ? $teacherProfile->school_id : null;
        $teacherSchoolSubjects = Subject::where('school_id', $teacherSchoolId)->get();
        $this->info("Subjects in teacher's school ({$teacherSchoolId}): {$teacherSchoolSubjects->count()}");
        
        foreach ($teacherSchoolSubjects as $subject) {
            $this->line("  - {$subject->name}");
        }
        
        $this->newLine();

        // Test the controller method
        $this->info('Testing Controller Index Method:');
        
        // Authenticate the teacher
        Auth::login($teacher);
        
        // Create a mock request
        $request = new Request();
        
        // Get the controller
        $controller = new SubjectController();
        
        // Call the index method
        $response = $controller->index($request);
        
        // Get the subjects from the response
        $subjects = $response->getData()['subjects'] ?? collect();
        
        if (is_object($subjects) && method_exists($subjects, 'items')) {
            $subjects = $subjects->items();
        }
        
        $this->info("Subjects returned by controller: " . count($subjects));
        
        foreach ($subjects as $subject) {
            $this->line("  - {$subject->name} (School: {$subject->school_id})");
        }

        $this->newLine();
        $this->info('Test completed!');
        
        return 0;
    }
} 