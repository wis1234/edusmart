<?php

namespace App\Http\Controllers;

use App\Models\StudentGrade;
use App\Models\Evaluation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EvaluationPublishedMail;
use App\Services\NotificationService;

class StudentGradeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->authorizeResource(StudentGrade::class, 'student_grade');
    }

    public function index(Evaluation $evaluation, Request $request)
    {
        $query = StudentGrade::with(['student.user', 'evaluation'])
            ->where('evaluation_id', $evaluation->id);

        // Search
        if ($search = $request->input('search')) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by status (pass/fail)
        if ($request->filled('status')) {
            if ($request->status == 'passed') {
                $query->where('marks_obtained', '>=', $evaluation->passing_marks);
            } elseif ($request->status == 'failed') {
                $query->where('marks_obtained', '<', $evaluation->passing_marks);
            }
        }

        $grades = $query->paginate(10)->withQueryString();

        // Calculate performance metrics
        $performanceMetrics = $this->calculatePerformanceMetrics($evaluation->studentGrades, $evaluation);

        return view('student_grades.index', compact('evaluation', 'grades', 'performanceMetrics'));
    }

    public function create(Evaluation $evaluation)
    {
        $gradedStudentIds = StudentGrade::where('evaluation_id', $evaluation->id)
            ->pluck('student_id')
            ->toArray();

        $schoolId = $evaluation->subject->school_id ?? $evaluation->classRoom->school_id;

        $user = Auth::user();
        // Un enseignant ne peut donner de note que pour une évaluation qu'il a créée
        if (($user->hasRole('enseignant') || $user->hasRole('teacher')) && $evaluation->teacher_id !== $user->teacherProfile?->id) {
            abort(403, "Vous n'êtes pas autorisé à noter cette évaluation.");
        }

        $studentsQuery = Student::where('class_room_id', $evaluation->class_room_id)
            ->where('school_id', $schoolId)
            ->whereNotIn('id', $gradedStudentIds)
            ->with('user');

        // Les admins et school admins peuvent voir tous les étudiants (actifs et inactifs)
        // Les autres rôles ne voient que les étudiants actifs
        if (!($user->hasRole('admin') || $user->hasRole('manager') || $user->role === 'school_admin')) {
            $studentsQuery->where('status', 'active');
        }

        $students = $studentsQuery->get();

        return view('student_grades.create', compact('evaluation', 'students'));
    }

    public function store(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => 'required|numeric|min:0|max:' . $evaluation->total_marks,
            'remarks' => 'nullable|string|max:500',
        ]);

        // Vérifier que l'étudiant appartient à la bonne école et classe
        $schoolId = $evaluation->subject->school_id ?? $evaluation->classRoom->school_id;
        $student = Student::where('id', $validated['student_id'])
            ->where('class_room_id', $evaluation->class_room_id)
            ->where('school_id', $schoolId)
            ->first();

        if (!$student) {
            return redirect()->back()
                ->withErrors(['student_id' => 'The selected student does not belong to this evaluation\'s class and school.'])
                ->withInput();
        }

        $validated['evaluation_id'] = $evaluation->id;

        $studentGrade = StudentGrade::updateOrCreate(
            ['evaluation_id' => $evaluation->id, 'student_id' => $validated['student_id']],
            $validated
        );

        // Send email notification to parent
        $student = $studentGrade->student;
        if ($student && $student->parent && $student->parent->user && $student->parent->user->email) {
            Mail::to($student->parent->user->email)->send(new EvaluationPublishedMail($studentGrade));
        }
        // Notification interne à l'élève et au parent
        if ($student && $student->user) {
            $this->notificationService->send(
                $student->user,
                'success',
                'Nouvelle note publiée',
                'Une nouvelle note a été publiée pour vous.',
                route('student_grades.show', $studentGrade)
            );
        }
        if ($student && $student->parent && $student->parent->user) {
            $this->notificationService->send(
                $student->parent->user,
                'success',
                'Nouvelle note pour votre enfant',
                'Une nouvelle note a été publiée pour votre enfant.',
                route('student_grades.show', $studentGrade)
            );
        }
        return redirect()->back()->with('success', 'Grade recorded successfully.');
    }

    public function edit(StudentGrade $student_grade)
    {
        $evaluation = $student_grade->evaluation;
        
        // Récupérer l'école de l'évaluation
        $schoolId = $evaluation->subject->school_id ?? $evaluation->classRoom->school_id;
        
        $user = Auth::user();
        $studentsQuery = Student::where('class_room_id', $evaluation->class_room_id)
            ->where('school_id', $schoolId) // Filtrer par école
            ->with('user'); // Charger la relation user pour afficher le nom

        // Les admins et school admins peuvent voir tous les étudiants (actifs et inactifs)
        // Les autres rôles ne voient que les étudiants actifs
        if (!($user->hasRole('admin') || $user->hasRole('manager') || $user->role === 'school_admin')) {
            $studentsQuery->where('status', 'active');
        }
            
        $students = $studentsQuery->get();
            
        return view('student_grades.edit', compact('student_grade', 'evaluation', 'students'));
    }

    public function update(Request $request, StudentGrade $student_grade)
    {
        $evaluation = $student_grade->evaluation;

        $validated = $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:' . $evaluation->total_marks,
            'remarks' => 'nullable|string|max:500',
        ]);

        $student_grade->update($validated);
        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'Student Grade Updated',
            'A student grade has been updated in the system.',
            'warning',
            route('evaluations.student_grades.index', $evaluation)
        );
        return redirect()->route('evaluations.student_grades.index', $evaluation)
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(StudentGrade $student_grade)
    {
        $evaluation = $student_grade->evaluation;
        try {
            $student_grade->delete();
            // Notification
            $this->notificationService->sendToRole(
                'admin',
                'Student Grade Deleted',
                'A student grade has been deleted from the system.',
                'error'
            );
            return redirect()->route('evaluations.show', $evaluation)
                ->with('success', 'Grade deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the grade. Please try again.');
        }
    }

    public function indexAll(Request $request)
    {
        $this->authorize('viewAny', StudentGrade::class);

        $user = Auth::user();
        $query = StudentGrade::with([
            'student.user',
            'evaluation.subject',
            'evaluation.evaluationType',
            'evaluation.classRoom'
        ])->latest('updated_at');

        // Si l'utilisateur est un enseignant, filtrer par ses matières enseignées
        if ($user->role === 'enseignant') {
            $query->whereHas('evaluation.subject', function($q) use ($user) {
                $q->whereIn('id', $user->taughtSubjects()->pluck('subjects.id'));
            });
        }

        if ($search = $request->input('search')) {
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($subjectId = $request->input('subject_id')) {
            $query->whereHas('evaluation.subject', function ($q) use ($subjectId) {
                $q->where('id', $subjectId);
            });
        }

        if ($classRoomId = $request->input('class_room_id')) {
            $query->whereHas('evaluation.classRoom', function ($q) use ($classRoomId) {
                $q->where('id', $classRoomId);
            });
        }
        
        $grades = $query->paginate(15)->withQueryString();
        
        // Filtrer les matières et classes par matières enseignées pour les enseignants
        if ($user->role === 'enseignant') {
            $subjects = $user->taughtSubjects()->orderBy('name')->get();
            $classRooms = $user->teachingClassRooms()->orderBy('name')->get();
        } else {
            $subjects = \App\Models\Subject::orderBy('name')->get();
            $classRooms = \App\Models\ClassRoom::orderBy('name')->get();
        }

        return view('student_grades.index_all', compact('grades', 'subjects', 'classRooms'));
    }

    protected function calculatePerformanceMetrics($grades, $evaluation)
    {
        $metrics = [
            'consistent_improvers' => 0,
            'inconsistent_performers' => 0,
            'declining_performers' => 0,
            'top_improver' => ['name' => null, 'improvement' => 0],
            'highest_score' => ['name' => null, 'score' => 0],
            'needs_attention' => ['name' => null, 'decline' => 0],
        ];

        foreach ($grades as $grade) {
            $performance = $grade->getPerformanceTrend();

            // Categorize students
            if ($performance['trend'] == 'improving') {
                $metrics['consistent_improvers']++;

                if ($performance['improvement_percentage'] > $metrics['top_improver']['improvement']) {
                    $metrics['top_improver'] = [
                        'name' => $grade->student->first_name . ' ' . $grade->student->last_name,
                        'improvement' => $performance['improvement_percentage']
                    ];
                }
            } elseif ($performance['trend'] == 'declining') {
                $metrics['declining_performers']++;

                if (abs($performance['improvement_percentage']) > abs($metrics['needs_attention']['decline'])) {
                    $metrics['needs_attention'] = [
                        'name' => $grade->student->first_name . ' ' . $grade->student->last_name,
                        'decline' => $performance['improvement_percentage']
                    ];
                }
            } elseif ($performance['trend'] == 'consistent') {
                $metrics['inconsistent_performers']++;
            }

            $currentScore = ($grade->marks_obtained / $evaluation->total_marks) * 100;
            if ($currentScore > $metrics['highest_score']['score']) {
                $metrics['highest_score'] = [
                    'name' => $grade->student->first_name . ' ' . $grade->student->last_name,
                    'score' => $currentScore
                ];
            }
        }

        // Add chart data keys
        $metrics['labels'] = ['Improving', 'Consistent', 'Declining'];
        $metrics['data'] = [
            $metrics['consistent_improvers'],
            $metrics['inconsistent_performers'],
            $metrics['declining_performers'],
        ];

        return $metrics;
    }
}
