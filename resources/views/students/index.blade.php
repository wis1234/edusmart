<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-user-graduate text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Students</h1>
                    <p class="text-gray-500 dark:text-gray-300">Manage your students</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition whitespace-nowrap">
                    <i class="fas fa-plus"></i> Add Student
                </a>
            </div>
        </div>

        <!-- Search and Filters + View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex gap-2 items-end">
                <div class="relative w-full max-w-xs">
                    <label for="status" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">&nbsp;</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="Search by name, class, parent...">
                    <span class="absolute left-3 top-8 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <!-- <div>
                    <label for="status" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" id="status" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                        <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
                        <option value="graduated" @if(request('status')=='graduated') selected @endif>Graduated</option>
                        <option value="transferred" @if(request('status')=='transferred') selected @endif>Transferred</option>
                    </select>
                </div> -->
                <div>
                    <select name="class_room" id="class_room" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                        <option value="">All Classes</option>
                        @isset($classRooms)
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" @if(request('class_room')==$classRoom->id) selected @endif>{{ $classRoom->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <select name="parent" id="parent" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                        <option value="">All Parents</option>
                        @isset($users)
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if(request('parent')==$user->id) selected @endif>{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition whitespace-nowrap">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('students.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition whitespace-nowrap">
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
                                <th class="px-2 py-3 text-left">Class Room</th>
                                <th class="px-2 py-3 text-left">Parent</th>
                                <th class="px-2 py-3 text-left">Gender</th>
                                <th class="px-2 py-3 text-left">Status</th>
                                <th class="px-2 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-2 py-3 max-w-xs truncate">
                                    <div class="flex items-center gap-2">
                                        @if($student->profile_photo)
                                            <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="Profile Photo" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </span>
                                        @endif
                                        <div class="max-w-[120px] truncate">
                                            <span class="font-semibold text-lg truncate">{{ $student->first_name }} {{ $student->last_name }}</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 break-all truncate">{{ $student->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3 max-w-xs truncate">
                                    @if($student->classRoom)
                                        {{ $student->classRoom->name }}
                                    @else
                                        <span class="text-gray-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 max-w-xs truncate">
                                    @php $parent = $users->firstWhere('id', $student->selected_parent_id); @endphp
                                    @if($parent)
                                        {{ $parent->first_name }} {{ $parent->last_name }}
                                    @else
                                        <span class="text-gray-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 capitalize">{{ $student->gender }}</td>
                                <td class="px-2 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $student->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-right">
                                    @php
                                        $canEdit = auth()->user()->can('update', $student);
                                        $canDelete = auth()->user()->can('delete', $student);
                                    @endphp
                                    @include('components.action-icons', [
                                        'viewRoute' => route('students.show', $student),
                                        'editRoute' => $canEdit ? route('students.edit', $student) : null,
                                        'deleteRoute' => $canDelete ? route('students.destroy', $student) : null,
                                        'canEdit' => $canEdit,
                                        'canDelete' => $canDelete,
                                        'deleteConfirmMessage' => 'Are you sure you want to delete this student?'
                                    ])
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-graduate fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No students found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first student</p>
                                        <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                            <i class="fas fa-plus"></i> Add Student
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($students, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                    </div>
                    <div>
                        {{ $students->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
        <!-- Grid View (masqué par défaut) -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($students as $student)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-3">
                        <div class="flex items-center gap-3 mb-2">
                            @if($student->profile_photo)
                                <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="Profile Photo" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-600 shadow">
                            @else
                                <span class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500"></i>
                                </span>
                            @endif
                            <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $student->classRoom->name ?? 'No class assigned' }}</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <span class="truncate"><i class="fas fa-calendar mr-1"></i> {{ $student->date_of_birth?->format('Y-m-d') }}</span>
                            <span class="truncate"><i class="fas fa-venus-mars mr-1"></i> {{ ucfirst($student->gender) }}</span>
                            <span class="truncate"><i class="fas fa-user mr-1"></i>
                                     @php $parent = $users->firstWhere('id', $student->selected_parent_id); @endphp
                                    @if($parent)
                                        {{ $parent->first_name }} {{ $parent->last_name }}
                                    @else
                                        <span class="text-gray-400">Not assigned</span>
                                    @endif                           
                             </span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $student->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                        <div class="flex gap-2 mt-3 justify-end">
                            @php
                                $canEdit = auth()->user()->can('update', $student);
                                $canDelete = auth()->user()->can('delete', $student);
                            @endphp
                            @include('components.action-icons', [
                                'viewRoute' => route('students.show', $student),
                                'editRoute' => $canEdit ? route('students.edit', $student) : null,
                                'deleteRoute' => $canDelete ? route('students.destroy', $student) : null,
                                'canEdit' => $canEdit,
                                'canDelete' => $canDelete,
                                'deleteConfirmMessage' => 'Are you sure you want to delete this student?'
                            ])
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-8">
                        <i class="fas fa-user-graduate fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h5 class="text-gray-400 dark:text-gray-500">No students found</h5>
                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by adding your first student</p>
                        <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                            <i class="fas fa-plus"></i> Add Student
                        </a>
                    </div>
                @endforelse
            </div>
            @if(method_exists($students, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                    </div>
                    <div>
                        {{ $students->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
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
</x-app-layout>
