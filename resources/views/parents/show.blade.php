@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mb-4 inline-block">← Back to Dashboard</a>
    <h1 class="text-3xl font-extrabold text-gray-800 mb-8 border-b pb-3">👨‍👩‍👧‍👦 Parent Details</h1>

    <div class="flex justify-end mb-6 space-x-2">
        <a href="{{ route('parents.edit', $parent) }}" class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-5 py-2 rounded-lg shadow-md transition duration-200">
            ✏️ Edit
        </a>
        <a href="{{ route('parents.index') }}" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-medium px-5 py-2 rounded-lg shadow-md transition duration-200">
            🔙 Back
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Basic Information Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition duration-200">
            <h2 class="text-xl font-semibold text-gray-700 mb-5 border-b pb-2">📋 Basic Information</h2>
            <div class="space-y-4 text-gray-700">
                <div>
                    <label class="block text-sm font-bold">👤 Name:</label>
                    <p class="ml-2 text-base">{{ $parent->first_name }} {{ $parent->last_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold">📧 Email:</label>
                    <p class="ml-2 text-base">{{ $parent->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold">📞 Phone:</label>
                    <p class="ml-2 text-base">{{ $parent->phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold">💼 Profession:</label>
                    <p class="ml-2 text-base">{{ $parent->profession }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold">📌 Status:</label>
                    <span class="inline-block ml-2 px-3 py-1 text-sm font-semibold rounded-full {{ $parent->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($parent->status) }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-bold">🏠 Address:</label>
                    <p class="ml-2 text-base">{{ $parent->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
