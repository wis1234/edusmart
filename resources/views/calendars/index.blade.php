<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Schedules</h1>
                    <p class="text-gray-500 dark:text-gray-300">Weekly timetable for all classes</p>
                </div>
            </div>
            <a href="{{ route('calendars.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition whitespace-nowrap">
                <i class="fas fa-plus"></i> Add Schedule
            </a>
        </div>

        <!-- Filters and View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex flex-wrap gap-2">
                <select name="school" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @if(request('school')==$school->id) selected @endif>{{ $school->name }}</option>
                    @endforeach
                </select>
                <select name="class_room" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Classes</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @if(request('class_room')==$classRoom->id) selected @endif>{{ $classRoom->name }}</option>
                    @endforeach
                </select>
                <select name="subject" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @if(request('subject')==$subject->id) selected @endif>{{ $subject->name }}</option>
                    @endforeach
                </select>
                <select name="academic_year" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Years</option>
                    <option value="2023-2024" @if(request('academic_year')=='2023-2024') selected @endif>2023-2024</option>
                    <option value="2024-2025" @if(request('academic_year')=='2024-2025') selected @endif>2024-2025</option>
                    <option value="2025-2026" @if(request('academic_year')=='2025-2026') selected @endif>2025-2026</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('calendars.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
                <!-- View Toggle -->
                <div class="flex gap-2">
                    <button type="button" id="tableViewBtn" class="px-3 py-2 rounded-lg border border-indigo-600 text-indigo-600 bg-white dark:bg-gray-800 font-semibold shadow hover:bg-indigo-50 dark:hover:bg-indigo-900 transition active">
                        <i class="fas fa-table"></i>
                    </button>
                    <button type="button" id="gridViewBtn" class="px-3 py-2 rounded-lg border border-indigo-600 text-indigo-600 bg-white dark:bg-gray-800 font-semibold shadow hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
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
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">School</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">Class</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">Subject</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/6">Year</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/6">Week</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($calendars as $calendar)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $calendar->school->name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $calendar->classRoom->name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $calendar->subject->name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $calendar->academic_year }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $calendar->week_number ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-medium whitespace-nowrap">
                                    <x-action-icons
                                        :viewRoute="route('calendars.show', $calendar)"
                                        :editRoute="route('calendars.edit', $calendar)"
                                        :deleteRoute="route('calendars.destroy', $calendar)"
                                        :canEdit="auth()->user() && auth()->user()->can('update', $calendar)"
                                        :canDelete="auth()->user() && auth()->user()->can('delete', $calendar)"
                                        deleteConfirmMessage="Are you sure you want to delete this schedule?"
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-alt fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No schedules found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first schedule</p>
                                        <a href="{{ route('calendars.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                            <i class="fas fa-plus"></i> Add Schedule
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($calendars, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $calendars->firstItem() }} to {{ $calendars->lastItem() }} of {{ $calendars->total() }} schedules
                    </div>
                    <div>
                        {{ $calendars->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Grid View -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($calendars as $calendar)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600">
                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-0 truncate">{{ $calendar->subject->name ?? '-' }}</h2>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $calendar->school->name ?? '-' }} | {{ $calendar->classRoom->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="text-sm text-gray-600 dark:text-gray-300">Academic Year: <span class="font-semibold">{{ $calendar->academic_year }}</span></div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Week: <span class="font-semibold">{{ $calendar->week_number ?? '-' }}</span></div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <x-action-icons
                            :viewRoute="route('calendars.show', $calendar)"
                            :editRoute="route('calendars.edit', $calendar)"
                            :deleteRoute="route('calendars.destroy', $calendar)"
                            :canEdit="auth()->user() && auth()->user()->can('update', $calendar)"
                            :canDelete="auth()->user() && auth()->user()->can('delete', $calendar)"
                            deleteConfirmMessage="Are you sure you want to delete this schedule?"
                        />
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8">
                    <i class="fas fa-calendar-alt fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                    <h5 class="text-gray-400 dark:text-gray-500">No schedules found</h5>
                    <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first schedule</p>
                    <a href="{{ route('calendars.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                        <i class="fas fa-plus"></i> Add Schedule
                    </a>
                </div>
                @endforelse
            </div>
            @if(method_exists($calendars, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $calendars->firstItem() }} to {{ $calendars->lastItem() }} of {{ $calendars->total() }} schedules
                    </div>
                    <div>
                        {{ $calendars->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');
            const tableViewBtn = document.getElementById('tableViewBtn');
            const gridViewBtn = document.getElementById('gridViewBtn');

            // Get view preference from localStorage or default to table
            const currentView = localStorage.getItem('calendarViewPreference') || 'table';
            
            // Set initial view
            if (currentView === 'grid') {
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');
                tableViewBtn.classList.remove('active', 'bg-indigo-50', 'dark:bg-indigo-900');
                gridViewBtn.classList.add('active', 'bg-indigo-50', 'dark:bg-indigo-900');
            } else {
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');
                gridViewBtn.classList.remove('active', 'bg-indigo-50', 'dark:bg-indigo-900');
                tableViewBtn.classList.add('active', 'bg-indigo-50', 'dark:bg-indigo-900');
            }

            // Toggle view handlers
            tableViewBtn.addEventListener('click', function() {
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');
                gridViewBtn.classList.remove('active', 'bg-indigo-50', 'dark:bg-indigo-900');
                tableViewBtn.classList.add('active', 'bg-indigo-50', 'dark:bg-indigo-900');
                localStorage.setItem('calendarViewPreference', 'table');
            });

            gridViewBtn.addEventListener('click', function() {
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');
                tableViewBtn.classList.remove('active', 'bg-indigo-50', 'dark:bg-indigo-900');
                gridViewBtn.classList.add('active', 'bg-indigo-50', 'dark:bg-indigo-900');
                localStorage.setItem('calendarViewPreference', 'grid');
            });
        });
    </script>
    @endpush
</x-app-layout>

