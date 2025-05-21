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
    $query = Evaluation::with(['subject', 'classRoom', 'evaluationType', 'teacher']);

    if ($search = $request->input('search')) {
        $query->whereHas('subject', fn($q) => $q->where('name', 'like', "%$search%"))
              ->orWhereHas('classRoom', fn($q) => $q->where('name', 'like', "%$search%"))
              ->orWhereHas('evaluationType', fn($q) => $q->where('name', 'like', "%$search%"))
              ->orWhere('academic_year', 'like', "%$search%")
              ->orWhere('term', 'like', "%$search%");
    }

    $evaluations = $query->orderBy('evaluation_date', 'desc')->paginate(10);
    return view('evaluations.index', compact('evaluations'));
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
            $subjects = $user->taughtSubjects()->get();
            $classRooms = $user->teachingClassRooms()
                ->wherePivot('subject_id', request()->old('subject_id'))
                ->get();
            $teachers = collect([$user->teacher ?? $user]);
        }

        return view('evaluations.create', compact('subjects', 'evaluationTypes', 'classRooms', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvaluation($request);
        
        Evaluation::create($validated + [
            'teacher_id' => $this->getTeacherId($request),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation created successfully.');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['subject', 'classRoom', 'evaluationType', 'teacher', 'creator']);
        return view('evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        $user = Auth::user();
        $evaluationTypes = EvaluationType::all();

        if ($user->isAdmin() || $user->email === 'ronaldoagbohou@gmail.com') {
            $subjects = Subject::all();
            $classRooms = ClassRoom::all();
            $teachers = Teacher::with('user')->get();
        } else {
            $subjects = $user->taughtSubjects()->get();
            $classRooms = $user->teachingClassRooms()
                ->wherePivot('subject_id', $evaluation->subject_id)
                ->get();
            $teachers = collect([$user->teacher ?? $user]);
        }

        return view('evaluations.edit', compact('evaluation', 'subjects', 'evaluationTypes', 'classRooms', 'teachers'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $this->validateEvaluation($request, $evaluation);

        if (!Gate::allows('update', $evaluation)) {
            abort(403, 'You are not authorized to update this evaluation');
        }

        $evaluation->update($validated);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation deleted successfully.');
    }

    protected function validateEvaluation(Request $request, ?Evaluation $evaluation = null)
    {
        $user = Auth::user();
        $rules = [
            'subject_id' => 'required|exists:subjects,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'evaluation_type_id' => 'required|exists:evaluation_types,id',
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
            $teacherId = $this->getTeacherId($request);

            $rules['subject_id'][] = Rule::exists('subject_teacher', 'subject_id')
                ->where('teacher_id', $teacherId)
                ->where('class_room_id', $request->class_room_id);

            $rules['class_room_id'][] = Rule::exists('subject_teacher', 'class_room_id')
                ->where('teacher_id', $teacherId)
                ->where('subject_id', $request->subject_id);
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
        
        return $user->teacher?->id ?? $user->id;
    }
}