<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header modernized -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Notifications</h1>
                        <p class="text-gray-500 dark:text-gray-300">Manage your notifications and stay informed about the latest activities</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="refreshNotificationsBtn" class="p-2 rounded-full bg-white dark:bg-gray-800 shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Refresh">
                        <i class="fas fa-sync-alt text-indigo-500"></i>
                    </button>
                    @php $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count(); @endphp
                    @if($unreadCount > 0)
                        <button id="markAllAsReadBtn" class="px-4 py-2 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600 transition font-medium">
                            <i class="fas fa-check-double mr-2"></i>
                            Mark all as read
                        </button>
                    @endif
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900 dark:to-blue-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-bell text-blue-500 dark:text-blue-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">Total</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notifications->total() }}</div>
                        <div class="text-blue-600 dark:text-blue-400 text-xs flex items-center gap-1">
                            <i class="fas fa-chart-line"></i> All notifications
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-100 to-red-50 dark:from-red-900 dark:to-red-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-exclamation-circle text-red-500 dark:text-red-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">Unread</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $unreadCount }}</div>
                        <div class="text-red-600 dark:text-red-400 text-xs flex items-center gap-1">
                            <i class="fas fa-clock"></i> Pending
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-100 to-green-50 dark:from-green-900 dark:to-green-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-check-circle text-green-500 dark:text-green-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">Read</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notifications->total() - $unreadCount }}</div>
                        <div class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1">
                            <i class="fas fa-check"></i> Processed
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-100 to-purple-50 dark:from-purple-900 dark:to-purple-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-clock text-purple-500 dark:text-purple-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">Today</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->notifications()->whereDate('created_at', today())->count() }}</div>
                        <div class="text-purple-600 dark:text-purple-400 text-xs flex items-center gap-1">
                            <i class="fas fa-calendar-day"></i> Recent
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Notification List</h2>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition" id="markAllReadBtn">
                            <i class="fas fa-check-double mr-1"></i>Mark all as read
                        </button>
                        <a href="{{ route('dashboard') }}" class="px-3 py-1 rounded bg-indigo-500 text-white hover:bg-indigo-600 transition">
                            <i class="fas fa-home mr-1"></i>Dashboard
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-xl overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Type</th>
                                <th class="px-4 py-3 text-left">Title</th>
                                <th class="px-4 py-3 text-left">Message</th>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="notificationsContainer">
                            @forelse($notifications as $notification)
                                <tr class="notification-item {{ $notification->isUnread() ? 'bg-gray-50 dark:bg-gray-700' : '' }} transition hover:bg-gray-50 dark:hover:bg-gray-700" 
                                     data-notification-id="{{ $notification->id }}"
                                    data-notification-link="{{ $notification->link ?? '' }}">
                                    <td class="px-4 py-3">
                                            @switch($notification->type)
                                                @case('success')
                                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-lg"></i>
                                                    @break
                                                @case('warning')
                                                <i class="fas fa-exclamation-triangle text-yellow-500 dark:text-yellow-400 text-lg"></i>
                                                    @break
                                                @case('error')
                                                <i class="fas fa-times-circle text-red-500 dark:text-red-400 text-lg"></i>
                                                    @break
                                                @default
                                                <i class="fas fa-bell text-indigo-500 dark:text-blue-400 text-lg"></i>
                                            @endswitch
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white cursor-pointer hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                            {{ $notification->title }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-gray-600 dark:text-gray-300">
                                            {{ Str::limit($notification->message, 60) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($notification->isUnread())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                <i class="fas fa-circle mr-1 text-xs"></i>Unread
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <i class="fas fa-check mr-1 text-xs"></i>Read
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="flex gap-2 justify-end">
                                            @if($notification->link)
                                                <a href="{{ $notification->link }}" 
                                                   class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition" 
                                                   title="View more">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                            @if($notification->isUnread())
                                                <button type="button" 
                                                        class="mark-as-read-btn px-2 py-1 rounded bg-green-200 dark:bg-green-700 text-green-700 dark:text-green-200 hover:bg-green-300 dark:hover:bg-green-600 transition" 
                                                        title="Mark as read"
                                                        data-notification-id="{{ $notification->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" 
                                                    class="delete-notification-btn px-2 py-1 rounded bg-red-200 dark:bg-red-700 text-red-700 dark:text-red-200 hover:bg-red-300 dark:hover:bg-red-600 transition" 
                                                    title="Delete"
                                                    data-notification-id="{{ $notification->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-center">
                                            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500 text-3xl"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No notifications</h3>
                                            <p class="text-gray-500 dark:text-gray-400">You have not received any notifications yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
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
    // Mark notification as read
    document.querySelectorAll('.mark-as-read-btn').forEach(button => {
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
                    notificationItem.classList.remove('bg-gray-50');
                    notificationItem.classList.remove('dark:bg-gray-700');
                    
                    // Mettre à jour le statut
                    const statusCell = notificationItem.querySelector('td:nth-child(5)');
                    if (statusCell) {
                        statusCell.innerHTML = `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-check mr-1 text-xs"></i>Read
                            </span>
                        `;
                    }
                    
                    // Supprimer le bouton marquer comme lu
                    this.remove();
                    
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
                                    <td colspan="6" class="px-4 py-3 text-center">
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

    // Marquer toutes les notifications comme lues
    const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    
    [markAllAsReadBtn, markAllReadBtn].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Afficher l'état de chargement
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
                
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                        // Mettre à jour toutes les notifications
                document.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.remove('bg-gray-50', 'dark:bg-gray-700');
                            item.classList.add('bg-white', 'dark:bg-gray-800');
                            
                            // Mettre à jour le statut
                            const statusCell = item.querySelector('td:nth-child(5)');
                            if (statusCell) {
                                statusCell.innerHTML = `
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="fas fa-check mr-1 text-xs"></i>Read
                                    </span>
                                `;
                            }
                            
                            // Supprimer le bouton marquer comme lu
                            const markAsReadBtn = item.querySelector('.mark-as-read-btn');
                            if (markAsReadBtn) {
                                markAsReadBtn.remove();
                            }
                        });
                        
                        // Masquer les boutons
                        [markAllAsReadBtn, markAllReadBtn].forEach(btn => {
                            if (btn) btn.style.display = 'none';
                        });
                        
                        // Mettre à jour les statistiques
                        updateNotificationStats();
                        
                        showToast('All notifications have been marked as read', 'success');
                    } else {
                        showToast('Error marking notifications', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error marking notifications', 'error');
                })
                .finally(() => {
                    // Restaurer les boutons
                    [markAllAsReadBtn, markAllReadBtn].forEach(btn => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-check-double mr-2"></i>Mark all as read';
                        }
                    });
                });
            });
        }
    });

    // Actualiser les notifications
    const refreshBtn = document.getElementById('refreshNotificationsBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            setTimeout(() => {
                location.reload();
            }, 500);
        });
    }

    // Cliquer sur une notification pour la marquer comme lue et naviguer
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Ne pas déclencher si on clique sur un bouton
            if (e.target.closest('button') || e.target.closest('a')) {
                return;
            }
            
            const notificationId = this.dataset.notificationId;
            const notificationLink = this.dataset.notificationLink;
            
            // Marquer comme lu si ce n'est pas déjà fait
            if (this.classList.contains('bg-gray-50') || this.classList.contains('dark:bg-gray-700')) {
                const markAsReadBtn = this.querySelector('.mark-as-read-btn');
                if (markAsReadBtn) {
                    markAsReadBtn.click();
                }
            }
            
            // Rediriger vers le lien ou la vue détaillée
            if (notificationLink && notificationLink !== '') {
                window.location.href = notificationLink;
            } else {
                window.location.href = `/notifications/${notificationId}`;
            }
        });
    });
});

