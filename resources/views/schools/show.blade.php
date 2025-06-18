<x-app-layout>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" 
                                 alt="School Logo" 
                                 class="w-16 h-16 object-contain rounded-lg border-4 border-primary me-3">
                        @else
                            <i class="fas fa-school fa-3x text-primary me-3"></i>
                        @endif
                        <div>
                            <h4 class="mb-0">{{ $school->name }}</h4>
                            <small class="text-muted">{{ $school->type }} - {{ $school->status }}</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @can('update', $school)
                        <a href="{{ route('schools.edit', $school) }}" class="btn btn-light">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        @endcan
                        <a href="{{ route('schools.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                    Basic Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Type</label>
                                    <div class="text-dark">
                                        <i class="fas fa-building me-2 text-muted"></i>
                                        {{ ucfirst($school->type) }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <div>
                                        <span class="badge bg-{{ $school->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($school->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Founded Date</label>
                                    <div class="text-dark">
                                        <i class="fas fa-calendar me-2 text-muted"></i>
                                        {{ $school->founded_date?->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Description</label>
                                    <div class="text-dark">
                                        <i class="fas fa-align-left me-2 text-muted"></i>
                                        {{ $school->description ?: 'No description available' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-address-book me-2 text-primary"></i>
                                    Contact Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <div class="text-dark">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        {{ $school->email }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <div class="text-dark">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        {{ $school->phone }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Website</label>
                                    <div class="text-dark">
                                        <i class="fas fa-globe me-2 text-muted"></i>
                                        @if($school->website)
                                            <a href="{{ $school->website }}" target="_blank" class="text-primary">
                                                {{ $school->website }}
                                            </a>
                                        @else
                                            No website available
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    Address Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Address</label>
                                    <div class="text-dark">
                                        <i class="fas fa-map me-2 text-muted"></i>
                                        {{ $school->address }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">City</label>
                                    <div class="text-dark">
                                        <i class="fas fa-city me-2 text-muted"></i>
                                        {{ $school->city }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">State</label>
                                    <div class="text-dark">
                                        <i class="fas fa-map-pin me-2 text-muted"></i>
                                        {{ $school->state }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Country</label>
                                    <div class="text-dark">
                                        <i class="fas fa-flag me-2 text-muted"></i>
                                        {{ $school->country }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teachers -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                                    Teachers
                                </h5>
                                <span class="badge bg-primary">{{ $school->teachers->count() }} Teachers</span>
                            </div>
                            <div class="card-body">
                                @if($school->teachers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Subjects</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($school->teachers->take(5) as $teacher)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('teachers.show', $teacher) }}" class="text-primary">
                                                        {{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}
                                                    </a>
                                                </td>
                                                <td>{{ $teacher->subjects->pluck('name')->join(', ') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($teacher->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <x-action-icons 
                                                        :viewRoute="route('teachers.show', $teacher)" 
                                                        :canEdit="false" 
                                                        :canDelete="false" 
                                                    />
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($school->teachers->count() > 5)
                                    <div class="text-end mt-3">
                                        <a href="{{ route('teachers.index', ['school_id' => $school->id]) }}" 
                                           class="btn btn-link text-primary">
                                            View All Teachers
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <p class="text-muted text-center my-4">No teachers assigned yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Classrooms -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-door-open me-2 text-primary"></i>
                                    Classrooms
                                </h5>
                                <span class="badge bg-primary">{{ $school->classRooms->count() }} Classrooms</span>
                            </div>
                            <div class="card-body">
                                @if($school->classRooms->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Grade Level</th>
                                                <th>Students</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($school->classRooms->take(5) as $classRoom)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('class_rooms.show', $classRoom) }}" class="text-primary">
                                                        {{ $classRoom->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $classRoom->grade_level }}</td>
                                                <td>{{ $classRoom->students->count() }}</td>
                                                <td>
                                                    <x-action-icons 
                                                        :viewRoute="route('class_rooms.show', $classRoom)" 
                                                        :canEdit="false" 
                                                        :canDelete="false" 
                                                    />
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($school->classRooms->count() > 5)
                                    <div class="text-end mt-3">
                                        <a href="{{ route('class_rooms.index', ['school_id' => $school->id]) }}" 
                                           class="btn btn-link text-primary">
                                            View All Classrooms
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <p class="text-muted text-center my-4">No classrooms created yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Students -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                                    Students
                                </h5>
                                <span class="badge bg-primary">{{ $school->students->count() }} Students</span>
                            </div>
                            <div class="card-body">
                                @if($school->students->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($school->students->take(5) as $student)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('students.show', $student) }}" class="text-primary">
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($student->classRoom)
                                                        <a href="{{ route('class_rooms.show', $student->classRoom) }}" class="text-primary">
                                                            {{ $student->classRoom->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Not Assigned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($student->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <x-action-icons 
                                                        :viewRoute="route('students.show', $student)" 
                                                        :canEdit="false" 
                                                        :canDelete="false" 
                                                    />
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($school->students->count() > 5)
                                    <div class="text-end mt-3">
                                        <a href="{{ route('students.index', ['school_id' => $school->id]) }}" 
                                           class="btn btn-link text-primary">
                                            View All Students
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <p class="text-muted text-center my-4">No students enrolled yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .content-wrapper {
            padding: 0;
            background: #f8fafc;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            margin: 0;
        }

        .card-header {
            background: linear-gradient(to right, #ffffff, #f8fafc);
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem;
        }

        .card-header h4 {
            color: #1e293b;
            font-weight: 600;
            letter-spacing: -0.025em;
        }

        .card-header small {
            color: #64748b;
            font-size: 0.875rem;
        }

        .form-label {
            color: #475569;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            border: none;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-light {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
            font-size: 0.75rem;
            border-radius: 0.375rem;
        }

        .badge.bg-success {
            background: linear-gradient(to right, #10b981, #059669) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(to right, #ef4444, #dc2626) !important;
        }

        .badge.bg-info {
            background: linear-gradient(to right, #3b82f6, #2563eb) !important;
        }

        .text-dark {
            color: #1e293b !important;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            color: #475569;
            border-bottom-width: 1px;
        }

        .table td {
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .card-header {
                padding: 1rem;
            }

            .row {
                margin: 0 -0.5rem;
            }

            .col-md-6, .col-md-4, .col-md-3, .col-md-12 {
                padding: 0 0.5rem;
            }
        }
    </style>
    @endpush
</x-app-layout>
