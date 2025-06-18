<header class="main-header">
    <div class="container-fluid h-100">
        <div class="d-flex align-items-center justify-content-between h-100 px-3">
            <!-- Left side -->
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark p-0 me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
            </div>

            <!-- Center - Search -->
            <div class="search-box d-none d-lg-flex align-items-center">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search...">
                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Right side -->
            <div class="d-flex align-items-center">
                <!-- Connection Info -->
                <div class="connection-info me-4">
                    <div class="d-flex align-items-center">
                        <div class="connection-status me-2">
                            <span class="status-dot bg-success"></span>
                        </div>
                        <!-- <div class="connection-details">
                            <small class="text-muted d-block">Connected as</small>
                            <span class="text-dark fw-medium">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                        </div> -->
                    </div>
                </div>

                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="btn btn-link text-dark position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ auth()->user()->unreadNotifications()->count() === 0 ? 'd-none' : '' }}">
                            {{ auth()->user()->unreadNotifications()->count() }}
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width: 320px;">
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notifications</h6>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                    <button class="btn btn-link btn-sm text-muted p-0" id="markAllAsRead">
                                        Tout marquer comme lu
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="notifications-list" style="max-height: 400px; overflow-y: auto;">
                            @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                <a href="{{ $notification->link ?? '#' }}" 
                                   class="dropdown-item notification-item {{ $notification->isUnread() ? 'bg-light' : '' }}"
                                   data-notification-id="{{ $notification->id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
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
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0 text-sm">{{ $notification->title }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="dropdown-item text-center text-muted py-3">
                                    Aucune notification
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 border-top">
                            <a href="{{ route('notifications.index') }}" class="btn btn-link btn-sm text-center w-100">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <div class="position-relative">
                            <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name).'&background=4f46e5&color=fff' }}" 
                                 alt="User" 
                                 class="rounded-circle me-2"
                                 width="40" 
                                 height="40">
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle" style="width: 10px; height: 10px; border: 2px solid white;"></span>
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <div class="px-3 py-2 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name).'&background=4f46e5&color=fff' }}" 
                                     alt="User" 
                                     class="rounded-circle me-2"
                                     width="48" 
                                     height="48">
                                <div>
                                    <h6 class="mb-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2"></i> Personal
                            <small class="d-block text-muted">{{ Auth::user()->profession }}</small>
                        </a>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cookie me-2"></i> Cookie Preferences
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-bell me-2"></i> Notifications
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user-plus me-2"></i> Referrals
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-headset me-2"></i> Support
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-question-circle me-2"></i> Help
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-circle text-success me-2"></i> Status
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@push('styles')
<style>
    .main-header {
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .search-box {
        max-width: 400px;
        width: 100%;
    }

    .search-box .input-group {
        background: #f3f4f6;
        border-radius: 8px;
        overflow: hidden;
    }

    .search-box .input-group-text,
    .search-box .form-control {
        border-color: transparent;
        background: transparent;
    }

    .search-box .form-control:focus {
        box-shadow: none;
        background: #fff;
    }

    .search-box .btn {
        border-radius: 0;
        padding: 0.5rem 1rem;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        min-width: 280px;
        padding: 0.5rem 0;
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        color: #4b5563;
    }

    .dropdown-item:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    .btn-link {
        text-decoration: none;
    }

    .btn-link:hover {
        color: #4f46e5 !important;
    }

    .connection-info {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        background-color: #f9fafb;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .connection-details {
        line-height: 1.2;
    }

    /* Custom colors */
    .text-primary { color: #4f46e5 !important; }
    .text-success { color: #10b981 !important; }
    .text-danger { color: #ef4444 !important; }
    .text-warning { color: #f59e0b !important; }
    .text-info { color: #3b82f6 !important; }

    .bg-primary { background-color: #4f46e5 !important; }
    .bg-success { background-color: #10b981 !important; }
    .bg-danger { background-color: #ef4444 !important; }
    .bg-warning { background-color: #f59e0b !important; }
    .bg-info { background-color: #3b82f6 !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marquer une notification comme lue
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
                e.preventDefault();
            }
            
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
                    this.classList.remove('bg-light');
                    updateNotificationCount();
                }
            });
        });
    });

    // Marquer toutes les notifications comme lues
    const markAllAsReadBtn = document.getElementById('markAllAsRead');
    if (markAllAsReadBtn) {
        markAllAsReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
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
                        item.classList.remove('bg-light');
                    });
                    updateNotificationCount();
                }
            });
        });
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

    // Vérifier les nouvelles notifications toutes les 30 secondes
    setInterval(updateNotificationCount, 30000);
});
</script>
@endpush 