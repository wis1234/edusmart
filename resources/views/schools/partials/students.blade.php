@php /** @var \App\Models\School $school */ @endphp
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-user-graduate text-indigo-500"></i> Students</h2>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->students->count() }} Students</span>
            @can('create', App\Models\Student::class)
                <a href="{{ route('students.create', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-semibold bg-green-600 text-white hover:bg-green-700 transition">
                    <i class="fas fa-plus"></i> Add Student
                </a>
            @endcan
        </div>
    </div>
    @if($school->students->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Name</th>
                        <th class="px-4 py-2 text-left font-semibold">Class</th>
                        <th class="px-4 py-2 text-left font-semibold">Status</th>
                        <th class="px-4 py-2 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                    @foreach($school->students->take(5) as $student)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                            <td class="px-4 py-2">
                                <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:underline">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                @if($student->classRoom)
                                    <a href="{{ route('class_rooms.show', $student->classRoom) }}" class="text-indigo-600 hover:underline">
                                        {{ $student->classRoom->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Not Assigned</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $student->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <x-action-icons 
                                    :viewRoute="route('students.show', $student)" 
                                    :canEdit="false" 
                                    :canDelete="false" 
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($school->students->count() > 5)
                <div class="text-right mt-3">
                    <a href="{{ route('students.index', ['school_id' => $school->id]) }}" class="text-indigo-600 hover:underline">View All Students</a>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                <i class="fas fa-user-graduate text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No students enrolled yet.</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Start by creating the first student for this school.</p>
            @can('create', App\Models\Student::class)
                <a href="{{ route('students.create', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                    <i class="fas fa-plus"></i> Create First Student
                </a>
            @endcan
        </div>
    @endif
</div> 