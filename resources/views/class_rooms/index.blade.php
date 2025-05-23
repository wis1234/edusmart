@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
        <div class="mb-6 flex items-center space-x-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200">
            ← Back to Dashboard
        </a>
    </div>

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">🏫 Classrooms</h1>
        <div class="flex space-x-3">
    @can('create', App\Models\ClassRoom::class)
        <a href="{{ route('class_rooms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
            ➕ Add Classroom
        </a>
        <a href="{{ route('subjects.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
            📚 Add Subject
        </a>
        <a href="{{ route('subjects.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
            👀 View Subjects
        </a>
    @endcan
</div>

    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Classroom
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            School
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grade Level
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Section
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Academic Year
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Capacity
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Active
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($classRooms as $classRoom)
                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <a href="{{ route('class_rooms.show', $classRoom) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $classRoom->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $classRoom->school->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $classRoom->grade_level }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $classRoom->section }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $classRoom->academic_year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $classRoom->capacity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($classRoom->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Yes
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    No
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $canEdit = auth()->user()->can('update', $classRoom);
                                $canDelete = auth()->user()->can('delete', $classRoom);
                            @endphp
                            @include('components.action-icons', [
                                'viewRoute' => route('class_rooms.show', $classRoom),
                                'editRoute' => $canEdit ? route('class_rooms.edit', $classRoom) : null,
                                'deleteRoute' => $canDelete ? route('class_rooms.destroy', $classRoom) : null,
                                'canEdit' => $canEdit,
                                'canDelete' => $canDelete,
                                'deleteConfirmMessage' => 'Are you sure you want to delete this classroom?'
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No classrooms found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
