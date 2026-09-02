(() => {
  'use strict';

  document.addEventListener('DOMContentLoaded', () => {
    const header =
      document.getElementById('site-header') ||
      document.querySelector('header');

    function updateHeader() {
      if (!header) return;
      const isScrolled = window.scrollY > 24;
      header.classList.toggle('is-scrolled', isScrolled);
    }

    updateHeader();

    window.addEventListener(
      'scroll',
      updateHeader,
      { passive: true }
    );

    /* =========================
       MOBILE MENU
    ========================= */

    const menuBtn = document.getElementById('mobile-menu-btn');
    const drawer = document.getElementById('mobile-menu-drawer');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (!menuBtn || !drawer || !overlay) return;

    let isOpen = false;

    function setMenu(open) {
      isOpen = open;

      menuBtn.setAttribute(
        'aria-expanded',
        String(open)
      );

      drawer.classList.toggle(
        'translate-x-full',
        !open
      );

      overlay.classList.toggle(
        'opacity-0',
        !open
      );

      overlay.classList.toggle(
        'pointer-events-none',
        !open
      );

      document.body.style.overflow =
        open ? 'hidden' : '';

      const spans = menuBtn.querySelectorAll('span');

      if (spans.length === 3) {
        spans[0].style.transform =
          open
            ? 'translateY(8px) rotate(45deg)'
            : '';

        spans[1].style.opacity =
          open ? '0' : '1';

        spans[2].style.transform =
          open
            ? 'translateY(-8px) rotate(-45deg)'
            : '';
      }
    }

    menuBtn.addEventListener('click', () => {
      setMenu(!isOpen);
    });

    overlay.addEventListener('click', () => {
      setMenu(false);
    });

    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && isOpen) {
        setMenu(false);
      }
    });

    drawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        setMenu(false);
      });
    });
  });
})();