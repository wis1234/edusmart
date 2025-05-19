@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Calendar Details</h1>

    <div class="mb-4">
        <strong>Title:</strong> {{ $calendar->title }}
    </div>

    <div class="mb-4">
        <strong>Description:</strong> {{ $calendar->description }}
    </div>

    <div class="mb-4">
        <strong>Date:</strong> {{ $calendar->date->format('Y-m-d') }}
    </div>

    <a href="{{ route('calendars.edit', $calendar) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
    <a href="{{ route('calendars.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
</div>
@endsection
