@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Students</h1>
    <a href="{{ route('students.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New Student</a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">First Name</th>
                <th class="py-2 px-4 border-b">Last Name</th>
                <th class="py-2 px-4 border-b">Birth Date</th>
                <th class="py-2 px-4 border-b">Gender</th>
                <th class="py-2 px-4 border-b">Class Room</th>
                <th class="py-2 px-4 border-b">Parent</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td class="py-2 px-4 border-b">{{ $student->first_name }}</td>
                <td class="py-2 px-4 border-b">{{ $student->last_name }}</td>
                <td class="py-2 px-4 border-b">{{ $student->date_of_birth->format('Y-m-d') }}</td>
                <td class="py-2 px-4 border-b">{{ $student->gender }}</td>
                <td class="py-2 px-4 border-b">{{ $student->classRoom->name ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">
                  {{ $users->firstWhere('id', $student->selected_parent_id)?->first_name }} 
                  {{ $users->firstWhere('id', $student->selected_parent_id)?->last_name ?? 'N/A' }}
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    <a href="{{ route('students.edit', $student) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-4 px-4 text-center">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
