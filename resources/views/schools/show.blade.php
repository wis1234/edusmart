<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                        @if($school->logo)
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="w-16 h-16 object-contain rounded-lg border-4 border-indigo-500 shadow-lg">
                        @else
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-university text-white text-3xl"></i>
                    </span>
                        @endif
                        <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $school->name }}</h1>
                    <p class="text-gray-500 dark:text-gray-300">{{ ucfirst($school->type) }} - {{ ucfirst($school->status) }}</p>
                        </div>
                    </div>
            <div class="flex gap-2">
                        @can('update', $school)
                <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                <a href="{{ route('schools.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

        <!-- Infos principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-info-circle text-indigo-500"></i> Basic Information</h2>
                <div class="mb-2"><span class="text-gray-500">Type:</span> <span class="font-semibold">{{ ucfirst($school->type) }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Status:</span> <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $school->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">{{ ucfirst($school->status) }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Register on:</span> <span>{{ $school->created_at?->format('M d, Y') }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Principal:</span> <span>{{ $school->principal_name}}</span></div>

                <div class="mb-2"><span class="text-gray-500">Description:</span> <span>{{ $school->description ?: 'No description available' }}</span></div>
            </div>
                    <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-address-book text-indigo-500"></i> Contact Information</h2>
                <div class="mb-2"><span class="text-gray-500">Email:</span> <span class="font-semibold">{{ $school->email }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Phone:</span> <span class="font-semibold">{{ $school->phone }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Website:</span> @if($school->website)<a href="{{ $school->website }}" target="_blank" class="text-indigo-600 hover:underline">{{ $school->website }}</a>@else<span>No website available</span>@endif</div>
                    </div>
                    <!-- Address Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt text-indigo-500"></i> Address Information</h2>
                <div class="mb-2"><span class="text-gray-500">Address:</span> <span class="font-semibold">{{ $school->address }}</span></div>
                <div class="mb-2"><span class="text-gray-500">City:</span> <span class="font-semibold">{{ $school->city }}</span></div>
                <div class="mb-2"><span class="text-gray-500">State:</span> <span class="font-semibold">{{ $school->state }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Country:</span> <span class="font-semibold">{{ $school->country }}</span></div>
                        </div>
                    </div>

        <!-- Teachers & Classrooms -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Teachers -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-chalkboard-teacher text-indigo-500"></i> Teachers</h2>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->teachers->count() }} Teachers</span>
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
                                <td class="px-4 py-2">{{ $teacher->subjects->pluck('name')->join(', ') }}</td>
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
                    <div class="text-right mt-3">
                        <a href="{{ route('teachers.index', ['school_id' => $school->id]) }}" class="text-indigo-600 hover:underline">View All Teachers</a>
                                    </div>
                                    @endif
                                </div>
                                @else
                <p class="text-gray-400 text-center my-4">No teachers assigned yet.</p>
                                @endif
                            </div>
                    <!-- Classrooms -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-door-open text-indigo-500"></i> Classrooms</h2>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->classRooms->count() }} Classrooms</span>
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
                    <div class="text-right mt-3">
                        <a href="{{ route('class_rooms.index', ['school_id' => $school->id]) }}" class="text-indigo-600 hover:underline">View All Classrooms</a>
                                    </div>
                                    @endif
                                </div>
                                @else
                <p class="text-gray-400 text-center my-4">No classrooms created yet.</p>
                                @endif
                        </div>
                    </div>

                    <!-- Students -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-user-graduate text-indigo-500"></i> Students</h2>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->students->count() }} Students</span>
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
            <p class="text-gray-400 text-center my-4">No students enrolled yet.</p>
                                @endif
        </div>
    </div>
</x-app-layout>
