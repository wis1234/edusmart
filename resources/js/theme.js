// Global Theme Management
console.log('Theme management script loaded - version 1.2');

// Define toggleTheme function immediately
window.toggleTheme = function() {
    console.log('toggleTheme function called');
    const currentTheme = document.documentElement.className;
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    document.documentElement.className = newTheme;
    localStorage.setItem('theme', newTheme);

    // Dispatch custom event for other components
    window.dispatchEvent(new CustomEvent('themeChanged', { 
        detail: { theme: newTheme } 
    }));

    // Update all theme toggle buttons
    const toggleButtons = document.querySelectorAll('.theme-toggle-btn');
    toggleButtons.forEach(button => {
        const sunIcon = button.querySelector('.fa-sun');
        const moonIcon = button.querySelector('.fa-moon');

        if (newTheme === 'dark') {
            if (sunIcon) sunIcon.style.display = 'inline';
            if (moonIcon) moonIcon.style.display = 'none';
        } else {
            if (sunIcon) sunIcon.style.display = 'none';
            if (moonIcon) moonIcon.style.display = 'inline';
        }
    });
};

// Initialize theme on page load
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme) {
        document.documentElement.className = savedTheme;
    } else if (prefersDark) {
        document.documentElement.className = 'dark';
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.className = 'light';
        localStorage.setItem('theme', 'light');
    }
}

// Initialize theme immediately
initializeTheme();

// Debug: Verify function is available
console.log('toggleTheme function defined:', typeof window.toggleTheme);

// Force function availability
if (typeof window.toggleTheme === 'undefined') {
    console.error('toggleTheme function is not defined!');
} else {
    console.log('toggleTheme function is properly defined and available');
} 