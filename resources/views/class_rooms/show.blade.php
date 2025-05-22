@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Classroom Details</h1>
        <div class="space-x-2">
            @can('update', $classRoom)
            <a href="{{ route('class_rooms.edit', $classRoom) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit Classroom</a>
            @endcan
            <a href="{{ route('class_rooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Classrooms</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Basic Information</h2>
            <div class="space-y-3">
                <p><strong>Name:</strong> {{ $classRoom->name }}</p>
                <p><strong>School:</strong> <a href="{{ route('schools.show', $classRoom->school) }}" class="text-blue-600 hover:underline">{{ $classRoom->school->name ?? 'N/A' }}</a></p>
                <p><strong>Grade Level:</strong> {{ $classRoom->grade_level }}</p>
                <p><strong>Section:</strong> {{ $classRoom->section }}</p>
                <p><strong>Academic Year:</strong> {{ $classRoom->academic_year }}</p>
                <p><strong>Capacity:</strong> {{ $classRoom->capacity }}</p>
                <p><strong>Room Number:</strong> {{ $classRoom->room_number }}</p>
                <p><strong>Building:</strong> {{ $classRoom->building }}</p>
                <p><strong>Floor:</strong> {{ $classRoom->floor }}</p>
                <p><strong>Active:</strong> {{ $classRoom->is_active ? 'Yes' : 'No' }}</p>
                <p><strong>Days of Week:</strong> {{ implode(', ', array_map('ucfirst', $classRoom->days_of_week ?? [])) }}</p>
                <p><strong>Start Time:</strong> {{ $classRoom->start_time ? $classRoom->start_time->format('H:i') : 'N/A' }}</p>
                <p><strong>End Time:</strong> {{ $classRoom->end_time ? $classRoom->end_time->format('H:i') : 'N/A' }}</p>
            </div>
        </div>

        <!-- Related Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Related Information</h2>
            <div class="space-y-3">
                <p><strong>Teachers:</strong></p>
                <ul class="list-disc list-inside">
                    @forelse($classRoom->classRoomTeachers as $assignment)
                        <li>
                            @if($assignment->teacher)
                                {{ $assignment->teacher->first_name ?? $assignment->teacher->teacher_firstname }} {{ $assignment->teacher->last_name ?? $assignment->teacher->teacher_lastname }}
                            @else
                                <em>Teacher data not available</em>
                            @endif
                            @if($assignment->subject)
                                - <em>{{ $assignment->subject->name }}</em>
                            @endif
                            <br>
                            <small>
                                Year: {{ $assignment->year }}<br>
                            </small>
                        </li>
                    @empty
                        <li>No teachers assigned.</li>
                    @endforelse
                </ul>

                <p><strong>Students:</strong></p>
                <ul class="list-disc list-inside">
                    @forelse($classRoom->students as $student)
                        <li>
                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:underline">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </a>
                        </li>
                    @empty
                        <li>No students enrolled.</li>
                    @endforelse
                </ul>

                <p><strong>Evaluations:</strong></p>
                <ul class="list-disc list-inside">
@forelse($classRoom->evaluations as $evaluation)
    <li>
        <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:underline">
            {{ $evaluation->term }} ({{ $evaluation->evaluation_date ? $evaluation->evaluation_date->format('Y-m-d') : 'N/A' }})
        </a>
    </li>
@empty
    <li>No evaluations scheduled.</li>
@endforelse
                </ul>
            </div>
        </div>

        <!-- User Tracking -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 pb-2 border-b">User Tracking</h2>
            <div class="space-y-3">
                <p><strong>Created By:</strong> {{ $classRoom->createdBy ? $classRoom->createdBy->first_name . ' ' . $classRoom->createdBy->last_name : 'N/A' }}</p>
                <p><strong>Updated By:</strong> {{ $classRoom->updatedBy ? $classRoom->updatedBy->first_name . ' ' . $classRoom->updatedBy->last_name : 'N/A' }}</p>
                <p><strong>Created At:</strong> {{ $classRoom->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $classRoom->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
