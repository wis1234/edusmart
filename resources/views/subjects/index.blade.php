@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-extrabold text-gray-900">📚 Subjects

    </h1>
        <a href="{{ route('subjects.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
            ➕ Add Subject
        </a>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours/Week</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <a href="{{ route('subjects.show', $subject) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $subject->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subject->code ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subject->credits ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subject->level ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subject->hours_per_week ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($subject->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $canEdit = true; // temporarily no restriction
                                $canDelete = true;
                            @endphp
                            @include('components.action-icons', [
                                'viewRoute' => route('subjects.show', $subject),
                                'editRoute' => $canEdit ? route('subjects.edit', $subject) : null,
                                'deleteRoute' => $canDelete ? route('subjects.destroy', $subject) : null,
                                'canEdit' => $canEdit,
                                'canDelete' => $canDelete,
                                'deleteConfirmMessage' => 'Are you sure you want to delete this subject?'
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No subjects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $subjects->links() }}
    </div>
</div>
@endsection
