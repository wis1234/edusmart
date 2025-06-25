<div class="sidebar fixed top-[60px] left-0 h-[calc(100vh-60px)] w-64 z-40 bg-[whitesmoke] shadow-2xl border-r border-slate-200/60 overflow-y-auto transition-all duration-300" id="sidebar">
    <div class="sidebar-content">
        <ul class="nav flex-column space-y-1 mt-4">
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
            <li class="nav-item dropdown">
                <button type="button" class="nav-link w-full flex items-center justify-between dropdown-btn">
                    <div class="flex items-center">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('messages.system') }}</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-content hidden pl-4 py-2 space-y-1">
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

@push('styles')
<style>
    .sidebar {
        background: whitesmoke !important;
        box-shadow: 0 8px 32px 0 rgba(80, 72, 229, 0.10), 0 1.5px 8px 0 rgba(129,140,248,0.10);
        /* border-top-right-radius: 2rem; */
        /* border-bottom-right-radius: 2rem; */
    }
    .sidebar-content {
        padding: 1rem 0;
    }
    .nav-link {
        padding: 0.75rem 1.5rem;
        color: #6366f1;
        background: transparent;
        border-radius: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        transition: all 0.22s cubic-bezier(.4,0,.2,1);
        z-index: 1;
        box-shadow: none;
    }
    .nav-link:before {
        content: '';
        position: absolute;
        left: 1rem; top: 50%;
        transform: translateY(-50%);
        width: 8px; height: 36px;
        background: linear-gradient(135deg, #6366f1, #a78bfa);
        border-radius: 8px;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 2;
    }
    .nav-link.active:before {
        opacity: 1;
    }
    .nav-link.active {
        background: linear-gradient(90deg, #6366f1 0%, #a78bfa 100%);
        color: #fff;
        box-shadow: 0 4px 24px 0 #a78bfa33;
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
        background: #a78bfa;
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
        background: linear-gradient(90deg, #6366f1 0%, #a78bfa 100%);
        color: #fff;
        box-shadow: 0 4px 24px 0 #a78bfa33, 0 2px 8px 0 #6366f1;
        transform: translateX(2px) scale(1.03);
    }
    .nav-link:focus {
        outline: none;
        box-shadow: 0 0 0 2px #a78bfa;
    }
    .nav-link i {
        width: 24px;
        text-align: center;
        font-size: 1.25rem;
        transition: all 0.2s cubic-bezier(.4,0,.2,1);
        filter: drop-shadow(0 1px 2px #a78bfa);
    }
    .nav-link.active i {
        color: #fff;
        filter: drop-shadow(0 2px 6px #a78bfa);
    }
    .nav-link:hover i {
        transform: scale(1.12) rotate(-6deg);
        color: #fff;
    }
    .nav-link span {
        margin-left: 0.5rem;
        font-weight: 500;
        letter-spacing: 0.01em;
        font-size: 1.08em;
    }
    .sidebar-header {
        background: linear-gradient(135deg, #f0f4ff 0%, #f9f7fd 60%, #e9e4fc 100%);
    }
    .sidebar-header i {
        font-size: 2rem;
        background: linear-gradient(135deg, #6366f1, #a78bfa);
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
        background: #a78bfa44;
        border-radius: 8px;
    }

    /* Submenu styles */
    .nav-submenu {
        overflow: hidden;
    }

    .nav-submenu .nav-link {
        padding-left: 1rem;
        font-size: 0.95em;
        opacity: 0.9;
    }

    .nav-submenu .nav-link:before {
        display: none;
    }

    .nav-submenu .nav-link:hover {
        transform: translateX(4px);
    }

    .nav-submenu .nav-link.active {
        transform: none;
        background: transparent;
        color: #6366f1;
        font-weight: 600;
    }

    .nav-submenu .nav-link.active i {
        color: #6366f1;
    }

    /* System submenu styles */
    .nav-item button.nav-link {
        text-align: left;
        width: 100%;
    }

    .nav-item button.nav-link:focus {
        outline: none;
    }

    .nav-item .submenu {
        transition: max-height 0.2s ease-out;
    }

    .nav-item .submenu .nav-link {
        padding-left: 2.5rem;
    }

    .nav-item .submenu .nav-link:hover {
        background: rgba(99, 102, 241, 0.1);
    }

    .nav-item .submenu .nav-link.active {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        font-weight: 600;
    }

    /* Dropdown styles */
    .dropdown-btn {
        width: 100%;
        text-align: left;
        transition: all 0.3s ease;
    }

    .dropdown-btn:focus {
        outline: none;
    }

    .dropdown-content {
        background: rgba(99, 102, 241, 0.05);
        border-left: 2px solid rgba(99, 102, 241, 0.2);
        margin-left: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .dropdown-content .nav-link {
        padding-left: 2.5rem;
        font-size: 0.95em;
        opacity: 0.9;
    }

    .dropdown-content .nav-link:hover {
        background: rgba(99, 102, 241, 0.1);
        transform: translateX(4px);
    }

    .dropdown-content .nav-link.active {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        font-weight: 600;
    }

    .dropdown-content .nav-link.active i {
        color: #6366f1;
    }

    /* Show dropdown content when parent is active */
    .nav-item.active .dropdown-content {
        display: block;
    }

    .nav-item.active .dropdown-btn .fa-chevron-down {
        transform: rotate(180deg);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all dropdown buttons
    const dropdownBtns = document.querySelectorAll('.dropdown-btn');

    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Toggle active class on parent li
            const parentLi = this.closest('.nav-item');
            parentLi.classList.toggle('active');

            // Toggle dropdown content
            const dropdownContent = this.nextElementSibling;
            if (dropdownContent.classList.contains('hidden')) {
                dropdownContent.classList.remove('hidden');
                dropdownContent.style.maxHeight = dropdownContent.scrollHeight + 'px';
            } else {
                dropdownContent.classList.add('hidden');
                dropdownContent.style.maxHeight = '0';
            }

            // Rotate chevron icon
            const chevron = this.querySelector('.fa-chevron-down');
            chevron.style.transform = dropdownContent.classList.contains('hidden') ? 'rotate(0)' : 'rotate(180deg)';
        });
    });

    // Auto-expand dropdown if a child link is active
    const activeLinks = document.querySelectorAll('.dropdown-content .nav-link.active');
    activeLinks.forEach(link => {
        const parentLi = link.closest('.nav-item');
        const dropdownContent = link.closest('.dropdown-content');
        if (parentLi && dropdownContent) {
            parentLi.classList.add('active');
            dropdownContent.classList.remove('hidden');
            dropdownContent.style.maxHeight = dropdownContent.scrollHeight + 'px';
            const chevron = parentLi.querySelector('.fa-chevron-down');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
    });
});
</script>
@endpush 