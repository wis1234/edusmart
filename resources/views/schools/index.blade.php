<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-university text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Institutions</h1>
                    <p class="text-gray-500 dark:text-gray-300">Manage your educational institutions</p>
                </div>
            </div>
            @if(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
            <a href="{{ route('schools.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                <i class="fas fa-plus"></i> Add Institution
            </a>
            @endif
        </div>

        <!-- Search and Filters + View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex gap-2">
                <div class="relative w-full max-w-xs">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="Search institutions...">
                    <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <select name="status" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                    <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
                </select>
                <select name="type" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    <option value="primary" @if(request('type')=='primary') selected @endif>Primary</option>
                    <option value="secondary" @if(request('type')=='secondary') selected @endif>Secondary</option>
                    <option value="high" @if(request('type')=='high') selected @endif>High School</option>
                    <option value="university" @if(request('type')=='university') selected @endif>University</option>
                </select>
                <select name="country" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Countries</option>
                    @foreach($schools->pluck('country')->unique() as $country)
                        <option value="{{ $country }}" @if(request('country')==$country) selected @endif>{{ $country }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('schools.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
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
                <table class="table-fixed w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Address</th>
                            <th class="px-4 py-3 text-left">Phone</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schools as $school)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="font-semibold text-lg">{{ $school->name }}</span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $school->type }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 max-w-[120px] truncate">
                                    <div class="flex flex-col">
                                        <span class="truncate">{{ $school->address }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $school->city }}, {{ $school->country }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $school->phone }}</td>
                                <td class="px-4 py-3 max-w-[120px] truncate">
                                    <span class="truncate">{{ $school->email }}</span>
                                </td>
                                <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold 
                                {{ $school->status === 'active' 
                                 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' 
                                : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                 {{ ucfirst($school->status) }}
                                </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <x-action-icons
                                        :viewRoute="route('schools.show', $school)"
                                        :editRoute="(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin') ? route('schools.edit', $school) : null"
                                        :deleteRoute="(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin') ? route('schools.destroy', $school) : null"
                                        :canEdit="auth()->user()->hasRole('admin') || auth()->user()->role === 'admin'"
                                        :canDelete="auth()->user()->hasRole('admin') || auth()->user()->role === 'admin'"
                                        deleteConfirmMessage="Are you sure you want to delete this institution?"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-university fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No institutions found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first institution</p>
                                        @if(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
                                        <a href="{{ route('schools.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                            <i class="fas fa-plus"></i> Add Institution
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($schools, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $schools->firstItem() }} to {{ $schools->lastItem() }} of {{ $schools->total() }} institutions
                    </div>
                    <div>
                        {{ $schools->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Grid View (masqué par défaut) -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($schools as $school)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-3">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow">
                                <i class="fas fa-university text-white text-lg"></i>
                            </span>
                            <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $school->name }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $school->type }}</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <span class="truncate"><i class="fas fa-map-marker-alt mr-1"></i> {{ $school->address }}</span>
                            <span class="truncate"><i class="fas fa-phone mr-1"></i> {{ $school->phone }}</span>
                            <span class="truncate"><i class="fas fa-envelope mr-1"></i> {{ $school->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $school->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $school->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $school->city }}, {{ $school->country }}</span>
                        </div>
                        <div class="flex gap-2 mt-3 justify-end">
                            <x-action-icons
                                :viewRoute="route('schools.show', $school)"
                                :editRoute="(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin') ? route('schools.edit', $school) : null"
                                :deleteRoute="(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin') ? route('schools.destroy', $school) : null"
                                :canEdit="auth()->user()->hasRole('admin') || auth()->user()->role === 'admin'"
                                :canDelete="auth()->user()->hasRole('admin') || auth()->user()->role === 'admin'"
                                deleteConfirmMessage="Are you sure you want to delete this institution?"
                            />
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-8">
                        <i class="fas fa-university fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h5 class="text-gray-400 dark:text-gray-500">No institutions found</h5>
                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first institution</p>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
                        <a href="{{ route('schools.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                            <i class="fas fa-plus"></i> Add Institution
                        </a>
                        @endif
                    </div>
                @endforelse
            </div>
            @if(method_exists($schools, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $schools->firstItem() }} to {{ $schools->lastItem() }} of {{ $schools->total() }} institutions
                    </div>
                    <div>
                        {{ $schools->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>

        <script>
            // Bascule Table/Grid
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
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');
                gridViewBtn.classList.add('active');
                tableViewBtn.classList.remove('active');
            });
        </script>
    </div>
</x-app-layout>
