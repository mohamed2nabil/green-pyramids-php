(() => {
  'use strict';

  document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
    }

    initPremiumHeadings();
    initCounters();
    initIndexCategoryGallery();
    initScrollReveals();

    // Safety refresh on load after images render
    window.addEventListener('load', () => {
      requestAnimationFrame(() => {
        setTimeout(() => {
          if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.refresh();
          }
        }, 150);
      });
    });
  });

  /* =========================================
     TEXT SPLITTER (Per-letter Span Creator)
  ========================================= */
  function splitText(el, className) {
    if (!el || el.dataset.split === 'true') {
      return [...(el.querySelectorAll('.' + className) || [])];
    }

    const original = el.textContent;
    el.dataset.split = 'true';
    el.setAttribute('aria-label', original.trim());

    const chars = [];

    const walk = node => {
      if (node.nodeType === Node.TEXT_NODE) {
        const fragment = document.createDocumentFragment();
        [...node.nodeValue].forEach(char => {
          if (/\s/.test(char)) {
            fragment.appendChild(document.createTextNode(char));
          } else {
            const span = document.createElement('span');
            span.className = className;
            span.textContent = char;
            span.setAttribute('aria-hidden', 'true');
            fragment.appendChild(span);
            chars.push(span);
          }
        });
        node.parentNode.replaceChild(fragment, node);
        return;
      }

      if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== 'BR') {
        [...node.childNodes].forEach(walk);
      }
    };

    [...el.childNodes].forEach(walk);
    return chars;
  }

  /* =========================================
     PER-LETTER HEADING REVEAL ANIMATION
     (Fades in, slides up, reduces blur)
  ========================================= */
  function initPremiumHeadings() {
    const headings = document.querySelectorAll('.anim-heading');
    if (!headings.length) return;

    headings.forEach(heading => {
      const chars = splitText(heading, 'anim-char');
      if (!chars.length) return;

      if (typeof gsap === 'undefined') {
        chars.forEach(c => {
          c.style.opacity = '1';
          c.style.transform = 'none';
          c.style.filter = 'none';
        });
        return;
      }

      // Check if heading is in the initial visible viewport
      const rect = heading.getBoundingClientRect();
      const inViewport = rect.top < window.innerHeight * 0.9 && rect.bottom > 0;

      if (inViewport) {
        // Immediate timeline reveal for top/hero headings
        gsap.fromTo(chars,
          {
            opacity: 0,
            y: 20,
            filter: 'blur(8px)'
          },
          {
            opacity: 1,
            y: 0,
            filter: 'blur(0px)',
            duration: 0.75,
            stagger: 0.022,
            ease: 'power3.out',
            delay: 0.08
          }
        );
      } else if (typeof ScrollTrigger !== 'undefined') {
        // Scroll-triggered reveal for sections further down
        gsap.fromTo(chars,
          {
            opacity: 0,
            y: 24,
            filter: 'blur(8px)'
          },
          {
            opacity: 1,
            y: 0,
            filter: 'blur(0px)',
            duration: 0.75,
            stagger: 0.02,
            ease: 'power3.out',
            scrollTrigger: {
              trigger: heading,
              start: 'top 85%',
              once: true
            }
          }
        );
      }
    });
  }

  /* =========================================
     DYNAMIC COUNT-UP ANIMATION
  ========================================= */
  function initCounters() {
    const counterElements = document.querySelectorAll('.anim-counter, [data-count]');
    if (!counterElements.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        obs.unobserve(el);

        const target = parseFloat(el.dataset.count) || 0;
        const prefix = el.dataset.prefix || '';
        const suffix = el.dataset.suffix || '';
        const duration = 1800;
        const startTime = performance.now();

        function update(now) {
          const elapsed = now - startTime;
          const progress = Math.min(elapsed / duration, 1);
          // Cubic ease-out deceleration
          const eased = 1 - Math.pow(1 - progress, 3);
          const current = Math.floor(eased * target);

          el.textContent = prefix + current.toLocaleString() + suffix;

          if (progress < 1) {
            requestAnimationFrame(update);
          } else {
            el.textContent = prefix + target.toLocaleString() + suffix;
            el.style.animation = 'countPop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
          }
        }

        requestAnimationFrame(update);
      });
    }, { threshold: 0.25 });

    counterElements.forEach(el => observer.observe(el));
  }

  /* =========================================
     STICKY HORIZONTAL SCROLL CATEGORY GALLERY
     Vertical scroll locks, cards slide horizontally.
     Scroll down = left, scroll up = right (bidirectional via scrub).
  ========================================= */
  function initIndexCategoryGallery() {
    const section = document.getElementById('idx-cat-section');
    const pinWrapper = document.getElementById('idx-cat-pin-wrapper');
    const pinned = document.getElementById('idx-cat-pinned');
    const track = document.getElementById('idx-cat-track');
    if (!section || !pinWrapper || !pinned || !track) return;
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    // ponytail: small delay ensures DOM is fully rendered & card widths are accurate
    waitForLayout(track).then(() => {
      // How far the track needs to slide: total track width minus one viewport width
      const scrollDistance = track.scrollWidth - window.innerWidth;
      if (scrollDistance <= 0) return; // all cards fit on screen, no pin needed

      // Set wrapper height = viewport + scroll distance so there's enough room to scrub
      pinWrapper.style.height = (window.innerHeight + scrollDistance) + 'px';

      gsap.to(track, {
        x: -scrollDistance,
        ease: 'none',
        scrollTrigger: {
          trigger: pinWrapper,
          start: 'top top',
          end: 'bottom bottom',
          pin: pinned,
          scrub: 1,
          invalidateOnRefresh: true,
          onRefresh: (self) => {
            // Recalculate on resize
            const newDist = track.scrollWidth - window.innerWidth;
            if (newDist > 0) {
              pinWrapper.style.height = (window.innerHeight + newDist) + 'px';
            }
          }
        }
      });
    });
  }

  /* =========================================
     WAIT FOR IMAGES & DOM LAYOUT
  ========================================= */
  function waitForLayout(container) {
    const images = [...container.querySelectorAll('img')];
    const pending = images.map(img => {
      if (img.complete) return Promise.resolve();
      return new Promise(resolve => {
        img.addEventListener('load', resolve, { once: true });
        img.addEventListener('error', resolve, { once: true });
      });
    });

    // Card widths are fixed, so do not let an off-screen lazy image delay the gallery forever.
    return Promise.race([
      Promise.all(pending),
      new Promise(resolve => setTimeout(resolve, 700))
    ]).then(() => {
      return new Promise(resolve => {
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            setTimeout(resolve, 80);
          });
        });
      });
    });
  }

  /* =========================================
     GENERAL SCROLL REVEALS
  ========================================= */
  function initScrollReveals() {
    if (typeof ScrollTrigger === 'undefined' || typeof gsap === 'undefined') return;

    document.querySelectorAll('.reveal-up').forEach(el => {
      gsap.fromTo(el,
        { opacity: 0, y: 24 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: el,
            start: 'top 88%',
            once: true
          }
        }
      );
    });
  }

})();
