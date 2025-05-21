@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Parent Details</h1>
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <div class="space-x-2">
            <a href="{{ route('parents.edit', $parent) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit Parent</a>
            <a href="{{ route('parents.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Parents</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Basic Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="font-semibold">Name:</label>
                    <p>{{ $parent->first_name }} {{ $parent->last_name }}</p>
                </div>
                <div>
                    <label class="font-semibold">Email:</label>
                    <p>{{ $parent->email }}</p>
                </div>
                <div>
                    <label class="font-semibold">Phone:</label>
                    <p>{{ $parent->phone }}</p>
                </div>
                <div>
                    <label class="font-semibold">Profession:</label>
                    <p>{{ $parent->profession }}</p>
                </div>
                <div>
                    <label class="font-semibold">Status:</label>
                    <span class="px-2 py-1 text-sm rounded-full {{ $parent->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($parent->status) }}
                    </span>
                </div>
                <div>
                    <label class="font-semibold">Address:</label>
                    <p>{{ $parent->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
