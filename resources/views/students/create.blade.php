@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Add New Student</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.store') }}" method="POST" class="max-w-lg">
        @csrf

        <div class="mb-4">
            <label for="first_name" class="block font-semibold mb-1">First Name</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="last_name" class="block font-semibold mb-1">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="birth_date" class="block font-semibold mb-1">Birth Date</label>
            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="gender" class="block font-semibold mb-1">Gender</label>
            <select name="gender" id="gender" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Select Gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="class_room_id" class="block font-semibold mb-1">Class Room</label>
            <select name="class_room_id" id="class_room_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Select Class Room</option>
                @foreach($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                        {{ $classRoom->name }} ({{ $classRoom->level }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="parent_id" class="block font-semibold mb-1">Parent</label>
            <select name="parent_id" id="parent_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Select Parent</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Student</button>
    </form>
</div>
@endsection
