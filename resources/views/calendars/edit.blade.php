@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Edit Calendar</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('calendars.update', $calendar) }}" method="POST" class="max-w-lg">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $calendar->title) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-semibold mb-1">Description</label>
            <textarea name="description" id="description" class="w-full border border-gray-300 rounded px-3 py-2" required>{{ old('description', $calendar->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="date" class="block font-semibold mb-1">Date</label>
            <input type="date" name="date" id="date" value="{{ old('date', $calendar->date->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Update Calendar</button>
    </form>
</div>
@endsection
