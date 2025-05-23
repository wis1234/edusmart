@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <h1 class="text-3xl font-bold mb-6 border-b pb-2">Subject Details</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Name</label>
                <p class="text-lg">{{ $subject->name }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Code</label>
                <p class="text-lg">{{ $subject->code ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Description</label>
                <p class="text-lg">{{ $subject->description ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Credits</label>
                <p class="text-lg">{{ $subject->credits ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Level</label>
                <p class="text-lg">{{ $subject->level ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Hours per Week</label>
                <p class="text-lg">{{ $subject->hours_per_week ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Active</label>
                <p class="text-lg">{{ $subject->is_active ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <a href="{{ route('subjects.edit', $subject) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded shadow">
                Edit
            </a>
            <form action="{{ route('subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow">
                    Delete
                </button>
            </form>
            <a href="{{ route('subjects.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow">
                Back to Subjects
            </a>
        </div>
    </div>
</div>
@endsection
