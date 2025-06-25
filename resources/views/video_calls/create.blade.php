<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8">
                <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Créer un appel vidéo/audio</h1>
                <form method="POST" action="{{ route('video-calls.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Titre de l'appel</label>
                        <input type="text" name="title" id="title" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" value="{{ old('title') }}" maxlength="255">
                        @error('title')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Description</label>
                        <textarea name="description" id="description" rows="2" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Type d'appel <span class="text-red-500">*</span></label>
                        <select name="type" id="type" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                            <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Vidéo & Audio</option>
                        </select>
                        @error('type')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Participants <span class="text-red-500">*</span></label>
                        <div class="max-h-48 overflow-y-auto border rounded p-2 bg-gray-50 dark:bg-gray-700">
                            @foreach($availableUsers as $user)
                                <div class="flex items-center mb-1">
                                    <input type="checkbox" name="participant_ids[]" value="{{ $user->id }}" id="user-{{ $user->id }}" class="mr-2" {{ in_array($user->id, old('participant_ids', [])) ? 'checked' : '' }}>
                                    <label for="user-{{ $user->id }}" class="text-gray-800 dark:text-gray-100">{{ $user->name }} <span class="text-xs text-gray-400">({{ $user->email }})</span></label>
                                </div>
                            @endforeach
                        </div>
                        @error('participant_ids')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">Créer l'appel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 