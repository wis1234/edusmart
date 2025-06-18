<div class="sidebar" id="sidebar">
    <div class="sidebar-header p-3 border-bottom">
        <div class="d-flex align-items-center">
            <i class="fas fa-graduation-cap text-primary me-2"></i>
            <h5 class="mb-0">{{ config('app.name', 'EduSmart') }}</h5>
        </div>
    </div>
    <div class="sidebar-content">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('schools.index') }}" class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                    <i class="fas fa-school me-2"></i>
                    <span>Schools</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate me-2"></i>
                    <span>Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('parents.index') }}" class="nav-link {{ request()->routeIs('parents.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>
                    <span>Parents</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i>
                    <span>Subjects</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('class_rooms.index') }}" class="nav-link {{ request()->routeIs('class_rooms.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open me-2"></i>
                    <span>Classrooms</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks me-2"></i>
                    <span>Evaluations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.student_grades.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    <span>Grades</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calendars.index') }}" class="nav-link {{ request()->routeIs('calendars.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <span>Calendar</span>
                </a>
            </li>
        </ul>
    </div>
</div>

@push('styles')
<style>
    .sidebar {
        overflow-y: auto;
        background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
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
        position: relative;
        overflow: hidden;
    }

    .nav-link:hover {
        background-color: rgba(79, 70, 229, 0.05);
        color: #4f46e5;
    }

    .nav-link.active {
        background: linear-gradient(90deg, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0.05) 100%);
        color: #4f46e5;
        border-left: 4px solid #4f46e5;
    }

    .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
        transition: all 0.2s ease;
    }

    .nav-link:hover i {
        transform: scale(1.1);
    }

    .nav-link span {
        margin-left: 0.5rem;
        font-weight: 500;
    }

    .sidebar-header {
        background: white;
    }

    .sidebar-header i {
        font-size: 1.5rem;
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Scrollbar */
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 2px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>
@endpush 