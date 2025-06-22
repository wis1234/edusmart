<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;

class AssignSubjectsToTeacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:subjects-to-teacher {teacher_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign subjects and classes to a teacher for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $teacherId = $this->argument('teacher_id');

        // If no teacher_id provided, get the first teacher
        if (!$teacherId) {
            $teacher = User::where('role', 'enseignant')->first();
            if (!$teacher) {
                $this->error('No teacher found in the system.');
                return 1;
            }
            $teacherId = $teacher->id;
        }

        $teacher = User::find($teacherId);
        $teacherProfile = Teacher::where('user_id', $teacherId)->first();

        if (!$teacher) {
            $this->error("Teacher with ID {$teacherId} not found.");
            return 1;
        }

        if (!$teacherProfile) {
            $this->error("Teacher profile not found for user ID {$teacherId}.");
            return 1;
        }

        $this->info("Assigning subjects and classes to teacher '{$teacher->name}'...");
        $this->info("Teacher's school ID: {$teacherProfile->school_id}");

        // Get subjects from teacher's school
        $schoolSubjects = Subject::where('school_id', $teacherProfile->school_id)->get();
        $this->info("Available subjects in teacher's school: {$schoolSubjects->count()}");

        // Get classes from teacher's school
        $schoolClasses = ClassRoom::where('school_id', $teacherProfile->school_id)->get();
        $this->info("Available classes in teacher's school: {$schoolClasses->count()}");

        if ($schoolSubjects->isEmpty() || $schoolClasses->isEmpty()) {
            $this->error('No subjects or classes available in teacher\'s school.');
            return 1;
        }

        // Assign first 2 subjects to teacher
        $subjectsToAssign = $schoolSubjects->take(2);
        foreach ($subjectsToAssign as $subject) {
            if (!$teacherProfile->subjects()->where('subjects.id', $subject->id)->exists()) {
                $teacherProfile->subjects()->attach($subject->id, ['year' => date('Y')]);
                $this->info("Assigned subject: {$subject->name}");
            } else {
                $this->info("Subject already assigned: {$subject->name}");
            }
        }

        // Assign first 2 classes to teacher
        $classesToAssign = $schoolClasses->take(2);
        foreach ($classesToAssign as $class) {
            if (!$teacherProfile->classRooms()->where('class_rooms.id', $class->id)->exists()) {
                $teacherProfile->classRooms()->attach($class->id, [
                    'subject_id' => $subjectsToAssign->first()->id,
                    'year' => date('Y')
                ]);
                $this->info("Assigned class: {$class->name}");
            } else {
                $this->info("Class already assigned: {$class->name}");
            }
        }

        $this->info("Successfully assigned subjects and classes to teacher '{$teacher->name}'!");
        
        // Show current assignments
        $this->newLine();
        $this->info("Current teacher assignments:");
        $this->info("Subjects: " . $teacherProfile->subjects()->pluck('subjects.name')->implode(', '));
        $this->info("Classes: " . $teacherProfile->classRooms()->pluck('class_rooms.name')->implode(', '));

        return 0;
    }
} 