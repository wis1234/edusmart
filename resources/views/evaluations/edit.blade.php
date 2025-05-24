@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">✏️ Edit Evaluation</h1>
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Evaluations
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-6 mt-6 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Section -->
        <form action="{{ route('evaluations.update', $evaluation) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Teacher Selection (Admin Only) -->
                @if(auth()->user()->isAdmin() || auth()->user()->email === 'ronaldoagbohou@gmail.com')
                <div class="space-y-1">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700">Teacher</label>
                    <select name="teacher_id" id="teacher_id" class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Teacher</option>
                        @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('teacher_id', $evaluation->teacher_id) == $teacher->id)>
                            {{ $teacher->user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="teacher_id" value="{{ auth()->user()->teacher?->id ?? auth()->id() }}">
                @endif

                <!-- Subject Selection -->
                <div class="space-y-1">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                    <select name="subject_id" id="subject_id" class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Subject</option>
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id', $evaluation->subject_id) == $subject->id)>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Class Room Selection -->
                <div class="space-y-1">
                    <label for="class_room_id" class="block text-sm font-medium text-gray-700">Class Room</label>
                    <select name="class_room_id" id="class_room_id" class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Class Room</option>
                        @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(old('class_room_id', $evaluation->class_room_id) == $classRoom->id)>
                            {{ $classRoom->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Evaluation Type -->
                <div class="space-y-1">
                    <label for="evaluation_type_id" class="block text-sm font-medium text-gray-700">Evaluation Type</label>
                    <select name="evaluation_type_id" id="evaluation_type_id" class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Type</option>
                        @foreach ($evaluationTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('evaluation_type_id', $evaluation->evaluation_type_id) == $type->id)>
                            {{ $type->name }} ({{ $type->weight }}%)
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Academic Year (Now as select) -->
                <div class="space-y-1">
                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select name="academic_year" id="academic_year" class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @php
                            // Generate academic years from current year -5 to +5
                            $currentYear = date('Y');
                            $years = [];
                            for ($i = -5; $i <= 5; $i++) {
                                $year = $currentYear + $i;
                                $years[] = $year . '-' . ($year + 1);
                            }
                        @endphp
                        <option value="">Select Academic Year</option>
                        @foreach ($years as $year)
                        <option value="{{ $year }}" @selected(old('academic_year', $evaluation->academic_year) == $year)>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Term -->
                <div class="space-y-1">
                    <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                    <select name="term" id="term" class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Term</option>
                        <option value="Term 1" @selected(old('term', $evaluation->term) == 'Term 1')>Term 1</option>
                        <option value="Term 2" @selected(old('term', $evaluation->term) == 'Term 2')>Term 2</option>
                        <option value="Term 3" @selected(old('term', $evaluation->term) == 'Term 3')>Term 3</option>
                        <option value="Final" @selected(old('term', $evaluation->term) == 'Final')>Final</option>
                    </select>
                </div>

                <!-- Evaluation Date -->
                <div class="space-y-1">
                    <label for="evaluation_date" class="block text-sm font-medium text-gray-700">Evaluation Date</label>
                    <input type="date" name="evaluation_date" id="evaluation_date" 
                           value="{{ old('evaluation_date', $evaluation->evaluation_date->format('Y-m-d')) }}" 
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-sm py-2.5 px-3" 
                           required>
                </div>

                <!-- Total Marks -->
                <div class="space-y-1">
                    <label for="total_marks" class="block text-sm font-medium text-gray-700">Total Marks</label>
                    <input type="number" name="total_marks" id="total_marks" 
                           value="{{ old('total_marks', $evaluation->total_marks) }}" 
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-sm py-2.5 px-3" 
                           min="1" required>
                </div>

                <!-- Passing Marks -->
                <div class="space-y-1">
                    <label for="passing_marks" class="block text-sm font-medium text-gray-700">Passing Marks</label>
                    <input type="number" name="passing_marks" id="passing_marks" 
                           value="{{ old('passing_marks', $evaluation->passing_marks) }}" 
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-sm py-2.5 px-3" 
                           min="0" required>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6 space-y-1">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-sm py-2.5 px-3">{{ old('notes', $evaluation->notes) }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                <button type="reset" class="inline-flex items-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Reset Changes
                </button>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Evaluation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic classroom loading based on subject
    const subjectSelect = document.getElementById('subject_id');
    const classroomSelect = document.getElementById('class_room_id');

    if(subjectSelect && classroomSelect) {
        subjectSelect.addEventListener('change', function() {
            const subjectId = this.value;
            classroomSelect.innerHTML = '<option value="">Loading...</option>';
            
            fetch(`/api/classrooms?subject_id=${subjectId}`)
                .then(response => response.json())
                .then(data => {
                    classroomSelect.innerHTML = '<option value="">Select Class Room</option>';
                    data.forEach(classroom => {
                        const option = document.createElement('option');
                        option.value = classroom.id;
                        option.textContent = classroom.name;
                        option.selected = classroom.id == {{ old('class_room_id', $evaluation->class_room_id) }};
                        classroomSelect.appendChild(option);
                    });
                });
        });

        // Trigger initial load if subject is preselected
        if(subjectSelect.value) {
            subjectSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endsection