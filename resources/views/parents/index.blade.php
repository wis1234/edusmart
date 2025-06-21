@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                <i class="fas fa-users text-white text-2xl"></i>
            </span>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ __('messages.parents') }}</h1>
                <p class="text-gray-500 dark:text-gray-300">{{ __('messages.manage_parents') }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('parents.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition whitespace-nowrap">
                <i class="fas fa-plus"></i> {{ __('messages.add_parent') }}
            </a>
        </div>
    </div>
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-2 py-3 text-left">{{ __('messages.name') }}</th>
                        <th class="px-2 py-3 text-left">{{ __('messages.email') }}</th>
                        <th class="px-2 py-3 text-left">{{ __('messages.phone') }}</th>
                        <th class="px-2 py-3 text-left">{{ __('messages.status') }}</th>
                        <th class="px-2 py-3 text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parents as $parent)
                    <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                        <td class="px-2 py-3 max-w-xs truncate">{{ $parent->first_name }} {{ $parent->last_name }}</td>
                        <td class="px-2 py-3 max-w-xs truncate">{{ $parent->email }}</td>
                        <td class="px-2 py-3 max-w-xs truncate">{{ $parent->phone }}</td>
                        <td class="px-2 py-3 capitalize">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $parent->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                {{ ucfirst($parent->status) }}
                            </span>
                        </td>
                        <td class="px-2 py-3 text-center">
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
                                'deleteConfirmMessage' => __('messages.delete_parent_confirm')
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center">
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
                                <h5 class="text-gray-400 dark:text-gray-500">{{ __('messages.no_parents_found') }}</h5>
                                <a href="{{ route('parents.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                                    <i class="fas fa-plus"></i> {{ __('messages.add_parent') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
