@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-tachometer-alt fa-2x text-primary me-3"></i>
                    <div>
                        <h4 class="mb-0">Dashboard</h4>
                        <small class="text-muted">Overview of your educational system</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light" id="refreshBtn" title="Refresh Dashboard">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button type="button" class="btn btn-light" id="exportBtn" title="Export Data">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fas fa-school text-primary fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted mb-1">Total Schools</h6>
                                    <h3 class="mb-0">{{ \App\Models\School::count() }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up"></i> 12% this month
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fas fa-chalkboard-teacher text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted mb-1">Total Teachers</h6>
                                    <h3 class="mb-0">{{ \App\Models\Teacher::count() }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up"></i> 8% this month
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                    <i class="fas fa-user-graduate text-info fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted mb-1">Total Students</h6>
                                    <h3 class="mb-0">{{ \App\Models\Student::count() }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up"></i> 15% this month
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded p-3">
                                    <i class="fas fa-door-open text-warning fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted mb-1">Total Classes</h6>
                                    <h3 class="mb-0">{{ \App\Models\ClassRoom::count() }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up"></i> 5% this month
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card-body border-bottom">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('schools.create') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                        <div class="card-body text-center">
                            <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                            <h6 class="text-dark mb-0">Add School</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('teachers.create') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                        <div class="card-body text-center">
                            <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
                            <h6 class="text-dark mb-0">Add Teacher</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('students.create') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-2x text-info mb-2"></i>
                            <h6 class="text-dark mb-0">Add Student</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('class_rooms.create') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                        <div class="card-body text-center">
                            <i class="fas fa-chalkboard fa-2x text-warning mb-2"></i>
                            <h6 class="text-dark mb-0">Add Class</h6>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Recent Notifications</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-light" id="markAllReadBtn">
                        <i class="fas fa-check-double me-1"></i>Mark All as Read
                    </button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Message</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                            <tr class="notification-item {{ $notification->isUnread() ? 'bg-light' : '' }}" 
                                data-notification-id="{{ $notification->id }}">
                                <td class="px-4 py-3">
                                    @switch($notification->type)
                                        @case('success')
                                            <i class="fas fa-check-circle text-success"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                            @break
                                        @case('error')
                                            <i class="fas fa-times-circle text-danger"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-primary"></i>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">{{ $notification->title }}</td>
                                <td class="px-4 py-3">{{ Str::limit($notification->message, 50) }}</td>
                                <td class="px-4 py-3">{{ $notification->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3 text-end">
                                    <div class="btn-group">
                                        @if($notification->link)
                                            <a href="{{ route('notifications.show', $notification) }}" class="btn btn-sm btn-light" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-light mark-read-btn" title="Mark as Read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light delete-notification-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No recent notifications</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card-body">
            <h5 class="mb-3">Recent Activity</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Action</th>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-{{ $activity->type === 'create' ? 'success' : ($activity->type === 'update' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $activity->user->profile_photo_url }}" alt="{{ $activity->user->name }}" 
                                             class="rounded-circle me-2" width="32" height="32">
                                        {{ $activity->user->name }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $activity->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3">{{ $activity->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center">
                                    <div class="text-center py-4">
                                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No recent activity</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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

    /* Header Styles */
    .card-header {
        background: linear-gradient(to right, #ffffff, #f8fafc);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .card-header h4 {
        color: #1e293b;
        font-weight: 600;
        letter-spacing: -0.025em;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .card-header small {
        color: #64748b;
        font-size: 0.875rem;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Button Styles */
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
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.3s ease;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .btn-light:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    /* Card Styles */
    .card {
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .table td {
        vertical-align: middle;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Badge Styles */
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
        letter-spacing: 0.025em;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Quick Action Cards */
    .quick-action-card {
        transition: all 0.3s ease;
    }

    .quick-action-card:hover {
        transform: translateY(-2px);
    }

    /* Notification Item */
    .notification-item {
        transition: background-color 0.2s ease;
    }

    .notification-item:hover {
        background-color: #f8fafc;
    }

    /* Activity User Avatar */
    .activity-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Headings */
    h5, h6 {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark notification as read
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const notificationItem = this.closest('.notification-item');
            const notificationId = notificationItem.dataset.notificationId;
            const button = this;
            
            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    notificationItem.classList.remove('bg-light');
                    updateNotificationCount();
                } else {
                    throw new Error(data.message || 'Failed to mark notification as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore button state
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check"></i>';
                // Show error message
                alert(error.message || 'An error occurred while marking the notification as read.');
            });
        });
    });

    // Delete notification
    document.querySelectorAll('.delete-notification-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            if (confirm('Are you sure you want to delete this notification?')) {
                const notificationItem = this.closest('.notification-item');
                const notificationId = notificationItem.dataset.notificationId;
                const button = this;
                
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        notificationItem.remove();
                        updateNotificationCount();
                        
                        // Si c'était la dernière notification, afficher le message "No notifications"
                        const tbody = notificationItem.closest('tbody');
                        if (tbody && tbody.children.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center">
                                        <div class="text-center py-4">
                                            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No recent notifications</h5>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    } else {
                        throw new Error(data.message || 'Failed to delete notification');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Restore button state
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-trash"></i>';
                    // Show error message
                    alert(error.message || 'An error occurred while deleting the notification.');
                });
            }
        });
    });

    // Mark all notifications as read
    document.getElementById('markAllReadBtn').addEventListener('click', function() {
        const button = this;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';

        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-light');
                });
                updateNotificationCount();
            } else {
                throw new Error(data.message || 'Failed to mark all notifications as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while marking all notifications as read.');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check-double me-1"></i>Mark All as Read';
        });
    });

    // Refresh dashboard
    document.getElementById('refreshBtn').addEventListener('click', function() {
        window.location.reload();
    });

    // Export data
    document.getElementById('exportBtn').addEventListener('click', function() {
        // Implement export functionality
        alert('Export functionality will be implemented soon');
    });

    function updateNotificationCount() {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('#notificationsDropdown .badge');
                if (badge) {
                    badge.textContent = data.count;
                    if (data.count === 0) {
                        badge.classList.add('d-none');
                    } else {
                        badge.classList.remove('d-none');
                    }
                }
            });
    }
});
</script>
@endpush
@endsection