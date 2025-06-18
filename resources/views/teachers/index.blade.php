<x-app-layout>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-0">Teachers</h4>
                    </div>
                    <div class="d-flex gap-2">
                        @can('create', App\Models\Teacher::class)
                        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Teacher
                        </a>
                        @endcan
                        @can('create', App\Models\Evaluation::class)
                        <a href="{{ route('evaluations.create') }}" class="btn btn-primary">
                            <i class="fas fa-clipboard-check me-2"></i>Create Evaluation
                        </a>
                        @endcan
                        @can('viewAny', App\Models\Evaluation::class)
                        <a href="{{ route('evaluations.index') }}" class="btn btn-light">
                            <i class="fas fa-list me-2"></i>View Evaluations
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>School</th>
                                <th>Class Room</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($teacher->profile_photo)
                                            <img src="{{ asset('storage/' . $teacher->profile_photo) }}" 
                                                 alt="Profile Photo" 
                                                 class="w-10 h-10 rounded-full object-cover me-3">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center me-3">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $teacher->teacher_firstname }} {{ $teacher->teacher_lastname }}</div>
                                            <div class="text-muted small">{{ $teacher->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($teacher->teachingClassRooms->isNotEmpty())
                                        {{ $teacher->teachingClassRooms->first()->school->name }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($teacher->teachingClassRooms->isNotEmpty())
                                        {{ $teacher->teachingClassRooms->first()->name }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        {{ $teacher->phone }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @can('view', $teacher)
                                        <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-light" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endcan
                                        @can('update', $teacher)
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-light" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $teacher)
                                        <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light" title="Delete" onclick="return confirm('Are you sure you want to delete this teacher?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No teachers found.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{ $teachers->links() }}
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
        }
    </style>
    @endpush
</x-app-layout>
