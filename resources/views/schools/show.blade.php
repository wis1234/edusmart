@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">School Details</h1>

    <div class="mb-4">
        <strong>Name:</strong> {{ $school->name }}
    </div>

    <div class="mb-4">
        <strong>Address:</strong> {{ $school->address }}
    </div>

    <div class="mb-4">
        <strong>Phone:</strong> {{ $school->phone }}
    </div>

    <a href="{{ route('schools.edit', $school) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
    <a href="{{ route('schools.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
</div>
@endsection
