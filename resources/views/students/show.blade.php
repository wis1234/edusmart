@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Student Details</h1>

    <div class="mb-4">
        <strong>First Name:</strong> {{ $student->first_name }}
    </div>

    <div class="mb-4">
        <strong>Last Name:</strong> {{ $student->last_name }}
    </div>

    <div class="mb-4">
        <strong>Birth Date:</strong> {{ $student->birth_date->format('Y-m-d') }}
    </div>

    <div class="mb-4">
        <strong>Gender:</strong> {{ $student->gender }}
    </div>

    <div class="mb-4">
        <strong>Class Room:</strong> {{ $student->classRoom->name ?? 'N/A' }} ({{ $student->classRoom->level ?? 'N/A' }})
    </div>

    <div class="mb-4">
        <strong>Parent:</strong> {{ $student->parent->name ?? 'N/A' }}
    </div>

    <a href="{{ route('students.edit', $student) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
    <a href="{{ route('students.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
</div>
@endsection
