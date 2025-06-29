// App Responsive JavaScript - Header et Sidebar

class ResponsiveApp {
    constructor() {
        this.sidebar = document.querySelector('.main-sidebar');
        this.header = document.querySelector('.main-header');
        this.content = document.querySelector('.main-content');
        this.sidebarToggle = document.querySelector('.sidebar-toggle');
        this.dropdowns = document.querySelectorAll('.dropdown');
        this.themeToggle = document.querySelector('.theme-toggle');
        this.notificationToggle = document.querySelector('.notification-toggle');
        this.notificationDropdown = document.querySelector('.notification-dropdown');
        this.notificationCount = document.querySelector('.notification-count');
        
        this.isSidebarOpen = this.getSidebarState();
        
        this.init();
    }
    
    init() {
        this.setupSidebar();
        this.setupDropdowns();
        this.setupThemeToggle();
        this.setupNotifications();
        this.setupTouchGestures();
        this.setupResponsive();
        
        // Initialiser l'état
        this.updateLayout();
    }
    
    setupSidebar() {
        if (!this.sidebarToggle) {
            console.error('Sidebar toggle button not found!');
            return;
        }
        
        this.sidebarToggle.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleSidebar();
        });
        
        // Fermer la sidebar en cliquant à l'extérieur sur mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && this.isSidebarOpen) {
                if (!this.sidebar.contains(e.target) && !this.sidebarToggle.contains(e.target)) {
                    this.closeSidebar();
                }
            }
        });
    }
    
    setupDropdowns() {
        this.dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            if (!toggle || !menu) return;
            
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                // Fermer tous les autres dropdowns
                this.dropdowns.forEach(other => {
                    if (other !== dropdown) {
                        other.classList.remove('open');
                    }
                });
                
                dropdown.classList.toggle('open');
            });
        });
        
        // Fermer les dropdowns en cliquant à l'extérieur
        document.addEventListener('click', () => {
            this.dropdowns.forEach(dropdown => {
                dropdown.classList.remove('open');
            });
        });
    }
    
    setupThemeToggle() {
        if (!this.themeToggle) return;
        
        this.themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleTheme();
        });
    }
    
    setupNotifications() {
        if (!this.notificationToggle || !this.notificationDropdown) return;
        
        this.notificationToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleNotifications();
        });
        
        // Fermer les notifications en cliquant à l'extérieur
        document.addEventListener('click', () => {
            this.notificationDropdown.classList.remove('open');
        });
    }
    
    setupTouchGestures() {
        let startX = 0;
        let startY = 0;
        let isDragging = false;
        
        // Gestion des gestes tactiles pour la sidebar
        document.addEventListener('touchstart', (e) => {
            if (window.innerWidth <= 768) {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isDragging = false;
            }
        });
        
        document.addEventListener('touchmove', (e) => {
            if (window.innerWidth <= 768 && startX > 0) {
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const diffX = currentX - startX;
                const diffY = currentY - startY;
                
                // Détecter un swipe horizontal
                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    isDragging = true;
                    
                    if (diffX > 0 && startX < 50 && !this.isSidebarOpen) {
                        // Swipe de gauche à droite depuis le bord
                        this.openSidebar();
                    } else if (diffX < 0 && this.isSidebarOpen) {
                        // Swipe de droite à gauche
                        this.closeSidebar();
                    }
                }
            }
        });
        
        document.addEventListener('touchend', () => {
            startX = 0;
            startY = 0;
            isDragging = false;
        });
    }
    
    setupResponsive() {
        window.addEventListener('resize', () => {
            this.updateLayout();
        });
    }
    
    toggleSidebar() {
        this.isSidebarOpen = !this.isSidebarOpen;
        this.saveSidebarState();
        this.updateLayout();
    }
    
    openSidebar() {
        this.isSidebarOpen = true;
        this.saveSidebarState();
        this.updateLayout();
    }
    
    closeSidebar() {
        this.isSidebarOpen = false;
        this.saveSidebarState();
        this.updateLayout();
    }
    
    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Mettre à jour l'icône
        const icon = this.themeToggle.querySelector('i');
        if (icon) {
            icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    }
    
    toggleNotifications() {
        this.notificationDropdown.classList.toggle('open');
    }
    
    updateLayout() {
        if (!this.sidebar || !this.header || !this.content) return;
        
        const isMobile = window.innerWidth <= 768;
        const isTablet = window.innerWidth > 768 && window.innerWidth <= 1024;
        
        if (isMobile) {
            // Mode mobile
            if (this.isSidebarOpen) {
                this.sidebar.classList.add('show');
                this.content.classList.add('expanded');
            } else {
                this.sidebar.classList.remove('show');
                this.content.classList.remove('expanded');
            }
        } else if (isTablet) {
            // Mode tablet
            if (this.isSidebarOpen) {
                this.sidebar.classList.remove('collapsed');
                this.content.classList.remove('expanded');
            } else {
                this.sidebar.classList.add('collapsed');
                this.content.classList.add('expanded');
            }
        } else {
            // Mode desktop
            if (this.isSidebarOpen) {
                this.sidebar.classList.remove('collapsed');
                this.content.classList.remove('expanded');
            } else {
                this.sidebar.classList.add('collapsed');
                this.content.classList.add('expanded');
            }
        }
    }
    
    getSidebarState() {
        const saved = localStorage.getItem('sidebar-open');
        if (saved !== null) {
            return JSON.parse(saved);
        }
        // Par défaut, fermé sur mobile, ouvert sur desktop
        // Sur desktop, la sidebar est ouverte par défaut (pas de classe collapsed)
        return window.innerWidth > 768;
    }
    
    saveSidebarState() {
        localStorage.setItem('sidebar-open', JSON.stringify(this.isSidebarOpen));
    }
    
    // Méthode pour mettre à jour le compteur de notifications
    updateNotificationCount(count) {
        if (this.notificationCount) {
            this.notificationCount.textContent = count;
            this.notificationCount.style.display = count > 0 ? 'block' : 'none';
        }
    }
}

// Initialiser quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
    window.responsiveApp = new ResponsiveApp();
});

// Exposer la classe pour un usage global
window.ResponsiveApp = ResponsiveApp; 