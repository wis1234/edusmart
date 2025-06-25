<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-calendar-plus text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">New Schedule</h1>
                    <p class="text-gray-500 dark:text-gray-300">Add a new weekly timetable</p>
                </div>
            </div>
            <a href="{{ route('calendars.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold shadow hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
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

            @error('conflict')
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-times"></i>
                        <span class="font-semibold">{{ $message }}</span>
                    </div>
                </div>
            @enderror

            <form action="{{ route('calendars.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teacher -->
                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 @error('teacher_id') border-red-500 @enderror">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @if(old('teacher_id') == $teacher->id) selected @endif>{{ $teacher->name ?? ($teacher->teacher_firstname . ' ' . $teacher->teacher_lastname) }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Room -->
                    <div>
                        <label for="class_room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class Room</label>
                        <select name="class_room_id" id="class_room_id" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 @error('class_room_id') border-red-500 @enderror">
                            <option value="">Select Class Room</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" @if(old('class_room_id') == $classRoom->id) selected @endif>{{ $classRoom->name }}</option>
                            @endforeach
                        </select>
                        @error('class_room_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                        <select name="subject_id" id="subject_id" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 @error('subject_id') border-red-500 @enderror">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @if(old('subject_id') == $subject->id) selected @endif>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- School -->
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">School</label>
                        <select name="school_id" id="school_id" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 @error('school_id') border-red-500 @enderror">
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" @if(old('school_id') == $school->id) selected @endif>{{ $school->name }}</option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 @error('academic_year') border-red-500 @enderror">
                            <option value="">Select Academic Year</option>
                            <option value="2023-2024" @if(old('academic_year') == '2023-2024') selected @endif>2023-2024</option>
                            <option value="2024-2025" @if(old('academic_year') == '2024-2025') selected @endif>2024-2025</option>
                            <option value="2025-2026" @if(old('academic_year') == '2025-2026') selected @endif>2025-2026</option>
                        </select>
                        @error('academic_year')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Week Number -->
                    <div>
                        <label for="week_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Week Number</label>
                        <input type="number" name="week_number" id="week_number" min="1" max="52" value="{{ old('week_number') }}" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 @error('week_number') border-red-500 @enderror" placeholder="Enter week number">
                        @error('week_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Weekly Schedule Cards -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-week text-indigo-500"></i> Weekly Schedule
                    </h3>
                    @php
                        $days = ['monday','tuesday','wednesday','thursday','friday','saturday'];
                        $dayLabels = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                    @endphp
                    <div class="flex items-center justify-center gap-6 mb-4">
                        <button type="button" id="prev-day" class="p-3 rounded-full bg-gradient-to-tr from-indigo-400 to-purple-500 text-white shadow-lg hover:scale-110 hover:from-purple-500 hover:to-indigo-400 transition-all duration-200 text-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500"><i class="fas fa-chevron-left"></i></button>
                        <span id="current-day-label" class="text-2xl font-extrabold text-gray-800 dark:text-white drop-shadow-lg"></span>
                        <button type="button" id="next-day" class="p-3 rounded-full bg-gradient-to-tr from-indigo-400 to-purple-500 text-white shadow-lg hover:scale-110 hover:from-purple-500 hover:to-indigo-400 transition-all duration-200 text-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div id="day-schedule-container" class="flex justify-center items-center min-h-[320px]">
                        @foreach($days as $i => $day)
                        <div class="day-schedule transition-all duration-300 ease-in-out transform scale-95 opacity-0 absolute w-full max-w-lg mx-auto z-10" id="schedule-{{ $day }}" style="display: none;">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 flex flex-col items-center w-full max-w-lg mx-auto border border-gray-100 dark:border-gray-700 transition-all duration-300">
                                <div class="flex items-center gap-2 mb-4">
                                    <i class="fas fa-calendar-day text-indigo-400 text-2xl"></i>
                                    <span class="font-extrabold text-gray-800 dark:text-white text-2xl tracking-wide">{{ $dayLabels[$i] }}</span>
                                </div>
                                <div id="slots-{{ $day }}" class="w-full">
                                    @php
                                        $oldSlots = old('week_schedule.'.$day);
                                        if (is_array($oldSlots)) {
                                            // Si tous les slots sont vides, n'en afficher qu'un seul
                                            $allEmpty = collect($oldSlots)->every(function($slot) {
                                                return empty($slot['start_time']) && empty($slot['end_time']);
                                            });
                                            if ($allEmpty) {
                                                $oldSlots = [[]];
                                            }
                                        } else {
                                            $oldSlots = [[]];
                                        }
                                    @endphp
                                    @foreach($oldSlots as $slotIndex => $slot)
                                    <div class="flex items-center gap-3 mb-2 slot-row">
                                        <input type="time" name="week_schedule[{{ $day }}][{{ $slotIndex }}][start_time]" value="{{ $slot['start_time'] ?? '' }}" class="rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 w-28 @error('week_schedule.'.$day.'.'.$slotIndex.'.start_time') border-red-500 @enderror" placeholder="Start">
                                        <span class="text-gray-500">-</span>
                                        <input type="time" name="week_schedule[{{ $day }}][{{ $slotIndex }}][end_time]" value="{{ $slot['end_time'] ?? '' }}" class="rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 w-28 @error('week_schedule.'.$day.'.'.$slotIndex.'.end_time') border-red-500 @enderror" placeholder="End">
                                        <button type="button" class="remove-slot px-2 py-1 rounded bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-700 transition" title="Remove"><i class="fas fa-times"></i></button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="add-slot mt-4 px-4 py-2 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-semibold shadow hover:from-purple-600 hover:to-indigo-500 transition-all duration-200 text-base flex items-center gap-2" data-day="{{ $day }}"><i class="fas fa-plus"></i> Add time slot</button>
                                @for($slotIndex = 0; $slotIndex < count($oldSlots); $slotIndex++)
                                    @error('week_schedule.'.$day.'.'.$slotIndex.'.start_time')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                    @error('week_schedule.'.$day.'.'.$slotIndex.'.end_time')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                @endfor
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">Add one or more time slots for each day. Leave blank for days without class.</p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-8">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-save"></i> Create Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const days = ['monday','tuesday','wednesday','thursday','friday','saturday'];
            const dayLabels = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            let currentDayIndex = 0;
            function showDay(index, direction = 0) {
                days.forEach((day, i) => {
                    const el = document.getElementById('schedule-' + day);
                    if (i === index) {
                        el.style.display = '';
                        setTimeout(() => {
                            el.classList.remove('scale-95', 'opacity-0', 'z-0');
                            el.classList.add('scale-100', 'opacity-100', 'z-20');
                        }, 10);
                    } else {
                        el.classList.remove('scale-100', 'opacity-100', 'z-20');
                        el.classList.add('scale-95', 'opacity-0', 'z-0');
                        setTimeout(() => { el.style.display = 'none'; }, 300);
                    }
                });
                document.getElementById('current-day-label').textContent = dayLabels[index];
            }
            document.getElementById('prev-day').addEventListener('click', function() {
                currentDayIndex = (currentDayIndex - 1 + days.length) % days.length;
                showDay(currentDayIndex, -1);
            });
            document.getElementById('next-day').addEventListener('click', function() {
                currentDayIndex = (currentDayIndex + 1) % days.length;
                showDay(currentDayIndex, 1);
            });
            showDay(currentDayIndex);

            // Ajout/suppression de créneaux horaires (inchangé)
            days.forEach(function(day) {
                const addBtn = document.querySelector('.add-slot[data-day="'+day+'"]');
                const slotsDiv = document.getElementById('slots-'+day);
                addBtn.addEventListener('click', function() {
                    const idx = slotsDiv.querySelectorAll('.slot-row').length;
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-3 mb-2 slot-row';
                    row.innerHTML = `
                        <input type="time" name="week_schedule[${day}][${idx}][start_time]" class="rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 w-28" placeholder="Start">
                        <span class="text-gray-500">-</span>
                        <input type="time" name="week_schedule[${day}][${idx}][end_time]" class="rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 w-28" placeholder="End">
                        <button type="button" class="remove-slot px-2 py-1 rounded bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-700 transition" title="Remove"><i class="fas fa-times"></i></button>
                    `;
                    slotsDiv.appendChild(row);
                    row.querySelector('.remove-slot').addEventListener('click', function() {
                        row.remove();
                    });
                });
                // Remove slot handler for initial rows
                slotsDiv.querySelectorAll('.remove-slot').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        btn.closest('.slot-row').remove();
                    });
                });
            });

            // --- LOGIQUE DYNAMIQUE ENSEIGNANT/CLASSE/MATIERE ---
            const teacherSelect = document.getElementById('teacher_id');
            const classSelect = document.getElementById('class_room_id');
            const subjectSelect = document.getElementById('subject_id');

            teacherSelect.addEventListener('change', function() {
                const teacherId = this.value;
                classSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                subjectSelect.innerHTML = '<option value="">Sélectionner une matière</option>';
                if (!teacherId) return;
                fetch(`/api/teacher/${teacherId}/classrooms`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            classSelect.innerHTML = '<option value="">Aucune classe affectée</option>';
                        } else {
                            data.forEach(cls => {
                                const opt = document.createElement('option');
                                opt.value = cls.id;
                                opt.textContent = cls.name;
                                classSelect.appendChild(opt);
                            });
                        }
                    });
            });

            classSelect.addEventListener('change', function() {
                const teacherId = teacherSelect.value;
                const classId = this.value;
                subjectSelect.innerHTML = '<option value="">Sélectionner une matière</option>';
                if (!teacherId || !classId) return;
                fetch(`/api/teacher/${teacherId}/classroom/${classId}/subjects`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            subjectSelect.innerHTML = '<option value="">Aucune matière affectée</option>';
                        } else {
                            data.forEach(sub => {
                                const opt = document.createElement('option');
                                opt.value = sub.id;
                                opt.textContent = sub.name;
                                subjectSelect.appendChild(opt);
                            });
                        }
                    });
            });
        });
    </script>
    @endpush
</x-app-layout>
