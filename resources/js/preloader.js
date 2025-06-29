
document.addEventListener('DOMContentLoaded', function() {
    const preloader = document.getElementById('preloader-overlay');

    // Function to show the preloader
    function showPreloader() {
        if (preloader) {
            preloader.style.display = 'flex';
        }
    }

    // Function to hide the preloader
    function hidePreloader() {
        if (preloader) {
            preloader.style.display = 'none';
        }
    }

    // Show preloader for navigation events
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function(e) {
            // Exclude external links, links with target="_blank", and no-loading links
            if (this.hostname !== window.location.hostname || this.target === '_blank' || this.hasAttribute('data-no-loading')) {
                return;
            }
            showPreloader();
        });
    });

    // Show preloader for form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            if (!this.hasAttribute('data-no-loading')) {
                showPreloader();
            }
        });
    });

    // Hide preloader when the page is fully loaded
    window.addEventListener('load', () => {
        hidePreloader();
    });

    // Also hide on DOMContentLoaded as a fallback
    hidePreloader();
});
