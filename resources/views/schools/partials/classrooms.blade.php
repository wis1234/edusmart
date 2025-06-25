@php /** @var \App\Models\School $school */ @endphp
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-door-open text-indigo-500"></i> Classrooms</h2>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->classRooms->count() }} Classrooms</span>
            <a href="{{ route('class_rooms.index', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                <i class="fas fa-list"></i> View All
            </a>
            @can('create', App\Models\ClassRoom::class)
                <a href="{{ route('class_rooms.create') }}" class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-semibold bg-green-600 text-white hover:bg-green-700 transition">
                    <i class="fas fa-plus"></i> Add Classroom
                </a>
            @endcan
        </div>
    </div>
    @if($school->classRooms->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Name</th>
                        <th class="px-4 py-2 text-left font-semibold">Grade Level</th>
                        <th class="px-4 py-2 text-left font-semibold">Students</th>
                        <th class="px-4 py-2 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                    @foreach($school->classRooms->take(5) as $classRoom)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                            <td class="px-4 py-2">
                                <a href="{{ route('class_rooms.show', $classRoom) }}" class="text-indigo-600 hover:underline">
                                    {{ $classRoom->name }}
                                </a>
                            </td>
                            <td class="px-4 py-2">{{ $classRoom->grade_level }}</td>
                            <td class="px-4 py-2">{{ $classRoom->students->count() }}</td>
                            <td class="px-4 py-2 text-right">
                                <x-action-icons 
                                    :viewRoute="route('class_rooms.show', $classRoom)" 
                                    :canEdit="false" 
                                    :canDelete="false" 
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($school->classRooms->count() > 5)
                <!-- Removed conditional View All link, now always in header -->
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                <i class="fas fa-door-open text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No classrooms created yet.</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Start by creating the first classroom for this school.</p>
            @can('create', App\Models\ClassRoom::class)
                <a href="{{ route('class_rooms.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                    <i class="fas fa-plus"></i> Create First Classroom
                </a>
            @endcan
        </div>
    @endif
</div> 