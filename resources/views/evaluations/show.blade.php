@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">📝 Evaluation Details</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Created by {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} 
                        on {{ $evaluation->created_at->format('M d, Y H:i') }}
                    </p>
                </div>
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Evaluations
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Evaluation Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Subject</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->subject->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Class Room</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->classRoom->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Evaluation Type</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->evaluationType->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Teacher</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->teacher->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Academic Year</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->academic_year }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Term</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->term }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Dates & Marks</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Evaluation Date</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->evaluation_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Marks</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->total_marks }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Passing Marks</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $evaluation->passing_marks }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    View Grades
                </a>
                
                <a href="{{ route('evaluations.student_grades.create', $evaluation) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Grade a Student
                </a>

                @can('update', $evaluation)
                <a href="{{ route('evaluations.edit', $evaluation) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Evaluation
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection