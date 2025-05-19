@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Parent Details</h1>

    <div class="mb-4">
        <strong>Name:</strong> {{ $parent->name }}
    </div>

    <div class="mb-4">
        <strong>Email:</strong> {{ $parent->email }}
    </div>

    <a href="{{ route('parents.edit', $parent) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
    <a href="{{ route('parents.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
</div>
@endsection
