<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header modernisé -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                @if($student->profile_photo)
                    <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="Profile Photo" class="w-16 h-16 object-cover rounded-lg border-4 border-indigo-500 shadow-lg">
                @else
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                    </span>
                @endif
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $student->first_name }} {{ $student->last_name }}</h1>
                    <p class="text-gray-500 dark:text-gray-300">{{ $student->classRoom?->name ?? 'No class assigned' }} - {{ ucfirst($student->status) }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('update', $student)
                <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
                <a href="{{ route('students.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Infos principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-info-circle text-indigo-500"></i> Basic Information</h2>
                <div class="mb-2"><span class="text-gray-500">Date of Birth:</span> <span>{{ $student->date_of_birth?->format('M d, Y') }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Gender:</span> <span>{{ ucfirst($student->gender) }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Admission Number:</span> <span>{{ $student->admission_number }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Roll Number:</span> <span>{{ $student->roll_number ?? 'N/A' }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Admission Date:</span> <span>{{ $student->admission_date?->format('M d, Y') }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Academic Year:</span> <span>{{ $student->academic_year }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Status:</span> <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $student->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">{{ ucfirst($student->status) }}</span></div>
            </div>
            <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-address-book text-indigo-500"></i> Contact Information</h2>
                <div class="mb-2"><span class="text-gray-500">Parent:</span> <span class="font-semibold">
                     @php $parent = $users->firstWhere('id', $student->selected_parent_id); @endphp
                                    @if($parent)
                                        {{ $parent->first_name }} {{ $parent->last_name }}
                                    @else
                                        <span class="text-gray-400">Not assigned</span>
                                    @endif
                                </span>
                </div>
                <div class="mb-2"><span class="text-gray-500">Parent Email:</span> <span class="font-semibold">{{ $parent?->email ?? 'N/A' }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Emergency Contact:</span> <span>{{ $student->emergency_contact ?? 'N/A' }}</span></div>
                <div class="mb-2"><span class="text-gray-500">Medical Conditions:</span> <span>{{ $student->medical_conditions ?? 'N/A' }}</span></div>
            </div>
            <!-- School/Class Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><i class="fas fa-university text-indigo-500"></i> School & Class</h2>
                <div class="mb-2"><span class="text-gray-500">School:</span> @if($student->school)<a href="{{ route('schools.show', $student->school) }}" class="text-indigo-600 hover:underline font-semibold">{{ $student->school->name }}</a>@else<span>N/A</span>@endif</div>
                <div class="mb-2"><span class="text-gray-500">Class Room:</span> @if($student->classRoom)<a href="{{ route('class_rooms.show', $student->classRoom) }}" class="text-indigo-600 hover:underline font-semibold">{{ $student->classRoom->name }}</a>@else<span>N/A</span>@endif</div>
                <div class="mb-2"><span class="text-gray-500">Address:</span> <span>{{ $student->address ?? 'N/A' }}</span></div>
            </div>
        </div>

        <!-- Evaluations and Grades -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-clipboard-check text-indigo-500"></i> Evaluations and Grades</h2>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $student->grades->count() }} Evaluations</span>
            </div>
            @if($student->grades->isEmpty())
                <p class="text-gray-400 text-center my-4">No evaluations or grades found for this student.</p>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Evaluation Subject</th>
                            <th class="px-4 py-2 text-left font-semibold">Evaluation Type</th>
                            <th class="px-4 py-2 text-left font-semibold">Marks Obtained</th>
                            <th class="px-4 py-2 text-left font-semibold">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach($student->grades as $grade)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                            <td class="px-4 py-2">{{ $grade->evaluation->subject->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $grade->evaluation->evaluationType->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $grade->marks_obtained }}</td>
                            <td class="px-4 py-2">{{ $grade->remarks ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
