<header class="fixed top-0 left-0 right-0 z-30 w-full bg-white dark:bg-gray-900 shadow transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Left: Logo & Menu -->
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition">
                <i class="fas fa-bars text-indigo-600 dark:text-indigo-300 text-xl"></i>
            </button>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                <i class="fas fa-graduation-cap text-white text-xl"></i>
            </span>
            <span class="ml-2 text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">EduSmart</span>
        </div>
        <!-- Center: Search -->
        <div class="hidden md:flex flex-1 justify-center">
            <div class="relative w-full max-w-xs">
                <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
        <!-- Right: Actions -->
        <div class="flex items-center gap-4">
            <!-- Toggle Dark/Light -->
            <button class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition theme-toggle-btn" id="themeToggleBtn" title="Basculer le thème">
                <span class="hidden dark:inline"><i class="fas fa-sun text-yellow-400"></i></span>
                <span class="inline dark:hidden"><i class="fas fa-moon text-gray-700"></i></span>
            </button>
            <!-- Notifications Dropdown -->
            <div class="relative">
                <button class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition relative" id="notificationsDropdownBtn">
                    <i class="fas fa-bell text-indigo-600 dark:text-indigo-300 text-xl"></i>
                    @php $unread = auth()->user()->notifications()->whereNull('read_at')->count(); @endphp
                    @if($unread > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold flex items-center justify-center rounded-full border-2 border-white dark:border-gray-900 animate-pulse">{{ $unread }}</span>
                    @endif
                </button>
                <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="font-semibold text-gray-900 dark:text-white">Notifications</span>
                        @if($unread > 0)
                            <button type="button" id="markAllAsReadBtn" class="text-xs text-indigo-600 dark:text-indigo-300 hover:text-indigo-800 dark:hover:text-indigo-200 hover:underline transition">
                                Tout marquer comme lu
                            </button>
                        @endif
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700" id="notificationsList">
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                            <div class="notification-item px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition cursor-pointer {{ $notification->isUnread() ? 'bg-gray-50 dark:bg-gray-700' : '' }}" 
                                 data-notification-id="{{ $notification->id }}"
                                 data-notification-link="{{ $notification->link ?? '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                    @switch($notification->type)
                                        @case('success')
                                            <i class="fas fa-check-circle text-green-500 dark:text-green-400"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-triangle text-yellow-500 dark:text-yellow-400"></i>
                                            @break
                                        @case('error')
                                            <i class="fas fa-times-circle text-red-500 dark:text-red-400"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-indigo-500 dark:text-blue-400"></i>
                                    @endswitch
                                    </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $notification->title }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-300 mt-1">{{ Str::limit($notification->message, 60) }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                    @if($notification->isUnread())
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p>Aucune notification</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-center">
                        <a href="{{ route('notifications.index') }}" class="text-indigo-600 dark:text-indigo-300 hover:text-indigo-800 dark:hover:text-indigo-200 hover:underline text-sm font-semibold transition">
                            Voir toutes les notifications
                        </a>
                    </div>
                </div>
            </div>
            <!-- User Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-2 focus:outline-none">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name).'&background=4f46e5&color=fff' }}" alt="User" class="w-10 h-10 rounded-full border-2 border-indigo-500 shadow" />
                    <span class="hidden md:inline text-gray-900 dark:text-gray-100 font-semibold">{{ Auth::user()->first_name }}</span>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500"></i>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition"><i class="fas fa-user-edit mr-2"></i> Edit Profile</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition"><i class="fas fa-cog mr-2"></i> Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 transition"><i class="fas fa-sign-out-alt mr-2"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Theme toggle button event listener
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    if (typeof window.toggleTheme === 'function') {
                        window.toggleTheme();
        } else {
                        console.error('toggleTheme function not available');
                    }
                });
            }
        });

        // Notifications dropdown toggle
        const notifBtn = document.getElementById('notificationsDropdownBtn');
        const notifDropdown = document.getElementById('notificationsDropdown');
        
        document.addEventListener('click', function(e) {
            if (notifBtn && notifDropdown) {
                if (notifBtn.contains(e.target)) {
                    notifDropdown.classList.toggle('hidden');
                } else if (!notifDropdown.contains(e.target)) {
                    notifDropdown.classList.add('hidden');
                }
            }
        });

        // Notification interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Marquer une notification comme lue
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const notificationId = this.dataset.notificationId;
                    const notificationLink = this.dataset.notificationLink;
                    
                    // Marquer comme lu si ce n'est pas déjà fait
                    if (this.classList.contains('bg-gray-50') || this.classList.contains('dark:bg-gray-700')) {
                        markNotificationAsRead(notificationId, this);
                    }
                    
                    // Rediriger vers le lien ou la vue détaillée
                    if (notificationLink && notificationLink !== '') {
                        window.location.href = notificationLink;
                    } else {
                        window.location.href = `/notifications/${notificationId}`;
                    }
                });
            });

            // Marquer toutes les notifications comme lues
            const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
            if (markAllAsReadBtn) {
                markAllAsReadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Afficher l'état de chargement
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
                    
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
                            // Mettre à jour l'interface
                            document.querySelectorAll('.notification-item').forEach(item => {
                                item.classList.remove('bg-gray-50', 'dark:bg-gray-700');
                                const unreadDot = item.querySelector('.w-2.h-2.bg-indigo-500');
                                if (unreadDot) {
                                    unreadDot.remove();
                                }
                            });
                            
                            // Masquer le bouton et le badge
                            this.style.display = 'none';
                            const badge = document.querySelector('#notificationsDropdownBtn .bg-red-500');
                            if (badge) {
                                badge.style.display = 'none';
                            }
                            
                            // Afficher un message de succès
                            showToast('Toutes les notifications ont été marquées comme lues', 'success');
                        } else {
                            showToast('Erreur lors du marquage des notifications', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Erreur lors du marquage des notifications', 'error');
                    })
                    .finally(() => {
                        // Restaurer le bouton
                        this.disabled = false;
                        this.innerHTML = 'Tout marquer comme lu';
                    });
                });
            }
        });

        // Fonction pour marquer une notification comme lue
        function markNotificationAsRead(notificationId, element) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
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
                    // Mettre à jour l'apparence
                    element.classList.remove('bg-gray-50', 'dark:bg-gray-700');
                    const unreadDot = element.querySelector('.w-2.h-2.bg-indigo-500');
                    if (unreadDot) {
                        unreadDot.remove();
                    }
                    
                    // Mettre à jour le compteur
                    updateNotificationCount();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        // Fonction pour mettre à jour le compteur de notifications
        function updateNotificationCount() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('#notificationsDropdownBtn .bg-red-500');
                    if (badge) {
                        if (data.count === 0) {
                            badge.style.display = 'none';
                        } else {
                            badge.style.display = 'flex';
                            badge.textContent = data.count;
                        }
                    }
                    
                    // Masquer le bouton "Tout marquer comme lu" si plus de notifications non lues
                    const markAllBtn = document.getElementById('markAllAsReadBtn');
                    if (markAllBtn && data.count === 0) {
                        markAllBtn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error updating notification count:', error);
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
    </script>
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
    const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
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