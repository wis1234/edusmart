@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Edit Teacher</h1>
        <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Teachers</a>
    </div>

    @foreach (['success' => 'green', 'error' => 'red'] as $msg => $color)
        @if (session($msg))
            <div class="bg-{{ $color }}-100 border border-{{ $color }}-400 text-{{ $color }}-700 px-4 py-3 rounded mb-4">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

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
            {{-- Basic Info --}}
            <div class="col-span-2">
                <h2 class="text-xl font-semibold mb-3 border-b pb-2">Basic Information</h2>
            </div>

<div class="mb-4">
    <label for="first_name" class="block font-semibold mb-1">First Name*</label>
    <input type="text" name="first_name" id="first_name" 
        value="{{ old('first_name', $teacher->teacher_firstname) }}"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
</div>

<div class="mb-4">
    <label for="last_name" class="block font-semibold mb-1">Last Name</label>
    <input type="text" name="last_name" id="last_name" 
        value="{{ old('last_name', $teacher->teacher_lastname) }}"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
</div>

            {{-- teacher_email --}}
            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">Email Address*</label>
                <input type="email" name="email" id="email" value="{{ old('teacher_email', $teacher->teacher_email) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            {{-- teacher_phone --}}
            <div class="mb-4">
                <label for="phone" class="block font-semibold mb-1">Phone Number*</label>
                <input type="tel" name="phone" id="phone" value="{{ old('teacher_phone', $teacher->teacher_phone) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            {{-- date_of_birth --}}
            <div class="mb-4">
                <label for="date_of_birth" class="block font-semibold mb-1">Date of Birth*</label>
                <input type="date" name="date_of_birth" id="date_of_birth" 
                    value="{{ old('date_of_birth', optional($teacher->date_of_birth)->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            {{-- gender --}}
            <div class="mb-4">
                <label for="gender" class="block font-semibold mb-1">Gender*</label>
                <select name="gender" id="gender" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    <option value="">Select Gender</option>
                    @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                        <option value="{{ $val }}" {{ old('gender', $teacher->gender) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- address --}}
            <div class="col-span-2 mb-4">
                <label for="address" class="block font-semibold mb-1">Address*</label>
                <textarea name="address" id="address" rows="3" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">{{ old('address', $teacher->address) }}</textarea>
            </div>

            {{-- grade --}}
            <div class="mb-4">
                <label for="grade" class="block font-semibold mb-1">Grade*</label>
                <input type="text" name="grade" id="grade" value="{{ old('grade', $teacher->grade) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            {{-- speciality --}}
            <div class="mb-4">
                <label for="speciality" class="block font-semibold mb-1">Speciality*</label>
                <input type="text" name="speciality" id="speciality" value="{{ old('speciality', $teacher->speciality) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            {{-- subject_title --}}
            <div class="mb-4">
                <label for="subject_title" class="block font-semibold mb-1">Subject Title</label>
                <input type="text" name="subject_title" id="subject_title" value="{{ old('subject_title', $teacher->subject_title) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            {{-- status --}}
            <div class="mb-4">
                <label for="status" class="block font-semibold mb-1">Status</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">Select Status</option>
                    @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $teacher->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- profile_photo --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Profile Photo</label>
                @if($teacher->profile_photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $teacher->profile_photo) }}" alt="Profile photo" class="w-32 h-32 rounded object-cover">
                    </div>
                @endif
                <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                    onchange="previewImage(this)">
                <div id="image-preview" class="mt-2 hidden">
                    <img src="" alt="Preview" class="w-32 h-32 rounded object-cover">
                </div>
            </div>

            {{-- Teaching Assignments --}}
            <div class="col-span-2">
                <h2 class="text-xl font-semibold mb-3 pb-2 border-b">Teaching Assignments</h2>
            </div>

            <div class="col-span-2">
                <div class="border rounded-lg p-4" id="assignments-container">
                    @forelse ($teacher->taughtSubjects as $subject)
                        @foreach($teacher->teachingClassRooms->where('pivot.subject_id', $subject->id) as $classRoom)
                            @include('teachers.partials.assignment', [
                                'subjectId' => $subject->id,
                                'classRoomId' => $classRoom->id,
                                'year' => $classRoom->pivot->year
                            ])
                        @endforeach
                    @empty
                        @include('teachers.partials.assignment')
                    @endforelse
                </div>
            </div>

        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded hover:bg-blue-700">
                Update Teacher
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const previewContainer = document.getElementById('image-preview');
        const previewImage = previewContainer.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            previewImage.src = '';
            previewContainer.classList.add('hidden');
        }
    }
</script>
@endsection
