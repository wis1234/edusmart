<div class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('schools.index') }}" class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                    <i class="fas fa-school"></i>
                    <span>Schools</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('parents.index') }}" class="nav-link {{ request()->routeIs('parents.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Parents</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Subjects</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('class_rooms.index') }}" class="nav-link {{ request()->routeIs('class_rooms.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open"></i>
                    <span>Classrooms</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Evaluations</span>
                </a>
            </li>
            @can('viewAny', App\Models\StudentGrade::class)
            <li class="nav-item">
                <a href="{{ route('student_grades.index') }}" class="nav-link {{ request()->routeIs('student_grades.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Grades</span>
                </a>
            </li>
            @endcan
            <li class="nav-item">
                <a href="{{ route('calendars.index') }}" class="nav-link {{ request()->routeIs('calendars.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendar</span>
                </a>
            </li>

            <!-- System Menu -->
            <li class="nav-item">
                <button type="button" class="nav-link dropdown-btn">
                    <div class="flex items-center">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('messages.system') }}</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-content">
                    <li>
                        <a href="{{ route('activities.index') }}" 
                           class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>{{ __('messages.activities') }}</span>
                        </a>
                    </li>
                    @if(auth()->user() && auth()->user()->hasRole('admin'))
                    <li>
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>{{ __('messages.users') }}</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</div>

<!-- Overlay pour mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

@push('styles')
<style>
    /* Scrollbar personnalisée pour le sidebar */
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(167, 139, 250, 0.3);
        border-radius: 8px;
    }
    
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(167, 139, 250, 0.5);
    }
</style>
@endpush 