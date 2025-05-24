@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6 flex items-center space-x-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200">
            ← Back to Dashboard
        </a>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">📝 Evaluations</h1>
        @can('create', App\Models\Evaluation::class)
        <a href="{{ route('evaluations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
            ➕ Create Evaluation
        </a>
        @endcan
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

    <!-- Search form -->
    <form method="GET" action="{{ route('evaluations.index') }}" class="mb-6">
        <div class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search evaluations..." 
                   class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
            <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow-md transition duration-150">
                Search
            </button>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Room</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evaluation Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Marks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Passing Marks</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($evaluations as $evaluation)
                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 hover:underline">
                            @if($evaluation->subject)
                                <a href="{{ route('subjects.show', $evaluation->subject->id) }}">
                                    {{ $evaluation->subject->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:underline">
                            @if($evaluation->classRoom)
                                <a href="{{ route('class_rooms.show', $evaluation->classRoom->id) }}">
                                    {{ $evaluation->classRoom->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $evaluation->evaluationType->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:underline">
                            @if($evaluation->teacher)
                                <a href="{{ route('teachers.show', $evaluation->teacher->id) }}">
                                    {{ $evaluation->teacher->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $evaluation->academic_year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $evaluation->evaluation_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $evaluation->total_marks }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $evaluation->passing_marks }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $canEdit = auth()->user()->can('update', $evaluation);
                                $canDelete = auth()->user()->can('delete', $evaluation);
                            @endphp
                            @include('components.action-icons', [
                                'viewRoute' => route('evaluations.show', $evaluation),
                                'editRoute' => $canEdit ? route('evaluations.edit', $evaluation) : null,
                                'deleteRoute' => $canDelete ? route('evaluations.destroy', $evaluation) : null,
                                'canEdit' => $canEdit,
                                'canDelete' => $canDelete,
                                'deleteConfirmMessage' => 'Are you sure you want to delete this evaluation?'
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">No evaluations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $evaluations->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection
