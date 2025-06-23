<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Policies\ClassRoomPolicy;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test d'accès des enseignants aux classes ===\n\n";

// Trouver un enseignant
$teacher = User::where('role', 'enseignant')->with('teacherProfile')->first();

if (!$teacher) {
    echo "Aucun enseignant trouvé dans la base de données.\n";
    exit(1);
}

echo "Enseignant trouvé: {$teacher->first_name} {$teacher->last_name}\n";
echo "Email: {$teacher->email}\n";
echo "Rôle: {$teacher->role}\n";

if (!$teacher->teacherProfile) {
    echo "ERREUR: L'enseignant n'a pas de profil enseignant.\n";
    exit(1);
}

echo "Profil enseignant ID: {$teacher->teacherProfile->id}\n";
echo "École: {$teacher->teacherProfile->school->name}\n\n";

// Trouver les classes assignées à cet enseignant
$assignedClassrooms = $teacher->teachingClassRooms;
echo "Classes assignées à l'enseignant:\n";
foreach ($assignedClassrooms as $classroom) {
    echo "- {$classroom->name} (ID: {$classroom->id})\n";
}

if ($assignedClassrooms->isEmpty()) {
    echo "AUCUNE CLASSE ASSIGNÉE\n\n";
} else {
    echo "\n";
}

// Trouver toutes les classes de l'école de l'enseignant
$schoolClassrooms = ClassRoom::where('school_id', $teacher->teacherProfile->school_id)->get();
echo "Toutes les classes de l'école ({$teacher->teacherProfile->school->name}):\n";
foreach ($schoolClassrooms as $classroom) {
    echo "- {$classroom->name} (ID: {$classroom->id})\n";
}

echo "\n=== Test des permissions ===\n";

$policy = new ClassRoomPolicy();

// Tester l'accès aux classes assignées
foreach ($assignedClassrooms as $classroom) {
    $canView = $policy->view($teacher, $classroom);
    $status = $canView ? '✓ ACCÈS' : '✗ PAS D\'ACCÈS';
    echo "Classe {$classroom->name} (ID: {$classroom->id}): {$status}\n";
}

// Tester l'accès aux classes non assignées
$unassignedClassrooms = $schoolClassrooms->whereNotIn('id', $assignedClassrooms->pluck('id'));
foreach ($unassignedClassrooms as $classroom) {
    $canView = $policy->view($teacher, $classroom);
    $status = $canView ? '✓ ACCÈS (INATTENDU)' : '✗ PAS D\'ACCÈS (ATTENDU)';
    echo "Classe {$classroom->name} (ID: {$classroom->id}): {$status}\n";
}

echo "\n=== Test de la relation classRoomTeachers ===\n";

foreach ($schoolClassrooms as $classroom) {
    $assignments = $classroom->classRoomTeachers()->where('teacher_id', $teacher->teacherProfile->id)->get();
    $hasAssignment = $assignments->isNotEmpty();
    $status = $hasAssignment ? '✓ ASSIGNÉ' : '✗ NON ASSIGNÉ';
    echo "Classe {$classroom->name}: {$status}\n";
    
    if ($hasAssignment) {
        foreach ($assignments as $assignment) {
            echo "  - Matière: {$assignment->subject->name}, Année: {$assignment->year}\n";
        }
    }
}

echo "\nTest terminé!\n"; 