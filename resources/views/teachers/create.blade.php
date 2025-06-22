<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Add New Teacher</h1>
                    <p class="text-gray-500 dark:text-gray-300">Create a new teacher record</p>
    </div>
        </div>
            <div class="flex gap-2">
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
        </div>
        </div>

        <!-- Formulaire modernisé -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

            <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">First Name <span class="text-red-500">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required maxlength="20"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required
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
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
                <div>
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Profile Photo</label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('profile_photo') border-red-500 @enderror"
                        onchange="previewImage(this)">
                        @error('profile_photo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    <div id="image-preview" class="mt-2 hidden">
                        <img src="" alt="Profile Preview" class="w-32 h-32 object-cover rounded">
                    </div>
                </div>
            </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address <span class="text-red-500">*</span></label>
                    <textarea id="address" name="address" rows="3" required maxlength="255"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
            </div>

            <!-- Teaching Assignments -->
                <div class="border rounded-lg p-6 bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Teaching Assignments</h3>
                        <span id="assignment-counter" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">
                            1 assignment
                        </span>
            </div>
                    <div id="assignments-container">
                        <div class="assignment-entry grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 shadow-sm" data-assignment-id="initial">
                            <div>
                                @if(auth()->user()->role === 'school_admin')
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">School</label>
                                    <input type="text" value="{{ auth()->user()->school->name }}" readonly
                                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                    <input type="hidden" name="schools[]" value="{{ auth()->user()->school_id }}">
                                @else
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">School <span class="text-red-500">*</span></label>
                                    <select name="schools[]" class="school-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('schools.0') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
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
                                        <option value="{{ $year }}" {{ old('years.0', date('Y')) == $year ? 'selected' : '' }}>
                                            {{ $year }}-{{ $year + 1 }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="remove-assignment bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded-lg transition w-full opacity-50 cursor-not-allowed" 
                                        onclick="window.removeAssignment('initial')" title="Remove this assignment" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-assignment" 
                        class="mt-2 bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition">
                        <i class="fas fa-plus mr-1"></i> Add Assignment
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Grade <span class="text-red-500">*</span></label>
                        <input type="text" id="grade" name="grade" value="{{ old('grade') }}" required maxlength="100"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('grade') border-red-500 @enderror">
                        @error('grade')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
                    <div>
                        <label for="speciality" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Speciality <span class="text-red-500">*</span></label>
                        <input type="text" id="speciality" name="speciality" value="{{ old('speciality') }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('speciality') border-red-500 @enderror">
                        @error('speciality')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
            </div>
                    <div>
                        <label for="subject_title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subject Title <span class="text-red-500">*</span></label>
                        <input type="text" id="subject_title" name="subject_title" value="{{ old('subject_title') }}" required maxlength="255"
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
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
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
                        <i class="fas fa-save"></i> Create Teacher
            </button>
        </div>
    </form>
        </div>
</div>

<script>
        (function() {
            'use strict';
            
            // Variables globales
            let assignmentCounter = 0;
            
            // Initialiser les événements
            function init() {
                console.log('Initializing teacher create form...');
                
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
                
                console.log('Teacher create form initialized successfully');
            }
            
            // Ajouter un nouvel assignment
            function addAssignment() {
                console.log('Adding new assignment');
                
                const container = document.getElementById('assignments-container');
                const assignments = container.querySelectorAll('.assignment-entry');
                const assignmentId = 'assignment_' + Date.now();
                const assignmentIndex = assignments.length;
                
                // Récupérer les données des selects existants
                const schools = Array.from(document.querySelectorAll('select[name="schools[]"] option')).map(option => ({
                    value: option.value,
                    text: option.textContent
                })).filter(option => option.value !== '');
                
                const subjects = Array.from(document.querySelectorAll('select[name="subjects[]"] option')).map(option => ({
                    value: option.value,
                    text: option.textContent
                })).filter(option => option.value !== '');
                
                const classRooms = Array.from(document.querySelectorAll('select[name="class_rooms[]"] option')).map(option => ({
                    value: option.value,
                    text: option.textContent
                })).filter(option => option.value !== '');
                
                // Générer les options d'année
                const yearOptions = [];
                for (let year = new Date().getFullYear() + 2; year >= new Date().getFullYear() - 5; year--) {
                    yearOptions.push(`<option value="${year}">${year}-${year + 1}</option>`);
                }
                
                // Vérifier si l'utilisateur est un school_admin
                const isSchoolAdmin = {{ auth()->user()->role === 'school_admin' ? 'true' : 'false' }};
                const schoolName = '{{ auth()->user()->role === "school_admin" ? auth()->user()->school->name : "" }}';
                const schoolId = '{{ auth()->user()->role === "school_admin" ? auth()->user()->school_id : "" }}';
                
                const newAssignment = document.createElement('div');
                newAssignment.className = 'assignment-entry grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 shadow-sm';
                newAssignment.setAttribute('data-assignment-id', assignmentId);
                newAssignment.style.opacity = '0';
                newAssignment.style.transform = 'translateY(-20px) scale(0.95)';
                
                const schoolField = isSchoolAdmin 
                    ? `<div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">School</label>
                        <input type="text" value="${schoolName}" readonly
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                        <input type="hidden" name="schools[]" value="${schoolId}">
                       </div>`
                    : `<div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">School <span class="text-red-500">*</span></label>
                        <select name="schools[]" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select School</option>
                            ${schools.map(s => `<option value="${s.value}">${s.text}</option>`).join('')}
                        </select>
                       </div>`;
                
                newAssignment.innerHTML = `
                    ${schoolField}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subjects <span class="text-red-500">*</span></label>
                        <select name="subjects[${assignmentIndex}][]" class="subject-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" multiple required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" data-school="{{ $subject->school_id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
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
                            ${yearOptions.join('')}
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-assignment bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded-lg transition w-full" 
                                onclick="window.removeAssignment('${assignmentId}')" title="Remove this assignment">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                container.appendChild(newAssignment);
                
                // Animation d'entrée
                requestAnimationFrame(() => {
                    animateIn(newAssignment);
                });
                
                // Feedback utilisateur
                showNotification('Assignment added successfully!', 'success');
                
                console.log('Assignment added successfully');
                
                // Mettre à jour l'interface
                updateUI();
            }
            
            // Animation d'apparition
            function animateIn(element) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(-20px) scale(0.95)';
                
                requestAnimationFrame(() => {
                    element.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0) scale(1)';
                });
            }
            
            // Animation de disparition
            function animateOut(element, callback) {
                element.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                element.style.opacity = '0';
                element.style.transform = 'translateY(-20px) scale(0.95)';
                
                setTimeout(callback, 300);
            }
            
            // Supprimer un assignment
            window.removeAssignment = function(assignmentId) {
                console.log('Removing assignment:', assignmentId);
                
                const assignment = document.querySelector(`[data-assignment-id="${assignmentId}"]`);
                if (!assignment) {
                    console.error('Assignment not found:', assignmentId);
                    return;
                }
                
                animateOut(assignment, () => {
                    assignment.remove();
                    updateUI();
                    showNotification('Assignment removed successfully!', 'info');
                    console.log('Assignment removed successfully');
                });
            };
            
            // Mettre à jour l'interface utilisateur
            function updateUI() {
                const assignments = document.querySelectorAll('.assignment-entry');
                const removeButtons = document.querySelectorAll('.remove-assignment');
                const counter = document.getElementById('assignment-counter');
                const addBtn = document.getElementById('add-assignment');
                
                const count = assignments.length;
                
                // Mettre à jour le compteur
                if (counter) {
                    counter.textContent = count === 1 ? '1 assignment' : `${count} assignments`;
                }
                
                // Mettre à jour les boutons de suppression
                removeButtons.forEach((button, index) => {
                    if (count === 1) {
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                        button.classList.remove('hover:bg-red-700');
                    } else {
                        button.disabled = false;
                        button.classList.remove('opacity-50', 'cursor-not-allowed');
                        button.classList.add('hover:bg-red-700');
                    }
                });
                
                // Mettre à jour le bouton d'ajout
                if (addBtn) {
                    if (count === 1) {
                        addBtn.innerHTML = '<i class="fas fa-plus mr-1"></i> Add Assignment';
                    } else {
                        addBtn.innerHTML = '<i class="fas fa-plus mr-1"></i> Add Another Assignment';
                    }
                }
            }
            
            // Gérer la soumission du formulaire
            function handleFormSubmit(event) {
                const assignments = document.querySelectorAll('.assignment-entry');
                let isValid = true;
                let errorMessage = '';
                
                assignments.forEach((assignment, index) => {
                    const school = assignment.querySelector('select[name="schools[]"]');
                    const schoolHidden = assignment.querySelector('input[name="schools[]"]');
                    const subject = assignment.querySelector('select[name="subjects[]"]');
                    const classRoom = assignment.querySelector('select[name="class_rooms[]"]');
                    const year = assignment.querySelector('select[name="years[]"]');
                    
                    // Vérifier l'école (select ou input hidden pour school_admin)
                    const schoolValue = school ? school.value : (schoolHidden ? schoolHidden.value : '');
                    
                    if (!schoolValue || !subject.value || !classRoom.value || !year.value) {
                        isValid = false;
                        assignment.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                        errorMessage = 'Please fill out all required fields for each assignment.';
                    } else {
                        assignment.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                    }
                });
                
                if (!isValid) {
                    event.preventDefault();
                    showNotification(errorMessage, 'error');
                    return false;
                }
                
                return true;
            }
            
            // Gérer la preview d'image
            function handleImagePreview(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('image-preview');
                
                if (!preview) return;
                
                if (file) {
                    // Vérifier la taille (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        showNotification('The file size must be less than 5MB', 'error');
                        event.target.value = '';
                        return;
                    }
                    
                    // Vérifier le type
                    if (!file.type.startsWith('image/')) {
                        showNotification('Please select an image file', 'error');
                        event.target.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.querySelector('img').src = e.target.result;
                        preview.classList.remove('hidden');
                        animateIn(preview);
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('hidden');
                }
            }
            
            // Afficher les notifications
            function showNotification(message, type = 'info') {
                // Supprimer les notifications existantes
                const existingNotifications = document.querySelectorAll('.assignment-notification');
                existingNotifications.forEach(notification => {
                    animateOut(notification, () => notification.remove());
                });
                
                const notification = document.createElement('div');
                notification.className = 'assignment-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-xl transition-all duration-300 transform translate-x-full max-w-sm';
                
                let bgColor, textColor, icon;
                switch(type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        textColor = 'text-white';
                        icon = 'fas fa-check-circle';
                        break;
                    case 'error':
                        bgColor = 'bg-red-500';
                        textColor = 'text-white';
                        icon = 'fas fa-exclamation-circle';
                        break;
                    default:
                        bgColor = 'bg-blue-500';
                        textColor = 'text-white';
                        icon = 'fas fa-info-circle';
                }
                
                notification.className += ` ${bgColor} ${textColor}`;
                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <i class="${icon} text-lg"></i>
                        <span class="flex-1">${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="hover:opacity-75 transition-opacity">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Animation d'entrée
                requestAnimationFrame(() => {
                    notification.classList.remove('translate-x-full');
                });
                
                // Auto-suppression après 4 secondes
                setTimeout(() => {
                    if (notification.parentElement) {
                        animateOut(notification, () => notification.remove());
                    }
                }, 4000);
            }
            
            // Helper to filter subjects by school
            function filterSubjectsBySchool(subjectSelect, schoolId) {
                Array.from(subjectSelect.options).forEach(option => {
                    option.style.display = (option.getAttribute('data-school') == schoolId) ? '' : 'none';
                });
            }
            
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
            
            // Initialisation
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
            
        })();
</script>
</x-app-layout>
