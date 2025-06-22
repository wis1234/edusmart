<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\School;

class AssignSchoolToTeacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:school-to-teacher {teacher_id?} {school_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a school to a teacher for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $teacherId = $this->argument('teacher_id');
        $schoolId = $this->argument('school_id');

        // If no teacher_id provided, get the first teacher
        if (!$teacherId) {
            $teacher = User::where('role', 'enseignant')->first();
            if (!$teacher) {
                $this->error('No teacher found in the system.');
                return 1;
            }
            $teacherId = $teacher->id;
        }

        // If no school_id provided, get the first school
        if (!$schoolId) {
            $school = School::first();
            if (!$school) {
                $this->error('No school found in the system.');
                return 1;
            }
            $schoolId = $school->id;
        }

        $teacher = User::find($teacherId);
        $school = School::find($schoolId);

        if (!$teacher) {
            $this->error("Teacher with ID {$teacherId} not found.");
            return 1;
        }

        if (!$school) {
            $this->error("School with ID {$schoolId} not found.");
            return 1;
        }

        $this->info("Assigning teacher '{$teacher->name}' to school '{$school->name}'...");

        // Update teacher's school_id
        $teacher->update(['school_id' => $schoolId]);

        // Also update the teacher profile if it exists
        if ($teacher->teacherProfile) {
            $teacher->teacherProfile->update(['school_id' => $schoolId]);
        }

        $this->info("Successfully assigned teacher '{$teacher->name}' to school '{$school->name}'!");
        $this->info("Teacher ID: {$teacher->id}, School ID: {$school->id}");

        return 0;
    }
} 