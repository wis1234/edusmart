<x-app-layout>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($teacher->profile_photo)
                            <img src="{{ asset('storage/' . $teacher->profile_photo) }}" 
                                 alt="Profile Photo" 
                                 class="w-16 h-16 object-cover rounded-full border-4 border-primary me-3">
                        @else
                            <i class="fas fa-user-circle fa-3x text-primary me-3"></i>
                        @endif
                        <div>
                            <h4 class="mb-0">{{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</h4>
                            <small class="text-muted">{{ $teacher->subjects->pluck('name')->join(', ') }}</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @can('update', $teacher)
                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-light">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        @endcan
                        <a href="{{ route('teachers.index') }}" class="btn btn-light">
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
                                    <label class="form-label text-muted">Email</label>
                                    <div class="text-dark">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        {{ $teacher->email }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <div class="text-dark">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        {{ $teacher->phone }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Date of Birth</label>
                                    <div class="text-dark">
                                        <i class="fas fa-calendar me-2 text-muted"></i>
                                        {{ $teacher->date_of_birth?->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Gender</label>
                                    <div class="text-dark">
                                        <i class="fas fa-venus-mars me-2 text-muted"></i>
                                        {{ ucfirst($teacher->gender) }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <div>
                                        <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($teacher->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Address</label>
                                    <div class="text-dark">
                                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                        {{ $teacher->address }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teaching Assignments -->
                    <div class="col-md-8">
                        <div class="card h-100">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                                    Teaching Assignments
                                </h5>
                                @can('update', $teacher)
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i>Manage Assignments
                                </a>
                                @endcan
                            </div>
                            <div class="card-body">
                                @if($teacher->taughtSubjects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Class Room</th>
                                                <th>School</th>
                                                <th>Schedule</th>
                                                <th>Students</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teacher->taughtSubjects as $subject)
                                                @foreach($teacher->teachingClassRooms->where('pivot.subject_id', $subject->id) as $classRoom)
                                                <tr>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>
                                                        <a href="{{ route('class_rooms.show', $classRoom) }}" class="text-primary">
                                                            {{ $classRoom->name }} ({{ $classRoom->grade_level }})
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('schools.show', $classRoom->school) }}" class="text-primary">
                                                            {{ $classRoom->school->name }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $classRoom->start_time->format('g:i A') }} - {{ $classRoom->end_time->format('g:i A') }}
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @foreach($classRoom->days_of_week as $day)
                                                                <span class="badge bg-info">
                                                                    {{ $day }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td>{{ $classRoom->students->count() }}</td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <p class="text-muted text-center my-4">No teaching assignments yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Evaluations -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-check me-2 text-primary"></i>
                                    Recent Evaluations
                                </h5>
                                @can('create', App\Models\Evaluation::class)
                                @if($teacher->status === 'active')
                                <a href="{{ route('evaluations.create', ['teacher_id' => $teacher->id]) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Evaluation
                                </a>
                                @endif
                                @endcan
                            </div>
                            <div class="card-body">
                                @if($teacher->conductedEvaluations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Subject</th>
                                                <th>Class Room</th>
                                                <th>Type</th>
                                                <th>Students</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teacher->conductedEvaluations->take(5) as $evaluation)
                                            <tr>
                                                <td>{{ $evaluation->evaluation_date?->format('M d, Y') }}</td>
                                                <td>{{ $evaluation->subject->name }}</td>
                                                <td>
                                                    <a href="{{ route('class_rooms.show', $evaluation->classRoom) }}" class="text-primary">
                                                        {{ $evaluation->classRoom->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $evaluation->evaluationType->name }}</td>
                                                <td>{{ $evaluation->studentGrades->count() }}</td>
                                                <td>
                                                    <x-action-icons 
                                                        :viewRoute="route('evaluations.show', $evaluation)" 
                                                        :canEdit="false" 
                                                        :canDelete="false" 
                                                    />
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <p class="text-muted text-center my-4">No evaluations conducted yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Grades -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2 text-primary"></i>
                                    Recent Grades Given
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($teacher->givenGrades->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Subject</th>
                                                <th>Evaluation</th>
                                                <th>Grade</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teacher->givenGrades->take(5) as $grade)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('students.show', $grade->student) }}" class="text-primary">
                                                        {{ $grade->student->user->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $grade->evaluation->subject->name }}</td>
                                                <td>{{ $grade->evaluation->evaluationType->name }}</td>
                                                <td>{{ $grade->marks_obtained }}/{{ $grade->evaluation->total_marks }}</td>
                                                <td>{{ $grade->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($teacher->givenGrades->count() > 5)
                                    <div class="text-end mt-3">
                                        <a href="{{ route('grades.index', ['teacher_id' => $teacher->id]) }}" 
                                           class="btn btn-link text-primary">
                                            View All Grades
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <p class="text-muted text-center my-4">No grades given yet.</p>
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
