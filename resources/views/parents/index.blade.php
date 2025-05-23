@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4">
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mb-6 inline-block shadow-sm">
        ← Back to Dashboard
    </a>

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Parents</h1>

    <a href="{{ route('parents.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6 inline-flex items-center gap-2 shadow-md transition">
        ➕ Add New Parent
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
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Email</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Phone</th>
                    <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($parents as $parent)
                <tr class="hover:bg-blue-50 transition duration-200 ease-in-out">
                    <td class="py-4 px-6 text-sm text-gray-800">{{ $parent->name }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800">{{ $parent->email }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800">{{ $parent->phone }}</td>
                    <td class="py-4 px-6 text-sm text-gray-800 capitalize">
                        {{ str_replace('_', ' ', $parent->status) }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $canEdit = auth()->user()->can('update', $parent);
                            $canDelete = auth()->user()->can('delete', $parent);
                        @endphp
                        @include('components.action-icons', [
                            'viewRoute' => route('parents.show', $parent),
                            'editRoute' => $canEdit ? route('parents.edit', $parent) : null,
                            'deleteRoute' => $canDelete ? route('parents.destroy', $parent) : null,
                            'canEdit' => $canEdit,
                            'canDelete' => $canDelete,
                            'deleteConfirmMessage' => 'Are you sure you want to delete this parent?'
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 px-6 text-center text-gray-500">No parents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
