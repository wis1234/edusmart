@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ $school->name }}</h1>
        <div class="space-x-2">
            @can('update', $school)
            <a href="{{ route('schools.edit', $school) }}" 
               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                Edit School
            </a>
            @endcan
            <a href="{{ route('schools.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Schools
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">School Code</dt>
                    <dd class="text-sm text-gray-900">{{ $school->code }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="text-sm text-gray-900">{{ ucfirst($school->type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $school->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($school->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Student Capacity</dt>
                    <dd class="text-sm text-gray-900">{{ $school->capacity }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Current Students</dt>
                    <dd class="text-sm text-gray-900">{{ $school->students->count() }}</dd>
                </div>
            </dl>
        </div>
        

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Principal</dt>
                    <dd class="text-sm text-gray-900">{{ $school->principal_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="mailto:{{ $school->email }}" class="text-blue-600 hover:text-blue-800">
                            {{ $school->email }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="text-sm text-gray-900">{{ $school->phone }}</dd>
                </div>
                @if($school->website)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Website</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="{{ $school->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            {{ $school->website }}
                        </a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Address Information</h2>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Street Address</dt>
                    <dd class="text-sm text-gray-900">{{ $school->address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">City</dt>
                    <dd class="text-sm text-gray-900">{{ $school->city }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">State/Province</dt>
                    <dd class="text-sm text-gray-900">{{ $school->state }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
                    <dd class="text-sm text-gray-900">{{ $school->postal_code }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                    <dd class="text-sm text-gray-900">{{ $school->country }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Description -->
    @if($school->description)
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Description</h2>
        <p class="text-gray-700 whitespace-pre-line">{{ $school->description }}</p>
    </div>
    @endif

    <!-- Related Information -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Teachers -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Teachers</h2>
            @if($school->teachers->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($school->teachers as $teacher)
                <li class="py-2">
                    <div class="text-sm text-gray-900">{{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</div>
                    <div class="text-sm text-gray-500">{{ $teacher->phone }}</div>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500">No teachers assigned yet.</p>
            @endif
        </div>

        <!-- Classrooms -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Classrooms</h2>
            @if($school->classRooms->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($school->classRooms as $classroom)
                <li class="py-2">
                    <div class="text-sm text-gray-900">{{ $classroom->name }}</div>
                    <div class="text-sm text-gray-500">Capacity: {{ $classroom->capacity }}</div>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500">No classrooms created yet.</p>
            @endif
        </div>

        <!-- Record Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Record Information</h2>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                    <dd class="text-sm text-gray-900">
                        {{ $school->createdBy ? $school->createdBy->first_name . ' ' . $school->createdBy->last_name : 'System' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="text-sm text-gray-900">{{ $school->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated By</dt>
                    <dd class="text-sm text-gray-900">
                        {{ $school->updatedBy ? $school->updatedBy->first_name . ' ' . $school->updatedBy->last_name : 'System' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated At</dt>
                    <dd class="text-sm text-gray-900">{{ $school->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @can('delete', $school)
    <!-- Delete School -->
    <div class="mt-6">
        <form action="{{ route('schools.destroy', $school) }}" 
              method="POST" 
              class="inline-block"
              onsubmit="return confirm('Are you sure you want to delete this school? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Delete School
            </button>
        </form>
    </div>
    @endcan
</div>
@endsection
