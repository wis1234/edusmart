@extends('layouts.not_main')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Evaluations</h1>

    @can('create', App\Models\Evaluation::class)
    <a href="{{ route('evaluations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Evaluation</a>
    @endcan

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Subject</th>
                <th class="py-2 px-4 border-b">Class Room</th>
                <th class="py-2 px-4 border-b">Evaluation Type</th>
                <th class="py-2 px-4 border-b">Teacher</th>
                <th class="py-2 px-4 border-b">Academic Year</th>
                <th class="py-2 px-4 border-b">Term</th>
                <th class="py-2 px-4 border-b">Date</th>
                <th class="py-2 px-4 border-b">Total Marks</th>
                <th class="py-2 px-4 border-b">Passing Marks</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evaluations as $evaluation)
            <tr>
                <td class="py-2 px-4 border-b">{{ $evaluation->subject->name ?? '' }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->classRoom->name ?? '' }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->evaluationType->name ?? '' }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->teacher->name ?? '' }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->academic_year }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->term }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->evaluation_date->format('Y-m-d') }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->total_marks }}</td>
                <td class="py-2 px-4 border-b">{{ $evaluation->passing_marks }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    @can('update', $evaluation)
                    <a href="{{ route('evaluations.edit', $evaluation) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    @endcan
                    @can('delete', $evaluation)
                    <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this evaluation?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="py-4 px-4 text-center">No evaluations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $evaluations->links() }}
</div>
@endsection
