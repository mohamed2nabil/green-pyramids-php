(function () {
    'use strict';

    function closeAllUserMenus(exceptMenu) {
        document.querySelectorAll('.user-menu.is-open').forEach((m) => {
            if (exceptMenu && m === exceptMenu) return;
            m.classList.remove('is-open');
        });
    }

    function initUserMenus() {
        document.querySelectorAll('.user-menu').forEach((menu) => {
            const toggle = menu.querySelector('.user-menu-toggle');
            if (!toggle) return;

            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const willOpen = !menu.classList.contains('is-open');
                closeAllUserMenus(menu);
                menu.classList.toggle('is-open', willOpen);
            });
        });

        document.addEventListener('click', () => closeAllUserMenus());
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeAllUserMenus();
        });
    }

    function initAvatarFallback() {
        document.querySelectorAll('img[data-fallback-avatar]').forEach((img) => {
            img.addEventListener('error', () => {
                img.src = 'assets/user.png';
            }, { once: true });
        });
    }

    function init() {
        initUserMenus();
        initAvatarFallback();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
