@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Grade Student for Evaluation: {{ $evaluation->subject->name }} - {{ $evaluation->evaluationType->name }}</h1>

    <form action="{{ route('evaluations.student_grades.store', $evaluation) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="student_id" class="block font-semibold mb-1">Student</label>
            <select name="student_id" id="student_id" class="w-full border border-gray-300 rounded px-3 py-2">
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->user->name }} ({{ $student->admission_number }})
                    </option>
                @endforeach
            </select>
            @error('student_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="marks_obtained" class="block font-semibold mb-1">Marks Obtained (out of {{ $evaluation->total_marks }})</label>
            <input type="number" name="marks_obtained" id="marks_obtained" value="{{ old('marks_obtained') }}" min="0" max="{{ $evaluation->total_marks }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            @error('marks_obtained')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="remarks" class="block font-semibold mb-1">Remarks</label>
            <textarea name="remarks" id="remarks" rows="3" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('remarks') }}</textarea>
            @error('remarks')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit Grade</button>
    </form>
</div>
@endsection
