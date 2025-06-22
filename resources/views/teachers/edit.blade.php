<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Edit Teacher</h1>
                    <p class="text-gray-500 dark:text-gray-300">Update information for {{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
    </div>

        <!-- Messages de session -->
    @foreach (['success' => 'green', 'error' => 'red'] as $msg => $color)
        @if (session($msg))
                <div class="mb-6 bg-{{ $color }}-100 border border-{{ $color }}-400 text-{{ $color }}-700 px-4 py-3 rounded-lg">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <!-- Formulaire modernisé -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">First Name <span class="text-red-500">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $teacher->teacher_firstname) }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $teacher->teacher_lastname) }}" maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $teacher->teacher_email) }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
</div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $teacher->teacher_phone) }}" required maxlength="20"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($teacher->date_of_birth)->format('Y-m-d')) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('date_of_birth') border-red-500 @enderror">
                        @error('date_of_birth')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Gender <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('gender') border-red-500 @enderror">
                    <option value="">Select Gender</option>
                    @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                        <option value="{{ $val }}" {{ old('gender', $teacher->gender) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Profile Photo</label>
                        @if($teacher->profile_photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $teacher->profile_photo) }}" alt="Profile Photo" class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                            </div>
                        @endif
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('profile_photo') border-red-500 @enderror"
                            onchange="previewImage(this)">
                        @error('profile_photo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="image-preview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                        </div>
                    </div>
            </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address <span class="text-red-500">*</span></label>
                    <textarea id="address" name="address" rows="3" required maxlength="255"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror">{{ old('address', $teacher->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
            </div>

                <!-- Teaching Assignments -->
                <div class="border rounded-lg p-6 bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Teaching Assignments</h3>
                        <span id="assignment-counter" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">
                            {{ count($teacher->classRoomTeachers) > 0 ? count($teacher->classRoomTeachers) . ' assignment(s)' : '1 assignment' }}
                        </span>
                    </div>
                    <div id="assignments-container">
                        @php $assignmentIndex = 0; @endphp
                        @forelse ($teacher->classRoomTeachers as $assignment)
                            <div class="assignment-entry grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 shadow-sm" data-assignment-id="{{ $assignmentIndex }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">School <span class="text-red-500">*</span></label>
                                    <select name="schools[]" class="school-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ $assignment->classRoom && $assignment->classRoom->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subjects <span class="text-red-500">*</span></label>
                                    <select name="subjects[{{ $assignmentIndex }}][]" class="subject-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" multiple required>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" data-school="{{ $subject->school_id }}" @if($assignment->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-xs text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple subjects</small>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Class Room <span class="text-red-500">*</span></label>
                                    <select name="class_rooms[]" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select Class Room</option>
                                        @foreach($classRooms as $classRoom)
                                            <option value="{{ $classRoom->id }}" data-school="{{ $classRoom->school_id }}" {{ $assignment->class_room_id == $classRoom->id ? 'selected' : '' }}>{{ $classRoom->name }} ({{ $classRoom->grade_level }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Academic Year <span class="text-red-500">*</span></label>
                                    <select name="years[]" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        @for($year = date('Y') + 2; $year >= date('Y') - 5; $year--)
                                            <option value="{{ $year }}" {{ $assignment->year == $year ? 'selected' : '' }}>
                                                {{ $year }}-{{ $year + 1 }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-assignment bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded-lg transition w-full" 
                                            onclick="this.closest('.assignment-entry').remove();" title="Remove this assignment">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @php $assignmentIndex++; @endphp
                        @empty
                            <!-- If no assignments, show a blank row -->
                            <div class="assignment-entry grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 shadow-sm" data-assignment-id="0">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">School <span class="text-red-500">*</span></label>
                                    <select name="schools[]" class="school-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subjects <span class="text-red-500">*</span></label>
                                    <select name="subjects[0][]" class="subject-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" multiple required>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" data-school="{{ $subject->school_id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-xs text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple subjects</small>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Class Room <span class="text-red-500">*</span></label>
                                    <select name="class_rooms[]" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select Class Room</option>
                                        @foreach($classRooms as $classRoom)
                                            <option value="{{ $classRoom->id }}" data-school="{{ $classRoom->school_id }}">{{ $classRoom->name }} ({{ $classRoom->grade_level }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Academic Year <span class="text-red-500">*</span></label>
                                    <select name="years[]" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        @for($year = date('Y') + 2; $year >= date('Y') - 5; $year--)
                                            <option value="{{ $year }}">
                                                {{ $year }}-{{ $year + 1 }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-assignment bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded-lg transition w-full opacity-50 cursor-not-allowed" 
                                            onclick="this.closest('.assignment-entry').remove();" title="Remove this assignment" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" id="add-assignment" 
                        class="mt-2 bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition">
                        <i class="fas fa-plus mr-1"></i> Add Assignment
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Grade <span class="text-red-500">*</span></label>
                        <input type="text" id="grade" name="grade" value="{{ old('grade', $teacher->grade) }}" required maxlength="100"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('grade') border-red-500 @enderror">
                        @error('grade')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="speciality" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Speciality <span class="text-red-500">*</span></label>
                        <input type="text" id="speciality" name="speciality" value="{{ old('speciality', $teacher->speciality) }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('speciality') border-red-500 @enderror">
                        @error('speciality')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subject_title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subject Title</label>
                        <input type="text" id="subject_title" name="subject_title" value="{{ old('subject_title', $teacher->subject_title) }}" maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('subject_title') border-red-500 @enderror">
                        @error('subject_title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
            </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                        <option value="">Select Status</option>
                        @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'on_leave' => 'On Leave'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $teacher->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
            </div>

                <div class="flex justify-end gap-2 mt-8">
                    <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-save"></i> Update Teacher
            </button>
        </div>
    </form>
        </div>
</div>

<script>
        // JavaScript pour l'ajout dynamique d'assignments et preview d'image
        (function() {
            'use strict';
            
            // Variables globales
            let assignmentCounter = 1;
            let schools = [];
            let subjects = [];
            let classRooms = [];
            
            // Initialiser les événements
            function init() {
                console.log('Initializing teacher edit form...');
                
                // Bouton d'ajout d'assignment
                const addBtn = document.getElementById('add-assignment');
                if (addBtn) {
                    addBtn.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        addAssignment();
                    });
                }
                
                // Preview d'image
                const imageInput = document.getElementById('profile_photo');
                if (imageInput) {
                    imageInput.addEventListener('change', handleImagePreview);
                }
                
                // Validation du formulaire
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', handleFormSubmit);
                }
                
                // Initialiser l'interface
                updateUI();
                
                console.log('Teacher edit form initialized successfully');
            }
            
            // Initialisation
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
        } else {
                init();
        }
            
        })();

        // Helper to filter subjects by school
        function filterSubjectsBySchool(subjectSelect, schoolId) {
            Array.from(subjectSelect.options).forEach(option => {
                option.style.display = (option.getAttribute('data-school') == schoolId) ? '' : 'none';
            });
        }

        // Assignment row management
        let assignmentIndex = {{ $assignmentIndex ?? 1 }};
        document.getElementById('add-assignment').addEventListener('click', function() {
            const container = document.getElementById('assignments-container');
            const firstEntry = container.querySelector('.assignment-entry');
            const newEntry = firstEntry.cloneNode(true);
            // Update names and clear values
            newEntry.setAttribute('data-assignment-id', assignmentIndex);
            newEntry.querySelectorAll('select, input').forEach(el => {
                if (el.name && el.name.startsWith('subjects[')) {
                    el.name = `subjects[${assignmentIndex}][]`;
                    el.selectedIndex = -1;
                } else if (el.name === 'schools[]') {
                    el.selectedIndex = 0;
                } else if (el.name === 'class_rooms[]') {
                    el.selectedIndex = 0;
                } else if (el.name === 'years[]') {
                    el.selectedIndex = 0;
                }
            });
            // Enable remove button
            const removeBtn = newEntry.querySelector('.remove-assignment');
            removeBtn.disabled = false;
            removeBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            removeBtn.onclick = function() { newEntry.remove(); };
            container.appendChild(newEntry);
            assignmentIndex++;
        });

        // School select change event to filter subjects
        function updateSubjectDropdowns() {
            document.querySelectorAll('.assignment-entry').forEach(entry => {
                const schoolSelect = entry.querySelector('.school-select');
                const subjectSelect = entry.querySelector('.subject-select');
                if (schoolSelect && subjectSelect) {
                    schoolSelect.addEventListener('change', function() {
                        filterSubjectsBySchool(subjectSelect, this.value);
                    });
                    // Initial filter
                    filterSubjectsBySchool(subjectSelect, schoolSelect.value);
                }
            });
        }
        document.addEventListener('DOMContentLoaded', updateSubjectDropdowns);
        document.getElementById('assignments-container').addEventListener('DOMNodeInserted', updateSubjectDropdowns);
</script>
</x-app-layout>
