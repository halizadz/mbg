<script>
    // ==================== GLOBAL VARIABLES ====================
    let isOnline = navigator.onLine;
    let touchStartY = 0;
    let isDarkMode = localStorage.getItem('theme') !== 'light';

    // ==================== THEME MANAGEMENT ====================
    function toggleTheme() {
        const html = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        
        if (html.classList.contains('dark-mode')) {
            html.classList.remove('dark-mode');
            html.classList.add('light-mode');
            themeToggle.textContent = '🌞';
            localStorage.setItem('theme', 'light');
            showToast('Mode terang diaktifkan', 'info');
        } else {
            html.classList.remove('light-mode');
            html.classList.add('dark-mode');
            themeToggle.textContent = '🌙';
            localStorage.setItem('theme', 'dark');
            showToast('Mode gelap diaktifkan', 'info');
        }
    }

    // Initialize theme
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark-mode');
        document.documentElement.classList.add('light-mode');
        document.getElementById('themeToggle').textContent = '🌞';
    }

    document.getElementById('themeToggle').addEventListener('click', toggleTheme);

    // ==================== NETWORK STATUS ====================
    function updateNetworkStatus() {
        const networkStatus = document.getElementById('networkStatus');
        
        if (navigator.onLine) {
            networkStatus.textContent = 'Kembali online';
            networkStatus.className = 'network-status online show';
            showToast('Koneksi internet tersambung', 'success');
            
            setTimeout(() => {
                networkStatus.classList.remove('show');
            }, 3000);
        } else {
            networkStatus.textContent = 'Anda sedang offline - Menampilkan data cache';
            networkStatus.className = 'network-status offline show';
            showToast('Anda sedang offline', 'warning');
        }
    }

    window.addEventListener('online', updateNetworkStatus);
    window.addEventListener('offline', updateNetworkStatus);

    // ==================== TOAST NOTIFICATIONS ====================
    function showToast(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icon = type === 'success' ? '✅' : (type === 'error' ? '❌' : (type === 'warning' ? '⚠️' : 'ℹ️'));
        
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <span>${icon}</span>
                <span class="flex-1 text-sm">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)]">✕</button>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // ==================== PULL TO REFRESH ====================
    const content = document.querySelector('.content');
    const ptrElement = document.getElementById('ptrElement');
    const ptrText = document.getElementById('ptrText');

    if (content) {
        content.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0 && isOnline) {
                touchStartY = e.touches[0].clientY;
            }
        });

        content.addEventListener('touchmove', (e) => {
            if (touchStartY > 0 && window.scrollY === 0 && isOnline) {
                const currentY = e.touches[0].clientY;
                const diff = currentY - touchStartY;
                
                if (diff > 50 && diff < 150) {
                    ptrElement.classList.add('show');
                    ptrText.textContent = 'Lepaskan untuk refresh';
                } else if (diff >= 150) {
                    ptrText.textContent = 'Me-refresh...';
                }
            }
        });

        content.addEventListener('touchend', (e) => {
            if (touchStartY > 0 && window.scrollY === 0 && isOnline) {
                const currentY = e.changedTouches[0].clientY;
                const diff = currentY - touchStartY;
                
                if (diff > 100) {
                    location.reload();
                }
                
                ptrElement.classList.remove('show');
                ptrText.textContent = 'Tarik untuk refresh';
                touchStartY = 0;
            }
        });
    }

    document.getElementById('refreshBtn').addEventListener('click', () => {
        location.reload();
    });

    // ==================== MOBILE MENU ====================
    function toggleMobileMenu(show) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (show) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    document.getElementById('mobileMenuBtn')?.addEventListener('click', () => toggleMobileMenu(true));
    document.getElementById('sidebarOverlay')?.addEventListener('click', () => toggleMobileMenu(false));

    // Close on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            toggleMobileMenu(false);
        }
    });

    // ==================== MOBILE SEARCH ====================
    const mobileSearchDrawer = document.getElementById('mobileSearchDrawer');
    
    document.getElementById('mobileSearchBtn')?.addEventListener('click', () => {
        mobileSearchDrawer?.classList.remove('-translate-y-full');
        setTimeout(() => {
            document.getElementById('mobileSearchInput')?.focus();
        }, 300);
    });

    document.getElementById('closeMobileSearch')?.addEventListener('click', () => {
        mobileSearchDrawer?.classList.add('-translate-y-full');
    });

    // ==================== NOTIFICATION SYSTEM ====================
    document.getElementById('notificationBtn')?.addEventListener('click', () => {
        showToast('Tidak ada notifikasi baru', 'info');
        document.getElementById('notificationDot')?.classList.add('hidden');
    });

    // ==================== INITIALIZATION ====================
    document.addEventListener('DOMContentLoaded', () => {
        updateNetworkStatus();
        
        setTimeout(() => {
            showToast('Selamat datang di InvenTrack!', 'success');
        }, 1000);
    });

    // ==================== LOGOUT ====================
    document.getElementById('logoutBtn')?.addEventListener('click', () => {
        showToast('Anda akan keluar...', 'warning');
        setTimeout(() => {
            window.location.href = '/login';
        }, 1500);
    });
</script>