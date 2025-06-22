<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-book text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Add New Subject</h1>
                    <p class="text-gray-500 dark:text-gray-300">Create a new academic subject</p>
                </div>
            </div>
            <a href="{{ route('subjects.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i> Back to Subjects
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- School Information Display -->
            @if(auth()->user()->role === 'school_admin' && auth()->user()->school)
                <div class="mb-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3 flex items-center gap-2">
                        <i class="fas fa-school text-blue-500"></i> School Assignment
                    </h3>
                    <div class="text-sm">
                        <span class="text-blue-600 dark:text-blue-300">This subject will be created for:</span>
                        <span class="font-semibold text-blue-800 dark:text-blue-200 ml-2">
                            {{ auth()->user()->school->name }}
                        </span>
                        <div class="mt-1 text-blue-500 dark:text-blue-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            School-admin users can only create subjects for their assigned school.
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('subjects.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Subject Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="Enter subject name">
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Subject Code
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="e.g., MATH101">
                    </div>

                    <!-- Level -->
                    <div>
                        <label for="level" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Academic Level
                        </label>
                        <select name="level" id="level" 
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Select Level</option>
                            <option value="primary" {{ old('level') == 'primary' ? 'selected' : '' }}>Primary</option>
                            <option value="secondary" {{ old('level') == 'secondary' ? 'selected' : '' }}>Secondary</option>
                            <option value="high" {{ old('level') == 'high' ? 'selected' : '' }}>High School</option>
                            <option value="university" {{ old('level') == 'university' ? 'selected' : '' }}>University</option>
                        </select>
                    </div>

                    <!-- Credits -->
                    <div>
                        <label for="credits" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Credits
                        </label>
                        <input type="number" name="credits" id="credits" value="{{ old('credits') }}" min="0"
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="e.g., 3">
                    </div>

                    <!-- Hours per Week -->
                    <div>
                        <label for="hours_per_week" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Hours per Week
                        </label>
                        <input type="number" name="hours_per_week" id="hours_per_week" value="{{ old('hours_per_week') }}" min="0"
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="e.g., 4">
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_active" id="is_active" {{ old('is_active') ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <label for="is_active" class="ml-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Active Subject
                        </label>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition resize-none"
                        placeholder="Enter subject description...">{{ old('description') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('subjects.index') }}" 
                        class="px-6 py-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-plus"></i> Create Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
