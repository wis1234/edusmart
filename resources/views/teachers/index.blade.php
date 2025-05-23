@extends('layouts.not_main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Navigation --}}
    <div class="mb-6 flex items-center space-x-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Title and Buttons --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">👩‍🏫 Teachers</h1>
        <div class="flex space-x-3">
            @can('create', App\Models\User::class)
                <a href="{{ route('teachers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
                    ➕ Add New Teacher
                </a>
            @endcan

            @can('create', App\Models\Evaluation::class)
                <a href="{{ route('evaluations.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
                    📝 Create Evaluation
                </a>
                <a href="{{ route('evaluations.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150">
                    📊 View Evaluations
                </a>
            @endcan
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-lg shadow mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stylish Table --}}
    <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
        <table class="min-w-full text-sm text-left text-gray-800">
            <thead class="bg-gradient-to-r from-gray-100 to-gray-200 text-xs uppercase tracking-wider text-gray-600 font-bold">
                <tr>
                    <th class="py-4 px-6">Name</th>
                    <th class="py-4 px-6">School</th>
                    <th class="py-4 px-6">Class room</th>
                    <th class="py-4 px-6">Phone</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($teachers as $teacher)
                <tr class="hover:bg-blue-50 transition duration-200">
                    <td class="px-6 py-4">{{ $teacher->name }}</td>
                   <td class="px-6 py-4">
    {{ $teacher->school?->name ?? 'No school assigned' }}
</td>
<td class="px-6 py-4">
    {{ $teacher->subjects?->pluck('name')->join(', ') ?? 'No subjects assigned' }}
</td>


                    <td class="px-6 py-4">{{ $teacher->phone }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'inactive' => 'bg-red-100 text-red-800',
                                'on_leave' => 'bg-yellow-100 text-yellow-800',
                            ];
                        @endphp
                         <span class="inline-block px-2 py-1 text-xs font-bold rounded-full capitalize {{ $statusColors[strtolower($teacher->status)] ?? 'bg-gray-100 text-gray-800' }}">
                         {{ ucwords(str_replace('_', ' ', strtolower($teacher->status))) }}
                        </span>
                        </td>

                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $canEdit = auth()->user()->can('update', $teacher);
                            $canDelete = auth()->user()->can('delete', $teacher);
                        @endphp
                        @include('components.action-icons', [
                            'viewRoute' => route('teachers.show', $teacher),
                            'editRoute' => $canEdit ? route('teachers.edit', $teacher) : null,
                            'deleteRoute' => $canDelete ? route('teachers.destroy', $teacher) : null,
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                            'deleteConfirmMessage' => 'Are you sure you want to delete this teacher?'
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-6">
                        No teachers found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
