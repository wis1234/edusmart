@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Edit Grade for Student: {{ $student_grade->student->user->name }} - Evaluation: {{ $evaluation->subject->name }} - {{ $evaluation->evaluationType->name }}</h1>

    <form action="{{ route('student_grades.update', $student_grade) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="marks_obtained" class="block font-semibold mb-1">Marks Obtained (out of {{ $evaluation->total_marks }})</label>
            <input type="number" name="marks_obtained" id="marks_obtained" value="{{ old('marks_obtained', $student_grade->marks_obtained) }}" min="0" max="{{ $evaluation->total_marks }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            @error('marks_obtained')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="remarks" class="block font-semibold mb-1">Remarks</label>
            <textarea name="remarks" id="remarks" rows="3" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('remarks', $student_grade->remarks) }}</textarea>
            @error('remarks')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Grade</button>
    </form>
</div>
@endsection
