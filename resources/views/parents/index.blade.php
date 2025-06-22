<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-users text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Parents</h1>
                    <p class="text-gray-500 dark:text-gray-300">Manage your parents</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('parents.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition whitespace-nowrap">
                    <i class="fas fa-plus"></i> Add Parent
                </a>
            </div>
        </div>

        <!-- Search and Filters + View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex gap-2 items-end">
                <div class="relative w-full max-w-xs">
                    <label for="status" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">&nbsp;</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="Search by name, email, phone...">
                    <span class="absolute left-3 top-8 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <div>
                    <select name="profession" id="profession" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                        <option value="">All Professions</option>
                        @php
                            $professions = $parents->pluck('profession')->filter()->unique()->sort();
                        @endphp
                        @foreach($professions as $profession)
                            <option value="{{ $profession }}" @if(request('profession')==$profession) selected @endif>{{ $profession }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition whitespace-nowrap">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('parents.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition whitespace-nowrap">
                <i class="fas fa-undo mr-2"></i>Reset
            </a>
            <!-- View Toggle -->
            <div class="flex gap-2 ml-auto">
                <button type="button" id="tableViewBtn" class="px-3 py-2 rounded-lg border border-indigo-600 text-indigo-600 bg-white dark:bg-gray-800 font-semibold shadow hover:bg-indigo-50 dark:hover:bg-indigo-900 transition active">
                    <i class="fas fa-table"></i>
                </button>
                <button type="button" id="gridViewBtn" class="px-3 py-2 rounded-lg border border-indigo-600 text-indigo-600 bg-white dark:bg-gray-800 font-semibold shadow hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>
        </form>

        <!-- Table View -->
        <div id="tableView">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 py-3 text-left">Name</th>
                                <th class="px-2 py-3 text-left">Phone</th>
                                <th class="px-2 py-3 text-left">Profession</th>
                                <th class="px-2 py-3 text-left">Role</th>
                                <th class="px-2 py-3 text-left">Status</th>
                                <th class="px-2 py-3 text-left">Students</th>
                                <th class="px-2 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parents as $parent)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-2 py-3 max-w-xs truncate">
                                    <div class="flex items-center gap-2">
                                        @if($parent->profile_photo)
                                            <img src="{{ asset('storage/' . $parent->profile_photo) }}" alt="Profile Photo" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </span>
                                        @endif
                                        <div class="max-w-[120px] truncate">
                                            <span class="font-semibold text-lg truncate">{{ $parent->first_name }} {{ $parent->last_name }}</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 break-all truncate">{{ $parent->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3 max-w-xs truncate">
                                    <span class="text-sm">{{ $parent->phone ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $parent->profession ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $parent->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 
                                           ($parent->role === 'teacher' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                           ($parent->role === 'student' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                            'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200')) }}">
                                        {{ ucfirst($parent->role ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $parent->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ ucfirst($parent->status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $parent->students_count ?? 0 }} student(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <x-action-icons 
                                        :viewRoute="route('parents.show', $parent)"
                                        :editRoute="route('parents.edit', $parent)"
                                        :deleteRoute="route('parents.destroy', $parent)"
                                        :canEdit="auth()->user()->can('update', $parent)"
                                        :canDelete="auth()->user()->can('delete', $parent)"
                                        deleteConfirmMessage="Are you sure you want to delete this parent?"
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No parents found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first parent</p>
                                        <a href="{{ route('parents.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                            <i class="fas fa-plus"></i> Add Parent
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Grid View (masqué par défaut) -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($parents as $parent)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-3">
                        <div class="flex items-center gap-3 mb-2">
                            @if($parent->profile_photo)
                                <img src="{{ asset('storage/' . $parent->profile_photo) }}" alt="Profile Photo" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-600 shadow">
                            @else
                                <span class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500"></i>
                                </span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-lg truncate">{{ $parent->first_name }} {{ $parent->last_name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $parent->email }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Phone:</span>
                                <span>{{ $parent->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Profession:</span>
                                <span>{{ $parent->profession ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Role:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $parent->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 
                                       ($parent->role === 'teacher' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                       ($parent->role === 'student' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200')) }}">
                                    {{ ucfirst($parent->role ?? 'N/A') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Status:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $parent->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($parent->status ?? 'N/A') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-auto pt-3 border-t border-gray-200 dark:border-gray-700 justify-center">
                            <x-action-icons 
                                :viewRoute="route('parents.show', $parent)"
                                :editRoute="route('parents.edit', $parent)"
                                :deleteRoute="route('parents.destroy', $parent)"
                                :canEdit="auth()->user()->can('update', $parent)"
                                :canDelete="auth()->user()->can('delete', $parent)"
                                deleteConfirmMessage="Are you sure you want to delete this parent?"
                            />
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-users fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h5 class="text-gray-400 dark:text-gray-500">No parents found</h5>
                        <p class="text-gray-400 dark:text-gray-500 mb-4">Get started by adding your first parent</p>
                        <a href="{{ route('parents.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                            <i class="fas fa-plus"></i> Add Parent
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // View Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tableViewBtn = document.getElementById('tableViewBtn');
            const gridViewBtn = document.getElementById('gridViewBtn');
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');

            tableViewBtn.addEventListener('click', function() {
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
                tableViewBtn.classList.add('active');
                gridViewBtn.classList.remove('active');
            });

            gridViewBtn.addEventListener('click', function() {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
                gridViewBtn.classList.add('active');
                tableViewBtn.classList.remove('active');
            });
        });
    </script>
</x-app-layout>
