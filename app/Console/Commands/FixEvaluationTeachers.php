<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evaluation;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class FixEvaluationTeachers extends Command
{
    protected $signature = 'fix:evaluation-teachers';
    protected $description = 'Fix evaluation teacher_id based on actual subject_teacher assignments';

    public function handle()
    {
        $this->info('Fixing evaluation teacher assignments...');
        $this->newLine();

        $evaluations = Evaluation::all();
        $fixed = 0;
        $notFound = 0;

        foreach ($evaluations as $evaluation) {
            $this->info("Processing evaluation {$evaluation->id}: {$evaluation->subject->name} - {$evaluation->classRoom->name}");
            
            // Chercher l'enseignant assigné à cette matière et classe dans cette école
            $assignment = DB::table('subject_teacher')
                ->where('subject_id', $evaluation->subject_id)
                ->where('class_room_id', $evaluation->class_room_id)
                ->first();
            
            if ($assignment) {
                // Vérifier si cet enseignant a un profil dans la table teachers
                $teacherProfile = Teacher::where('user_id', $assignment->teacher_id)->first();
                
                if ($teacherProfile) {
                    // Vérifier que l'enseignant enseigne dans la bonne école
                    if ($teacherProfile->school_id == $evaluation->subject->school_id) {
                        $oldTeacherId = $evaluation->teacher_id;
                        $evaluation->update(['teacher_id' => $assignment->teacher_id]);
                        
                        $this->line("  ✓ Fixed: teacher_id changed from {$oldTeacherId} to {$assignment->teacher_id}");
                        $this->line("  Teacher: {$teacherProfile->teacher_firstname} {$teacherProfile->teacher_lastname}");
                        $fixed++;
                    } else {
                        $this->warn("  ⚠ Teacher {$teacherProfile->teacher_firstname} {$teacherProfile->teacher_lastname} is not in the same school");
                        $notFound++;
                    }
                } else {
                    $this->warn("  ⚠ Teacher user ID {$assignment->teacher_id} has no profile in teachers table");
                    $notFound++;
                }
            } else {
                $this->warn("  ⚠ No assignment found for subject {$evaluation->subject_id} and class {$evaluation->class_room_id}");
                $notFound++;
            }
            
            $this->newLine();
        }
        
        $this->info("Fixed: {$fixed} evaluations");
        $this->info("Not found: {$notFound} evaluations");
        $this->info('Done!');
        
        return 0;
    }
} 