@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mb-6 inline-block shadow-sm">
        ← Back to Dashboard
    </a>

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Students</h1>

<a href="{{ route('students.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6 inline-flex items-center gap-2 shadow-md transition">
    ➕ Add New Student
</a>


    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full bg-white rounded-xl overflow-hidden">
            <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                <tr>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">First Name</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Last Name</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Birth Date</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Gender</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Class Room</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Parent</th>
                    <th class="py-4 px-6 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($students as $student)
                <tr class="hover:bg-blue-50 transition duration-200 ease-in-out">
                    <td class="py-4 px-6 text-sm text-gray-800">{{ $student->first_name }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800">{{ $student->last_name }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800">{{ $student->date_of_birth->format('Y-m-d') }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800 capitalize">{{ $student->gender }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800">{{ $student->classRoom->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800">
                        {{ $users->firstWhere('id', $student->selected_parent_id)?->first_name }} 
                        {{ $users->firstWhere('id', $student->selected_parent_id)?->last_name ?? 'N/A' }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $canEdit = auth()->user()->can('update', $student);
                            $canDelete = auth()->user()->can('delete', $student);
                        @endphp
                        @include('components.action-icons', [
                            'viewRoute' => route('students.show', $student),
                            'editRoute' => $canEdit ? route('students.edit', $student) : null,
                            'deleteRoute' => $canDelete ? route('students.destroy', $student) : null,
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                            'deleteConfirmMessage' => 'Are you sure you want to delete this student?'
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-6 px-6 text-center text-gray-500">No students found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
