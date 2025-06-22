<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-book text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Subjects</h1>
                    <p class="text-gray-500 dark:text-gray-300">
                        @if(auth()->user()->role === 'enseignant')
                            View your school's academic subjects (Read-only access)
                        @else
                            Manage your academic subjects
                        @endif
                    </p>
                </div>
            </div>
            @if(auth()->user()->role !== 'enseignant')
                <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-plus"></i> Add Subject
                </a>
            @endif
        </div>

        <!-- Search and Filters + View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex gap-2">
                <div class="relative w-full max-w-xs">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="Search subjects...">
                    <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <select name="level" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Levels</option>
                    <option value="primary" @if(request('level')=='primary') selected @endif>Primary</option>
                    <option value="secondary" @if(request('level')=='secondary') selected @endif>Secondary</option>
                    <option value="high" @if(request('level')=='high') selected @endif>High School</option>
                    <option value="university" @if(request('level')=='university') selected @endif>University</option>
                </select>
                <select name="status" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="1" @if(request('status')=='1') selected @endif>Active</option>
                    <option value="0" @if(request('status')=='0') selected @endif>Inactive</option>
                </select>
                <select name="hours_per_week" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Hours</option>
                    <option value="1" @if(request('hours_per_week')=='1') selected @endif>1 hour</option>
                    <option value="2" @if(request('hours_per_week')=='2') selected @endif>2 hours</option>
                    <option value="3" @if(request('hours_per_week')=='3') selected @endif>3 hours</option>
                    <option value="4" @if(request('hours_per_week')=='4') selected @endif>4 hours</option>
                    <option value="5" @if(request('hours_per_week')=='5') selected @endif>5+ hours</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('subjects.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
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

        @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 flex items-center gap-2">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
        @endif

        <!-- Table View -->
        <div id="tableView">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <table class="table-fixed w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Code</th>
                            <th class="px-4 py-3 text-left">Credits</th>
                            <th class="px-4 py-3 text-left">Level</th>
                            <th class="px-4 py-3 text-left">Hours/Week</th>
                            <th class="px-4 py-3 text-left">School</th>
                            <th class="px-4 py-3 text-left">Created By</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="font-semibold text-lg">{{ $subject->name }}</span>
                                        @if($subject->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">{{ $subject->description }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $subject->code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $subject->credits ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @if($subject->level)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                            {{ $subject->level }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $subject->hours_per_week ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @if($subject->school)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                                            {{ $subject->school->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($subject->user)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                                            {{ $subject->user->first_name }} {{ $subject->user->last_name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold 
                                    {{ $subject->is_active 
                                     ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' 
                                    : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                     {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <x-action-icons
                                        :viewRoute="route('subjects.show', $subject)"
                                        :editRoute="route('subjects.edit', $subject)"
                                        :deleteRoute="route('subjects.destroy', $subject)"
                                        :canEdit="auth()->user()->role !== 'enseignant'"
                                        :canDelete="auth()->user()->role !== 'enseignant'"
                                        deleteConfirmMessage="Are you sure you want to delete this subject?"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-book fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No subjects found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first subject</p>
                                        @if(auth()->user()->role !== 'enseignant')
                                            <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                                <i class="fas fa-plus"></i> Add Subject
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($subjects, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }} of {{ $subjects->total() }} subjects
                    </div>
                    <div>
                        {{ $subjects->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Grid View (masqué par défaut) -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($subjects as $subject)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-3">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow">
                                <i class="fas fa-book text-white text-lg"></i>
                            </span>
                            <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $subject->name }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $subject->code ?? 'No Code' }}</div>
                        <div class="flex flex-col gap-1 text-sm">
                            @if($subject->description)
                                <span class="truncate"><i class="fas fa-info-circle mr-1"></i> {{ $subject->description }}</span>
                            @endif
                            <span class="truncate"><i class="fas fa-graduation-cap mr-1"></i> {{ $subject->level ?? 'No Level' }}</span>
                            <span class="truncate"><i class="fas fa-clock mr-1"></i> {{ $subject->hours_per_week ?? 'N/A' }} hours/week</span>
                            <span class="truncate"><i class="fas fa-star mr-1"></i> {{ $subject->credits ?? 'N/A' }} credits</span>
                            @if($subject->school)
                                <span class="truncate"><i class="fas fa-school mr-1"></i> {{ $subject->school->name }}</span>
                            @endif
                            @if($subject->user)
                                <span class="truncate"><i class="fas fa-user mr-1"></i> {{ $subject->user->first_name }} {{ $subject->user->last_name }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $subject->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $subject->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex gap-2 mt-3 justify-end">
                            <x-action-icons
                                :viewRoute="route('subjects.show', $subject)"
                                :editRoute="route('subjects.edit', $subject)"
                                :deleteRoute="route('subjects.destroy', $subject)"
                                :canEdit="auth()->user()->role !== 'enseignant'"
                                :canDelete="auth()->user()->role !== 'enseignant'"
                                deleteConfirmMessage="Are you sure you want to delete this subject?"
                            />
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-8">
                        <i class="fas fa-book fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h5 class="text-gray-400 dark:text-gray-500">No subjects found</h5>
                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first subject</p>
                        @if(auth()->user()->role !== 'enseignant')
                            <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                <i class="fas fa-plus"></i> Add Subject
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
            @if(method_exists($subjects, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }} of {{ $subjects->total() }} subjects
                    </div>
                    <div>
                        {{ $subjects->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
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