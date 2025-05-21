@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Edit Student</h1>
        <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Students</a>
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

    <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')

        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Basic Information -->
            <div class="col-span-2">
                <h2 class="text-xl font-semibold mb-3 pb-2 border-b">Basic Information</h2>
            </div>

            <div class="mb-4">
                <label for="first_name" class="block font-semibold mb-1">First Name*</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $student->first_name) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="last_name" class="block font-semibold mb-1">Last Name*</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $student->last_name) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="date_of_birth" class="block font-semibold mb-1">Date of Birth*</label>
                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="gender" class="block font-semibold mb-1">Gender*</label>
                <select name="gender" id="gender" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="school_id" class="block font-semibold mb-1">School*</label>
                <select name="school_id" id="school_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    <option value="">Select School</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', $student->school_id) == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="class_room_id" class="block font-semibold mb-1">Class Room</label>
                <select name="class_room_id" id="class_room_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">Select Class Room</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" {{ old('class_room_id', $student->class_room_id) == $classRoom->id ? 'selected' : '' }}>
                            {{ $classRoom->name }} ({{ $classRoom->level }})
                        </option>
                    @endforeach
                </select>
            </div>

<div class="mb-4">
    <label for="selected_parent_id" class="block font-semibold mb-1">Parent</label>
    <select name="selected_parent_id" id="selected_parent_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
        <option value="">Select Parent</option>
        @foreach($users as $parent)
            @if($parent->role === 'parent')
                <option value="{{ $parent->id }}" {{ old('selected_parent_id') == $parent->id ? 'selected' : '' }}>
                    {{ $parent->first_name }} {{ $parent->last_name }}
                </option>
            @endif
        @endforeach
    </select>
</div>

            <div class="mb-4">
                <label for="admission_number" class="block font-semibold mb-1">Admission Number*</label>
                <input type="text" name="admission_number" id="admission_number" value="{{ old('admission_number', $student->admission_number) }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="roll_number" class="block font-semibold mb-1">Roll Number</label>
                <input type="text" name="roll_number" id="roll_number" value="{{ old('roll_number', $student->roll_number) }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="admission_date" class="block font-semibold mb-1">Admission Date*</label>
                <input type="date" name="admission_date" id="admission_date" value="{{ old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '') }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="academic_year" class="block font-semibold mb-1">Academic Year*</label>
                <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', $student->academic_year) }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="status" class="block font-semibold mb-1">Status*</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="transferred" {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="profile_photo" class="block font-semibold mb-1">Profile Photo</label>
                @if($student->profile_photo_url)
                    <img src="{{ $student->profile_photo_url }}" alt="Profile Photo" class="mb-2 w-32 h-32 object-cover rounded-full">
                @endif
                <input type="file" name="profile_photo" id="profile_photo" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('profile_photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Student
            </button>
            <p class="text-sm text-gray-600">* Required fields</p>
        </div>
    </form>
</div>
@endsection
