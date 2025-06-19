<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-user-graduate text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Add New Student</h1>
                    <p class="text-gray-500 dark:text-gray-300">Register a new student in the system</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('students.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 flex items-center gap-2">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 shadow-md rounded-xl px-8 pt-6 pb-8 mb-8">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required maxlength="255"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror">
                    @error('first_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required maxlength="255"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('last_name') border-red-500 @enderror">
                    @error('last_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Name (optional, for compatibility) -->
                <!-- <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div> -->
                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('date_of_birth') border-red-500 @enderror">
                    @error('date_of_birth')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" id="gender" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('gender') border-red-500 @enderror">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Blood Group -->
                <div>
                    <label for="blood_group" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Blood Group</label>
                    <input type="text" name="blood_group" id="blood_group" value="{{ old('blood_group') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('blood_group') border-red-500 @enderror">
                    @error('blood_group')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- School -->
                <div>
                    <label for="school_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">School <span class="text-red-500">*</span></label>
                    <select name="school_id" id="school_id" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('school_id') border-red-500 @enderror">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Class Room -->
                <div>
                    <label for="class_room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Class Room</label>
                    <select name="class_room_id" id="class_room_id" data-current="{{ old('class_room_id', '') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('class_room_id') border-red-500 @enderror">
                        <option value="">Select Class Room</option>
                        @foreach($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" data-school="{{ $classRoom->school_id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>{{ $classRoom->name }} ({{ $classRoom->grade_level ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('class_room_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Admission Date -->
                <div>
                    <label for="admission_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Admission Date <span class="text-red-500">*</span></label>
                    <input type="date" name="admission_date" id="admission_date" value="{{ old('admission_date') }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('admission_date') border-red-500 @enderror">
                    @error('admission_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Parent (selected_parent_id) -->
                <div>
                    <label for="selected_parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Parent</label>
                    <select name="selected_parent_id" id="selected_parent_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('selected_parent_id') border-red-500 @enderror">
                        <option value="">Select Parent</option>
                        @foreach($users as $parent)
                            @if($parent->role === 'parent')
                                <option value="{{ $parent->id }}" {{ old('selected_parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->first_name }} {{ $parent->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('selected_parent_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Parent Email -->
                <!-- <div>
                    <label for="parent_email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Parent Email</label>
                    <input type="email" name="parent_email" id="parent_email" value="{{ old('parent_email') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('parent_email') border-red-500 @enderror">
                    @error('parent_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div> -->
                <!-- Parent ID (hidden, for compatibility) -->
                <input type="hidden" name="parent_id" value="{{ old('parent_id') }}">
                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address</label>
                    <textarea name="address" id="address" rows="2"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Emergency Contact -->
                <div>
                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Emergency Contact</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('emergency_contact') border-red-500 @enderror">
                    @error('emergency_contact')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Medical Conditions -->
                <div>
                    <label for="medical_conditions" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Medical Conditions</label>
                    <textarea name="medical_conditions" id="medical_conditions" rows="2"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('medical_conditions') border-red-500 @enderror">{{ old('medical_conditions') }}</textarea>
                    @error('medical_conditions')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Academic Year (select) -->
                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Academic Year <span class="text-red-500">*</span></label>
                    <select name="academic_year" id="academic_year" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('academic_year') border-red-500 @enderror">
                        <option value="">Select Academic Year</option>
                        @php
                            $startYear = date('Y') - 5;
                            $endYear = date('Y') + 5;
                        @endphp
                        @for ($year = $startYear; $year <= $endYear; $year++)
                            @php $academic = $year . '-' . ($year + 1); @endphp
                            <option value="{{ $academic }}" {{ old('academic_year') == $academic ? 'selected' : '' }}>{{ $academic }}</option>
                        @endfor
                    </select>
                    @error('academic_year')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                    </select>
                    @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <!-- Profile Photo -->
                <div>
                    <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Profile Photo</label>
                    <input type="file" name="profile_photo" id="profile_photo"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('profile_photo') border-red-500 @enderror">
                    @error('profile_photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex items-center justify-between mt-8">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-plus"></i> Create Student
                </button>
                <p class="text-sm text-gray-600 dark:text-gray-400">* Required fields</p>
            </div>
        </form>
    </div>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const schoolSelect = document.getElementById('school_id');
    const classRoomSelect = document.getElementById('class_room_id');
    const allOptions = Array.from(classRoomSelect.options);

    function filterClassRooms() {
        const schoolId = schoolSelect.value;
        const currentValue = classRoomSelect.getAttribute('data-current') || '';
        classRoomSelect.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Class Room';
        classRoomSelect.appendChild(defaultOption);
        allOptions.forEach(option => {
            if (!option.value || !option.dataset.school || option.dataset.school === schoolId) {
                const opt = option.cloneNode(true);
                if (opt.value && (opt.value === currentValue)) {
                    opt.selected = true;
                }
                classRoomSelect.appendChild(opt);
            }
        });
    }

    // Pour l'édition, stocker la valeur actuelle
    if (classRoomSelect.value) {
        classRoomSelect.setAttribute('data-current', classRoomSelect.value);
    }

    schoolSelect.addEventListener('change', function() {
        classRoomSelect.setAttribute('data-current', '');
        filterClassRooms();
    });

    filterClassRooms();
});
</script>
@endpush
