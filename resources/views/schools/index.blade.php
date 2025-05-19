@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Schools</h1>
    <a href="{{ route('schools.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New School</a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Address</th>
                <th class="py-2 px-4 border-b">Phone</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schools as $school)
            <tr>
                <td class="py-2 px-4 border-b">{{ $school->name }}</td>
                <td class="py-2 px-4 border-b">{{ $school->address }}</td>
                <td class="py-2 px-4 border-b">{{ $school->phone }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('schools.show', $school) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    <a href="{{ route('schools.edit', $school) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this school?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-4 px-4 text-center">No schools found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
