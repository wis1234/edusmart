<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 sm:p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-user-edit text-white text-2xl"></i>
                    </span>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit User</h1>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select name="role" id="role" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                                <option value="school_admin" @if($user->role == 'school_admin') selected @endif>School Admin</option>
                                <option value="teacher" @if($user->role == 'teacher') selected @endif>Teacher</option>
                                <option value="parent" @if($user->role == 'parent') selected @endif>Parent</option>
                                <option value="student" @if($user->role == 'student') selected @endif>Student</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active" @if($user->status == 'active') selected @endif>Active</option>
                                <option value="inactive" @if($user->status == 'inactive') selected @endif>Inactive</option>
                            </select>
                        </div>

                        <!-- Validated -->
                        <div>
                            <label for="validated" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Validated</label>
                            <select name="validated" id="validated" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1" @if($user->validated) selected @endif>Yes</option>
                                <option value="0" @if(!$user->validated) selected @endif>No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 