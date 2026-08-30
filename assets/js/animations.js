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

  // 5. Product Showcase Pinned Horizontal Scroll Gallery (GSAP + ScrollTrigger)
  initProductShowcaseGallery();
});

function initProductShowcaseGallery() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const stage = document.getElementById('product-showcase-stage');
  const viewport = document.getElementById('product-showcase-viewport');
  const track = document.getElementById('product-showcase-track');

  if (!stage || !viewport || !track) return;

  gsap.registerPlugin(ScrollTrigger);

  const mediaQueryMobile = window.matchMedia('(max-width: 1023px)');
  const mediaQueryReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  const getHeaderHeight = () => {
    const headerEl = document.querySelector('header') || document.getElementById('site-header');
    return headerEl ? headerEl.offsetHeight : 80;
  };

  const getDistance = () => {
    return Math.max(0, track.scrollWidth - viewport.clientWidth);
  };

  let ctx = null;

  const buildGallery = () => {
    if (ctx) {
      ctx.revert();
      ctx = null;
    }

    if (mediaQueryMobile.matches || mediaQueryReducedMotion.matches) {
      gsap.set(track, { clearProps: 'all' });
      return;
    }

    const distance = getDistance();
    if (distance <= 0) return;

    ctx = gsap.context(() => {
      gsap.to(track, {
        x: () => -getDistance(),
        ease: 'none',
        scrollTrigger: {
          trigger: stage,
          start: () => 'top top+=' + getHeaderHeight(),
          end: () => '+=' + getDistance(),
          pin: true,
          scrub: 1,
          invalidateOnRefresh: true,
          anticipatePin: 1
        }
      });
    }, stage);
  };

  // Ensure dimensions are valid after images load
  const images = track.querySelectorAll('img');
  const imagePromises = Array.from(images).map(img => {
    if (img.complete) return Promise.resolve();
    return new Promise(resolve => {
      img.addEventListener('load', resolve, { once: true });
      img.addEventListener('error', resolve, { once: true });
    });
  });

  Promise.all(imagePromises).then(() => {
    requestAnimationFrame(() => {
      setTimeout(() => {
        buildGallery();
        ScrollTrigger.refresh();
      }, 60);
    });
  });

  // Handle window resize and orientation changes cleanly
  if (window._productShowcaseResizeHandler) {
    window.removeEventListener('resize', window._productShowcaseResizeHandler);
    window.removeEventListener('orientationchange', window._productShowcaseResizeHandler);
  }

  let resizeTimeout;
  window._productShowcaseResizeHandler = () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      buildGallery();
      ScrollTrigger.refresh();
    }, 150);
  };

  window.addEventListener('resize', window._productShowcaseResizeHandler, { passive: true });
  window.addEventListener('orientationchange', window._productShowcaseResizeHandler, { passive: true });
}
