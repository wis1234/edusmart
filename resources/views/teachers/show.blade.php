@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Teacher Details</h1>
        <div class="space-x-2">
            @can('update', $teacher)
            <a href="{{ route('teachers.edit', $teacher) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit Teacher</a>
            @endcan
            <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Teachers</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Basic Information</h2>
            <div class="space-y-3">
                @if($teacher->profile_photo)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $teacher->profile_photo) }}" alt="Profile Photo" 
                         class="w-32 h-32 object-cover rounded-full border-4 border-blue-200">
                </div>
                @endif
                <div>
                    <label class="font-semibold">Name:</label>
                    <p>{{ $teacher->name }}</p>
                </div>
                <div>
                    <label class="font-semibold">Email:</label>
                    <p>{{ $teacher->email }}</p>
                </div>
                <div>
                    <label class="font-semibold">Phone:</label>
                    <p>{{ $teacher->phone }}</p>
                </div>
                <div>
                    <label class="font-semibold">Date of Birth:</label>
                    <p>{{ $teacher->date_of_birth?->format('M d, Y') }}</p>
                </div>
                <div>
                    <label class="font-semibold">Gender:</label>
                    <p class="capitalize">{{ $teacher->gender }}</p>
                </div>
                <div>
                    <label class="font-semibold">Status:</label>
                    <span class="px-2 py-1 text-sm rounded-full {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($teacher->status) }}
                    </span>
                </div>
                <div>
                    <label class="font-semibold">Address:</label>
                    <p>{{ $teacher->address }}</p>
                </div>
            </div>
        </div>

        <!-- Teaching Assignments -->
        <div class="bg-white rounded-lg shadow-md p-6 col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Teaching Assignments</h2>
                @can('update', $teacher)
                <a href="{{ route('teachers.edit', $teacher) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                    Manage Assignments
                </a>
                @endcan
            </div>
            @if($teacher->taughtSubjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Subject</th>
                            <th class="px-4 py-2 text-left">Class Room</th>
                            <th class="px-4 py-2 text-left">Schedule</th>
                            <th class="px-4 py-2 text-left">Students</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($teacher->taughtSubjects as $subject)
                            @foreach($teacher->teachingClassRooms->where('pivot.subject_id', $subject->id) as $classRoom)
                            <tr>
                                <td class="px-4 py-2">{{ $subject->name }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('class_rooms.show', $classRoom) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $classRoom->name }} ({{ $classRoom->grade_level }})
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    {{ $classRoom->start_time->format('g:i A') }} - {{ $classRoom->end_time->format('g:i A') }}
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($classRoom->days_of_week as $day)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $day }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                
                                <td class="px-4 py-2">{{ $classRoom->students->count() }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 italic">No teaching assignments yet.</p>
            @endif
        </div>
    </div>

    <!-- Recent Evaluations -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Evaluations</h2>
            @can('create', App\Models\Evaluation::class)
            <a href="{{ route('evaluations.create', ['teacher_id' => $teacher->id]) }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                Add Evaluation
            </a>
            @endcan
        </div>
        @if($teacher->conductedEvaluations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Subject</th>
                        <th class="px-4 py-2 text-left">Class Room</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Students</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($teacher->conductedEvaluations->take(5) as $evaluation)
                    <tr>
<td class="px-4 py-2">{{ $evaluation->evaluation_date?->format('M d, Y') }}</td>
                        <td class="px-4 py-2">{{ $evaluation->subject->name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('class_rooms.show', $evaluation->classRoom) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $evaluation->classRoom->name }}
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ $evaluation->evaluationType->name }}</td>
                        <td class="px-4 py-2">{{ $evaluation->studentGrades->count() }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($teacher->conductedEvaluations->count() > 5)
            <div class="mt-4 text-right">
                <a href="{{ route('evaluations.index', ['teacher_id' => $teacher->id]) }}" 
                   class="text-blue-600 hover:text-blue-900">View All Evaluations</a>
            </div>
            @endif
        </div>
        @else
        <p class="text-gray-500 italic">No evaluations conducted yet.</p>
        @endif
    </div>

    <!-- Recent Grades -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Recent Grades Given</h2>
        </div>
        @if($teacher->givenGrades->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Student</th>
                        <th class="px-4 py-2 text-left">Subject</th>
                        <th class="px-4 py-2 text-left">Evaluation</th>
                        <th class="px-4 py-2 text-left">Grade</th>
                        <th class="px-4 py-2 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($teacher->givenGrades->take(5) as $grade)
                    <tr>
                        <td class="px-4 py-2">
                            <a href="{{ route('students.show', $grade->student) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $grade->student->user->name }}
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ $grade->evaluation->subject->name }}</td>
                        <td class="px-4 py-2">{{ $grade->evaluation->evaluationType->name }}</td>
                        <td class="px-4 py-2">{{ $grade->marks_obtained }}/{{ $grade->evaluation->total_marks }}</td>
                        <td class="px-4 py-2">{{ $grade->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($teacher->givenGrades->count() > 5)
            <div class="mt-4 text-right">
                <a href="{{ route('grades.index', ['teacher_id' => $teacher->id]) }}" 
                   class="text-blue-600 hover:text-blue-900">View All Grades</a>
            </div>
            @endif
        </div>
        @else
        <p class="text-gray-500 italic">No grades given yet.</p>
        @endif
    </div>
</div>
@endsection
