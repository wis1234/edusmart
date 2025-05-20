@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Edit Teacher</h1>
        <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Teachers</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Basic Information -->
            <div class="col-span-2">
                <h2 class="text-xl font-semibold mb-3 pb-2 border-b">Basic Information</h2>
            </div>

            <div class="mb-4">
                <label for="first_name" class="block font-semibold mb-1">First Name*</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $teacher->teacher_firstname) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="last_name" class="block font-semibold mb-1">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $teacher->teacher_lastname) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">Email Address*</label>
                <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block font-semibold mb-1">Password (leave blank to keep current)</label>
                <input type="password" name="password" id="password" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block font-semibold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <!-- Personal Information -->
            <div class="col-span-2">
                <h2 class="text-xl font-semibold mb-3 pb-2 border-b">Personal Information</h2>
            </div>

            <div class="mb-4">
                <label for="phone" class="block font-semibold mb-1">Phone Number*</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $teacher->phone) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="date_of_birth" class="block font-semibold mb-1">Date of Birth*</label>
                <input type="date" name="date_of_birth" id="date_of_birth" 
                    value="{{ old('date_of_birth', $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '') }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="gender" class="block font-semibold mb-1">Gender*</label>
                <select name="gender" id="gender" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="profile_photo" class="block font-semibold mb-1">Profile Photo</label>
                @if($teacher->profile_photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $teacher->profile_photo) }}" alt="Current profile photo" class="w-32 h-32 object-cover rounded">
                    </div>
                @endif
                <div>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" 
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                        onchange="previewImage(this)">
                    <div id="image-preview" class="mt-2 hidden">
                        <img src="" alt="Profile Preview" class="w-32 h-32 object-cover rounded">
                    </div>
                </div>
            </div>

            <div class="mb-4 col-span-2">
                <label for="address" class="block font-semibold mb-1">Address*</label>
                <textarea name="address" id="address" rows="3" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>{{ old('address', $teacher->address) }}</textarea>
            </div>

            <!-- Teaching Assignments -->
            <div class="col-span-2">
                <h2 class="text-xl font-semibold mb-3 pb-2 border-b">Teaching Assignments</h2>
            </div>

            <div class="col-span-2 mb-4">
                <div class="border rounded-lg p-4">
                    <div id="assignments-container">
                        @foreach($teacher->taughtSubjects as $subject)
                            @foreach($teacher->teachingClassRooms->where('pivot.subject_id', $subject->id) as $classRoom)
                                <div class="assignment-entry grid grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="block font-semibold mb-1">Subject*</label>
                                        <select name="subjects[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $s)
                                                <option value="{{ $s->id }}" {{ $subject->id == $s->id ? 'selected' : '' }}>
                                                    {{ $s->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-semibold mb-1">Class Room*</label>
                                        <select name="class_rooms[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                            <option value="">Select Class Room</option>
                                            @foreach($classRooms as $cr)
                                                <option value="{{ $cr->id }}" {{ $classRoom->id == $cr->id ? 'selected' : '' }}>
                                                    {{ $cr->name }} ({{ $cr->grade_level }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-semibold mb-1">Year*</label>
                                        <input type="number" name="years[]" class="w-full border border-gray-300 rounded px-3 py-2" 
                                            value="{{ $classRoom->pivot->year ?? date('Y') }}" min="2000" max="2100" required>
                                        @if(!$loop->first)
                                            <button type="button" class="mt-2 bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-2 rounded remove-assignment">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach

                        @if(!$teacher->taughtSubjects->count())
                            <div class="assignment-entry grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block font-semibold mb-1">Subject*</label>
                                    <select name="subjects[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1">Class Room*</label>
                                    <select name="class_rooms[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                        <option value="">Select Class Room</option>
                                        @foreach($classRooms as $classRoom)
                                            <option value="{{ $classRoom->id }}">{{ $classRoom->name }} ({{ $classRoom->grade_level }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1">Year*</label>
                                    <input type="number" name="years[]" class="w-full border border-gray-300 rounded px-3 py-2" 
                                        value="{{ date('Y') }}" min="2000" max="2100" required>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-assignment" 
                        class="mt-2 bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-2 rounded">
                        Add Another Assignment
                    </button>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-4 col-span-2">
                <label for="grade" class="block font-semibold mb-1">Grade*</label>
                <input type="text" name="grade" id="grade" value="{{ old('grade', $teacher->grade) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4 col-span-2">
                <label for="speciality" class="block font-semibold mb-1">Speciality*</label>
                <input type="text" name="speciality" id="speciality" value="{{ old('speciality', $teacher->speciality) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4 col-span-2">
                <label for="subject_title" class="block font-semibold mb-1">Subject Title*</label>
                <input type="text" name="subject_title" id="subject_title" value="{{ old('subject_title', $teacher->subject_title) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4 col-span-2">
                <label for="status" class="block font-semibold mb-1">Status*</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    <option value="active" {{ old('status', $teacher->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $teacher->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on_leave" {{ old('status', $teacher->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Teacher
            </button>
            <p class="text-sm text-gray-600">* Required fields</p>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = preview.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewImg.src = '';
            preview.classList.add('hidden');
        }
    }

    function createAssignmentEntry(subjects, classRooms) {
        const div = document.createElement('div');
        div.className = 'assignment-entry grid grid-cols-3 gap-4 mb-4';
        
        // Subject select
        const subjectDiv = document.createElement('div');
        subjectDiv.innerHTML = `
            <label class="block font-semibold mb-1">Subject*</label>
            <select name="subjects[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Select Subject</option>
                ${subjects.map(s => `<option value="${s.value}">${s.text}</option>`).join('')}
            </select>
        `;
        
        // Class room select
        const classRoomDiv = document.createElement('div');
        classRoomDiv.innerHTML = `
            <label class="block font-semibold mb-1">Class Room*</label>
            <select name="class_rooms[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Select Class Room</option>
                ${classRooms.map(cr => `<option value="${cr.value}">${cr.text}</option>`).join('')}
            </select>
        `;

        // Year input
        const yearDiv = document.createElement('div');
        yearDiv.innerHTML = `
            <label class="block font-semibold mb-1">Year*</label>
            <input type="number" name="years[]" class="w-full border border-gray-300 rounded px-3 py-2" 
                value="${new Date().getFullYear()}" min="2000" max="2100" required>
        `;
        
        // Remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'mt-2 bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-2 rounded';
        removeBtn.textContent = 'Remove';
        removeBtn.onclick = function() {
            div.remove();
        };
        yearDiv.appendChild(removeBtn);
        
        div.appendChild(subjectDiv);
        div.appendChild(classRoomDiv);
        div.appendChild(yearDiv);
        
        return div;
    }

    // Get subjects and classrooms data from the select elements
    const getOptionsData = (selector) => {
        return Array.from(document.querySelector(selector).options).map(opt => ({
            value: opt.value,
            text: opt.text
        })).filter(opt => opt.value !== ''); // Remove empty option
    };

    document.addEventListener('DOMContentLoaded', function() {
        const subjects = getOptionsData('select[name="subjects[]"]');
        const classRooms = getOptionsData('select[name="class_rooms[]"]');

        document.getElementById('add-assignment').addEventListener('click', function() {
            const container = document.getElementById('assignments-container');
            const newEntry = createAssignmentEntry(subjects, classRooms);
            container.appendChild(newEntry);
        });

        // Add click handlers for existing remove buttons
        document.querySelectorAll('.remove-assignment').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.assignment-entry').remove();
            });
        });
    });
</script>
@endpush
@endsection
