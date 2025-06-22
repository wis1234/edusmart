<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-clipboard-check text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Edit Evaluation</h1>
                    <p class="text-gray-500 dark:text-gray-300">Update academic evaluation details</p>
                </div>
            </div>
            <a href="{{ route('evaluations.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i> Back to Evaluations
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('evaluations.update', $evaluation) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Teacher Selection -->
                    @if(auth()->user()->isAdmin() || auth()->user()->email === 'ronaldoagbohou@gmail.com')
                    <div>
                        <label for="teacher_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" required>
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

                    <!-- Subject -->
                    <div>
                        <label for="subject_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                        <select name="subject_id" id="subject_id" class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" required>
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id', $evaluation->subject_id) == $subject->id)>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Classroom -->
                    <div>
                        <label for="class_room_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Class Room</label>
                        <select name="class_room_id" id="class_room_id" class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" required>
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
                        <label for="evaluation_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Evaluation Type</label>
                        <input type="text" name="evaluation_type" id="evaluation_type" value="{{ old('evaluation_type', optional($evaluation->evaluationType)->name ?? '') }}" class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" placeholder="e.g. Quiz, Exam, Assignment" required>
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Academic Year</label>
                        <select name="academic_year" id="academic_year" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Select Academic Year</option>
                            @php
                                $currentYear = date('Y');
                                $academicYears = [];
                                for ($i = 0; $i < 5; $i++) {
                                    $year = $currentYear + $i;
                                    $academicYears[] = $year . '-' . ($year + 1);
                                }
                            @endphp
                            @foreach($academicYears as $academic)
                                <option value="{{ $academic }}" {{ old('academic_year', $evaluation->academic_year) == $academic ? 'selected' : '' }}>{{ $academic }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Term -->
                    <div>
                        <label for="term" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Term</label>
                        <input type="text" name="term" id="term" value="{{ old('term', $evaluation->term) }}" 
                               class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" 
                               placeholder="Term 1" required>
                    </div>

                    <!-- Evaluation Date -->
                    <div>
                        <label for="evaluation_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Evaluation Date</label>
                        <input type="date" name="evaluation_date" id="evaluation_date" value="{{ old('evaluation_date', $evaluation->evaluation_date->format('Y-m-d')) }}" 
                               class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" 
                               required>
                    </div>

                    <!-- Total Marks -->
                    <div>
                        <label for="total_marks" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Marks</label>
                        <input type="number" name="total_marks" id="total_marks" value="{{ old('total_marks', $evaluation->total_marks) }}" 
                               class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" 
                               min="1" required>
                    </div>

                    <!-- Passing Marks -->
                    <div>
                        <label for="passing_marks" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Passing Marks</label>
                        <input type="number" name="passing_marks" id="passing_marks" value="{{ old('passing_marks', $evaluation->passing_marks) }}" 
                               class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition" 
                               min="0" required>
                    </div>
                </div>

                <!-- Notes -->
                <div class="col-span-full">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">{{ old('notes', $evaluation->notes) }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('evaluations.index') }}" class="px-6 py-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-save"></i> Update Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectSelect = document.getElementById('subject_id');
        const classroomSelect = document.getElementById('class_room_id');

        if(subjectSelect && classroomSelect) {
            subjectSelect.addEventListener('change', function() {
                const subjectId = this.value;
                 if (!subjectId) {
                    classroomSelect.innerHTML = '<option value="">Select Subject First</option>';
                    return;
                }
                classroomSelect.innerHTML = '<option value="">Loading...</option>';
                
                fetch(`/api/classrooms?subject_id=${subjectId}`)
                    .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok.'))
                    .then(data => {
                        classroomSelect.innerHTML = '<option value="">Select Class Room</option>';
                        data.forEach(classroom => {
                            const option = document.createElement('option');
                            option.value = classroom.id;
                            option.textContent = classroom.name;
                            classroomSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching classrooms:', error);
                        classroomSelect.innerHTML = '<option value="">Error loading classrooms</option>';
                    });
            });
        }
    });
    </script>
    @endpush
</x-app-layout>