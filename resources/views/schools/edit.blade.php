<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-university text-white text-2xl"></i>
                </span>
                        <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Edit Institution</h1>
                    <p class="text-gray-500 dark:text-gray-300">Update institution information</p>
                        </div>
                    </div>
            <div class="flex gap-2">
                <a href="{{ route('schools.show', $school) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-eye"></i> View Details
                        </a>
                <a href="{{ route('schools.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                </div>
            </div>

        <!-- Formulaire modernisé -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <form action="{{ route('schools.update', $school) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Institution Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $school->name) }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                                @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Institution Code <span class="text-red-500">*</span></label>
                        <input type="text" id="code" name="code" value="{{ old('code', $school->code) }}" required maxlength="50"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-500 @enderror">
                                @error('code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $school->description) }}</textarea>
                                @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Principal Name -->
                    <div>
                        <label for="principal_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Principal's Name <span class="text-red-500">*</span></label>
                        <input type="text" id="principal_name" name="principal_name" value="{{ old('principal_name', $school->principal_name) }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('principal_name') border-red-500 @enderror">
                                @error('principal_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $school->email) }}" required maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                                @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $school->phone) }}" required maxlength="20"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                                @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website', $school->website) }}" maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('website') border-red-500 @enderror">
                                @error('website')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                <!-- Adresse -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address <span class="text-red-500">*</span></label>
                    <textarea id="address" name="address" rows="2" required maxlength="255"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror">{{ old('address', $school->address) }}</textarea>
                                @error('address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-200">City <span class="text-red-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city', $school->city) }}" required maxlength="100"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('city') border-red-500 @enderror">
                                @error('city')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- State -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-200">State/Province <span class="text-red-500">*</span></label>
                        <input type="text" id="state" name="state" value="{{ old('state', $school->state) }}" required maxlength="100"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('state') border-red-500 @enderror">
                                @error('state')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Country <span class="text-red-500">*</span></label>
                        <input type="text" id="country" name="country" value="{{ old('country', $school->country) }}" required maxlength="100"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('country') border-red-500 @enderror">
                                @error('country')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Postal Code -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Postal/ZIP Code <span class="text-red-500">*</span></label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $school->postal_code) }}" required maxlength="20"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('postal_code') border-red-500 @enderror">
                                @error('postal_code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200">School Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('type') border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="public" {{ old('type', $school->type) == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('type', $school->type) == 'private' ? 'selected' : '' }}>Private</option>
                                    <option value="charter" {{ old('type', $school->type) == 'charter' ? 'selected' : '' }}>Charter</option>
                                </select>
                                @error('type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Student Capacity <span class="text-red-500">*</span></label>
                        <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $school->capacity) }}" required min="1"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('capacity') border-red-500 @enderror">
                                @error('capacity')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $school->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $school->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                <div class="flex justify-end gap-2 mt-8">
                    <a href="{{ route('schools.show', $school) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-save"></i> Update Institution
                        </button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>
