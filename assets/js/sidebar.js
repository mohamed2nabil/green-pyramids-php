(function () {
    'use strict';

    // 🔹 Get current page name
    function currentPageFile() {
        let name = (window.location.pathname || '').split('/').pop();
        if (!name) {
            return 'index.php';
        }
        return name;
    }

    // 🔹 Set active nav link
    function setActiveNav() {
        const page = currentPageFile();

        document.querySelectorAll('.sidebar .nav-item').forEach((item) => {
            item.classList.remove('active');
        });

        document.querySelectorAll('.sidebar .nav-link').forEach((link) => {
            const href = link.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript:')) return;

            try {
                const target = href.split('/').pop();
                if (target === page) {
                    link.closest('.nav-item')?.classList.add('active');
                }
            } catch (_) {
                /* ignore */
            }
        });
    }

    // 🔹 Close sidebar (mobile)
    function closeSidebar() {
        document.body.classList.remove('sidebar-open');

        const toggle = document.querySelector('.sidebar-toggle');
        const backdrop = document.querySelector('.sidebar-backdrop');

        if (toggle) {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Open navigation menu');
        }

        if (backdrop) {
            backdrop.hidden = true;
        }

        document.body.style.overflow = '';
    }

    // 🔹 Open sidebar (mobile)
    function openSidebar() {
        document.body.classList.add('sidebar-open');

        const toggle = document.querySelector('.sidebar-toggle');
        const backdrop = document.querySelector('.sidebar-backdrop');

        if (toggle) {
            toggle.setAttribute('aria-expanded', 'true');
            toggle.setAttribute('aria-label', 'Close navigation menu');
        }

        if (backdrop) {
            backdrop.hidden = false;
        }

        document.body.style.overflow = 'hidden';
    }

    // 🔹 Mobile behavior
    function initMobileNav() {
        const toggle = document.querySelector('.sidebar-toggle');
        const backdrop = document.querySelector('.sidebar-backdrop');

        if (!toggle) return;

        toggle.addEventListener('click', () => {
            if (document.body.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        if (backdrop) {
            backdrop.addEventListener('click', closeSidebar);
        }

        document.querySelectorAll('.sidebar .nav-link').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.matchMedia('(max-width: 1024px)').matches) {
                    closeSidebar();
                }
            });
        });

        // ESC key closes sidebar
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.body.classList.contains('sidebar-open')) {
                closeSidebar();
            }
        });

        // Resize fix
        window.addEventListener('resize', () => {
            if (window.matchMedia('(min-width: 1025px)').matches) {
                closeSidebar();
            }
        });
    }

    // 🔹 Auto init
    document.addEventListener('DOMContentLoaded', () => {
        setActiveNav();
        initMobileNav();
    });

})();
