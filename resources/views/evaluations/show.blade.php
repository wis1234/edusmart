<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-clipboard-check text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Evaluation Details</h1>
                    <p class="text-gray-500 dark:text-gray-300">Detailed view of the academic evaluation</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('update', $evaluation)
                <a href="{{ route('evaluations.edit', $evaluation) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-edit"></i> Edit Evaluation
                </a>
                @endcan
                <a href="{{ route('evaluations.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to Evaluations
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900">
                        <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-400"></i>
                    </span>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Evaluation Information</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Subject:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $evaluation->subject->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Classroom:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $evaluation->classRoom->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">School:</span>
                        <span class="font-bold text-gray-900 dark:text-white">
                            {{ $evaluation->subject->school->name ?? ($evaluation->classRoom->school->name ?? 'N/A') }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Evaluation Type:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $evaluation->evaluationType->name ?? 'N/A' }}</span>
                    </div>
                    @php
    $teacher = \App\Models\Teacher::where('user_id', $evaluation->teacher_id)->first();
    $teacherUser = $evaluation->teacher;
@endphp

<div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
    <span class="font-semibold text-gray-600 dark:text-gray-400">Teacher:</span>
    <span class="font-bold text-gray-900 dark:text-white">
        @if($teacherUser)
            {{ $teacherUser->first_name }} {{ $teacherUser->last_name }}
            @if($teacher && $teacher->specialization)
                <span class="text-xs text-gray-500">({{ $teacher->specialization }})</span>
            @endif
        @else
            N/A
        @endif
    </span>
</div>

<div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
    <span class="font-semibold text-gray-600 dark:text-gray-400">Teacher Email:</span>
    <span class="font-bold text-gray-900 dark:text-white">{{ $teacherUser->email ?? 'N/A' }}</span>
</div>

                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Academic Year:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $evaluation->academic_year }}</span>
                    </div>
                     <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Term:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $evaluation->term }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Evaluation Date:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $evaluation->evaluation_date->format('d M, Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Total Marks:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $evaluation->total_marks }}</span>
                    </div>
                     <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Passing Marks:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $evaluation->passing_marks }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-gray-600 dark:text-gray-400">Pass Rate:</span>
                        <span class="font-bold text-gray-900 dark:text-white">
                            @php
                                $passRate = $evaluation->total_marks > 0 ? round(($evaluation->passing_marks / $evaluation->total_marks) * 100, 1) : 0;
                            @endphp
                            {{ $passRate }}%
                        </span>
                    </div>
                </div>
                @if($evaluation->notes)
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-600 dark:text-gray-400 mb-2">Notes</h3>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-sm">
                        {{ $evaluation->notes }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions and Grades -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900">
                            <i class="fas fa-marker text-green-600 dark:text-green-400"></i>
                        </span>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Grading</h2>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition">
                            <i class="fas fa-list-ol fa-fw"></i> View Grades
                        </a>
                        <a href="{{ route('evaluations.student_grades.create', $evaluation) }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-700 transition">
                           <i class="fas fa-plus-circle fa-fw"></i> Grade a Student
                        </a>
                    </div>
                </div>
                
                <!-- Student Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900">
                            <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400"></i>
                        </span>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Statistics</h2>
                    </div>
                    <div class="text-sm space-y-2">
                        @php
                            $totalStudents = $evaluation->studentGrades()->count();
                            $gradedStudents = $evaluation->studentGrades()->whereNotNull('marks_obtained')->count();
                            $passedStudents = $evaluation->studentGrades()->where('marks_obtained', '>=', $evaluation->passing_marks)->count();
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total Students:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $totalStudents }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Graded Students:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $gradedStudents }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Passed Students:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $passedStudents }}</span>
                        </div>
                        @if($gradedStudents > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Pass Rate:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ round(($passedStudents / $gradedStudents) * 100, 1) }}%</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900">
                            <i class="fas fa-history text-blue-600 dark:text-blue-400"></i>
                        </span>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Tracking</h2>
                    </div>
                    <div class="text-sm space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Created By:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                @if($evaluation->creator)
                                    {{ $evaluation->creator->first_name }} {{ $evaluation->creator->last_name }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                         <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Created At:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $evaluation->created_at->format('d M, Y H:i') }}</span>
                        </div>
                         <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $evaluation->updated_at->format('d M, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>