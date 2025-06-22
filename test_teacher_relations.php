<?php

// Script pour tester les relations des enseignants
// À exécuter avec: php artisan tinker < test_teacher_relations.php

use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassRoom;

echo "Test des relations des enseignants...\n\n";

// Récupérer tous les utilisateurs avec le rôle enseignant
$teachers = User::role('enseignant')->with('teacherProfile')->get();

echo "Nombre d'utilisateurs avec le rôle enseignant: " . $teachers->count() . "\n\n";

foreach ($teachers as $teacher) {
    echo "Utilisateur: {$teacher->first_name} {$teacher->last_name} (ID: {$teacher->id})\n";
    
    if ($teacher->teacherProfile) {
        echo "  - Profil enseignant trouvé (ID: {$teacher->teacherProfile->id})\n";
        
        // Vérifier les matières enseignées
        $subjects = $teacher->taughtSubjects;
        echo "  - Matières enseignées: " . $subjects->count() . "\n";
        foreach ($subjects as $subject) {
            echo "    * {$subject->name}\n";
        }
        
        // Vérifier les classes assignées
        $classRooms = $teacher->teachingClassRooms;
        echo "  - Classes assignées: " . $classRooms->count() . "\n";
        foreach ($classRooms as $classRoom) {
            echo "    * {$classRoom->name} (École: {$classRoom->school->name})\n";
        }
        
    } else {
        echo "  - Aucun profil enseignant trouvé!\n";
    }
    
    echo "\n";
}

// Vérifier la table subject_teacher
echo "Contenu de la table subject_teacher:\n";
$subjectTeacherEntries = \DB::table('subject_teacher')->get();
echo "Nombre d'entrées: " . $subjectTeacherEntries->count() . "\n";

foreach ($subjectTeacherEntries as $entry) {
    echo "  - Teacher ID: {$entry->teacher_id}, Subject ID: {$entry->subject_id}, Class Room ID: " . ($entry->class_room_id ?? 'NULL') . "\n";
}

echo "\nTest terminé!\n"; 