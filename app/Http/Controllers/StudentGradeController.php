<?php

namespace App\Http\Controllers;

use App\Models\StudentGrade;
use App\Models\Evaluation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EvaluationPublishedMail;

class StudentGradeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(StudentGrade::class, 'student_grade');
    }

    public function index(Evaluation $evaluation)
    {
        $grades = StudentGrade::with(['student.user', 'evaluation'])
            ->where('evaluation_id', $evaluation->id)
            ->get();

        // Calculate performance metrics
        $performanceMetrics = $this->calculatePerformanceMetrics($grades, $evaluation);

        return view('student_grades.index', compact('evaluation', 'grades', 'performanceMetrics'));
    }

    public function create(Evaluation $evaluation)
    {
        $gradedStudentIds = StudentGrade::where('evaluation_id', $evaluation->id)
            ->pluck('student_id')
            ->toArray();

        $students = Student::where('class_room_id', $evaluation->class_room_id)
            ->whereNotIn('id', $gradedStudentIds)
            ->get();

        return view('student_grades.create', compact('evaluation', 'students'));
    }

    public function store(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => 'required|numeric|min:0|max:' . $evaluation->total_marks,
            'remarks' => 'nullable|string|max:500',
        ]);

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

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Grade recorded successfully.');
    }

    public function edit(StudentGrade $student_grade)
    {
        $evaluation = $student_grade->evaluation;
        $students = Student::where('class_room_id', $evaluation->class_room_id)->get();
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

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(StudentGrade $student_grade)
    {
        $evaluation = $student_grade->evaluation;
        $student_grade->delete();

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Grade deleted successfully.');
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
