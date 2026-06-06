document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('nav-toggle');
    const closeBtn = document.getElementById('nav-close');
    const menu = document.getElementById('mobile-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            menu.classList.remove('translate-x-full');
            menu.classList.add('translate-x-0');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeBtn && menu) {
        closeBtn.addEventListener('click', function () {
            menu.classList.remove('translate-x-0');
            menu.classList.add('translate-x-full');
            document.body.style.overflow = '';
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
