@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Classroom</h1>
        <a href="{{ route('class_rooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Classrooms</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('class_rooms.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Classroom Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder=' Ex: Maternelle 1'
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700">School</label>
                        <select name="school_id" id="school_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select School</option>
                            @foreach(\App\Models\School::orderBy('name')->get() as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

<div>
    <label for="grade_level" class="block text-sm font-medium text-gray-700">Grade Level</label>
    <select name="grade_level" id="grade_level" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Select Grade Level </option>
        <option value="Maternelle" {{ old('grade_level') == 'Maternelle' ? 'selected' : '' }}>Maternelle</option>
        <option value="Primaire" {{ old('grade_level') == 'Primaire' ? 'selected' : '' }}>Primaire</option>
        <option value="Secondaire" {{ old('grade_level') == 'Secondaire' ? 'selected' : '' }}>Secondaire</option>
        <option value="Lycée" {{ old('grade_level') == 'Lycée' ? 'selected' : '' }}>Lycée</option>
        <option value="Université" {{ old('grade_level') == 'Université' ? 'selected' : '' }}>Université</option>
    </select>
    
    @error('grade_level')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


<div>
    <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
    <select name="section" id="section" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value=""> Select Section</</option>
        <option value="Commun" {{ old('section') == 'A' ? 'selected' : '' }}>TC (Tronc Commun)</option>
        <option value="A" {{ old('section') == 'A' ? 'selected' : '' }}>Section A (Littéraire)</option>
        <option value="B" {{ old('section') == 'B' ? 'selected' : '' }}>Section B (Économique)</option>
        <option value="C" {{ old('section') == 'C' ? 'selected' : '' }}>Section C (Scientifique - Maths/Physique)</option>
        <option value="D" {{ old('section') == 'D' ? 'selected' : '' }}>Section D (Scientifique - Bio/Physique)</option>
        <option value="E" {{ old('section') == 'E' ? 'selected' : '' }}>Section E (Technique Industrielle)</option>
        <option value="F" {{ old('section') == 'F' ? 'selected' : '' }}>Section F (Technique Commerciale)</option>
        <option value="G" {{ old('section') == 'G' ? 'selected' : '' }}>Section G (Gestion)</option>
    </select>

    @error('section')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


@php
    $currentYear = date('Y');
    $startYear = $currentYear  - 2;
    $endYear = $currentYear + 8;
@endphp

<div>
    <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year</label>
    <select name="academic_year" id="academic_year" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value=""> Select Academic Year</option>
        @for ($year = $startYear; $year <= $endYear; $year++)
            @php $academic = $year . '-' . ($year + 1); @endphp
            <option value="{{ $academic }}" {{ old('academic_year') == $academic ? 'selected' : '' }}>
                {{ $academic }}
            </option>
        @endfor
    </select>

    @error('academic_year')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" required min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold mb-4">Schedule Information</h2>
                    
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="days_of_week" class="block text-sm font-medium text-gray-700">Days of Week</label>
                        <select name="days_of_week[]" id="days_of_week" multiple required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <option value="{{ $day }}" {{ (collect(old('days_of_week'))->contains($day)) ? 'selected' : '' }}>
                                    {{ ucfirst($day) }}
                                </option>
                            @endforeach
                        </select>
                        @error('days_of_week')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number</label>
                        <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('room_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="building" class="block text-sm font-medium text-gray-700">Building</label>
                        <input type="text" name="building" id="building" value="{{ old('building') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('building')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

 @php
    $floors = [
        
        0 => 'Rez-de-chaussée',
        1 => 'Batiment',
        2 => '1er étage',
        3 => '2e étage',        
        4 => '3e étage',
        5 => '4e étage',
        6 => '5e étage',
        7 => '6e étage',
    ];
@endphp

<div>
    <label for="floor" class="block text-sm font-medium text-gray-700">Floor</label>
    <select name="floor" id="floor" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Select a floor</option>
        @foreach($floors as $key => $label)
            <option value="{{ $key }}" {{ old('floor') == $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error('floor')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Active</label>
                        <select name="is_active" id="is_active" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('class_rooms.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Classroom
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
