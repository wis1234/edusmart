@php /** @var \App\Models\School $school */ @endphp
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-chalkboard-teacher text-indigo-500"></i> Teachers</h2>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->teachers->count() }} Teachers</span>
            <a href="{{ route('teachers.index', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                <i class="fas fa-list"></i> View All
            </a>
            @can('create', App\Models\Teacher::class)
                <a href="{{ route('teachers.create', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-semibold bg-green-600 text-white hover:bg-green-700 transition">
                    <i class="fas fa-plus"></i> Add Teacher
                </a>
            @endcan
        </div>
    </div>
    @if($school->teachers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Name</th>
                        <th class="px-4 py-2 text-left font-semibold">Subjects</th>
                        <th class="px-4 py-2 text-left font-semibold">Status</th>
                        <th class="px-4 py-2 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                    @foreach($school->teachers->take(5) as $teacher)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                            <td class="px-4 py-2">
                                <a href="{{ route('teachers.show', $teacher) }}" class="text-indigo-600 hover:underline">
                                    {{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $subjects = $teacher->classRoomTeachers
                                        ->filter(function($assignment) use ($school) {
                                            return $assignment->classRoom && $assignment->classRoom->school_id == $school->id;
                                        })
                                        ->map(function($assignment) {
                                            return $assignment->subject->name ?? null;
                                        })
                                        ->filter()
                                        ->unique()
                                        ->join(', ');
                                @endphp
                                {{ $subjects ?: '—' }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $teacher->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <x-action-icons 
                                    :viewRoute="route('teachers.show', $teacher)" 
                                    :canEdit="false" 
                                    :canDelete="false" 
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($school->teachers->count() > 5)
                <!-- Removed conditional View All link, now always in header -->
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                <i class="fas fa-chalkboard-teacher text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No teachers assigned yet.</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Start by creating the first teacher for this school.</p>
            @can('create', App\Models\Teacher::class)
                <a href="{{ route('teachers.create', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                    <i class="fas fa-plus"></i> Create First Teacher
                </a>
            @endcan
        </div>
    @endif
</div> 