// Fonction pour mettre à jour les statistiques
function updateNotificationStats() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour le compteur de notifications non lues
            const unreadCountElement = document.querySelector('.bg-red-50 .text-2xl, .dark .bg-red-700 .text-2xl');
            if (unreadCountElement) {
                unreadCountElement.textContent = data.count;
            }
            
            // Masquer les boutons "Mark all as read" si plus de notifications non lues
            const markAllBtns = [document.getElementById('markAllAsReadBtn'), document.getElementById('markAllReadBtn')];
            markAllBtns.forEach(btn => {
                if (btn && data.count === 0) {
                    btn.style.display = 'none';
                }
            });
        })
        .catch(error => {
            console.error('Error updating notification stats:', error);
        });
}

// Fonction pour afficher des toasts
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    toast.className += ` ${colors[type] || colors.info}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2">
                ${type === 'success' ? '<i class="fas fa-check-circle"></i>' : 
                  type === 'error' ? '<i class="fas fa-times-circle"></i>' : 
                  type === 'warning' ? '<i class="fas fa-exclamation-triangle"></i>' : 
                  '<i class="fas fa-info-circle"></i>'}
            </span>
            <span>${message}</span>
            <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, 5000);
}

    // Mettre à jour le compteur de notifications
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
</script>
@endpush