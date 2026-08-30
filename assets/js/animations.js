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
  initAnimatedHeadings();
});


function initProductShowcaseGallery() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const stage = document.getElementById('product-showcase-stage');
  const viewport = document.getElementById('product-showcase-viewport');
  const track = document.getElementById('product-showcase-track');
  const navItems = document.querySelectorAll('.product-nav-item');

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
      // Create the main horizontal scroll animation
      const scrollTween = gsap.to(track, {
        x: () => -getDistance(),
        ease: 'none',
        scrollTrigger: {
          trigger: stage,
          start: () => 'top top+=' + getHeaderHeight(),
          end: () => '+=' + getDistance(),
          pin: true,
          scrub: 1,
          invalidateOnRefresh: true,
          anticipatePin: 1,
          onUpdate: (self) => {
            // Update active state in bottom nav
            if (navItems.length > 0) {
              const progress = self.progress;
              const slidesCount = navItems.length;
              // determine active slide index based on progress
              let activeIndex = Math.floor(progress * slidesCount);
              if (activeIndex >= slidesCount) activeIndex = slidesCount - 1;
              
              navItems.forEach((item, idx) => {
                const bar = item.querySelector('.nav-progress');
                if (idx === activeIndex) {
                  item.classList.remove('opacity-40');
                  item.classList.add('opacity-100');
                  if (bar) bar.style.width = '100%';
                } else {
                  item.classList.add('opacity-40');
                  item.classList.remove('opacity-100');
                  if (bar) bar.style.width = '0%';
                }
              });
            }
          }
        }
      });
      
      // Add click listeners to nav items to jump to position
      navItems.forEach((item, idx) => {
        item.addEventListener('click', (e) => {
          e.preventDefault();
          const slidesCount = navItems.length;
          const targetProgress = idx / (slidesCount - 0.9); // approximate
          // Calculate scroll position based on ScrollTrigger
          const st = scrollTween.scrollTrigger;
          if (st) {
            const scrollPos = st.start + (st.end - st.start) * targetProgress;
            window.scrollTo({ top: scrollPos, behavior: 'smooth' });
          }
        });
      });

    }, stage);
  };

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


function initAnimatedHeadings() {
  const headings = document.querySelectorAll('.anim-heading');
  if (!headings.length) return;
  
  const mediaQueryReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (mediaQueryReducedMotion.matches) return;

  headings.forEach(heading => {
    // Avoid double-splitting
    if (heading.dataset.splitted) return;
    
    const text = heading.textContent;
    heading.innerHTML = '';
    
    // Split into characters, keeping spaces intact
    let charIndex = 0;
    for (let i = 0; i < text.length; i++) {
      const char = text[i];
      if (char === ' ') {
        heading.appendChild(document.createTextNode(' '));
      } else {
        const span = document.createElement('span');
        span.textContent = char;
        span.className = 'letter-anim inline-block transition-all duration-700 ease-out';
        span.style.opacity = '0';
        span.style.transform = 'translateY(30px)';
        span.style.filter = 'blur(12px)';
        span.style.transitionDelay = `${charIndex * 0.03}s`;
        heading.appendChild(span);
        charIndex++;
      }
    }
    heading.dataset.splitted = 'true';
    
    // Use Intersection Observer to trigger the animation
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const spans = entry.target.querySelectorAll('span');
          spans.forEach(span => {
            span.style.opacity = '1';
          span.style.transform = 'translateY(0)';
          span.style.filter = 'blur(0)';
          });
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });
    
    observer.observe(heading);
  });
}
