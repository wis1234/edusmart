<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 sm:p-8">
                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff' }}" 
                             alt="{{ $user->name }}" 
                             class="w-16 h-16 rounded-full object-cover border-4 border-indigo-200 dark:border-indigo-800 shadow-lg"
                        />
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                            <p class="text-indigo-600 dark:text-indigo-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('users.edit', $user->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        <i class="fas fa-user-edit mr-2"></i>Edit
                    </a>
                </div>

                <!-- User Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 dark:text-gray-300">
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <strong class="block text-sm font-medium text-gray-500 dark:text-gray-400">First Name</strong>
                        <p>{{ $user->first_name }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <strong class="block text-sm font-medium text-gray-500 dark:text-gray-400">Last Name</strong>
                        <p>{{ $user->last_name }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <strong class="block text-sm font-medium text-gray-500 dark:text-gray-400">Role</strong>
                        <p class="px-2 py-1 inline-block rounded text-xs font-semibold
                            @switch($user->role)
                                @case('admin') bg-red-100 text-red-800 @break
                                @case('school_admin') bg-purple-100 text-purple-800 @break
                                @case('teacher') bg-blue-100 text-blue-800 @break
                                @case('parent') bg-yellow-100 text-yellow-800 @break
                                @case('student') bg-green-100 text-green-800 @break
                            @endswitch
                        ">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <strong class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</strong>
                        <p>
                            @if($user->status === 'active')
                                <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Active</span>
                            @else
                                <span class="px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-semibold">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <strong class="block text-sm font-medium text-gray-500 dark:text-gray-400">Validated</strong>
                        <p>
                            @if($user->validated)
                                <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Yes</span>
                            @else
                                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs font-semibold">No</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <strong class="block text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</strong>
                        <p>{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

            </div>
        </div>
        <div class="mt-6 text-center">
             <a href="{{ route('users.index') }}" class="text-indigo-600 hover:underline">← Back to Users List</a>
        </div>
    </div>
</x-app-layout> 