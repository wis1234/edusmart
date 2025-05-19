@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Class Rooms</h1>
    <a href="{{ route('class_rooms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New Class Room</a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Grade</th>
                <th class="py-2 px-4 border-b">Teacher</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($class_rooms as $class_room)
            <tr>
                <td class="py-2 px-4 border-b">{{ $class_room->name }}</td>
                <td class="py-2 px-4 border-b">{{ $class_room->grade }}</td>
                <td class="py-2 px-4 border-b">{{ $class_room->teacher->name ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('class_rooms.show', $class_room) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    <a href="{{ route('class_rooms.edit', $class_room) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('class_rooms.destroy', $class_room) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this class room?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-4 px-4 text-center">No class rooms found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
