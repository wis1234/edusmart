<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header modernized -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-green-500 to-blue-600 shadow-lg">
                        <i class="fas fa-video text-white text-2xl"></i>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">New Video Call</h1>
                        <p class="text-gray-500 dark:text-gray-300">Create a new video or audio call with participants</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('video-calls.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Calls
                    </a>
                </div>
            </div>

            <!-- Create Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form method="POST" action="{{ route('video-calls.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Call Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                Call Title
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                   placeholder="Enter call title (optional)">
                        </div>
                        
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                Call Type
                            </label>
                            <select name="type" id="type" required
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <option value="">Select call type</option>
                                <option value="video">Video Call</option>
                                <option value="audio">Audio Call</option>
                                <option value="both">Video & Audio</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                  placeholder="Enter call description (optional)"></textarea>
                    </div>

                    <!-- School Selection (Admin only) -->
                    @if(auth()->user()->isAdmin())
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                School
                            </label>
                            <select name="school_id" id="school_id"
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <option value="">Select school</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Participants Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Select Participants
                        </label>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-h-64 overflow-y-auto">
                            @if($availableUsers->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($availableUsers as $user)
                                        <label class="flex items-center p-3 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 cursor-pointer transition-colors">
                                            <input type="checkbox" name="participant_ids[]" value="{{ $user->id }}" 
                                                   class="rounded border-gray-300 dark:border-gray-500 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    @foreach($user->roles as $role)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                            @if($role->name == 'enseignant') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                            @elseif($role->name == 'parent') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                            {{ ucfirst($role->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-users text-gray-400 dark:text-gray-500 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No available participants</h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        There are no users available to invite to this call.
                                    </p>
                                </div>
                            @endif
                        </div>
                        @error('participant_ids')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('video-calls.index') }}" 
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-video mr-2"></i>
                            Create Call
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 