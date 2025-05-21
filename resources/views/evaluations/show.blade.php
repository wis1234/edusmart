@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Evaluation Details</h1>
            <a href="{{ route('evaluations.index') }}" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>

<div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <p class="mt-1">{{ $evaluation->subject->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class Room</label>
                    <p class="mt-1">{{ $evaluation->classRoom->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Evaluation Type</label>
                    <p class="mt-1">{{ $evaluation->evaluationType->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Teacher</label>
                    <p class="mt-1">{{ $evaluation->teacher->name ?? '' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <p class="mt-1">{{ $evaluation->academic_year }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Term</label>
                    <p class="mt-1">{{ $evaluation->term }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Evaluation Date</label>
                    <p class="mt-1">{{ $evaluation->evaluation_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Marks</label>
                    <p class="mt-1">{{ $evaluation->total_marks }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Passing Marks</label>
                    <p class="mt-1">{{ $evaluation->passing_marks }}</p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    View Grades
                </a>
                <a href="{{ route('evaluations.student_grades.create', $evaluation) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                    Grade a Student
                </a>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                Created by {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                on {{ $evaluation->created_at->format('M d, Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
