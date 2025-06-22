<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                        @if($school->logo)
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="w-16 h-16 object-contain rounded-lg border-4 border-indigo-500 shadow-lg">
                        @else
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-university text-white text-3xl"></i>
                    </span>
                        @endif
                        <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $school->name }}</h1>
                    <p class="text-gray-500 dark:text-gray-300">{{ ucfirst($school->type) }} - {{ ucfirst($school->status) }}</p>
                        </div>
                    </div>
            <div class="flex gap-2">
                        @can('update', $school)
                <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                <a href="{{ route('schools.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

        <!-- Infos principales -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-3 text-indigo-700 dark:text-indigo-300">
                <i class="fas fa-school"></i> School Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold mb-3 flex items-center gap-2 text-indigo-600 dark:text-indigo-200">
                        <i class="fas fa-info-circle"></i> Basic Information
                    </h3>
                    <ul class="space-y-2 text-gray-700 dark:text-gray-200">
                        <li><span class="font-medium text-gray-500">Type:</span> <span class="font-semibold">{{ ucfirst($school->type) }}</span></li>
                        <li><span class="font-medium text-gray-500">Status:</span> <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $school->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">{{ ucfirst($school->status) }}</span></li>
                        <li><span class="font-medium text-gray-500">Register on:</span> <span>{{ $school->created_at?->format('M d, Y') }}</span></li>
                        <li><span class="font-medium text-gray-500">Principal:</span> <span>{{ $school->principal_name}}</span></li>
                        <li><span class="font-medium text-gray-500">Description:</span> <span>{{ $school->description ?: 'No description available' }}</span></li>
                    </ul>
                </div>
                <!-- Contact Information -->
                <div class="border-l border-gray-200 dark:border-gray-700 pl-6">
                    <h3 class="text-lg font-semibold mb-3 flex items-center gap-2 text-indigo-600 dark:text-indigo-200">
                        <i class="fas fa-address-book"></i> Contact Information
                    </h3>
                    <ul class="space-y-2 text-gray-700 dark:text-gray-200">
                        <li><span class="font-medium text-gray-500">Email:</span> <span class="font-semibold">{{ $school->email }}</span></li>
                        <li><span class="font-medium text-gray-500">Phone:</span> <span class="font-semibold">{{ $school->phone }}</span></li>
                        <li><span class="font-medium text-gray-500">Website:</span> @if($school->website)<a href="{{ $school->website }}" target="_blank" class="text-indigo-600 hover:underline">{{ $school->website }}</a>@else<span>No website available</span>@endif</li>
                    </ul>
                </div>
                <!-- Address Information -->
                <div class="border-l border-gray-200 dark:border-gray-700 pl-6">
                    <h3 class="text-lg font-semibold mb-3 flex items-center gap-2 text-indigo-600 dark:text-indigo-200">
                        <i class="fas fa-map-marker-alt"></i> Address Information
                    </h3>
                    <ul class="space-y-2 text-gray-700 dark:text-gray-200">
                        <li><span class="font-medium text-gray-500">Address:</span> <span class="font-semibold">{{ $school->address }}</span></li>
                        <li><span class="font-medium text-gray-500">City:</span> <span class="font-semibold">{{ $school->city }}</span></li>
                        <li><span class="font-medium text-gray-500">State:</span> <span class="font-semibold">{{ $school->state }}</span></li>
                        <li><span class="font-medium text-gray-500">Country:</span> <span class="font-semibold">{{ $school->country }}</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <a href="#" data-tab="hosts" class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 border-indigo-500 text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-users-cog mr-2"></i> Hosts
                    </a>
                    <a href="#" data-tab="teachers" class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-chalkboard-teacher mr-2"></i> Teachers
                    </a>
                    <a href="#" data-tab="classrooms" class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-door-open mr-2"></i> Classrooms
                    </a>
                    <a href="#" data-tab="students" class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-user-graduate mr-2"></i> Students
                    </a>
                    <a href="#" data-tab="subjects" class="tab-link py-4 px-1 border-b-2 font-medium text-sm transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-book mr-2"></i> Subjects
                    </a>
                </nav>
            </div>
            <div class="p-6 relative">
                <div id="tab-loading" class="hidden absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-75 z-10 flex items-center justify-center rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Loading...</span>
                    </div>
                </div>
                <div id="tab-content" class="transition-all duration-300">
                    @include('schools.partials.hosts', ['school' => $school])
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContent = document.getElementById('tab-content');
            const tabLoading = document.getElementById('tab-loading');
            let currentTab = 'hosts';
            const partials = {
                hosts: `@include('schools.partials.hosts', ['school' => $school])`,
                teachers: `@include('schools.partials.teachers', ['school' => $school])`,
                classrooms: `@include('schools.partials.classrooms', ['school' => $school])`,
                students: `@include('schools.partials.students', ['school' => $school])`,
                subjects: `@include('schools.partials.subjects', ['school' => $school])`,
            };
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabName = this.getAttribute('data-tab');
                    if (tabName === currentTab) return;
                    tabLinks.forEach(l => l.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400'));
                    tabLinks.forEach(l => l.classList.add('border-transparent', 'text-gray-500'));
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                    tabLoading.classList.remove('hidden');
                    tabContent.style.opacity = '0.5';
                    setTimeout(() => {
                        tabContent.innerHTML = partials[tabName];
                        tabContent.style.opacity = '1';
                        tabLoading.classList.add('hidden');
                        currentTab = tabName;
                    }, 350);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
