@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Evaluation</h1>
            <a href="{{ route('evaluations.index') }}" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
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

        <form action="{{ route('evaluations.update', $evaluation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(auth()->user()->isAdmin() || auth()->user()->email === 'ronaldoagbohou@gmail.com')
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                    <select name="teacher_id" id="teacher_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
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
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <select name="subject_id" id="subject_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Select Subject</option>
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id', $evaluation->subject_id) == $subject->id)>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Class Room Selection -->
                <div>
                    <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-1">Class Room</label>
                    <select name="class_room_id" id="class_room_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Select Class Room</option>
                        @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(old('class_room_id', $evaluation->class_room_id) == $classRoom->id)>
                            {{ $classRoom->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Evaluation Type -->
                <div>
                    <label for="evaluation_type_id" class="block text-sm font-medium text-gray-700 mb-1">Evaluation Type</label>
                    <select name="evaluation_type_id" id="evaluation_type_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Select Type</option>
                        @foreach ($evaluationTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('evaluation_type_id', $evaluation->evaluation_type_id) == $type->id)>
                            {{ $type->name }} ({{ $type->weight }}%)
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Academic Year -->
                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <input type="text" name="academic_year" id="academic_year" 
                           value="{{ old('academic_year', $evaluation->academic_year) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                           placeholder="2023-2024" required>
                </div>

                <!-- Term -->
                <div>
                    <label for="term" class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                    <input type="text" name="term" id="term" 
                           value="{{ old('term', $evaluation->term) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                           placeholder="Term 1" required>
                </div>

                <!-- Evaluation Date -->
                <div>
                    <label for="evaluation_date" class="block text-sm font-medium text-gray-700 mb-1">Evaluation Date</label>
                    <input type="date" name="evaluation_date" id="evaluation_date" 
                           value="{{ old('evaluation_date', $evaluation->evaluation_date->format('Y-m-d')) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                           required>
                </div>

                <!-- Total Marks -->
                <div>
                    <label for="total_marks" class="block text-sm font-medium text-gray-700 mb-1">Total Marks</label>
                    <input type="number" name="total_marks" id="total_marks" 
                           value="{{ old('total_marks', $evaluation->total_marks) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                           min="1" required>
                </div>

                <!-- Passing Marks -->
                <div>
                    <label for="passing_marks" class="block text-sm font-medium text-gray-700 mb-1">Passing Marks</label>
                    <input type="number" name="passing_marks" id="passing_marks" 
                           value="{{ old('passing_marks', $evaluation->passing_marks) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                           min="0" required>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('notes', $evaluation->notes) }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                <button type="reset" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Reset
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Evaluation
                </button>
            </div>
        </form>
    </div>
</div>

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
@endsection