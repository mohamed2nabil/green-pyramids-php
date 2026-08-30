document.addEventListener('DOMContentLoaded', () => {
  // 1. Intersection Observer for Scroll Reveals
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        const elements = entry.target.querySelectorAll('.word, .letter');
        elements.forEach((el, index) => {
          el.style.transitionDelay = (index * 0.05) + 's';
        });
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.reveal-fade, .reveal-up').forEach(el => {
    observer.observe(el);
  });

  // 2. Mobile Menu Drawer Toggle
  const menuBtn = document.getElementById('mobile-menu-btn');
  const menuDrawer = document.getElementById('mobile-menu-drawer');
  if (menuBtn && menuDrawer) {
    menuBtn.addEventListener('click', () => {
      const isOpen = !menuDrawer.classList.contains('translate-x-full');
      if (isOpen) {
        menuDrawer.classList.add('translate-x-full');
        document.body.style.overflow = '';
      } else {
        menuDrawer.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
      }
    });
  }

  // 3. Process Page SVG S-Curve Line Scroll Drawing
  const path = document.querySelector('svg path[pathLength="1"]');
  if (path) {
    const updatePathDraw = () => {
      const scrollTotal = document.documentElement.scrollHeight - window.innerHeight;
      if (scrollTotal <= 0) return;
      const scrollPercent = window.scrollY / scrollTotal;
      // Animate strokeDashoffset from 1.0 (hidden) down to 0.0 (fully drawn)
      const offset = Math.max(0, Math.min(1, 1 - (scrollPercent * 1.25)));
      path.style.strokeDashoffset = offset;
    };

    window.addEventListener('scroll', updatePathDraw, { passive: true });
    updatePathDraw();
  }

  // 4. Header Shadow & Background on Scroll
  const header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 30) {
        header.classList.add('shadow-lg', 'bg-[#173F35]/95', 'backdrop-blur-md');
      } else {
        header.classList.remove('shadow-lg');
      }
    }, { passive: true });
  }
});
