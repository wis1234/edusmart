<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Subject;
use App\Models\EvaluationType;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Services\NotificationService;
use Exception;

class EvaluationController extends Controller
{
    protected $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->authorizeResource(Evaluation::class, 'evaluation');
    }

    // public function index()
    // {
    //     $evaluations = Evaluation::with(['subject', 'classRoom', 'evaluationType', 'teacher'])
    //         ->when(Auth::user()->isTeacher(), function ($query) {
    //             return $query->where('teacher_id', $this->getTeacherId());
    //         })
    //         ->latest()
    //         ->paginate(10);

    //     return view('evaluations.index', compact('evaluations'));
    // }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Evaluation::with(['subject', 'classRoom', 'evaluationType', 'teacher']);

        // Si l'utilisateur est un enseignant, filtrer par ses matières enseignées
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if ($teacher && $teacher->school_id) {
                // Filtrer les évaluations par école et matières enseignées
                $query->whereHas('subject', function($q) use ($teacher) {
                    $q->where('school_id', $teacher->school_id)
                      ->where('is_active', 1); // Ne montrer que les évaluations des matières actives
                });
                
                // Filtrer par matières enseignées
                $query->whereIn('subject_id', $teacher->taughtSubjects()->pluck('subjects.id'));
            } else {
                $query->whereRaw('1=0'); // Aucune évaluation si pas d'école assignée
            }
        } else {
            // Pour les admins et autres rôles, filtrer par matières actives
            $query->whereHas('subject', function($q) {
                $q->where('is_active', 1);
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('subject', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('classRoom', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('evaluationType', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by class room
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        // Filter by evaluation type
        if ($request->filled('evaluation_type_id')) {
            $query->where('evaluation_type_id', $request->evaluation_type_id);
        }

        $evaluations = $query->latest()->paginate(10)->withQueryString();

        // Filtrer les matières et classes par matières enseignées pour les enseignants
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if ($teacher && $teacher->school_id) {
                // Filtrer les matières par école
                $subjects = $teacher->taughtSubjects()
                    ->where('school_id', $teacher->school_id)
                    ->where('is_active', 1) // Ne montrer que les matières actives
                    ->orderBy('name')->get();
                
                // Filtrer les classes par école
                $classRooms = $teacher->teachingClassRooms()
                    ->where('school_id', $teacher->school_id)
                    ->orderBy('name')->get();
            } else {
                $subjects = collect();
                $classRooms = collect();
            }
        } else {
            $subjects = Subject::where('is_active', 1)->orderBy('name')->get();
            $classRooms = ClassRoom::orderBy('name')->get();
        }
        $evaluationTypes = EvaluationType::orderBy('name')->get();

        return view('evaluations.index', compact('evaluations', 'subjects', 'classRooms', 'evaluationTypes'));
    }

    public function create()
    {
        $user = Auth::user();
        $evaluationTypes = EvaluationType::all();

        if ($user->isAdmin() || $user->email === 'ronaldoagbohou@gmail.com') {
            $subjects = Subject::where('is_active', 1)->get();
            $classRooms = ClassRoom::all();
            $teachers = Teacher::with('user')->get();
        } else {
            // Pour les enseignants, filtrer par leur école, classes et matières
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if ($teacher && $teacher->school_id) {
                $subjects = $teacher->taughtSubjects()
                    ->where('school_id', $teacher->school_id)
                    ->where('is_active', 1) // Ne montrer que les matières actives
                    ->orderBy('name')->get();
                
                $classRooms = $teacher->teachingClassRooms()
                    ->where('school_id', $teacher->school_id)
                    ->orderBy('name')->get();
            } else {
                $subjects = collect();
                $classRooms = collect();
            }
            
            $teachers = collect([$teacher ?? $user]);
        }

        // Debug: Log the data being passed to the view
        \Log::info('Evaluation Create - User: ' . $user->name . ', Role: ' . $user->role);
        \Log::info('Subjects count: ' . $subjects->count());
        \Log::info('Classes count: ' . $classRooms->count());
        \Log::info('Subjects: ' . $subjects->pluck('id', 'name')->toJson());
        \Log::info('Classes: ' . $classRooms->pluck('id', 'name')->toJson());

        return view('evaluations.create', compact('subjects', 'evaluationTypes', 'classRooms', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvaluation($request);

        // Vérifier que la matière est active
        $subject = Subject::find($validated['subject_id']);
        if (!$subject || $subject->is_active !== 1) {
            abort(403, 'You can only create evaluations for active subjects.');
        }

        // Handle evaluation_type as free text, link to evaluation_types table
        $evaluationTypeName = $request->input('evaluation_type');
        $code = strtoupper(Str::slug($evaluationTypeName, '_'));
        $evaluationType = \App\Models\EvaluationType::firstOrCreate(
            ['name' => $evaluationTypeName],
            ['code' => $code, 'weight' => 0]
        );
        $validated['evaluation_type_id'] = $evaluationType->id;
        unset($validated['evaluation_type']);
        
        // Vérification supplémentaire pour les enseignants
        $user = Auth::user();
        $teacherId = null;
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if (!$teacher || $teacher->school_id !== $subject->school_id) {
                abort(403, 'You can only create evaluations for your assigned school.');
            }
            if (!$teacher->taughtSubjects()->where('subjects.id', $validated['subject_id'])->exists()) {
                abort(403, 'You can only create evaluations for subjects you teach.');
            }
            if (!$teacher->teachingClassRooms()->where('class_rooms.id', $validated['class_room_id'])->exists()) {
                abort(403, 'You can only create evaluations for classes you teach.');
            }
            $teacherId = $teacher->user_id;
        } else if (isset($validated['teacher_id'])) {
            $teacherId = $validated['teacher_id'];
        }
        
        $evaluation = Evaluation::create($validated + [
            'teacher_id' => $teacherId,
            'created_by' => Auth::id(),
        ]);
        // Notification to all school_admins of the evaluation's school
        $schoolId = $evaluation->subject->school_id;
        $schoolAdmins = \App\Models\User::where('role', 'school_admin')->where('school_id', $schoolId)->get();
        $this->notificationService->sendToMany(
            $schoolAdmins,
            'success',
            'New Evaluation Created',
            'A new evaluation has been created in the system.',
            route('evaluations.show', $evaluation)
        );
        // Rediriger vers la vue de l'évaluation créée avec gestion d'URL robuste
        try {
            // Essayer d'abord avec la route nommée
            return redirect()->route('evaluations.show', $evaluation)->with('success', 'Évaluation créée avec succès.');
        } catch (Exception $e) {
            // Fallback vers l'URL relative si la route échoue
            return redirect('/evaluations/' . $evaluation->id)->with('success', 'Évaluation créée avec succès.');
        }
    }

    public function show(Evaluation $evaluation)
    {
        // Charger toutes les relations nécessaires AVANT les vérifications
        $evaluation->load([
            'subject.school', 
            'classRoom.school', 
            'evaluationType', 
            'teacher', 
            'teacherProfile',
            'creator',
            'studentGrades.student'
        ]);
        
        // Vérification supplémentaire pour les enseignants
        $user = Auth::user();
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            // Vérifier que l'enseignant existe et appartient à la même école que l'évaluation
            if (!$teacher || $teacher->school_id !== $evaluation->subject->school_id) {
                abort(403, 'You can only view evaluations from your assigned school.');
            }
            
            // Vérifier que l'enseignant enseigne la matière de cette évaluation
            if (!$teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists()) {
                abort(403, 'You can only view evaluations for subjects you teach.');
            }
            
            // Vérifier que l'évaluation a été créée par cet enseignant ou qu'il enseigne dans cette classe
            if ($evaluation->teacher_id !== $user->id && 
                !$teacher->teachingClassRooms()->where('class_rooms.id', $evaluation->class_room_id)->exists()) {
                abort(403, 'You can only view evaluations you created or for classes you teach.');
            }
        }
        
        return view('evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        $user = Auth::user();
        $evaluationTypes = EvaluationType::all();

        // Vérification supplémentaire pour les enseignants
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if (!$teacher || $teacher->school_id !== $evaluation->subject->school_id) {
                abort(403, 'You can only edit evaluations from your assigned school.');
            }
            
            if (!$teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists()) {
                abort(403, 'You can only edit evaluations for subjects you teach.');
            }
            
            $subjects = $teacher->taughtSubjects()
                ->where('school_id', $teacher->school_id)
                ->where('is_active', 1) // Ne montrer que les matières actives
                ->orderBy('name')->get();
            
            $classRooms = $teacher->teachingClassRooms()
                ->where('school_id', $teacher->school_id)
                ->orderBy('name')->get();
        } elseif ($user->isAdmin() || $user->email === 'ronaldoagbohou@gmail.com') {
            $subjects = Subject::where('is_active', 1)->get();
            $classRooms = ClassRoom::all();
        } else {
            $subjects = $user->taughtSubjects()->where('is_active', 1)->get();
            $classRooms = $user->teachingClassRooms()
                ->wherePivot('subject_id', $evaluation->subject_id)
                ->get();
        }
        
        $teachers = collect([$user->teacher ?? $user]);

        return view('evaluations.edit', compact('evaluation', 'subjects', 'evaluationTypes', 'classRooms', 'teachers'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $this->authorize('update', $evaluation);
        $validated = $this->validateEvaluation($request, $evaluation);

        // Vérifier que la matière est active
        $subject = Subject::find($validated['subject_id']);
        if (!$subject || $subject->is_active !== 1) {
            abort(403, 'You can only update evaluations for active subjects.');
        }

        // Vérification supplémentaire pour les enseignants
        $user = Auth::user();
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if (!$teacher || $teacher->school_id !== $subject->school_id) {
                abort(403, 'You can only update evaluations from your assigned school.');
            }
            
            if (!$teacher->taughtSubjects()->where('subjects.id', $validated['subject_id'])->exists()) {
                abort(403, 'You can only update evaluations for subjects you teach.');
            }
            
            if (!$teacher->teachingClassRooms()->where('class_rooms.id', $validated['class_room_id'])->exists()) {
                abort(403, 'You can only update evaluations for classes you teach.');
            }
        }

        // Handle evaluation_type as free text, link to evaluation_types table (for update)
        $evaluationTypeName = $request->input('evaluation_type');
        $code = strtoupper(Str::slug($evaluationTypeName, '_'));
        $evaluationType = \App\Models\EvaluationType::firstOrCreate(
            ['name' => $evaluationTypeName],
            ['code' => $code, 'weight' => 0]
        );
        $validated['evaluation_type_id'] = $evaluationType->id;
        unset($validated['evaluation_type']);

        $evaluation->update($validated);
        // Notification to all school_admins of the evaluation's school
        $schoolId = $evaluation->subject->school_id;
        $schoolAdmins = \App\Models\User::where('role', 'school_admin')->where('school_id', $schoolId)->get();
        $this->notificationService->sendToMany(
            $schoolAdmins,
            'warning',
            'Evaluation Updated',
            'An evaluation has been updated in the system.',
            route('evaluations.show', $evaluation)
        );
        return redirect()->route('evaluations.index')->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $this->authorize('delete', $evaluation);
        try {
            $evaluation->delete();
            // Notification to all school_admins of the evaluation's school
            $schoolId = $evaluation->subject->school_id;
            $schoolAdmins = \App\Models\User::where('role', 'school_admin')->where('school_id', $schoolId)->get();
            $this->notificationService->sendToMany(
                $schoolAdmins,
                'error',
                'Evaluation Deleted',
                'An evaluation has been deleted from the system.'
            );
            return redirect()->route('evaluations.index')->with('success', 'Evaluation deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the evaluation. Please try again.');
        }
    }

    protected function validateEvaluation(Request $request, ?Evaluation $evaluation = null)
    {
        $user = Auth::user();
        $rules = [
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_room_id' => ['required', 'exists:class_rooms,id'],
            'evaluation_type' => 'required|string|max:100',
            'academic_year' => 'required|string|max:10',
            'term' => 'required|string|max:10',
            'evaluation_date' => 'required|date|after_or_equal:today',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lt:total_marks',
            'notes' => 'nullable|string|max:500',
        ];

        if ($user->isAdmin() || $user->email === 'ronaldoagbohou@gmail.com') {
            $rules['teacher_id'] = 'required|exists:teachers,id';
        } else {
            // Pour les enseignants, nous vérifions les permissions dans le contrôleur
            // plutôt que dans les règles de validation
        }

        if ($evaluation) {
            $rules['evaluation_date'] = 'required|date|after_or_equal:' . $evaluation->created_at->format('Y-m-d');
        }

        return $request->validate($rules);
    }

    protected function getTeacherId(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->email === 'ronaldoagbohou@gmail.com') {
            return $request->teacher_id;
        }
        
        // Pour les enseignants, retourner l'ID du profil enseignant
        $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
        return $teacher ? $teacher->id : $user->id;
    }
}