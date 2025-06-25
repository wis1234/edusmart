@php /** @var \App\Models\School $school */ @endphp
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-book text-indigo-500"></i> Subjects</h2>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->subjects->count() }} Subjects</span>
            <a href="{{ route('subjects.index', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                <i class="fas fa-list"></i> View All
            </a>
            @can('create', App\Models\Subject::class)
                @if(!auth()->user()->hasRole('student'))
                <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 px-3 py-1 rounded text-xs font-semibold bg-green-600 text-white hover:bg-green-700 transition">
                    <i class="fas fa-plus"></i> Add Subject
                </a>
                @endif
            @endcan
        </div>
    </div>
    @if($school->subjects->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Name</th>
                        <th class="px-4 py-2 text-left font-semibold">Code</th>
                        <th class="px-4 py-2 text-left font-semibold">Level</th>
                        <th class="px-4 py-2 text-left font-semibold">Credits</th>
                        <th class="px-4 py-2 text-left font-semibold">Status</th>
                        <th class="px-4 py-2 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                    @foreach($school->subjects->take(5) as $subject)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                            <td class="px-4 py-2">
                                <a href="{{ route('subjects.show', $subject) }}" class="text-indigo-600 hover:underline font-semibold">
                                    {{ $subject->name }}
                                </a>
                                @if($subject->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">{{ $subject->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{ $subject->code ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if($subject->level)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                        {{ $subject->level }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $subject->credits ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $subject->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                @if(!auth()->user()->hasRole('student'))
                                <x-action-icons 
                                    :viewRoute="route('subjects.show', $subject)" 
                                    :editRoute="route('subjects.edit', $subject)"
                                    :deleteRoute="route('subjects.destroy', $subject)"
                                    :canEdit="true"
                                    :canDelete="true"
                                    deleteConfirmMessage="Are you sure you want to delete this subject?"
                                />
                                @else
                                <a href="{{ route('subjects.show', $subject) }}" class="text-indigo-600 hover:underline font-semibold">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($school->subjects->count() > 5)
                <!-- Removed conditional View All link, now always in header -->
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <i class="fas fa-book fa-3x text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-400 dark:text-gray-500 font-semibold">No subjects created yet.</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Start by creating the first subject for this school.</p>
            @can('create', App\Models\Subject::class)
                @if(!auth()->user()->hasRole('student'))
                <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition mt-3">
                    <i class="fas fa-plus"></i> Create First Subject
                </a>
                @endif
            @endcan
        </div>
    @endif
</div> 