<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">All Student Grades</h1>
                    <p class="text-gray-500 dark:text-gray-300">Browse all grades recorded across all evaluations.</p>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <form method="GET" action="{{ route('student_grades.index') }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by student name..." class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                        <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500"><i class="fas fa-search"></i></span>
                    </div>
                    <select name="subject_id" class="rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                     <select name="class_room_id" class="rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">All Class Rooms</option>
                        @foreach($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>{{ $classRoom->name }}</option>
                        @endforeach
                    </select>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">Filter</button>
                        <a href="{{ route('student_grades.index') }}" class="w-full text-center px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 transition">Reset</a>
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
                <table class="min-w-full table-auto bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-4 text-left">Student</th>
                            <th class="p-4 text-left">Evaluation</th>
                            <th class="p-4 text-left">Class Room</th>
                            <th class="p-4 text-left">Grade</th>
                            <th class="p-4 text-left">Date</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">
                                <td class="p-4 whitespace-nowrap">
                                    <div class="font-semibold">{{ $grade->student?->user?->name ?? 'Student Deleted' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $grade->student?->admission_number ?? 'N/A' }}</div>
                                </td>
                                 <td class="p-4 whitespace-nowrap">
                                    <div class="font-semibold">{{ $grade->evaluation?->subject?->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $grade->evaluation?->evaluationType?->name ?? 'N/A' }}</div>
                                </td>
                                 <td class="p-4 whitespace-nowrap">
                                    {{ $grade->evaluation?->classRoom?->name ?? 'N/A' }}
                                 </td>
                                <td class="p-4 font-semibold whitespace-nowrap">
                                    {{ $grade->marks_obtained }} / {{ $grade->evaluation?->total_marks ?? 'N/A' }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    {{ $grade->updated_at?->format('d M, Y') }}
                                </td>
                                <td class="p-4 text-right">
                                    @if($grade->evaluation && $grade->student)
                                        <x-action-icons
                                            :viewRoute="route('evaluations.show', $grade->evaluation)"
                                            :editRoute="route('student_grades.edit', $grade)"
                                            :deleteRoute="route('student_grades.destroy', $grade)"
                                            :canEdit="auth()->user()->can('update', $grade)"
                                            :canDelete="auth()->user()->can('delete', $grade)"
                                            deleteConfirmMessage="Are you sure you want to delete this grade?"
                                        />
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Actions disabled</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center">
                                    <div class="py-8">
                                        <i class="fas fa-graduation-cap fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <h5 class="text-gray-400 dark:text-gray-500">No grades found</h5>
                                        <p class="text-gray-400 dark:text-gray-500 mb-0">No grades match your current filters.</p>
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