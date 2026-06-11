window.toggleMobileMenu = function() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (!sidebar || !overlay) return;

    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
};

window.addEventListener('resize', () => {
    const sidebar = document.getElementById('detail-sidebar');
    if (sidebar) {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('translate-x-full');
        } else {
            sidebar.classList.add('translate-x-full');
        }
    }
});
