<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;

class TestTeacherAssignments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-assignments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test teacher assignments for subjects and classes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Teacher Assignments...');
        $this->newLine();

        // Get a teacher user
        $teacher = User::where('role', 'enseignant')->first();
        
        if (!$teacher) {
            $this->error('No teacher found in the system.');
            return 1;
        }

        $this->info("Testing with teacher: {$teacher->name} (ID: {$teacher->id})");
        
        // Récupérer le profil enseignant
        $teacherProfile = Teacher::where('user_id', $teacher->id)->first();
        if (!$teacherProfile) {
            $this->error('No teacher profile found.');
            return 1;
        }

        $this->info("Teacher profile ID: {$teacherProfile->id}");
        $this->info("Teacher's school ID: {$teacherProfile->school_id}");
        
        $this->newLine();

        // Test subjects
        $this->info('Testing Subject Assignments:');
        $subjects = $teacherProfile->subjects()->get();
        $this->info("Total subjects assigned: {$subjects->count()}");
        
        foreach ($subjects as $subject) {
            $this->line("  - Subject ID: {$subject->id}, Name: {$subject->name}, School: {$subject->school_id}");
        }
        
        $this->newLine();

        // Test classes
        $this->info('Testing Class Assignments:');
        $classes = $teacherProfile->classRooms()->get();
        $this->info("Total classes assigned: {$classes->count()}");
        
        foreach ($classes as $class) {
            $this->line("  - Class ID: {$class->id}, Name: {$class->name}, School: {$class->school_id}");
        }
        
        $this->newLine();

        // Test available subjects and classes for selection
        $this->info('Testing Available Options for Evaluation Creation:');
        
        $availableSubjects = Subject::where('school_id', $teacherProfile->school_id)
            ->whereIn('id', $teacherProfile->subjects()->pluck('subjects.id'))
            ->get();
        
        $this->info("Available subjects for evaluation: {$availableSubjects->count()}");
        foreach ($availableSubjects as $subject) {
            $this->line("  - Subject ID: {$subject->id}, Name: {$subject->name}");
        }
        
        $availableClasses = ClassRoom::where('school_id', $teacherProfile->school_id)
            ->whereIn('id', $teacherProfile->classRooms()->pluck('class_rooms.id'))
            ->get();
        
        $this->info("Available classes for evaluation: {$availableClasses->count()}");
        foreach ($availableClasses as $class) {
            $this->line("  - Class ID: {$class->id}, Name: {$class->name}");
        }

        $this->newLine();
        $this->info('Test completed!');
        
        return 0;
    }
} 