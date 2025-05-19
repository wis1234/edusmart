@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Calendars</h1>
    <a href="{{ route('calendars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New Calendar</a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Title</th>
                <th class="py-2 px-4 border-b">Description</th>
                <th class="py-2 px-4 border-b">Date</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($calendars as $calendar)
            <tr>
                <td class="py-2 px-4 border-b">{{ $calendar->title }}</td>
                <td class="py-2 px-4 border-b">{{ $calendar->description }}</td>
                <td class="py-2 px-4 border-b">{{ $calendar->date->format('Y-m-d') }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('calendars.show', $calendar) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    <a href="{{ route('calendars.edit', $calendar) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('calendars.destroy', $calendar) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this calendar?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-4 px-4 text-center">No calendars found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
