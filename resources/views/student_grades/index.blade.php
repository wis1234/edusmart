@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Grades for Evaluation: {{ $evaluation->subject->name }} - {{ $evaluation->evaluationType->name }}</h1>

    <a href="{{ route('evaluations.student_grades.create', $evaluation) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
        Grade a Student
    </a>

    @if($grades->isEmpty())
        <p>No grades recorded yet.</p>
    @else
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Student</th>
                    <th class="py-2 px-4 border-b">Marks Obtained</th>
                    <th class="py-2 px-4 border-b">Remarks</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grades as $grade)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $grade->student->user->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $grade->marks_obtained }} / {{ $evaluation->total_marks }}</td>
                    <td class="py-2 px-4 border-b">{{ $grade->remarks }}</td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('student_grades.edit', $grade) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                        <form action="{{ route('student_grades.destroy', $grade) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this grade?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
