<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\User;

class CheckSubjectTeacherAssignments extends Command
{
    protected $signature = 'check:subject-teacher-assignments';
    protected $description = 'Check subject_teacher assignments';

    public function handle()
    {
        $this->info('Checking Subject_Teacher Assignments...');
        $this->newLine();

        $assignments = DB::table('subject_teacher')->get();
        
        if ($assignments->isEmpty()) {
            $this->warn('No assignments found in subject_teacher table!');
            return 1;
        }

        foreach ($assignments as $assignment) {
            $subject = Subject::find($assignment->subject_id);
            $class = ClassRoom::find($assignment->class_room_id);
            $teacher = User::find($assignment->teacher_id);
            
            $this->info("Assignment ID: {$assignment->id}");
            $this->line("  Subject: " . ($subject ? $subject->name : 'N/A') . " (ID: {$assignment->subject_id})");
            $this->line("  Class: " . ($class ? $class->name : 'N/A') . " (ID: {$assignment->class_room_id})");
            $this->line("  Teacher: " . ($teacher ? $teacher->first_name . ' ' . $teacher->last_name : 'N/A') . " (ID: {$assignment->teacher_id})");
            $this->line("  School: " . ($subject ? $subject->school->name : 'N/A'));
            $this->newLine();
        }
        
        return 0;
    }
} 