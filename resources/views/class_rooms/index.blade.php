<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-door-open text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Classrooms</h1>
                    <p class="text-gray-500 dark:text-gray-300">Manage your academic classrooms</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('create', App\Models\ClassRoom::class)
                <a href="{{ route('class_rooms.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition whitespace-nowrap">
                    <i class="fas fa-plus"></i> Add Classroom
                </a>
                @endcan
                @can('create', App\Models\Subject::class)
                <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-700 transition whitespace-nowrap">
                    <i class="fas fa-book"></i> Add Subject
                </a>
                @endcan
                @can('viewAny', App\Models\Subject::class)
                <a href="{{ route('subjects.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-purple-600 text-white font-semibold shadow hover:bg-purple-700 transition whitespace-nowrap">
                    <i class="fas fa-list"></i> View Subjects
                </a>
                @endcan
            </div>
        </div>

        <!-- Search and Filters + View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex gap-2">
                <!-- <div class="relative w-full max-w-xs">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="Search classrooms...">
                    <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                </div> -->
                <select name="school" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @if(request('school')==$school->id) selected @endif>{{ $school->name }}</option>
                    @endforeach
                </select>
                <!-- <select name="grade_level" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Grades</option>
                    <option value="1" @if(request('grade_level')=='1') selected @endif>Grade 1</option>
                    <option value="2" @if(request('grade_level')=='2') selected @endif>Grade 2</option>
                    <option value="3" @if(request('grade_level')=='3') selected @endif>Grade 3</option>
                    <option value="4" @if(request('grade_level')=='4') selected @endif>Grade 4</option>
                    <option value="5" @if(request('grade_level')=='5') selected @endif>Grade 5</option>
                    <option value="6" @if(request('grade_level')=='6') selected @endif>Grade 6</option>
                    <option value="7" @if(request('grade_level')=='7') selected @endif>Grade 7</option>
                    <option value="8" @if(request('grade_level')=='8') selected @endif>Grade 8</option>
                    <option value="9" @if(request('grade_level')=='9') selected @endif>Grade 9</option>
                    <option value="10" @if(request('grade_level')=='10') selected @endif>Grade 10</option>
                    <option value="11" @if(request('grade_level')=='11') selected @endif>Grade 11</option>
                    <option value="12" @if(request('grade_level')=='12') selected @endif>Grade 12</option>
                </select> -->
                <select name="academic_year" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Years</option>
                    <option value="2023-2024" @if(request('academic_year')=='2023-2024') selected @endif>2023-2024</option>
                    <option value="2024-2025" @if(request('academic_year')=='2024-2025') selected @endif>2024-2025</option>
                    <option value="2025-2026" @if(request('academic_year')=='2025-2026') selected @endif>2025-2026</option>
                </select>
                <select name="status" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="1" @if(request('status')=='1') selected @endif>Active</option>
                    <option value="0" @if(request('status')=='0') selected @endif>Inactive</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('class_rooms.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
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
                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Classroom</th>
                                <th class="px-4 py-3 text-left">School</th>
                                <!-- <th class="px-4 py-3 text-left">Grade Level</th> -->
                                <th class="px-4 py-3 text-left">Section</th>
                                <th class="px-4 py-3 text-left">Academic Year</th>
                                <th class="px-4 py-3 text-left">Capacity</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classRooms as $classRoom)
                                <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                    <td class="px-4 py-3">
                                        <div>
                                            <span class="font-semibold text-lg">{{ $classRoom->name }}</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $classRoom->students->count() }} students</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 max-w-[120px] truncate">
                                        <span class="truncate">{{ $classRoom->school->name ?? 'N/A' }}</span>
                                    </td>
                                    <!-- <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                            Grade {{ $classRoom->grade_level }}
                                        </span>
                                    </td> -->
                                    <td class="px-4 py-3">{{ $classRoom->section ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $classRoom->academic_year ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">{{ $classRoom->capacity }}</span>
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min(100, ($classRoom->students->count() / $classRoom->capacity) * 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $classRoom->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                            {{ $classRoom->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <x-action-icons
                                            :viewRoute="route('class_rooms.show', $classRoom)"
                                            :editRoute="route('class_rooms.edit', $classRoom)"
                                            :deleteRoute="route('class_rooms.destroy', $classRoom)"
                                            :canEdit="auth()->user() && auth()->user()->can('update', $classRoom)"
                                            :canDelete="auth()->user() && auth()->user()->can('delete', $classRoom)"
                                            deleteConfirmMessage="Are you sure you want to delete this classroom?"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center">
                                        <div class="text-center py-4">
                                            <i class="fas fa-door-open fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                            @if(auth()->user() && auth()->user()->hasRole('enseignant'))
                                                <h5 class="text-gray-400 dark:text-gray-500">You have no assigned classroom at the moment.</h5>
                                                <p class="text-gray-400 dark:text-gray-500 mb-0">Please contact the administration to get an assignment.</p>
                                            @else
                                                <h5 class="text-gray-400 dark:text-gray-500">No classrooms found</h5>
                                                <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first classroom</p>
                                                @can('create', App\Models\ClassRoom::class)
                                                <a href="{{ route('class_rooms.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                                    <i class="fas fa-plus"></i> Add Classroom
                                                </a>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($classRooms, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $classRooms->firstItem() }} to {{ $classRooms->lastItem() }} of {{ $classRooms->total() }} classrooms
                    </div>
                    <div>
                        {{ $classRooms->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Grid View (masqué par défaut) -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($classRooms as $classRoom)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-3">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow">
                                <i class="fas fa-door-open text-white text-lg"></i>
                            </span>
                            <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $classRoom->name }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $classRoom->school->name ?? 'No School' }}</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <span class="truncate"><i class="fas fa-graduation-cap mr-1"></i> Grade {{ $classRoom->grade_level }}</span>
                            <span class="truncate"><i class="fas fa-users mr-1"></i> {{ $classRoom->students->count() }}/{{ $classRoom->capacity }} students</span>
                            <span class="truncate"><i class="fas fa-calendar mr-1"></i> {{ $classRoom->academic_year ?? 'N/A' }}</span>
                            <span class="truncate"><i class="fas fa-chalkboard-teacher mr-1"></i> {{ $classRoom->teachers->count() }} teachers</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $classRoom->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $classRoom->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $classRoom->section ?? 'No Section' }}</span>
                        </div>
                        <div class="flex gap-2 mt-3 justify-end">
                            <x-action-icons
                                :viewRoute="route('class_rooms.show', $classRoom)"
                                :editRoute="route('class_rooms.edit', $classRoom)"
                                :deleteRoute="route('class_rooms.destroy', $classRoom)"
                                :canEdit="auth()->user() && auth()->user()->can('update', $classRoom)"
                                :canDelete="auth()->user() && auth()->user()->can('delete', $classRoom)"
                                deleteConfirmMessage="Are you sure you want to delete this classroom?"
                            />
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-8">
                        <i class="fas fa-door-open fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h5 class="text-gray-400 dark:text-gray-500">No classrooms found</h5>
                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first classroom</p>
                        @can('create', App\Models\ClassRoom::class)
                        <a href="{{ route('class_rooms.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                            <i class="fas fa-plus"></i> Add Classroom
                        </a>
                        @endcan
                    </div>
                @endforelse
            </div>
            @if(method_exists($classRooms, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $classRooms->firstItem() }} to {{ $classRooms->lastItem() }} of {{ $classRooms->total() }} classrooms
                    </div>
                    <div>
                        {{ $classRooms->appends(request()->except('page'))->links() }}
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
