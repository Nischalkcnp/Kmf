document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('nav-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const navIcon = document.getElementById('nav-icon');

    if (toggle && mobileNav && navIcon) {
        toggle.addEventListener('click', function () {
            const spans = navIcon.querySelectorAll('span');
            mobileNav.classList.toggle('hidden');
            
            if (!mobileNav.classList.contains('hidden')) {
                // Open state
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -7px)';
            } else {
                // Closed state
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
    }
});
