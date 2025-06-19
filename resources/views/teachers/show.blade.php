<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                @if($teacher->profile_photo)
                    <img src="{{ asset('storage/' . $teacher->profile_photo) }}" alt="Profile Photo" class="w-16 h-16 object-cover rounded-lg border-4 border-indigo-500 shadow-lg">
                @else
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </span>
                @endif
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</h1>
                    <p class="text-gray-500 dark:text-gray-300">{{ $teacher->subjects->pluck('name')->join(', ') ?: 'No subject assigned' }} - {{ ucfirst($teacher->status) }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('update', $teacher)
                <a href="{{ route('teachers.edit', $teacher) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Infos principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-info-circle text-indigo-500"></i> Basic Information</h2>
                <div class="mb-2"><span class="text-gray-500">Status:</span> <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $teacher->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">{{ ucfirst($teacher->status) }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Date of Birth:</span> <span>{{ $teacher->date_of_birth?->format('M d, Y') }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Gender:</span> <span>{{ ucfirst($teacher->gender) }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Grade:</span> <span>{{ $teacher->grade }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Speciality:</span> <span>{{ $teacher->speciality }}</span></div>
            </div>
            <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-address-book text-indigo-500"></i> Contact Information</h2>
                <div class="mb-2"><span class="text-gray-500">Email:</span> <span class="font-semibold">{{ $teacher->teacher_email }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Phone:</span> <span class="font-semibold">{{ $teacher->teacher_phone }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Address:</span> <span class="font-semibold">{{ $teacher->address }}</span></div>
            </div>
            <!-- School Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-university text-indigo-500"></i> School</h2>
                @if($teacher->school)
                    <div class="mb-2"><span class="text-gray-500">School:</span> <a href="{{ route('schools.show', $teacher->school) }}" class="text-indigo-600 hover:underline font-semibold">{{ $teacher->school->name }}</a></div>
                    <div class="mb-2"><span class="text-gray-500">Type:</span> <span>{{ ucfirst($teacher->school->type) }}</span></div>
                    <div class="mb-2"><span class="text-gray-500">Country:</span> <span>{{ $teacher->school->country }}</span></div>
                @else
                    <div class="text-gray-400">No school assigned</div>
                @endif
            </div>
        </div>

        <!-- Teaching Assignments -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-chalkboard-teacher text-indigo-500"></i> Teaching Assignments</h2>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $teacher->taughtSubjects->count() }} Subjects</span>
            </div>
            @if($teacher->taughtSubjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Subject</th>
                            <th class="px-4 py-2 text-left font-semibold">Class Room</th>
                            <th class="px-4 py-2 text-left font-semibold">School</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach($teacher->taughtSubjects as $subject)
                            @foreach($teacher->teachingClassRooms->where('pivot.subject_id', $subject->id) as $classRoom)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <td class="px-4 py-2">{{ $subject->name }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('class_rooms.show', $classRoom) }}" class="text-indigo-600 hover:underline">
                                        {{ $classRoom->name }} ({{ $classRoom->grade_level }})
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('schools.show', $classRoom->school) }}" class="text-indigo-600 hover:underline">
                                        {{ $classRoom->school->name }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-400 text-center my-4">No teaching assignments yet.</p>
            @endif
        </div>

        <!-- Recent Evaluations -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-clipboard-check text-indigo-500"></i> Recent Evaluations</h2>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $teacher->conductedEvaluations->count() }} Evaluations</span>
            </div>
            @if($teacher->conductedEvaluations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Date</th>
                            <th class="px-4 py-2 text-left font-semibold">Subject</th>
                            <th class="px-4 py-2 text-left font-semibold">Class Room</th>
                            <th class="px-4 py-2 text-left font-semibold">Type</th>
                            <th class="px-4 py-2 text-left font-semibold">Students</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach($teacher->conductedEvaluations->take(5) as $evaluation)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                            <td class="px-4 py-2">{{ $evaluation->evaluation_date?->format('M d, Y') }}</td>
                            <td class="px-4 py-2">{{ $evaluation->subject->name }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('class_rooms.show', $evaluation->classRoom) }}" class="text-indigo-600 hover:underline">
                                    {{ $evaluation->classRoom->name }}
                                </a>
                            </td>
                            <td class="px-4 py-2">{{ $evaluation->evaluationType->name }}</td>
                            <td class="px-4 py-2">{{ $evaluation->studentGrades->count() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-400 text-center my-4">No evaluations yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
