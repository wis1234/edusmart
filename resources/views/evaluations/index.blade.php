<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-clipboard-check text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Evaluations</h1>
                    <p class="text-gray-500 dark:text-gray-300">Manage all academic evaluations</p>
                </div>
            </div>
            @can('create', App\Models\Evaluation::class)
            <a href="{{ route('evaluations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                <i class="fas fa-plus"></i> Create Evaluation
            </a>
            @endcan
        </div>

        <!-- Search and Filters + View Toggle -->
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1 flex gap-2">
                <!-- <div class="relative w-full max-w-xs">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" placeholder="Search evaluations...">
                    <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                </div> -->
                <select name="subject_id" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @if(request('subject_id')==$subject->id) selected @endif>{{ $subject->name }}</option>
                    @endforeach
                </select>
                <select name="class_room_id" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Classrooms</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @if(request('class_room_id')==$classRoom->id) selected @endif>{{ $classRoom->name }}</option>
                    @endforeach
                </select>
                <select name="evaluation_type_id" class="rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    @foreach($evaluationTypes as $type)
                        <option value="{{ $type->id }}" @if(request('evaluation_type_id')==$type->id) selected @endif>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('evaluations.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
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

        <!-- Table View -->
        <div id="tableView">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <table class="table-fixed w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Subject </th>
                            <th class="px-4 py-3 text-left">Type / Term</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Marks</th>
                            <th class="px-4 py-3 text-left">Teacher</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="font-semibold text-lg">{{ $evaluation->subject->name ?? 'N/A' }}</span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $evaluation->classRoom->name ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="font-semibold">{{ $evaluation->evaluationType->name ?? 'N/A' }}</span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $evaluation->term ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="font-semibold">{{ $evaluation->evaluation_date->format('d M, Y') }}</span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $evaluation->evaluation_date->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold"> {{ $evaluation->passing_marks }} / {{ $evaluation->total_marks }} </span>
                                </td>
                              <td class="px-4 py-3">
    @php
        $teacher = \App\Models\Teacher::find($evaluation->teacher_id);
        $profile = $teacher?->profile;
    @endphp

    @if($profile)
        <div>
            <span class="font-semibold">{{ $profile->teacher_firstname }} {{ $profile->teacher_lastname }}</span>
            @if($profile->specialization)
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $profile->specialization }}</div>
            @endif
        </div>
    @elseif($teacher)
        <div>
            <span class="font-semibold">{{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</span>
            <div class="text-xs text-gray-500 dark:text-gray-400">(No profile)</div>
        </div>
    @else
        N/A
    @endif
</td>

                                <td class="px-4 py-3 text-right">
                                    <x-action-icons
                                        :viewRoute="route('evaluations.show', $evaluation)"
                                        :editRoute="route('evaluations.edit', $evaluation)"
                                        :deleteRoute="route('evaluations.destroy', $evaluation)"
                                        :canView="auth()->user()->can('view', $evaluation)"
                                        :canEdit="auth()->user()->can('update', $evaluation)"
                                        :canDelete="auth()->user()->can('delete', $evaluation)"
                                        deleteConfirmMessage="Are you sure you want to delete this evaluation?"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-clipboard-check fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No evaluations found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by creating your first evaluation</p>
                                        @can('create', App\Models\Evaluation::class)
                                        <a href="{{ route('evaluations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                            <i class="fas fa-plus"></i> Create Evaluation
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($evaluations, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $evaluations->firstItem() }} to {{ $evaluations->lastItem() }} of {{ $evaluations->total() }} evaluations
                    </div>
                    <div>
                        {{ $evaluations->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Grid View (masqué par défaut) -->
        <div id="gridView" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($evaluations as $evaluation)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col gap-3">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow">
                                <i class="fas fa-clipboard-check text-white text-lg"></i>
                            </span>
                            <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $evaluation->subject->name ?? 'N/A' }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $evaluation->classRoom->name ?? 'N/A' }}</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <span class="truncate"><i class="fas fa-tag mr-1"></i> {{ $evaluation->evaluationType->name ?? 'N/A' }}</span>
                            <span class="truncate"><i class="fas fa-calendar mr-1"></i> {{ $evaluation->evaluation_date->format('d M, Y') }}</span>
                            <span class="truncate"><i class="fas fa-user mr-1"></i> 
                                @if($evaluation->teacherProfile)
                                    {{ $evaluation->teacherProfile->teacher_firstname }} {{ $evaluation->teacherProfile->teacher_lastname }}
                                @elseif($evaluation->teacher)
                                    {{ $evaluation->teacher->first_name }} {{ $evaluation->teacher->last_name }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">
                                {{ $evaluation->term ?? 'N/A' }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $evaluation->total_marks }}/{{ $evaluation->passing_marks }}</span>
                        </div>
                        <div class="flex gap-2 mt-3 justify-end">
                            <x-action-icons
                                :viewRoute="route('evaluations.show', $evaluation)"
                                :editRoute="route('evaluations.edit', $evaluation)"
                                :deleteRoute="route('evaluations.destroy', $evaluation)"
                                :canView="auth()->user()->can('view', $evaluation)"
                                :canEdit="auth()->user()->can('update', $evaluation)"
                                :canDelete="auth()->user()->can('delete', $evaluation)"
                                deleteConfirmMessage="Are you sure you want to delete this evaluation?"
                            />
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-8">
                        <i class="fas fa-clipboard-check fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h5 class="text-gray-400 dark:text-gray-500">No evaluations found</h5>
                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by creating your first evaluation</p>
                        @can('create', App\Models\Evaluation::class)
                        <a href="{{ route('evaluations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                            <i class="fas fa-plus"></i> Create Evaluation
                        </a>
                        @endcan
                    </div>
                @endforelse
            </div>
            @if(method_exists($evaluations, 'links'))
                <div class="flex justify-between items-center mt-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Showing {{ $evaluations->firstItem() }} to {{ $evaluations->lastItem() }} of {{ $evaluations->total() }} evaluations
                    </div>
                    <div>
                        {{ $evaluations->appends(request()->except('page'))->links() }}
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
