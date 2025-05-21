@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Teachers</h1>
    @can('create', App\Models\User::class)
    <a href="{{ route('teachers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New Teacher</a>
    @endcan

    @can('create', App\Models\Evaluation::class)
    <a href="{{ route('evaluations.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Evaluation</a>
    <a href="{{ route('evaluations.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block ml-2">View Evaluations</a>
    @endcan

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Phone</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
            <tr>
                <td class="py-2 px-4 border-b">{{ $teacher->name }}</td>
                <td class="py-2 px-4 border-b">{{ $teacher->email }}</td>
                <td class="py-2 px-4 border-b">{{ $teacher->phone }}</td>
                <td class="py-2 px-4 border-b capitalize">{{ $teacher->status }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('teachers.show', $teacher) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    @can('update', $teacher)
                    <a href="{{ route('teachers.edit', $teacher) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    @endcan
                    @can('delete', $teacher)
                    <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center">No teachers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
