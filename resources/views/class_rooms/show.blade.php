<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-door-open text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Classroom Details</h1>
                    <p class="text-gray-500 dark:text-gray-300">View classroom information</p>
        </div>
    </div>
            <div class="flex gap-2">
                @can('update', $classRoom)
                <a href="{{ route('class_rooms.edit', $classRoom) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-edit"></i> Edit Classroom
                </a>
                @endcan
                <a href="{{ route('class_rooms.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to Classrooms
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900">
                        <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-400"></i>
                    </span>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Basic Information</h2>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Name:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">School:</span>
                        <a href="{{ route('schools.show', $classRoom->school) }}" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            {{ $classRoom->school->name ?? 'N/A' }}
                        </a>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Grade Level:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                            {{ $classRoom->grade_level }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Section:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->section ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Academic Year:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->academic_year ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Capacity:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->capacity }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Room Number:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->room_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Building:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->building ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Floor:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->floor ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $classRoom->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $classRoom->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Days of Week:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ implode(', ', array_map('ucfirst', $classRoom->days_of_week ?? [])) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Start Time:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->start_time ? $classRoom->start_time->format('H:i') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">End Time:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->end_time ? $classRoom->end_time->format('H:i') : 'N/A' }}</span>
                    </div>
            </div>
        </div>

        <!-- Related Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900">
                        <i class="fas fa-users text-green-600 dark:text-green-400"></i>
                    </span>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Related Information</h2>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Teachers ({{ $classRoom->classRoomTeachers->count() }})</h3>
                        <div class="space-y-2">
                    @forelse($classRoom->classRoomTeachers as $assignment)
                                <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            @if($assignment->teacher)
                                {{ $assignment->teacher->first_name ?? $assignment->teacher->teacher_firstname }} {{ $assignment->teacher->last_name ?? $assignment->teacher->teacher_lastname }}
                            @else
                                                <em class="text-gray-500">Teacher data not available</em>
                            @endif
                                        </span>
                            @if($assignment->subject)
                                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">{{ $assignment->subject->name }}</span>
                            @endif
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Year: {{ $assignment->year }}
                                    </div>
                                </div>
                    @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No teachers assigned.</p>
                    @endforelse
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Students ({{ $classRoom->students->count() }})</h3>
                        <div class="space-y-2">
                    @forelse($classRoom->students as $student)
                                <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <a href="{{ route('students.show', $student) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </a>
                                </div>
                    @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No students enrolled.</p>
                    @endforelse
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Evaluations ({{ $classRoom->evaluations->count() }})</h3>
                        <div class="space-y-2">
@forelse($classRoom->evaluations as $evaluation)
                                <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <a href="{{ route('evaluations.show', $evaluation) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $evaluation->term }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $evaluation->evaluation_date ? $evaluation->evaluation_date->format('Y-m-d') : 'N/A' }}
                                    </div>
                                </div>
@empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No evaluations scheduled.</p>
@endforelse
                        </div>
                    </div>
            </div>
        </div>

        <!-- User Tracking -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-history text-blue-600 dark:text-blue-400"></i>
                    </span>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">User Tracking</h2>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Created By:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ $classRoom->createdBy ? $classRoom->createdBy->first_name . ' ' . $classRoom->createdBy->last_name : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Updated By:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ $classRoom->updatedBy ? $classRoom->updatedBy->first_name . ' ' . $classRoom->updatedBy->last_name : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Created At:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Updated At:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classRoom->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Optionally, you can add a message for teachers if they are not assigned, but the policy should already block unauthorized access. --}}
    @if(auth()->user() && auth()->user()->hasRole('enseignant') && !$classRoom->teachers->contains('id', auth()->user()->teacherProfile ? auth()->user()->teacherProfile->id : null))
        <div class="mb-4 px-4 py-3 rounded-lg bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Vous n'êtes pas affecté à cette classe.
        </div>
    @endif
</div>
</x-app-layout>
