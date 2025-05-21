@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mb-4 inline-block">← Back to Dashboard</a>
    <h1 class="text-3xl font-bold text-center mb-8">Add New Parent</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('parents.store') }}" method="POST" class="bg-white max-w-5xl mx-auto p-8 rounded-xl shadow-xl">
        @csrf

        {{-- Personal Info --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">👤 Personal Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label for="first_name" class="block font-medium mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-user"></i></span>
                </div>

                <div class="relative">
                    <label for="last_name" class="block font-medium mb-1">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-user-tag"></i></span>
                </div>

                <div class="relative">
                    <label for="profession" class="block font-medium mb-1">Profession</label>
                    <input type="text" name="profession" id="profession" value="{{ old('profession') }}" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-briefcase"></i></span>
                </div>

                <div class="relative">
                    <label for="address" class="block font-medium mb-1">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-map-marker-alt"></i></span>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">📞 Contact Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label for="phone" class="block font-medium mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-phone"></i></span>
                </div>

                <div class="relative">
                    <label for="email" class="block font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-envelope"></i></span>
                </div>
            </div>
        </div>

        {{-- Security Info --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">🔒 Security</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label for="password" class="block font-medium mb-1">Password</label>
                    <input type="password" name="password" id="password" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-lock"></i></span>
                </div>

                <div class="relative">
                    <label for="password_confirmation" class="block font-medium mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <span class="absolute left-3 top-9 text-gray-400"><i class="fas fa-lock"></i></span>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300">
                ➕ Add Parent
            </button>
        </div>
    </form>
</div>
@endsection
