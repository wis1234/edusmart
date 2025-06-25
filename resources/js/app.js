// Notifications - Only if Echo is available
if (typeof Echo !== 'undefined' && typeof userId !== 'undefined') {
    Echo.private(`user.${userId}`)
        .listen('NewNotification', (e) => {
            // Mettre à jour le compteur de notifications
            updateNotificationCount();

            // Ajouter la nouvelle notification à la liste
            const notificationsList = document.querySelector('.notifications-list');
            if (notificationsList) {
                const notificationHtml = `
                    <a href="${e.link || '#'}" 
                       class="dropdown-item notification-item bg-light"
                       data-notification-id="${e.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                ${getNotificationIcon(e.type)}
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="mb-0 text-sm">${e.title}</p>
                                <small class="text-muted">${e.created_at}</small>
                            </div>
                        </div>
                    </a>
                `;
                notificationsList.insertAdjacentHTML('afterbegin', notificationHtml);
            }

            // Afficher une notification toast
            showNotificationToast(e);
        });
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success':
            return '<i class="fas fa-check-circle text-success"></i>';
        case 'warning':
            return '<i class="fas fa-exclamation-triangle text-warning"></i>';
        case 'error':
            return '<i class="fas fa-times-circle text-danger"></i>';
        default:
            return '<i class="fas fa-bell text-primary"></i>';
    }
}

function showNotificationToast(notification) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
        <div class="toast-header">
            ${getNotificationIcon(notification.type)}
            <strong class="me-auto">${notification.title}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${notification.message}
        </div>
    `;

    document.body.appendChild(toast);
    
    // Simple timeout to remove toast instead of bootstrap
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Preloader logic
function showPreloader() {
    const preloader = document.getElementById('preloader-overlay');
    if (preloader) preloader.style.display = 'flex';
}

function hidePreloader() {
    const preloader = document.getElementById('preloader-overlay');
    if (preloader) preloader.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    hidePreloader(); // Hide on initial load

    // Navigation links
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.hostname !== window.location.hostname || this.target === '_blank') return;
            if (this.hasAttribute('data-no-loading')) return;
            showPreloader();
        });
    });

    // Form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.hasAttribute('data-no-loading')) {
                showPreloader();
            }
        });
    });

    // Hide preloader after page load
    window.addEventListener('pageshow', hidePreloader);
    window.addEventListener('load', hidePreloader);
}); 