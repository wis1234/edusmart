<header class="fixed top-0 left-0 right-0 z-30 w-full" style="background: whitesmoke; box-shadow: 0 8px 32px 0 rgba(31,38,135,0.07);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Left: Logo & Menu -->
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition">
                <i class="fas fa-bars text-indigo-600 dark:text-indigo-300 text-xl"></i>
            </button>
            <!-- <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                <i class="fas fa-graduation-cap text-white text-xl"></i>
            </span> -->
            <span class="ml-2 text-3xl font-extrabold tracking-tight select-none" style="font-family: 'Inter', 'Segoe UI', Arial, sans-serif;">
                <span class="bg-gradient-to-tr from-indigo-600 to-purple-600 bg-clip-text text-transparent">Edu</span><span class="text-black dark:text-white">Smart</span>
            </span>
        </div>
        <!-- Center: Search -->
        <div class="hidden md:flex flex-1 justify-center">
            <form method="GET" action="{{ route('search.global') }}" class="relative w-full max-w-xs">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search the entire platform..." class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                <span class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                    <i class="fas fa-search"></i>
                </span>
            </form>
        </div>
        <!-- Right: Actions -->
        <div class="flex items-center gap-4">
            <!-- Toggle Dark/Light -->
            <button class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition theme-toggle-btn" id="themeToggleBtn" title="Basculer le thème">
                <span class="hidden dark:inline"><i class="fas fa-sun text-yellow-400"></i></span>
                <span class="inline dark:hidden"><i class="fas fa-moon text-gray-700"></i></span>
            </button>
            <!-- Language Dropdown -->
            <div class="relative">
                <button class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition" id="langDropdownBtn" title="Change language">
                    <i class="fas fa-globe text-indigo-600 dark:text-indigo-300 text-xl"></i>
                </button>
                <div id="langDropdown" class="hidden absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50 border border-gray-200 dark:border-gray-700">
                    <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">English</a>
                    <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">Français</a>
                </div>
            </div>
            <!-- Notifications Dropdown -->
            @auth
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
            @endauth
            <!-- User Dropdown (Vanilla JS) -->
            @auth
            <div class="relative" id="userDropdownWrapper">
                <button id="userDropdownBtn" class="flex items-center gap-3 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <div class="relative">
                        <img 
                            src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name).'&background=4f46e5&color=fff' }}" 
                            alt="{{ Auth::user()->first_name }}" 
                            class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-md"
                        />
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-700 rounded-full"></div>
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            @php
                                $spatieRole = Auth::user()->roles->first()?->name ?? null;
                                $dbRole = Auth::user()->role ?? null;
                                $role = $spatieRole ?: $dbRole;
                                switch ($role) {
                                    case 'school_admin':
                                        $roleLabel = 'School Admin';
                                        break;
                                    case 'enseignant':
                                        $roleLabel = 'Teacher';
                                        break;
                                    case 'student':
                                        $roleLabel = 'Student';
                                        break;
                                    case 'parent':
                                        $roleLabel = 'Parent';
                                        break;
                                    case 'admin':
                                        $roleLabel = 'Admin';
                                        break;
                                    default:
                                        $roleLabel = 'User';
                                }
                            @endphp
                            {{ $roleLabel }}
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200"></i>
                </button>
                <div id="userDropdownMenu" class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-2 border border-gray-200 dark:border-gray-700 z-50 hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ Auth::user()->email }}
                        </div>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition group">
                            <i class="fas fa-tachometer-alt w-5 text-gray-400 dark:text-gray-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400"></i>
                            <span class="ml-3">{{ __('messages.dashboard') }}</span>
                        </a>
                        <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition group">
                            <i class="fas fa-cog w-5 text-gray-400 dark:text-gray-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400"></i>
                            <span class="ml-3">Settings</span>
                        </a>
                    </div>
                    <div class="py-2 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50 transition group">
                                <i class="fas fa-sign-out-alt w-5 text-red-400 dark:text-red-500 group-hover:text-red-600 dark:group-hover:text-red-400"></i>
                                <span class="ml-3">{{ __('messages.logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <!-- Login Button for non-authenticated users -->
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </a>
            </div>
            @endauth
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

        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('userDropdownBtn');
            const menu = document.getElementById('userDropdownMenu');
            const wrapper = document.getElementById('userDropdownWrapper');
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        // Language dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('langDropdownBtn');
            const menu = document.getElementById('langDropdown');
            if(btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target)) menu.classList.add('hidden');
                });
            }
        });

        // Gestion des erreurs Laravel avec popup élégant
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier s'il y a des erreurs de validation
            @if($errors->any())
                const errorMessages = [];
                @foreach($errors->all() as $error)
                    errorMessages.push('{{ addslashes($error) }}');
                @endforeach
                
                // Afficher chaque erreur dans un popup
                errorMessages.forEach(function(error) {
                    showErrorPopup(error);
                });
            @endif
        });

        // Real-time update: poll every 30 seconds
        setInterval(updateNotificationCount, 30000);
    </script>
</header>

@push('styles')
<style>
    .main-header {
        background: whitesmoke !important;
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