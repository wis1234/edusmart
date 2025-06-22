<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-book text-white text-2xl"></i>
                </span>
            <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Subject Details</h1>
                    <p class="text-gray-500 dark:text-gray-300">
                        @if(auth()->user()->role === 'enseignant')
                            View subject information (Read-only access)
                        @else
                            View subject information
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                @if(auth()->user()->role !== 'enseignant')
                    <a href="{{ route('subjects.edit', $subject) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
                <a href="{{ route('subjects.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to Subjects
                </a>
            </div>
            </div>

        <!-- Subject Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <!-- Subject Header -->
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-book text-white text-3xl"></i>
                </span>
            <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $subject->name }}</h2>
                    @if($subject->code)
                        <p class="text-gray-500 dark:text-gray-400">Code: {{ $subject->code }}</p>
                    @endif
                </div>
                <div class="ml-auto">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $subject->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Subject Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Academic Level -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-graduation-cap text-indigo-600 dark:text-indigo-400"></i>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Academic Level</label>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $subject->level ? ucfirst($subject->level) : 'Not specified' }}
                    </p>
                </div>

                <!-- Credits -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-star text-indigo-600 dark:text-indigo-400"></i>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Credits</label>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $subject->credits ?? 'Not specified' }}
                    </p>
                </div>

                <!-- Hours per Week -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-clock text-indigo-600 dark:text-indigo-400"></i>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Hours per Week</label>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $subject->hours_per_week ?? 'Not specified' }}
                    </p>
                </div>

                <!-- School -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-school text-indigo-600 dark:text-indigo-400"></i>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">School</label>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $subject->school ? $subject->school->name : 'Not specified' }}
                    </p>
                </div>

                <!-- Created By -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-user text-indigo-600 dark:text-indigo-400"></i>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Created By</label>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $subject->user ? $subject->user->first_name . ' ' . $subject->user->last_name : 'Not specified' }}
                    </p>
                </div>

                <!-- Created Date -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-calendar text-indigo-600 dark:text-indigo-400"></i>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Created</label>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $subject->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>

            <!-- Description -->
            @if($subject->description)
                <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-400"></i>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Description</label>
                    </div>
                    <p class="text-gray-900 dark:text-white leading-relaxed">{{ $subject->description }}</p>
        </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex gap-3">
                    @if(auth()->user()->role !== 'enseignant')
                        <a href="{{ route('subjects.edit', $subject) }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                            <i class="fas fa-edit"></i> Edit Subject
                        </a>
                        <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline" 
                            onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                                <i class="fas fa-trash"></i> Delete Subject
                            </button>
                        </form>
                    @endif
                </div>
                <a href="{{ route('subjects.index') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
