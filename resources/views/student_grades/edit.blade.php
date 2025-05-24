@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">✏️ Edit Student Grade</h1>
                    <p class="text-blue-100 mt-1">
                    {{ $student_grade->student?->first_name . ' ' . $student_grade->student?->last_name . ' - ' . $evaluation->subject?->name . ' (' . $evaluation->evaluationType?->name . ')' }}
                    </p>
                </div>
                <div>
                    <p class="text-blue-100 mt-1">
                    </p>
                </div>
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200 backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <form action="{{ route('student_grades.update', $student_grade) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Marks Obtained -->
            <div class="mb-6">
                <label for="marks_obtained" class="block text-sm font-medium text-gray-700 mb-2">
                    Marks Obtained <span class="text-gray-500">(out of {{ $evaluation->total_marks }})</span>
                </label>
                <div class="relative rounded-md shadow-sm">
                    <input type="number" name="marks_obtained" id="marks_obtained" 
                           value="{{ old('marks_obtained', $student_grade->marks_obtained) }}" 
                           min="0" max="{{ $evaluation->total_marks }}"
                           class="block w-full pl-4 pr-12 py-3 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">/ {{ $evaluation->total_marks }}</span>
                    </div>
                </div>
                @error('marks_obtained')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remarks -->
            <div class="mb-6">
                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                <textarea name="remarks" id="remarks" rows="3" 
                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('remarks', $student_grade->remarks) }}</textarea>
                @error('remarks')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="reset" class="inline-flex items-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Reset
                </button>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Grade
                </button>
            </div>
        </form>
    </div>
</div>
@endsection