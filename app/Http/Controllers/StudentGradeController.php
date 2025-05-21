<?php

namespace App\Http\Controllers;

use App\Models\StudentGrade;
use App\Models\Evaluation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(StudentGrade::class, 'student_grade');
    }

    public function index(Evaluation $evaluation)
    {
        $grades = StudentGrade::with('student.user')
            ->where('evaluation_id', $evaluation->id)
            ->get();

        return view('student_grades.index', compact('evaluation', 'grades'));
    }

    public function create(Evaluation $evaluation)
    {
        $students = Student::where('class_room_id', $evaluation->class_room_id)->get();
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

        StudentGrade::updateOrCreate(
            ['evaluation_id' => $evaluation->id, 'student_id' => $validated['student_id']],
            $validated
        );

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
}
