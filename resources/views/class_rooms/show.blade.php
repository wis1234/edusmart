<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 shadow-lg">
                        <i class="fas fa-door-open text-white text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $classRoom->name }}</h1>
                        <p class="text-gray-600 dark:text-gray-300">Grade {{ $classRoom->grade_level }} - Section {{ $classRoom->section ?? 'N/A' }} | {{ $classRoom->school->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @can('update', $classRoom)
                    <a href="{{ route('class_rooms.edit', $classRoom) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    @endcan
                    <a href="{{ route('class_rooms.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Students</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $classRoom->students->count() }}/{{ $classRoom->capacity }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Teachers</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $classRoom->classRoomTeachers->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Evaluations</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $classRoom->evaluations->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Subjects</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $classRoom->classRoomTeachers->unique('subject_id')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Info & Teachers -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Class Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-info-circle text-indigo-500"></i>
                            Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Academic Year</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $classRoom->academic_year ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Room</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $classRoom->room_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Building</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $classRoom->building ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Floor</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $classRoom->floor ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classRoom->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $classRoom->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Teachers -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-chalkboard-teacher text-green-500"></i>
                            Teachers ({{ $classRoom->classRoomTeachers->count() }})
                        </h3>
                        <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 text-xs rounded-lg font-medium hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($classRoom->classRoomTeachers->take(5) as $assignment)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-600 dark:text-indigo-400 text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        @if($assignment->teacher)
                                            {{ $assignment->teacher->first_name ?? $assignment->teacher->teacher_firstname }} {{ $assignment->teacher->last_name ?? $assignment->teacher->teacher_lastname }}
                                        @else
                                            <em class="text-gray-500">Data missing</em>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $assignment->subject ? $assignment->subject->name : 'Unknown subject' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No teachers assigned</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Students Table & Evaluations -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-users text-blue-500"></i>
                            Students ({{ $classRoom->students->count() }})
                        </h3>
                        <div class="flex items-center gap-2">
                             <a href="{{ route('students.index', ['class_room' => $classRoom->id]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 text-xs rounded-lg font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                            @can('create', App\Models\Student::class)
                            <a href="{{ route('students.create', ['class_room_id' => $classRoom->id]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus"></i>
                                Add Student
                            </a>
                            @endcan
                        </div>
                    </div>
                    
                    @if($classRoom->students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Contact
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="students-table-body">
                                    @foreach($classRoom->students->take(5) as $student)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors student-row">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                            <i class="fas fa-user-graduate text-blue-600 dark:text-blue-400"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $student->first_name }} {{ $student->last_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            ID: {{ $student->id }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $student->email ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->phone ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                    {{ ucfirst($student->status ?? 'inactive') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('students.show', $student) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @can('update', $student)
                                                    <a href="{{ route('students.edit', $student) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <i class="fas fa-users text-4xl"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No students</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start by adding students to this class.</p>
                            @can('create', App\Models\Student::class)
                            <div class="mt-6">
                                <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus"></i>
                                    Add Student
                                </a>
                            </div>
                            @endcan
                        </div>
                    @endif
                </div>

                <!-- Evaluations -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-yellow-500"></i>
                            Recent Evaluations ({{ $classRoom->evaluations->count() }})
                        </h3>
                         <a href="{{ route('evaluations.index', ['class_room_id' => $classRoom->id]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 text-xs rounded-lg font-medium hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @if($classRoom->evaluations->count() > 0)
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($classRoom->evaluations->take(6) as $evaluation)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $evaluation->subject->name ?? 'Unknown subject' }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $evaluation->evaluationType->name ?? 'Unknown type' }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $evaluation->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <a href="{{ route('evaluations.show', $evaluation) }}" class="ml-4 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                     <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <i class="fas fa-clipboard-list text-4xl"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Evaluations</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No evaluations found for this class yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subjects (from classRoomTeachers pivot) - FULL WIDTH -->
        <div class="max-w-7xl mx-auto mt-10">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-book text-purple-500"></i>
                        Subjects ({{ $classRoom->classRoomTeachers->unique('subject_id')->count() }})
                    </h3>
                    <a href="{{ route('subjects.index', ['class_room_id' => $classRoom->id]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 text-xs rounded-lg font-medium hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @php
                    $subjectsInClass = $classRoom->classRoomTeachers->whereNotNull('subject_id')->unique('subject_id')->map(function($assignment) { return $assignment->subject; })->filter();
                @endphp
                @if($subjectsInClass->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Name</th>
                                <th class="px-4 py-2 text-left font-semibold">Code</th>
                                <th class="px-4 py-2 text-left font-semibold">Level</th>
                                <th class="px-4 py-2 text-left font-semibold">Hours/Week</th>
                                <th class="px-4 py-2 text-left font-semibold">Status</th>
                                <th class="px-4 py-2 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @foreach($subjectsInClass->take(5) as $subject)
                                <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('subjects.show', $subject) }}" class="text-indigo-600 hover:underline font-semibold">
                                            {{ $subject->name }}
                                        </a>
                                        @if($subject->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">{{ $subject->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            {{ $subject->code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($subject->level)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                                {{ $subject->level }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $subject->hours_per_week ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $subject->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                            {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right">
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
                            @endforeach
                        </tbody>
                    </table>
                    @if($subjectsInClass->count() > 5)
                        <div class="text-right mt-3">
                            <a href="{{ route('subjects.index', ['class_room_id' => $classRoom->id]) }}" class="text-indigo-600 hover:underline">View All Subjects</a>
                        </div>
                    @endif
                </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-book fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-400 dark:text-gray-500 font-semibold">No subjects assigned yet.</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Start by assigning subjects to this class.</p>
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->user() && auth()->user()->hasRole('enseignant') && !$classRoom->classRoomTeachers->where('teacher_id', auth()->user()->teacherProfile ? auth()->user()->teacherProfile->id : null)->count())
            <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                    <p class="text-yellow-800 dark:text-yellow-200">You are not assigned to this class.</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
