@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Add New Calendar</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('calendars.store') }}" method="POST" class="max-w-lg">
        @csrf

        <div class="mb-4">
            <label for="title" class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-semibold mb-1">Description</label>
            <textarea name="description" id="description" class="w-full border border-gray-300 rounded px-3 py-2" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="date" class="block font-semibold mb-1">Date</label>
            <input type="date" name="date" id="date" value="{{ old('date') }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Calendar</button>
    </form>
</div>
@endsection
