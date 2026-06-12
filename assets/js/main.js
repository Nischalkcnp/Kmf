document.addEventListener('DOMContentLoaded', function () {
    const toggle   = document.getElementById('nav-toggle');
    const closeBtn = document.getElementById('nav-close');
    const menu     = document.getElementById('mobile-menu');
    const backdrop = document.getElementById('mobile-menu-backdrop');

    function openMenu() {
        if (!menu) return;
        menu.classList.remove('translate-x-full');
        menu.classList.add('translate-x-0');
        if (backdrop) {
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-100', 'pointer-events-auto');
        }
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        if (!menu) return;
        menu.classList.remove('translate-x-0');
        menu.classList.add('translate-x-full');
        if (backdrop) {
            backdrop.classList.remove('opacity-100', 'pointer-events-auto');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
        }
        document.body.style.overflow = '';
    }

    if (toggle) toggle.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);

    // Close when clicking the backdrop
    if (backdrop) backdrop.addEventListener('click', closeMenu);

    // Close when any link inside menu is clicked
    if (menu) {
        menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeMenu);
        });
    }

    // Menu Click Animation
    document.querySelectorAll('.nav-link-animate').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href !== '#' && !this.getAttribute('target')) {
                e.preventDefault();
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    window.location.href = href;
                }, 100);
            }
        });
    });
});
