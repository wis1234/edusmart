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

class EvaluationController extends Controller
{
    public function __construct()
    {
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

        // Si l'utilisateur est un enseignant, filtrer par son école, ses classes et ses matières
        if ($user->role === 'enseignant') {
            // Récupérer le profil enseignant
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if ($teacher && $teacher->school_id) {
                // Filtrer par école
                $query->whereHas('subject', function($q) use ($teacher) {
                    $q->where('school_id', $teacher->school_id);
                });
                
                // Filtrer par classes où l'enseignant enseigne
                $query->whereHas('classRoom', function($q) use ($teacher) {
                    $q->where('school_id', $teacher->school_id);
                });
                
                // Filtrer par matières que l'enseignant enseigne
                $query->whereHas('subject', function($q) use ($teacher) {
                    $q->whereIn('id', $teacher->taughtSubjects()->pluck('subjects.id'));
                });
                // Filtrer par enseignant connecté
                $query->where('teacher_id', $teacher->id);
            } else {
                // Si pas d'école assignée, ne montrer aucune évaluation
                $query->where('id', null);
            }
        }

        // Handle search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('subject', fn($sq) => $sq->where('name', 'like', "%$search%"))
                  ->orWhereHas('classRoom', fn($cq) => $cq->where('name', 'like', "%$search%"))
                  ->orWhereHas('evaluationType', fn($tq) => $tq->where('name', 'like', "%$search%"))
                  ->orWhere('term', 'like', "%$search%");
            });
        }

        // Handle filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }
        if ($request->filled('evaluation_type_id')) {
            $query->where('evaluation_type_id', $request->evaluation_type_id);
        }
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }
        if ($request->filled('date_from')) {
            $query->where('evaluation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('evaluation_date', '<=', $request->date_to);
        }

        $evaluations = $query->with([
            'subject.school', 
            'classRoom.school', 
            'evaluationType', 
            'teacher', 
            'teacherProfile',
            'creator'
        ])->orderBy('evaluation_date', 'desc')->paginate(10)->withQueryString();
        
        // Data for filters - filtrer par école, classes et matières de l'enseignant
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if ($teacher && $teacher->school_id) {
                // Filtrer les matières par école
                $subjects = $teacher->taughtSubjects()
                    ->where('school_id', $teacher->school_id)
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
            $subjects = Subject::orderBy('name')->get();
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
            $subjects = Subject::all();
            $classRooms = ClassRoom::all();
            $teachers = Teacher::with('user')->get();
        } else {
            // Pour les enseignants, filtrer par leur école, classes et matières
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if ($teacher && $teacher->school_id) {
                $subjects = $teacher->taughtSubjects()
                    ->where('school_id', $teacher->school_id)
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
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if (!$teacher || $teacher->school_id !== Subject::find($validated['subject_id'])->school_id) {
                abort(403, 'You can only create evaluations for your assigned school.');
            }
            
            if (!$teacher->taughtSubjects()->where('subjects.id', $validated['subject_id'])->exists()) {
                abort(403, 'You can only create evaluations for subjects you teach.');
            }
            
            if (!$teacher->teachingClassRooms()->where('class_rooms.id', $validated['class_room_id'])->exists()) {
                abort(403, 'You can only create evaluations for classes you teach.');
            }
        }
        
        Evaluation::create($validated + [
            'teacher_id' => $this->getTeacherId($request),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation created successfully.');
    }

    public function show(Evaluation $evaluation)
    {
        // Vérification supplémentaire pour les enseignants
        $user = Auth::user();
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if (!$teacher || $teacher->school_id !== $evaluation->subject->school_id) {
                abort(403, 'You can only view evaluations from your assigned school.');
            }
            
            if (!$teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists()) {
                abort(403, 'You can only view evaluations for subjects you teach.');
            }
        }
        
        // Charger toutes les relations nécessaires
        $evaluation->load([
            'subject.school', 
            'classRoom.school', 
            'evaluationType', 
            'teacher', 
            'teacherProfile',
            'creator',
            'studentGrades.student'
        ]);
        
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
                ->orderBy('name')->get();
            
            $classRooms = $teacher->teachingClassRooms()
                ->where('school_id', $teacher->school_id)
                ->orderBy('name')->get();
        } elseif ($user->isAdmin() || $user->email === 'ronaldoagbohou@gmail.com') {
            $subjects = Subject::all();
            $classRooms = ClassRoom::all();
        } else {
            $subjects = $user->taughtSubjects()->get();
            $classRooms = $user->teachingClassRooms()
                ->wherePivot('subject_id', $evaluation->subject_id)
                ->get();
        }
        
        $teachers = collect([$user->teacher ?? $user]);

        return view('evaluations.edit', compact('evaluation', 'subjects', 'evaluationTypes', 'classRooms', 'teachers'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $this->validateEvaluation($request, $evaluation);

        // Vérification supplémentaire pour les enseignants
        $user = Auth::user();
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if (!$teacher || $teacher->school_id !== $evaluation->subject->school_id) {
                abort(403, 'You can only update evaluations from your assigned school.');
            }
            
            if (!$teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists()) {
                abort(403, 'You can only update evaluations for subjects you teach.');
            }
            
            if (!$teacher->teachingClassRooms()->where('class_rooms.id', $validated['class_room_id'])->exists()) {
                abort(403, 'You can only update evaluations for classes you teach.');
            }
        }

        if (!Gate::allows('update', $evaluation)) {
            abort(403, 'You are not authorized to update this evaluation');
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

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(Evaluation $evaluation)
    {
        // Vérification supplémentaire pour les enseignants
        $user = Auth::user();
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            
            if (!$teacher || $teacher->school_id !== $evaluation->subject->school_id) {
                abort(403, 'You can only delete evaluations from your assigned school.');
            }
            
            if (!$teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists()) {
                abort(403, 'You can only delete evaluations for subjects you teach.');
            }
        }

        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation deleted successfully.');
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