<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Student Grades</h1>
                    <p class="text-gray-500 dark:text-gray-300">Grades for: {{ $evaluation->subject->name }} - {{ $evaluation->evaluationType->name }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('create', App\Models\StudentGrade::class)
                <a href="{{ route('evaluations.student_grades.create', $evaluation) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                    <i class="fas fa-plus"></i> Add Grade
                </a>
                @endcan
                <a href="{{ route('evaluations.show', $evaluation) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Back to Evaluation
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <form method="GET" action="{{ route('evaluations.student_grades.index', $evaluation) }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by student name..." class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                        <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"><i class="fas fa-search"></i></span>
                    </div>
                    <select name="status" class="rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">All Statuses</option>
                        <option value="passed" @selected(request('status') == 'passed')>Passed</option>
                        <option value="failed" @selected(request('status') == 'failed')>Failed</option>
                    </select>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">Filter</button>
                        <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" class="w-full text-center px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
        @endif

        <!-- Grades Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                <table class="min-w-full table-fixed bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                            <th class="p-4 text-left">Student</th>
                            <th class="p-4 text-left">Marks Obtained</th>
                            <th class="p-4 text-left">Status</th>
                            <th class="p-4 text-left">Remarks</th>
                            <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">
                                <td class="p-4">
                                    <div class="font-semibold">
                                        {{ $grade->student && $grade->student->user ? $grade->student->user->name : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $grade->student ? $grade->student->admission_number : 'N/A' }}
                                    </div>
                                </td>
                                <td class="p-4 font-semibold">
                                        {{ $grade->marks_obtained }} / {{ $evaluation->total_marks }}
                                </td>
                                <td class="p-4">
                                        @php
                                        $isPassed = $grade->marks_obtained >= $evaluation->passing_marks;
                                        @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $isPassed ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ $isPassed ? 'Passed' : 'Failed' }}
                                        </span>
                                </td>
                                <td class="p-4 text-sm text-gray-600 dark:text-gray-400">{{ $grade->remarks ?? 'N/A' }}</td>
                                <td class="p-4 text-right">
                                    <x-action-icons
                                        :viewRoute="route('evaluations.show', $grade->evaluation)"
                                        :editRoute="route('student_grades.edit', $grade)"
                                        :deleteRoute="route('student_grades.destroy', $grade)"
                                        :canEdit="auth()->user()->can('update', $grade)"
                                        :canDelete="auth()->user()->can('delete', $grade)"
                                        deleteConfirmMessage="Are you sure you want to delete this grade?"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center">
                                    <div class="text-center py-8">
                                        <i class="fas fa-graduation-cap fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No grades found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">Get started by grading a student.</p>
                                        @can('create', App\Models\StudentGrade::class)
                                        <a href="{{ route('evaluations.student_grades.create', $evaluation) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                            <i class="fas fa-plus"></i> Add Grade
                                        </a>
                                        @endcan
                                    </div>
</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                        </div>

        <!-- Pagination -->
        @if ($grades->hasPages())
            <div class="mt-6">
                {{ $grades->links() }}
                </div>
            @endif
    </div>
</x-app-layout>