<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900 dark:to-blue-700 rounded-xl shadow-lg p-4 flex items-center gap-3 min-w-0">
                    <i class="fas fa-history text-blue-500 dark:text-blue-300 text-2xl sm:text-3xl"></i>
                    <div class="min-w-0">
                        <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm truncate">{{ __('messages.total_activities') }}</div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $activities->total() }}</div>
                        <div class="text-blue-600 dark:text-blue-400 text-xs flex items-center gap-1">
                            <i class="fas fa-chart-line"></i> {{ __('messages.all_activities') }}
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-100 to-green-50 dark:from-green-900 dark:to-green-700 rounded-xl shadow-lg p-4 flex items-center gap-3 min-w-0">
                    <i class="fas fa-plus-circle text-green-500 dark:text-green-300 text-2xl sm:text-3xl"></i>
                    <div class="min-w-0">
                        <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm truncate">{{ __('messages.creations') }}</div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $stats['create'] ?? 0 }}</div>
                        <div class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1">
                            <i class="fas fa-plus"></i> {{ __('messages.adds') }}
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-yellow-100 to-yellow-50 dark:from-yellow-900 dark:to-yellow-700 rounded-xl shadow-lg p-4 flex items-center gap-3 min-w-0">
                    <i class="fas fa-edit text-yellow-500 dark:text-yellow-300 text-2xl sm:text-3xl"></i>
                    <div class="min-w-0">
                        <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm truncate">{{ __('messages.modifications') }}</div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $stats['update'] ?? 0 }}</div>
                        <div class="text-yellow-600 dark:text-yellow-400 text-xs flex items-center gap-1">
                            <i class="fas fa-edit"></i> {{ __('messages.updates') }}
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-100 to-red-50 dark:from-red-900 dark:to-red-700 rounded-xl shadow-lg p-4 flex items-center gap-3 min-w-0">
                    <i class="fas fa-trash-alt text-red-500 dark:text-red-300 text-2xl sm:text-3xl"></i>
                    <div class="min-w-0">
                        <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm truncate">{{ __('messages.deletions') }}</div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $stats['delete'] ?? 0 }}</div>
                        <div class="text-red-600 dark:text-red-400 text-xs flex items-center gap-1">
                            <i class="fas fa-trash"></i> {{ __('messages.deletes') }}
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-900 dark:to-gray-700 rounded-xl shadow-lg p-4 flex items-center gap-3 min-w-0">
                    <i class="fas fa-sign-out-alt text-gray-500 dark:text-gray-300 text-2xl sm:text-3xl"></i>
                    <div class="min-w-0">
                        <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm truncate">{{ __('messages.logouts') }}</div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $stats['logout'] ?? 0 }}</div>
                        <div class="text-gray-600 dark:text-gray-400 text-xs flex items-center gap-1">
                            <i class="fas fa-sign-out-alt"></i> {{ __('messages.logouts') }}
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-indigo-100 to-indigo-50 dark:from-indigo-900 dark:to-indigo-700 rounded-xl shadow-lg p-4 flex items-center gap-3 min-w-0">
                    <i class="fas fa-sign-in-alt text-indigo-500 dark:text-indigo-300 text-2xl sm:text-3xl"></i>
                    <div class="min-w-0">
                        <div class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm truncate">{{ __('messages.logins') }}</div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $stats['login'] ?? 0 }}</div>
                        <div class="text-indigo-600 dark:text-indigo-400 text-xs flex items-center gap-1">
                            <i class="fas fa-sign-in-alt"></i> {{ __('messages.logins') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-teal-500 shadow-lg">
                        <i class="fas fa-history text-white text-2xl"></i>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ __('messages.activities') }}</h1>
                        <p class="text-gray-500 dark:text-gray-300">{{ __('messages.activities_description') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('activities.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Type Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('messages.type') }}
                                </label>
                                <select name="type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('messages.all_types') }}</option>
                                    @foreach($activityTypes as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- User Filter -->
                            @if(auth()->user()->hasRole('admin'))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('messages.user') }}
                                </label>
                                <select name="user_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('messages.all_users') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Date Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('messages.date') }}
                                </label>
                                <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('messages.search') }}
                                </label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_activities') }}" class="w-full max-w-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('activities.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                <i class="fas fa-undo mr-1"></i>{{ __('messages.reset') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <i class="fas fa-filter mr-1"></i>{{ __('messages.apply_filters') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Activities List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.user') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.description') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.type') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Details</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($activities as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($activity->user && $activity->user->profile_photo)
                                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $activity->user->profile_photo) }}" alt="">
                                                @else
                                                    <span class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-400"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @if($activity->user)
                                                        {{ $activity->user->first_name }} {{ $activity->user->last_name }}
                                                    @else
                                                        <span class="italic">System</span>
                                                    @endif
                                                </div>
                                                @if($activity->user)
                                                    <div class="text-sm text-gray-500">{{ $activity->user->email }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ Str::limit($activity->description, 80) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            style="background: {{ $activity->type === 'create' ? '#dbeafe' : ($activity->type === 'update' ? '#ede9fe' : ($activity->type === 'delete' ? '#fee2e2' : '#f3f4f6')) }}; color: {{ $activity->type === 'create' ? '#2563eb' : ($activity->type === 'update' ? '#7c3aed' : ($activity->type === 'delete' ? '#dc2626' : '#374151')) }};">
                                            {{ ucfirst($activity->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button" onclick="showActivityDetails({{ $activity->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                            {{ __('messages.details') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-center">
                                            <i class="fas fa-history text-gray-400 text-4xl mb-3"></i>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.no_activity_found') }}</h3>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($activities->hasPages())
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    {{ $activities->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal pour les détails -->
    <div id="activityDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl mx-4 sm:my-8 transform transition-all">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeActivityDetails()" class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times h-6 w-6"></i>
                </button>
            </div>
            <div id="activityDetailsContent" class="p-6">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showActivityDetails(id) {
            fetch(`/activities/${id}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('activityDetailsContent').innerHTML = html;
                    document.getElementById('activityDetailsModal').classList.remove('hidden');
                });
        }

        function closeActivityDetails() {
            document.getElementById('activityDetailsModal').classList.add('hidden');
            document.getElementById('activityDetailsContent').innerHTML = '';
        }
    </script>
    @endpush
</x-app-layout> 