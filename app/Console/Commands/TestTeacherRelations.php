<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassRoom;

class TestTeacherRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-relations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test teacher relations and assignments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Test des relations des enseignants...');

        // Récupérer tous les utilisateurs avec le rôle enseignant
        $teachers = User::role('enseignant')->with('teacherProfile')->get();

        $this->info("Nombre d'utilisateurs avec le rôle enseignant: " . $teachers->count());

        foreach ($teachers as $teacher) {
            $this->line("\nUtilisateur: {$teacher->first_name} {$teacher->last_name} (ID: {$teacher->id})");
            
            if ($teacher->teacherProfile) {
                $this->line("  - Profil enseignant trouvé (ID: {$teacher->teacherProfile->id})");
                
                // Vérifier les matières enseignées
                $subjects = $teacher->taughtSubjects;
                $this->line("  - Matières enseignées: " . $subjects->count());
                foreach ($subjects as $subject) {
                    $this->line("    * {$subject->name}");
                }
                
                // Vérifier les classes assignées
                $classRooms = $teacher->teachingClassRooms;
                $this->line("  - Classes assignées: " . $classRooms->count());
                foreach ($classRooms as $classRoom) {
                    $this->line("    * {$classRoom->name} (École: {$classRoom->school->name})");
                }
                
            } else {
                $this->error("  - Aucun profil enseignant trouvé!");
            }
        }

        // Vérifier la table subject_teacher
        $this->info("\nContenu de la table subject_teacher:");
        $subjectTeacherEntries = \DB::table('subject_teacher')->get();
        $this->line("Nombre d'entrées: " . $subjectTeacherEntries->count());

        foreach ($subjectTeacherEntries as $entry) {
            $this->line("  - Teacher ID: {$entry->teacher_id}, Subject ID: {$entry->subject_id}, Class Room ID: " . ($entry->class_room_id ?? 'NULL'));
        }

        $this->info("\nTest terminé!");
        return 0;
    }
} 