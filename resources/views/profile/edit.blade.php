<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <!-- Nouveau header sobre et profile card -->
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Profile Edit Card côte à côte -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 w-full -mt-10 mb-12">
                <div class="mb-8 flex items-center justify-center gap-3 text-indigo-700 dark:text-indigo-300 font-semibold text-xl">
                    <i class="fas fa-id-card-alt"></i>
                    <span>Basic Information</span>
                </div>
                <div class="flex flex-col md:flex-row gap-8 w-full">
                    <!-- Colonne gauche : photo + infos principales -->
                    <div class="flex flex-col items-center md:items-start md:w-1/3 w-full gap-4">
                        <img id="profile-photo-preview" src="{{ auth()->user()->profile_photo_url }}" alt="Profile Photo" class="w-28 h-28 rounded-xl object-cover border-4 border-gray-200 dark:border-gray-700 shadow">
                        <div class="mt-4 text-center md:text-left w-full">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
                            <div class="flex flex-col items-center md:items-start gap-1 w-full">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-indigo-50 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 shadow-sm mb-1">
                                    <i class="fas fa-user-tag mr-2"></i>
                                    @if(strtolower(auth()->user()->role) === 'school_admin')
                                        Administrator of School
                                    @else
                                        {{ ucfirst(auth()->user()->role) }}
                                    @endif
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ auth()->user()->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} mb-1">
                                    <i class="fas fa-circle mr-1 text-xs"></i> {{ ucfirst(auth()->user()->status ?? 'active') }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    <i class="fas fa-calendar-alt mr-1"></i>Member since {{ auth()->user()->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Colonne droite : formulaire d'édition -->
                    <div class="flex-1">
                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="w-full space-y-6">
                            @csrf
                            @method('patch')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">First Name</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('first_name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('last_name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="profession" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Profession</label>
                                    <input type="text" name="profession" id="profession" value="{{ old('profession', auth()->user()->profession) }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('profession')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <!-- Aperçu de la photo juste au-dessus de l'upload -->
                            <div class="flex flex-col items-center mb-2">
                                <img id="profile-photo-preview" src="{{ auth()->user()->profile_photo_url }}" alt="Profile Photo" class="w-28 h-28 rounded-xl object-cover border-4 border-gray-200 dark:border-gray-700 shadow">
                            </div>
                            <div>
                                <label for="profile_photo" class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 text-xs rounded-lg font-medium hover:bg-indigo-200 dark:hover:bg-indigo-800 cursor-pointer transition-colors">
                                    <i class="fas fa-camera"></i> Change Photo
                                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden" />
                                </label>
                                @error('profile_photo')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-8 rounded-lg shadow transition-all text-base flex items-center gap-2">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password & Delete Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8 w-full">
                <!-- Update Password -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 w-full">
                    <h2 class="mb-6 flex items-center gap-2 justify-center text-indigo-700 dark:text-indigo-300 font-semibold text-xl">
                        <i class="fas fa-key"></i> Update Password
                    </h2>
                    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">New Password</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-all flex items-center gap-2">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Delete Account -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 w-full">
                    <h2 class="mb-6 flex items-center gap-2 justify-center text-indigo-700 dark:text-indigo-300 font-semibold text-xl">
                        <i class="fas fa-trash-alt"></i> Delete Account
                    </h2>
                    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                        @csrf
                        @method('delete')
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-red-500 focus:border-red-500">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow transition-all flex items-center gap-2">
                                <i class="fas fa-trash-alt"></i> Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('profile_photo');
        const preview = document.getElementById('profile-photo-preview');
        if (input && preview) {
            input.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        preview.src = ev.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
