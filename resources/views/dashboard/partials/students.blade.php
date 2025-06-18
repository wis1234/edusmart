<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">Students Overview</h3>
        <a href="{{ route('students.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All</a>
    </div>

    <!-- Recent Enrollments -->
    <div>
        <h4 class="text-sm font-medium text-gray-700 mb-3">Recent Enrollments</h4>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($recentEnrollments as $student)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random" 
                                             alt="{{ $student->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $student->email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $student->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalStudents }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Active Students</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $students->where('status', 'active')->count() }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Average Age</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ number_format($students->avg('age'), 1) }}
                </dd>
            </div>
        </div>
    </div>
</div> 