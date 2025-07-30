import React from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';

const Sidebar = () => {
  const { url } = usePage();

  const navItems = [
    { route: 'dashboard', icon: 'fa-tachometer-alt', label: 'Dashboard' },
    { route: 'schools.index', icon: 'fa-school', label: 'Schools' },
    { route: 'teachers.index', icon: 'fa-chalkboard-teacher', label: 'Teachers' },
    { route: 'students.index', icon: 'fa-user-graduate', label: 'Students' },
    { route: 'parents.index', icon: 'fa-users', label: 'Parents' },
    { route: 'subjects.index', icon: 'fa-book', label: 'Subjects' },
    { route: 'class_rooms.index', icon: 'fa-door-open', label: 'Classrooms' },
    { route: 'evaluations.index', icon: 'fa-tasks', label: 'Evaluations' },
    { route: 'student_grades.index', icon: 'fa-chart-bar', label: 'Grades', canView: true },
    { route: 'calendars.index', icon: 'fa-calendar-alt', label: 'Calendar' },
  ];

  // Dropdown menus data
  const dropdowns = [
    {
      id: 'gifts',
      icon: 'fa-gift',
      label: 'Gifts',
      items: [
        { route: 'gifts.index', icon: 'fa-list', label: 'List Gifts' },
        { route: 'gifts.admin', icon: 'fa-cogs', label: 'Gifts Admin', canView: true },
      ],
    },
    {
      id: 'system',
      icon: 'fa-cog',
      label: 'System',
      items: [
        { route: 'activities.index', icon: 'fa-history', label: 'Activities' },
        { route: 'users.index', icon: 'fa-users', label: 'Users', canView: true },
      ],
    },
  ];

  // Helper to check if route is active
  const isActive = (routeName) => {
    return url.includes(routeName.replace('.', '/'));
  };

  // Render nav item
  const renderNavItem = (item) => {
    if (item.canView === false) return null; // Skip if no permission
    return (
      <li key={item.route} className="nav-item">
        <InertiaLink
          href={route(item.route)}
          className={`nav-link ${isActive(item.route) ? 'active' : ''}`}
        >
          <i className={`fas ${item.icon}`}></i>
          <span>{item.label}</span>
        </InertiaLink>
      </li>
    );
  };

  // Render dropdown menu
  const renderDropdown = (dropdown) => {
    const [open, setOpen] = React.useState(false);

    // Check if any child route is active
    const isDropdownActive = dropdown.items.some((item) => isActive(item.route));

    return (
      <li key={dropdown.id} className="nav-item">
        <button
          type="button"
          className={`nav-link dropdown-btn ${isDropdownActive ? 'active' : ''}`}
          onClick={() => setOpen(!open)}
        >
          <div className="flex items-center">
            <i className={`fas ${dropdown.icon}`}></i>
            <span>{dropdown.label}</span>
          </div>
          <i className={`fas fa-chevron-${open ? 'up' : 'down'}`}></i>
        </button>
        {open && (
          <ul className="dropdown-content">
            {dropdown.items.map((item) => {
              if (item.canView === false) return null;
              return (
                <li key={item.route}>
                  <InertiaLink
                    href={route(item.route)}
                    className={`nav-link ${isActive(item.route) ? 'active' : ''}`}
                  >
                    <i className={`fas ${item.icon}`}></i>
                    <span>{item.label}</span>
                  </InertiaLink>
                </li>
              );
            })}
          </ul>
        )}
      </li>
    );
  };

  return (
    <>
      <div className="sidebar main-sidebar" id="sidebar">
        <div className="sidebar-content">
          <ul className="nav">
            {navItems.map(renderNavItem)}
            {dropdowns.map(renderDropdown)}
          </ul>
        </div>
      </div>
      <div className="sidebar-overlay" id="sidebarOverlay"></div>
    </>
  );
};

export default Sidebar;
