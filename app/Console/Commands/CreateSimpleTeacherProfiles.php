<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class CreateSimpleTeacherProfiles extends Command
{
    protected $signature = 'create:simple-teacher-profiles';
    protected $description = 'Create missing teacher profiles with default values';

    public function handle()
    {
        $this->info('Creating simple teacher profiles...');
        $this->newLine();

        // Récupérer tous les teacher_id uniques de subject_teacher
        $teacherIds = DB::table('subject_teacher')
            ->whereNotNull('teacher_id')
            ->where('teacher_id', '!=', '')
            ->distinct()
            ->pluck('teacher_id');

        $created = 0;
        $alreadyExists = 0;

        foreach ($teacherIds as $teacherId) {
            // Vérifier si le profil existe déjà
            $existingProfile = Teacher::where('user_id', $teacherId)->first();
            if ($existingProfile) {
                $alreadyExists++;
                continue;
            }

            // Vérifier si l'utilisateur existe
            $user = User::find($teacherId);
            if (!$user) {
                $this->warn("User ID {$teacherId} not found, skipping...");
                continue;
            }

            // Déterminer l'école basée sur les assignations
            $assignments = DB::table('subject_teacher')
                ->where('teacher_id', $teacherId)
                ->get();

            $schoolId = null;
            foreach ($assignments as $assignment) {
                if ($assignment->subject_id) {
                    $subject = Subject::find($assignment->subject_id);
                    if ($subject && $subject->school_id) {
                        $schoolId = $subject->school_id;
                        break;
                    }
                }
            }

            try {
                // Créer le profil enseignant avec des valeurs par défaut
                Teacher::create([
                    'user_id' => $teacherId,
                    'school_id' => $schoolId,
                    'teacher_firstname' => $user->first_name,
                    'teacher_lastname' => $user->last_name,
                    'teacher_email' => $user->email,
                    'teacher_phone' => '', // Valeur par défaut
                    'specialization' => null,
                ]);

                $this->line("Created profile for {$user->first_name} {$user->last_name} (User ID: {$teacherId}, School: {$schoolId})");
                $created++;
            } catch (\Exception $e) {
                $this->error("Failed to create profile for {$user->first_name} {$user->last_name}: " . $e->getMessage());
            }
        }

        $this->info("Profiles created: {$created}");
        $this->info("Profiles already exist: {$alreadyExists}");
        $this->info('Done!');

        return 0;
    }
} 