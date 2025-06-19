<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header modernisé -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Profile</h1>
                        <p class="text-gray-500 dark:text-gray-300">Manage your personal information and settings</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Profile Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col items-center">
                    <div class="mb-6">
                        <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : asset('images/default-profile.png') }}" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 dark:border-indigo-600 shadow">
                    </div>
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="w-full space-y-6">
                        @csrf
                        @method('patch')
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Change Photo</label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Profession</label>
                            <input type="text" name="profession" id="profession" value="{{ old('profession', auth()->user()->profession) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('profession')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Password & Delete -->
                <div class="flex flex-col gap-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Update Password</h2>
                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">New Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">Update Password</button>
                            </div>
                        </form>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Delete Account</h2>
                        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                            @csrf
                            @method('delete')
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
