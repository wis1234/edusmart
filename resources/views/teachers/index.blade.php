<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Teachers</h1>
                    <p class="text-gray-500 dark:text-gray-300">Manage your teaching staff</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('create', App\Models\Teacher::class)
                <a href="{{ route('teachers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition whitespace-nowrap">
                    <i class="fas fa-plus"></i> Add Teacher
                </a>
                @endcan
                @can('create', App\Models\Evaluation::class)
                <a href="{{ route('evaluations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition whitespace-nowrap">
                    <i class="fas fa-clipboard-check"></i> Create Evaluation
                </a>
                @endcan
                @can('viewAny', App\Models\Evaluation::class)
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition whitespace-nowrap">
                    <i class="fas fa-list"></i> View Evaluations
                </a>
                @endcan
            </div>
        </div>

        <!-- Search and Filters + View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex gap-2 items-end">
                <div class="relative w-full max-w-xs">
                    <label for="status" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">&nbsp;</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="Search by name, email, phone, grade, speciality...">
                    <span class="absolute left-3 top-8 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <div>
                    <select name="status" id="status" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                        <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
                        <option value="on_leave" @if(request('status')=='on_leave') selected @endif>On Leave</option>
                    </select>
                </div>
                <div>
                    <select name="school" id="school" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                        <option value="">All Schools</option>
                        @isset($schools)
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" @if(request('school')==$school->id) selected @endif>{{ $school->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <select name="subject" id="subject" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                        <option value="">All Subjects</option>
                        @isset($subjects)
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @if(request('subject')==$subject->id) selected @endif>{{ $subject->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition whitespace-nowrap">
                </i>Filter
            </button>
            <a href="{{ route('teachers.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition whitespace-nowrap">
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
                                <th class="px-2 py-3 text-left">Name</th>
                                <th class="px-2 py-3 text-left">School</th>
                                <th class="px-2 py-3 text-left">Class Room</th>
                                <th class="px-2 py-3 text-left">Phone</th>
                                <th class="px-2 py-3 text-left">Status</th>
                                <th class="px-2 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-2 py-3 max-w-xs truncate">
                                    <div class="flex items-center gap-2">
                                        @if($teacher->profile_photo)
                                            <img src="{{ asset('storage/' . $teacher->profile_photo) }}" alt="Profile Photo" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </span>
                                        @endif
                                        <div class="max-w-[120px] truncate">
                                            <span class="font-semibold text-lg truncate">{{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 break-all truncate">{{ $teacher->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3 max-w-xs truncate">
                                    @if($teacher->teachingClassRooms->isNotEmpty())
                                        {{ $teacher->teachingClassRooms->first()->school->name }}
                                    @else
                                        <span class="text-gray-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 max-w-xs truncate">
                                    @if($teacher->teachingClassRooms->isNotEmpty())
                                        {{ $teacher->teachingClassRooms->first()->name }}
                                    @else
                                        <span class="text-gray-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 max-w-xs truncate">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-phone text-gray-400"></i>
                                        <span class="truncate">{{ $teacher->phone }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $teacher->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-right">
                                    <x-action-icons
                                        :viewRoute="route('teachers.show', $teacher)"
                                        :editRoute="route('teachers.edit', $teacher)"
                                        :deleteRoute="route('teachers.destroy', $teacher)"
                                        :canEdit="auth()->user() && auth()->user()->can('update', $teacher)"
                                        :canDelete="auth()->user() && auth()->user()->can('delete', $teacher)"
                                        deleteConfirmMessage="Are you sure you want to delete this teacher?"
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-chalkboard-teacher fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No teachers found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first teacher</p>
                                        @can('create', App\Models\Teacher::class)
                                        <a href="{{ route('teachers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                            <i class="fas fa-plus"></i> Add Teacher
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($teachers, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $teachers->firstItem() }} to {{ $teachers->lastItem() }} of {{ $teachers->total() }} teachers
                    </div>
                    <div>
                        {{ $teachers->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Grid View (hidden by default) -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @forelse ($teachers as $teacher)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex flex-col gap-2">
                        <div class="flex items-center gap-2 mb-2">
                            @if($teacher->profile_photo)
                                <img src="{{ asset('storage/' . $teacher->profile_photo) }}" alt="Profile Photo" class="w-10 h-10 rounded-full object-cover shadow">
                            @else
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200">
                                    <i class="fas fa-user text-gray-500 text-lg"></i>
                                </span>
                            @endif
                            <span class="font-bold text-lg text-gray-900 dark:text-white max-w-[120px] truncate">{{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 break-all truncate max-w-xs">{{ $teacher->email }}</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <span class="truncate max-w-xs"><i class="fas fa-school mr-1"></i> @if($teacher->teachingClassRooms->isNotEmpty()){{ $teacher->teachingClassRooms->first()->school->name }}@else Not assigned @endif</span>
                            <span class="truncate max-w-xs"><i class="fas fa-chalkboard mr-1"></i> @if($teacher->teachingClassRooms->isNotEmpty()){{ $teacher->teachingClassRooms->first()->name }}@else Not assigned @endif</span>
                            <span class="truncate max-w-xs"><i class="fas fa-phone mr-1"></i> {{ $teacher->phone }}</span>
                        </div>
                        <div class="flex items-center gap-1 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $teacher->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ ucfirst($teacher->status) }}
                            </span>
                        </div>
                        <div class="flex gap-1 mt-3 justify-end">
                            <x-action-icons
                                :viewRoute="route('teachers.show', $teacher)"
                                :editRoute="route('teachers.edit', $teacher)"
                                :deleteRoute="route('teachers.destroy', $teacher)"
                                :canEdit="auth()->user() && auth()->user()->can('update', $teacher)"
                                :canDelete="auth()->user() && auth()->user()->can('delete', $teacher)"
                                deleteConfirmMessage="Are you sure you want to delete this teacher?"
                            />
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-8">
                        <i class="fas fa-chalkboard-teacher fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h5 class="text-gray-400 dark:text-gray-500">No teachers found</h5>
                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first teacher</p>
                        @can('create', App\Models\Teacher::class)
                        <a href="{{ route('teachers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                            <i class="fas fa-plus"></i> Add Teacher
                        </a>
                        @endcan
                    </div>
                @endforelse
            </div>
            @if(method_exists($teachers, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $teachers->firstItem() }} to {{ $teachers->lastItem() }} of {{ $teachers->total() }} teachers
                    </div>
                    <div>
                        {{ $teachers->appends(request()->except('page'))->links() }}
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
    @push('styles')
    <style>
        /* Ombre uniquement sur le haut du tableau et des cards grid */
        .table thead, thead.bg-gray-100 {
            box-shadow: 0 -6px 16px -6px rgba(99,102,241,0.13);
            z-index: 2;
            position: relative;
        }
        .teacher-card-header {
            box-shadow: 0 -6px 16px -6px rgba(99,102,241,0.13);
            border-radius: 0.75rem 0.75rem 0 0;
            background: inherit;
        }
    </style>
    @endpush
</x-app-layout>
