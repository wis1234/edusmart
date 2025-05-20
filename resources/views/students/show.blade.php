@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Student Details</h1>
        <div class="space-x-2">
            @can('update', $student)
            <a href="{{ route('students.edit', $student) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit Student</a>
            @endcan
            <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Students</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Basic Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            @if($student->profile_photo)
            <div class="mb-4 md:col-span-2">
                <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="Profile Photo" 
                     class="w-32 h-32 object-cover rounded-full border-4 border-blue-200">
            </div>
            @endif
            <div>
                <label class="font-semibold">Name:</label>
                <p>{{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
            <div>
                <label class="font-semibold">Date of Birth:</label>
                <p>{{ $student->date_of_birth?->format('M d, Y') }}</p>
            </div>
            <div>
                <label class="font-semibold">Gender:</label>
                <p class="capitalize">{{ $student->gender }}</p>
            </div>
            <div>
                <label class="font-semibold">Admission Number:</label>
                <p>{{ $student->admission_number }}</p>
            </div>
            <div>
                <label class="font-semibold">Roll Number:</label>
                <p>{{ $student->roll_number ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="font-semibold">Admission Date:</label>
                <p>{{ $student->admission_date?->format('M d, Y') }}</p>
            </div>
            <div>
                <label class="font-semibold">Academic Year:</label>
                <p>{{ $student->academic_year }}</p>
            </div>
            <div>
                <label class="font-semibold">Status:</label>
                <span class="px-2 py-1 text-sm rounded-full {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($student->status) }}
                </span>
            </div>
            <div>
                <label class="font-semibold">School:</label>
                <p>{{ $student->school?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="font-semibold">Class Room:</label>
                <p>{{ $student->classRoom?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="font-semibold">Parent:</label>
                <p>{{ $student->parent?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="font-semibold">Address:</label>
                <p>{{ $student->address ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="font-semibold">Emergency Contact:</label>
                <p>{{ $student->emergency_contact ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="font-semibold">Medical Conditions:</label>
                <p>{{ $student->medical_conditions ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
