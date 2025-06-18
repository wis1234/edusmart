<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid">
        <!-- Toggle Button -->
        <button class="btn btn-link text-dark p-0 me-3" id="sidebarToggle" type="button">
            <i class="fas fa-bars fa-lg"></i>
        </button>

        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-graduation-cap text-primary me-2"></i>
            {{ config('app.name', 'EduSmart') }}
        </a>

        <!-- Right Side -->
        <div class="ms-auto d-flex align-items-center">
            <!-- Notifications -->
            <div class="dropdown me-3">
                <button class="btn btn-link text-dark position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-bell fa-lg"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                    <h6 class="dropdown-header">Notifications</h6>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-plus text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="mb-0">New student enrolled</p>
                                <small class="text-muted">5 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="#">View all notifications</a>
                </div>
            </div>

            <!-- User Menu -->
            <div class="dropdown">
                <button class="btn btn-link text-dark d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random" 
                         class="rounded-circle me-2" 
                         width="32" 
                         height="32" 
                         alt="{{ auth()->user()->name }}">
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i> Settings
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
</nav>

<!-- Sidebar -->
<div class="sidebar bg-white shadow-sm" id="sidebar">
    <div class="sidebar-header p-3 border-bottom">
        <h5 class="mb-0">Menu</h5>
    </div>
    <div class="sidebar-content">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('schools.index') }}" class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                    <i class="fas fa-school me-2"></i>
                    Schools
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate me-2"></i>
                    Students
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i>
                    Subjects
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks me-2"></i>
                    Evaluations
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

@push('styles')
<style>
    /* Navbar Styles */
    .navbar {
        height: 56px;
        z-index: 1030;
    }

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 56px;
        left: 0;
        height: calc(100vh - 56px);
        width: 250px;
        z-index: 1020;
        transition: all 0.3s ease-in-out;
        overflow-y: auto;
    }

    .sidebar.collapsed {
        transform: translateX(-250px);
    }

    .sidebar-content {
        padding: 1rem 0;
    }

    .nav-link {
        padding: 0.75rem 1.5rem;
        color: #4b5563;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
    }

    .nav-link:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    .nav-link.active {
        background-color: #eef2ff;
        color: #4f46e5;
        border-left: 4px solid #4f46e5;
    }

    .nav-link i {
        width: 20px;
        text-align: center;
    }

    /* Overlay */
    .sidebar-overlay {
        position: fixed;
        top: 56px;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1010;
        display: none;
    }

    /* Main Content Adjustment */
    .main-content {
        margin-left: 250px;
        margin-top: 56px;
        transition: margin-left 0.3s ease-in-out;
        min-height: calc(100vh - 56px);
        padding: 1.5rem;
    }

    .main-content.expanded {
        margin-left: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-250px);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .sidebar-overlay.show {
            display: block;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');

    // Fonction pour basculer la sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    }

    // Fonction pour afficher la sidebar sur mobile
    function showSidebar() {
        sidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
    }

    // Fonction pour cacher la sidebar sur mobile
    function hideSidebar() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    }

    // Gestionnaire d'événements pour le bouton toggle
    sidebarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        if (window.innerWidth <= 768) {
            showSidebar();
        } else {
            toggleSidebar();
        }
    });

    // Gestionnaire d'événements pour l'overlay
    sidebarOverlay.addEventListener('click', hideSidebar);

    // Gestion du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        }
    });

    // Initialisation de l'état de la sidebar sur mobile
    if (window.innerWidth <= 768) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
    }
});
</script>
@endpush

