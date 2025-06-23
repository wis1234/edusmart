<div class="space-y-6">
    <!-- Profile Lock Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-lock text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Privacy</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Control who can view your profile details
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $user->isProfileLocked() ? 'Locked' : 'Unlocked' }}
                </span>
                <form method="POST" action="{{ route('settings.toggle-profile-lock') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $user->isProfileLocked() ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }}"
                            role="switch" 
                            aria-checked="{{ $user->isProfileLocked() ? 'true' : 'false' }}">
                        <span class="sr-only">Toggle profile lock</span>
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->isProfileLocked() ? 'translate-x-5' : 'translate-x-0' }}">
                        </span>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                        {{ $user->isProfileLocked() ? 'Profile is Locked' : 'Profile is Unlocked' }}
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        @if($user->isProfileLocked())
                            Your profile is currently locked. Only administrators and school administrators can view your profile details. Other users will not be able to see your information.
                        @else
                            Your profile is currently unlocked. Other users can view your profile details according to their permissions and roles.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-tr from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Update your account's profile information</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                    <div class="flex items-center space-x-3">
                        <div class="flex-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <span class="text-gray-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</span>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:text-indigo-300 dark:bg-indigo-900 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </a>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <span class="text-gray-900 dark:text-white">{{ $user->email }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <span class="text-gray-900 dark:text-white">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Member Since</label>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <span class="text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Photo Section -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Profile Photo</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $user->profile_photo ? 'You have uploaded a profile photo.' : 'No profile photo uploaded yet.' }}
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:text-indigo-300 dark:bg-indigo-900 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <i class="fas fa-camera mr-2"></i>
                    Update Photo
                </a>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-shield-alt text-yellow-600 dark:text-yellow-400 mt-1"></i>
            </div>
            <div>
                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Privacy Notice</h4>
                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                    When your profile is locked, only administrators and school administrators can view your profile details. 
                    This helps protect your privacy while maintaining necessary access for system administration.
                </p>
            </div>
        </div>
    </div>
</div> 