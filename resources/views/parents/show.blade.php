<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                @if($parent->profile_photo)
                    <img src="{{ asset('storage/' . $parent->profile_photo) }}" alt="Profile Photo" class="w-16 h-16 object-cover rounded-lg border-4 border-indigo-500 shadow-lg">
                @else
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </span>
                @endif
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $parent->first_name }} {{ $parent->last_name }}</h1>
                    <p class="text-gray-500 dark:text-gray-300">{{ $parent->profession ?? 'No profession specified' }} - Active</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('update', $parent)
                <a href="{{ route('parents.edit', $parent) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
                <a href="{{ route('parents.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Infos principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-info-circle text-indigo-500"></i> Basic Information</h2>
                <div class="mb-2"><span class="text-gray-500">Full Name:</span> <span class="font-semibold">{{ $parent->first_name }} {{ $parent->last_name }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Email:</span> <span class="font-semibold">{{ $parent->email }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Phone:</span> <span>{{ $parent->phone ?? 'N/A' }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Profession:</span> <span>{{ $parent->profession ?? 'N/A' }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Status:</span> <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">Active</span></div>
            </div>
            <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-address-book text-indigo-500"></i> Contact Information</h2>
                <div class="mb-2"><span class="text-gray-500">Email:</span> <span class="font-semibold">{{ $parent->email }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Phone:</span> <span>{{ $parent->phone ?? 'N/A' }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Address:</span> <span>{{ $parent->address ?? 'N/A' }}</span></div>
            </div>
            <!-- Account Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-user-shield text-indigo-500"></i> Account Information</h2>
                <div class="mb-2"><span class="text-gray-500">Role:</span> <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">Parent</span></div>
                <div class="mb-2"><span class="text-gray-500">Created:</span> <span>{{ $parent->created_at?->format('M d, Y') }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Last Updated:</span> <span>{{ $parent->updated_at?->format('M d, Y') }}</span></div>
            </div>
        </div>

        <!-- Students Associated -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-user-graduate text-indigo-500"></i> Associated Students</h2>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $parent->students->count() }} Students</span>
            </div>
            @if($parent->students->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-user-graduate text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-400 dark:text-gray-500 text-lg font-medium">No students associated</p>
                    <p class="text-gray-400 dark:text-gray-500">This parent doesn't have any students assigned yet.</p>
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($parent->students as $student)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            @if($student->profile_photo)
                                <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="Student Photo" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-600">
                            @else
                                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('students.show', $student) }}" class="block group">
                                <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </h3>
                            </a>
                            <div class="mt-2 space-y-1 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-id-card text-gray-400 w-4"></i>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $student->admission_number }}</span>
                                </div>
                                @if($student->classRoom)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-chalkboard text-gray-400 w-4"></i>
                                    <a href="{{ route('class_rooms.show', $student->classRoom) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    </a>
                                </div>
                                @endif
                                @if($student->school)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-school text-gray-400 w-4"></i>
                                    <a href="{{ route('schools.show', $student->school) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $student->school->name }}
                                    </a>
                                </div>
                                @endif
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-circle text-gray-400 w-4"></i>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $student->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex gap-2">
                            <a href="{{ route('students.show', $student) }}" class="flex-1 text-center px-3 py-1.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-md text-sm font-medium hover:bg-indigo-200 dark:hover:bg-indigo-800 transition-colors">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            @can('update', $student)
                            <a href="{{ route('students.edit', $student) }}" class="flex-1 text-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-md text-sm font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
