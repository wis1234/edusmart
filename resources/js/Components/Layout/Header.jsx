import React from 'react';

const Header = () => {
  return (
    <header className="flex items-center justify-between p-4 bg-white dark:bg-gray-800 shadow">
      <div className="flex items-center gap-4">
        <button
          id="sidebarToggle"
          className="text-gray-500 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md"
          aria-label="Toggle sidebar"
          onClick={() => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar && overlay) {
              sidebar.classList.toggle('hidden');
              overlay.classList.toggle('hidden');
            }
          }}
        >
          <svg
            className="w-6 h-6"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
        <h1 className="text-2xl font-semibold text-gray-900 dark:text-white">EduSmart</h1>
      </div>
      <div className="flex items-center gap-4">
        {/* Add other header items like user profile, notifications, etc. here */}
        <button
          className="p-2 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
          title="Refresh"
          onClick={() => window.location.reload()}
        >
          <i className="fas fa-sync-alt text-indigo-600"></i>
        </button>
      </div>
    </header>
  );
};

export default Header;
