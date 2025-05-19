@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Class Room Details</h1>

    <div class="mb-4">
        <strong>Name:</strong> {{ $class_room->name }}
    </div>

    <div class="mb-4">
        <strong>Grade:</strong> {{ $class_room->grade }}
    </div>

    <div class="mb-4">
        <strong>Teacher:</strong> {{ $class_room->teacher->name ?? 'N/A' }}
    </div>

    <a href="{{ route('class_rooms.edit', $class_room) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
    <a href="{{ route('class_rooms.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
</div>
@endsection
