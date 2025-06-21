<div class="sidebar fixed top-[60px] left-0 h-[calc(100vh-60px)] w-64 z-40 bg-gradient-to-br from-slate-50 via-white to-blue-50/30 shadow-xl border-r border-slate-200/60 overflow-y-auto" id="sidebar">
    <div class="sidebar-header p-3 border-bottom">
        <!-- <div class="d-flex align-items-center">
            <i class="fas fa-graduation-cap text-primary me-2"></i>
            <h5 class="mb-0">{{ config('app.name', 'EduSmart') }}</h5>
        </div> -->
    </div>
    <div class="sidebar-content">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>{{ __('messages.dashboard') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('schools.index') }}" class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                    <i class="fas fa-school me-2"></i>
                    <span>{{ __('messages.schools') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    <span>{{ __('messages.teachers') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate me-2"></i>
                    <span>{{ __('messages.students') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('parents.index') }}" class="nav-link {{ request()->routeIs('parents.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>
                    <span>{{ __('messages.parents') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i>
                    <span>{{ __('messages.subjects') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('class_rooms.index') }}" class="nav-link {{ request()->routeIs('class_rooms.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open me-2"></i>
                    <span>{{ __('messages.classrooms') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks me-2"></i>
                    <span>{{ __('messages.evaluations') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.student_grades.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    <span>{{ __('messages.grades') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calendars.index') }}" class="nav-link {{ request()->routeIs('calendars.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <span>{{ __('messages.calendar') }}</span>
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
        color: var(--text-secondary);
        transition: all 0.25s cubic-bezier(.4,0,.2,1);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        background: transparent;
        z-index: 1;
    }

    .nav-link:before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 4px;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 2;
    }

    .nav-link.active:before {
        opacity: 1;
    }

    .nav-link.active {
        background: linear-gradient(90deg, rgba(79,70,229,0.10) 0%, rgba(129,140,248,0.08) 100%);
        color: var(--primary);
        box-shadow: 0 4px 24px 0 var(--primary-light);
        border-left: 0;
        font-weight: 700;
        transform: scale(1.04);
    }

    .nav-link:after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 0;
        height: 0;
        background: var(--primary-light);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s, opacity 0.3s;
        opacity: 0;
        z-index: 0;
    }

    .nav-link:active:after {
        width: 120%;
        height: 120%;
        opacity: 0.18;
        transition: 0s;
    }

    .nav-link:hover {
        background: linear-gradient(90deg, rgba(79,70,229,0.13) 0%, rgba(129,140,248,0.10) 100%);
        color: var(--primary);
        box-shadow: 0 4px 24px 0 var(--primary-light), 0 2px 8px 0 var(--primary);
        transform: translateX(2px) scale(1.03);
    }

    .nav-link:focus {
        outline: none;
        box-shadow: 0 0 0 2px var(--primary-light);
    }

    .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
        transition: all 0.2s cubic-bezier(.4,0,.2,1);
        filter: drop-shadow(0 1px 2px var(--primary-light));
    }

    .nav-link.active i {
        color: var(--primary-dark);
        filter: drop-shadow(0 2px 6px var(--primary-light));
    }

    .nav-link:hover i {
        transform: scale(1.12) rotate(-6deg);
        color: var(--primary);
    }

    .nav-link span {
        margin-left: 0.5rem;
        font-weight: 500;
        letter-spacing: 0.01em;
        font-size: 1.05em;
    }

    .sidebar-header {
        background: white;
    }

    .sidebar-header i {
        font-size: 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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
        background: var(--primary-light);
    }
</style>
@endpush 