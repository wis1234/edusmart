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
                @php $parent = $users->firstWhere('id', $student->selected_parent_id); @endphp
                @if($parent)
                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            @if($parent->profile_photo)
                                <img src="{{ asset('storage/' . $parent->profile_photo) }}" alt="Parent Photo" class="w-10 h-10 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-600">
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600 dark:text-indigo-400 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('parents.show', $parent) }}" class="block group">
                                <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $parent->first_name }} {{ $parent->last_name }}
                                </h3>
                            </a>
                            <div class="mt-1 space-y-1 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-envelope text-gray-400 w-4"></i>
                                    <a href="mailto:{{ $parent->email }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $parent->email }}
                                    </a>
                                </div>
                                @if($parent->phone)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-phone text-gray-400 w-4"></i>
                                    <a href="tel:{{ $parent->phone }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $parent->phone }}
                                    </a>
                                </div>
                                @endif
                                @if($parent->profession)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-briefcase text-gray-400 w-4"></i>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $parent->profession }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex gap-2">
                            <a href="{{ route('parents.show', $parent) }}" class="flex-1 text-center px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-xs font-medium hover:bg-indigo-200 dark:hover:bg-indigo-800 transition-colors">
                                <i class="fas fa-eye mr-1"></i> View Parent
                            </a>
                            @can('update', $parent)
                            <a href="{{ route('parents.edit', $parent) }}" class="flex-1 text-center px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                <i class="fas fa-edit mr-1"></i> Edit Parent
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                @else
                <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg border border-yellow-200 dark:border-yellow-700">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                        <span class="text-yellow-800 dark:text-yellow-200 font-medium">No parent assigned</span>
                    </div>
                    <p class="text-yellow-700 dark:text-yellow-300 text-sm mt-1">This student doesn't have a parent assigned yet.</p>
                </div>
                @endif
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone-alt text-gray-400 w-4"></i>
                        <span class="text-gray-600 dark:text-gray-300">{{ $student->emergency_contact ?? 'No emergency contact' }}</span>
                    </div>
                    @if($student->medical_conditions)
                    <div class="flex items-start gap-2">
                        <i class="fas fa-heartbeat text-gray-400 w-4 mt-1"></i>
                        <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $student->medical_conditions }}</span>
                    </div>
                    @endif
                </div>
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
            @php
                $grades = $student->grades;
                if(auth()->user()->hasRole('enseignant') || auth()->user()->hasRole('teacher')) {
                    $grades = $grades->filter(function($grade) {
                        return $grade->evaluation && $grade->evaluation->teacher_id == auth()->user()->teacherProfile?->id;
                    });
                }
                // Les admins et school admins peuvent voir les évaluations même pour les étudiants inactifs
                if($student->status !== 'active' && !(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->role === 'school_admin')) {
                    $grades = collect();
                }
            @endphp
            @if($grades->isEmpty())
                <p class="text-gray-400 text-center my-4">
                    @if($student->status !== 'active' && !(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->role === 'school_admin'))
                        Cet étudiant n'est pas actif.
                    @else
                        No evaluations or grades found for this student.
                    @endif
                </p>
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
                        @foreach($grades as $grade)
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
