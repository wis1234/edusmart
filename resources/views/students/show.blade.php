@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">← Back to Students</a>
        <h1 class="text-2xl font-bold">Student Details</h1>
        <div class="space-x-2">
            @can('update', $student)
            <a href="{{ route('students.edit', $student) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit Student</a>
            @endcan
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
                <p>
                    @if($student->school)
                        <a href="{{ route('schools.show', $student->school) }}" class="text-blue-600 hover:underline">
                            {{ $student->school->name }}
                        </a>
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div>
                <label class="font-semibold">Class Room:</label>
                <p>
                    @if($student->classRoom)
                        <a href="{{ route('class_rooms.show', $student->classRoom) }}" class="text-blue-600 hover:underline">
                            {{ $student->classRoom->name }}
                        </a>
                    @else
                        N/A
                    @endif
                </p>
            </div>
<div>
    <label class="font-semibold">Parent Name:</label>
    <p>
        {{ $users->firstWhere('id', $student->selected_parent_id)?->first_name }} 
        {{ $users->firstWhere('id', $student->selected_parent_id)?->last_name ?? 'N/A' }}
    </p>
</div>

<div>
    <label class="font-semibold">Parent Alert email:</label>
    <p>
        {{ $users->firstWhere('id', $student->selected_parent_id)?->email }} 
    </p>
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

{{-- Evaluations and Grades Table --}}
<div class="container mx-auto px-4 mt-8">
    <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Evaluations and Grades</h2>
    @if($student->grades->isEmpty())
        <p>No evaluations or grades found for this student.</p>
    @else
        <div class="overflow-y-auto max-h-96 border border-gray-300 rounded">
            <table class="min-w-full bg-white border-collapse border border-gray-200">
                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-300 text-left">Evaluation Subject</th>
                        <th class="py-2 px-4 border-b border-gray-300 text-left">Evaluation Type</th>
                        <th class="py-2 px-4 border-b border-gray-300 text-left">Marks Obtained</th>
                        <th class="py-2 px-4 border-b border-gray-300 text-left">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->grades as $grade)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-300">{{ $grade->evaluation->subject->name ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b border-gray-300">{{ $grade->evaluation->evaluationType->name ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b border-gray-300">{{ $grade->marks_obtained }}</td>
                        <td class="py-2 px-4 border-b border-gray-300">{{ $grade->remarks ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
