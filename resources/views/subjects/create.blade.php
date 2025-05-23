@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

     {{-- Navigation --}}
    <div class="mb-6 flex items-center space-x-4">
        <a href="{{ route('subjects.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200">
            ← Back to Subjects
        </a>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold mb-6 border-b pb-2">Add New Subject</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-1">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label for="code" class="block text-gray-700 font-semibold mb-1">Code</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label for="description" class="block text-gray-700 font-semibold mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full border border-gray-300 rounded px-3 py-2">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="credits" class="block text-gray-700 font-semibold mb-1">Credits</label>
                    <input type="number" name="credits" id="credits" value="{{ old('credits') }}" min="0"
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label for="level" class="block text-gray-700 font-semibold mb-1">Level</label>
                    <input type="text" name="level" id="level" value="{{ old('level') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label for="hours_per_week" class="block text-gray-700 font-semibold mb-1">Hours per Week</label>
                    <input type="number" name="hours_per_week" id="hours_per_week" value="{{ old('hours_per_week') }}" min="0"
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_active" id="is_active" {{ old('is_active') ? 'checked' : '' }}
                        class="mr-2">
                    <label for="is_active" class="font-semibold">Active</label>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                    Create Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
