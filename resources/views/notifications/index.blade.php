@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-12">
            <div class="bg-gray-100 min-h-screen">
                <div class="p-4">
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">Notifications</h2>
                            <button id="markAllAsRead" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-check-double mr-2"></i>
                                Tout marquer comme lu
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="divide-y divide-gray-200">
                            @forelse($notifications as $notification)
                                <div class="py-4 px-4 notification-item {{ $notification->isUnread() ? 'bg-indigo-50' : '' }}" 
                                     data-notification-id="{{ $notification->id }}"
                                     style="cursor: pointer;">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            @switch($notification->type)
                                                @case('success')
                                                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                                    @break
                                                @case('warning')
                                                    <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
                                                    @break
                                                @case('error')
                                                    <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-bell text-indigo-500 text-lg"></i>
                                            @endswitch
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    {{ $notification->title }}
                                                </h3>
                                                <span class="text-xs text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ $notification->message }}
                                            </p>
                                            @if($notification->link)
                                                <a href="{{ $notification->link }}" 
                                                   class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                                                    Voir plus
                                                    <i class="fas fa-arrow-right ml-1"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-4 text-center text-gray-500">
                                    Aucune notification
                                </div>
                            @endforelse
                        </div>

                        @if($notifications->hasPages())
                            <div class="px-4 py-3 border-t border-gray-200">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marquer une notification comme lue au clic
    document.querySelectorAll('.notification-item').forEach(element => {
        element.addEventListener('click', function(e) {
            if (e.target.closest('a')) return; // Ne rien faire si clic sur un lien
            
            const notificationId = this.dataset.notificationId;
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.remove('bg-indigo-50');
                    updateNotificationCount();
                }
            });
        });
    });

    // Marquer toutes les notifications comme lues
    document.getElementById('markAllAsRead').addEventListener('click', function() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-indigo-50');
                });
                updateNotificationCount();
            }
        });
    });

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
});
</script>
@endpush

@push('styles')
<style>
    .notification-item {
        transition: background-color 0.2s ease;
    }
    .notification-item:hover {
        background-color: #f9fafb;
    }
    .notification-item.bg-indigo-50:hover {
        background-color: #eef2ff;
    }
</style>
@endpush
@endsection 