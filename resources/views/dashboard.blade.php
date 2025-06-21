<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header modernisé -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ __('messages.dashboard') }}</h1>
                        <p class="text-gray-500 dark:text-gray-300">{{ __('messages.overview') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 rounded-full bg-white dark:bg-gray-800 shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Refresh Dashboard">
                        <i class="fas fa-sync-alt text-indigo-500"></i>
                    </button>
                    <button class="p-2 rounded-full bg-white dark:bg-gray-800 shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Export Data">
                        <i class="fas fa-download text-green-500"></i>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900 dark:to-blue-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-school text-blue-500 dark:text-blue-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">{{ __('messages.total_schools') }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\School::count() }}</div>
                        <div class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1"><i class="fas fa-arrow-up"></i> 12% this month</div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-100 to-green-50 dark:from-green-900 dark:to-green-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-chalkboard-teacher text-green-500 dark:text-green-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">{{ __('messages.total_teachers') }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Teacher::count() }}</div>
                        <div class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1"><i class="fas fa-arrow-up"></i> 8% this month</div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-cyan-100 to-cyan-50 dark:from-cyan-900 dark:to-cyan-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-user-graduate text-cyan-500 dark:text-cyan-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">{{ __('messages.total_students') }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Student::count() }}</div>
                        <div class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1"><i class="fas fa-arrow-up"></i> 15% this month</div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-yellow-100 to-yellow-50 dark:from-yellow-900 dark:to-yellow-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-door-open text-yellow-500 dark:text-yellow-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">{{ __('messages.total_classes') }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\ClassRoom::count() }}</div>
                        <div class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1"><i class="fas fa-arrow-up"></i> 5% this month</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('messages.quick_actions') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('schools.create') }}" class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:bg-blue-50 dark:hover:bg-gray-700 transition group">
                        <i class="fas fa-plus-circle text-blue-500 dark:text-blue-300 text-3xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ __('messages.add_school') }}</span>
                    </a>
                    <a href="{{ route('teachers.create') }}" class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:bg-green-50 dark:hover:bg-gray-700 transition group">
                        <i class="fas fa-user-plus text-green-500 dark:text-green-300 text-3xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ __('messages.add_teacher') }}</span>
                    </a>
                    <a href="{{ route('students.create') }}" class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:bg-cyan-50 dark:hover:bg-gray-700 transition group">
                        <i class="fas fa-user-graduate text-cyan-500 dark:text-cyan-300 text-3xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ __('messages.add_student') }}</span>
                    </a>
                    <a href="{{ route('class_rooms.create') }}" class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:bg-yellow-50 dark:hover:bg-gray-700 transition group">
                        <i class="fas fa-chalkboard text-yellow-500 dark:text-yellow-300 text-3xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ __('messages.add_class') }}</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activities (Timeline) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.recent_activities') }}</h2>
                </div>
                <div class="relative border-l-2 border-indigo-200 dark:border-indigo-700 pl-6">
                    @forelse($recentActivities as $activity)
                        <div class="mb-10 group relative p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <span class="absolute -left-4 top-2 w-8 h-8 rounded-full bg-white dark:bg-gray-800 border-2 border-indigo-400 flex items-center justify-center shadow">
                                @if($activity->user && $activity->user->profile_photo)
                                    <img src="{{ asset('storage/' . $activity->user->profile_photo) }}" alt="Profile Photo" class="w-7 h-7 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-indigo-400"></i>
                                @endif
                            </span>
                            <div class="ml-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ __('messages.profile_photo') }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full font-bold"
                                        style="background: {{ $activity->type === 'create' ? '#dbeafe' : ($activity->type === 'update' ? '#ede9fe' : ($activity->type === 'delete' ? '#fee2e2' : '#f3f4f6')) }}; color: {{ $activity->type === 'create' ? '#2563eb' : ($activity->type === 'update' ? '#7c3aed' : ($activity->type === 'delete' ? '#dc2626' : '#374151')) }};">
                                        <i class="fas {{ $activity->type === 'create' ? 'fa-plus-circle' : ($activity->type === 'update' ? 'fa-edit' : ($activity->type === 'delete' ? 'fa-trash-alt' : 'fa-info-circle')) }} mr-1"></i>
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </div>
                                <div class="text-gray-700 dark:text-gray-300 text-sm mt-1">
                                    {{ Str::limit($activity->description, 120) }}
                                </div>
                                <div class="flex items-center gap-3 mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    <span><i class="fas fa-clock mr-1"></i> {{ $activity->created_at->format('Y-m-d H:i:s') }}</span>
                                    <span><i class="fas fa-network-wired mr-1"></i> {{ $activity->ip_address }}</span>
                                    <button type="button" class="ml-2 text-indigo-600 dark:text-indigo-300 hover:underline focus:outline-none" onclick="showActivityDetails({{ $activity->id }})">Détails</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <i class="fas fa-history text-2xl mb-2"></i>
                            <p>{{ __('messages.no_activity_found') }}</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4 flex justify-center">
                    {{ $recentActivities->onEachSide(1)->links('pagination::tailwind') }}
                </div>
                <div class="mt-4 text-center">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        {{ __('messages.showing_results', ['from' => $recentActivities->firstItem(), 'to' => $recentActivities->lastItem(), 'total' => $recentActivities->total()]) }}
                    </div>
                </div>
            </div>

            <!-- Modal Activity Details (Popover style, fully transparent background, centered, hidden by default) -->
            <div id="activityDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" style="background: transparent !important;">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-lg p-6 relative flex flex-col items-center justify-center">
                    <button onclick="closeActivityDetails()" class="absolute top-2 right-2 text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                    <div id="activityDetailsContent"><!-- Dynamic content --></div>
                </div>
            </div>

            <script>
                function showActivityDetails(id) {
                    fetch(`/activities/${id}`)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('activityDetailsContent').innerHTML = html;
                            document.getElementById('activityDetailsModal').classList.remove('hidden');
                        });
                }
                function closeActivityDetails() {
                    document.getElementById('activityDetailsModal').classList.add('hidden');
                    document.getElementById('activityDetailsContent').innerHTML = '';
                }
                // Close on click outside
                document.addEventListener('mousedown', function(e) {
                    const modal = document.getElementById('activityDetailsModal');
                    const popover = document.querySelector('#activityDetailsModal > div');
                    if (!modal.classList.contains('hidden') && popover && !popover.contains(e.target)) {
                        closeActivityDetails();
                    }
                });
            </script>
        </div>
    </div>
</x-app-layout>

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
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
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
    // Delete activity functionality
    document.querySelectorAll('.delete-activity').forEach(button => {
        button.addEventListener('click', function() {
            const activityId = this.dataset.activityId;
            if (confirm('Are you sure you want to delete this activity?')) {
                fetch(`/dashboard/activities/${activityId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Remove the activity row from the table
                        const activityRow = button.closest('tr');
                        activityRow.remove();

                        // Show success message
                        alert('Activity deleted successfully');
                    } else {
                        throw new Error(data.error || 'Failed to delete activity');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
            }
        });
    });

    // Filter activities
    window.filterActivities = function() {
        const type = document.querySelector('select[name="type"]').value;
        const date = document.querySelector('input[name="date"]').value;
        
        let url = new URL(window.location.href);
        if (type) url.searchParams.set('type', type);
        else url.searchParams.delete('type');
        
        if (date) url.searchParams.set('date', date);
        else url.searchParams.delete('date');
        
        window.location.href = url.toString();
    };
});
</script>
@endpush