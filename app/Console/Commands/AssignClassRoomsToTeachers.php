<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class AssignClassRoomsToTeachers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teachers:assign-classrooms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign classrooms to teachers based on their subjects and schools';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Assignation des salles de classe aux enseignants...');

        // Récupérer tous les enseignants avec leurs profils
        $teachers = User::role('enseignant')->with('teacherProfile.school')->get();

        foreach ($teachers as $teacher) {
            if (!$teacher->teacherProfile) {
                $this->warn("Utilisateur {$teacher->first_name} {$teacher->last_name} n'a pas de profil enseignant");
                continue;
            }

            $this->line("\nTraitement de {$teacher->first_name} {$teacher->last_name} (École: {$teacher->teacherProfile->school->name})");

            // Récupérer les matières enseignées par cet enseignant
            $subjectAssignments = DB::table('subject_teacher')
                ->where('teacher_id', $teacher->teacherProfile->id)
                ->whereNull('class_room_id')
                ->get();

            foreach ($subjectAssignments as $assignment) {
                $subject = Subject::find($assignment->subject_id);
                $this->line("  - Matière: {$subject->name}");

                // Trouver une salle de classe disponible dans l'école de l'enseignant
                $availableClassRoom = ClassRoom::where('school_id', $teacher->teacherProfile->school_id)
                    ->whereDoesntHave('teachers', function($query) use ($assignment) {
                        $query->where('subject_id', $assignment->subject_id);
                    })
                    ->first();

                if ($availableClassRoom) {
                    // Mettre à jour l'assignation avec la salle de classe
                    DB::table('subject_teacher')
                        ->where('teacher_id', $teacher->teacherProfile->id)
                        ->where('subject_id', $assignment->subject_id)
                        ->where('year', $assignment->year)
                        ->update(['class_room_id' => $availableClassRoom->id]);

                    $this->info("    ✓ Assigné à la classe: {$availableClassRoom->name}");
                } else {
                    $this->warn("    ✗ Aucune salle de classe disponible pour cette matière");
                }
            }
        }

        $this->info("\nAssignation terminée!");
        
        // Afficher le résultat final
        $this->info("\nRésultat final:");
        $finalAssignments = DB::table('subject_teacher')
            ->join('teachers', 'subject_teacher.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'subject_teacher.subject_id', '=', 'subjects.id')
            ->leftJoin('class_rooms', 'subject_teacher.class_room_id', '=', 'class_rooms.id')
            ->select('teachers.teacher_firstname', 'teachers.teacher_lastname', 'subjects.name as subject', 'class_rooms.name as classroom')
            ->get();

        foreach ($finalAssignments as $assignment) {
            $classroom = $assignment->classroom ?? 'Non assigné';
            $this->line("  - {$assignment->teacher_firstname} {$assignment->teacher_lastname}: {$assignment->subject} → {$classroom}");
        }

        return 0;
    }
} 