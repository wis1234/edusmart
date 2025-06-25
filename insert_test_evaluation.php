<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Evaluation;
use App\Models\EvaluationType;
use Illuminate\Support\Facades\Auth;

// Simuler un environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Insertion d'évaluation de test ===\n\n";

// 1. Trouver un enseignant
$teacher = Teacher::with('user')->first();
if (!$teacher) {
    echo "❌ Aucun enseignant trouvé\n";
    exit(1);
}

echo "✅ Enseignant: {$teacher->user->first_name} {$teacher->user->last_name}\n";
echo "   - Teacher ID: {$teacher->id}\n";
echo "   - User ID: {$teacher->user_id}\n";
echo "   - School ID: {$teacher->school_id}\n\n";

// 2. Vérifier les données nécessaires
$subject = $teacher->taughtSubjects()->first();
$classRoom = $teacher->teachingClassRooms()->first();
$evaluationType = EvaluationType::first();

if (!$subject || !$classRoom || !$evaluationType) {
    echo "❌ Données manquantes pour créer l'évaluation\n";
    echo "   - Subject: " . ($subject ? $subject->name : 'Non trouvé') . "\n";
    echo "   - ClassRoom: " . ($classRoom ? $classRoom->name : 'Non trouvé') . "\n";
    echo "   - EvaluationType: " . ($evaluationType ? $evaluationType->name : 'Non trouvé') . "\n";
    exit(1);
}

echo "✅ Données trouvées:\n";
echo "   - Subject: {$subject->name} (ID: {$subject->id}, Active: " . ($subject->is_active ? 'Oui' : 'Non') . ")\n";
echo "   - ClassRoom: {$classRoom->name} (ID: {$classRoom->id})\n";
echo "   - EvaluationType: {$evaluationType->name} (ID: {$evaluationType->id})\n\n";

// 3. Créer l'évaluation
try {
    $evaluation = Evaluation::create([
        'subject_id' => $subject->id,
        'class_room_id' => $classRoom->id,
        'evaluation_type_id' => $evaluationType->id,
        'teacher_id' => $teacher->user_id, // Important: utiliser user_id
        'academic_year' => '2024-2025',
        'term' => 'Term Test ' . date('Y-m-d H:i:s'),
        'evaluation_date' => now()->addDays(7),
        'total_marks' => 100,
        'passing_marks' => 50,
        'notes' => 'Évaluation de test créée par script - ' . date('Y-m-d H:i:s'),
        'created_by' => $teacher->user_id,
    ]);

    echo "✅ Évaluation créée avec succès!\n";
    echo "   - ID: {$evaluation->id}\n";
    echo "   - Teacher ID: {$evaluation->teacher_id}\n";
    echo "   - Subject: {$evaluation->subject->name}\n";
    echo "   - Class: {$evaluation->classRoom->name}\n";
    echo "   - Type: {$evaluation->evaluationType->name}\n";
    echo "   - Date: {$evaluation->evaluation_date->format('d/m/Y')}\n";
    echo "   - Créée le: {$evaluation->created_at->format('d/m/Y H:i:s')}\n\n";

    // 4. Tester l'accès
    echo "=== Test d'accès ===\n";
    
    // Simuler l'authentification
    Auth::login($teacher->user);
    
    // Charger les relations
    $evaluation->load(['subject.school', 'classRoom.school', 'evaluationType', 'teacher', 'teacherProfile', 'creator']);
    
    echo "✅ Relations chargées:\n";
    echo "   - Subject School: " . ($evaluation->subject->school ? $evaluation->subject->school->name : 'N/A') . "\n";
    echo "   - Teacher: " . ($evaluation->teacher ? $evaluation->teacher->first_name . ' ' . $evaluation->teacher->last_name : 'N/A') . "\n";
    echo "   - Creator: " . ($evaluation->creator ? $evaluation->creator->first_name . ' ' . $evaluation->creator->last_name : 'N/A') . "\n\n";
    
    // 5. Tester les policies
    echo "=== Test des policies ===\n";
    
    $policy = new \App\Policies\EvaluationPolicy();
    
    $canView = $policy->view($teacher->user, $evaluation);
    $canUpdate = $policy->update($teacher->user, $evaluation);
    $canDelete = $policy->delete($teacher->user, $evaluation);
    
    echo "   - Peut voir: " . ($canView ? '✅ Oui' : '❌ Non') . "\n";
    echo "   - Peut modifier: " . ($canUpdate ? '✅ Oui' : '❌ Non') . "\n";
    echo "   - Peut supprimer: " . ($canDelete ? '✅ Oui' : '❌ Non') . "\n\n";
    
    // 6. Tester le contrôleur
    echo "=== Test du contrôleur ===\n";
    
    try {
        $controller = new \App\Http\Controllers\EvaluationController(app(\App\Services\NotificationService::class));
        $response = $controller->show($evaluation);
        
        echo "✅ Contrôleur exécuté avec succès\n";
        echo "   - Type de réponse: " . get_class($response) . "\n";
        
        if ($response instanceof \Illuminate\View\View) {
            echo "   - Vue: " . $response->getName() . "\n";
            echo "   - Données disponibles: " . implode(', ', array_keys($response->getData())) . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur dans le contrôleur: " . $e->getMessage() . "\n";
    }
    
    // 7. Générer les URLs
    echo "\n=== URLs de test ===\n";
    
    $showUrl = route('evaluations.show', $evaluation);
    $editUrl = route('evaluations.edit', $evaluation);
    $indexUrl = route('evaluations.index');
    
    echo "   - URL Show: {$showUrl}\n";
    echo "   - URL Edit: {$editUrl}\n";
    echo "   - URL Index: {$indexUrl}\n\n";
    
    // 8. Instructions pour tester
    echo "=== Instructions de test ===\n";
    echo "1. Connectez-vous en tant qu'enseignant: {$teacher->user->email}\n";
    echo "2. Allez à l'URL: {$showUrl}\n";
    echo "3. Vérifiez que vous pouvez voir les détails de l'évaluation\n";
    echo "4. Testez les boutons Modifier et Supprimer\n\n";
    
    echo "✅ Évaluation de test créée et prête pour les tests!\n";
    echo "   - ID de l'évaluation: {$evaluation->id}\n";
    echo "   - Gardez cet ID pour les tests manuels\n\n";
    
    echo "💡 Pour supprimer cette évaluation de test plus tard:\n";
    echo "   Evaluation::find({$evaluation->id})->delete();\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la création: " . $e->getMessage() . "\n";
    echo "   - Fichier: " . $e->getFile() . "\n";
    echo "   - Ligne: " . $e->getLine() . "\n";
} 