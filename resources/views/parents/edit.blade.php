@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Edit Parent</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('parents.update', $parent) }}" method="POST" class="max-w-lg">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="first_name" class="block font-semibold mb-1">First Name*</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $parent->first_name) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label for="last_name" class="block font-semibold mb-1">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $parent->last_name) }}" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="phone" class="block font-semibold mb-1">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $parent->phone) }}" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="profession" class="block font-semibold mb-1">Profession</label>
                <input type="text" name="profession" id="profession" value="{{ old('profession', $parent->profession) }}" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4 col-span-2">
                <label for="address" class="block font-semibold mb-1">Address</label>
                <textarea name="address" id="address" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('address', $parent->address) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $parent->email) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block font-semibold mb-1">Password (leave blank to keep current)</label>
                <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block font-semibold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>

        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Update Parent</button>
    </form>
</div>
@endsection
