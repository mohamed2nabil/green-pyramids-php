document.addEventListener('DOMContentLoaded', () => {
  gsap.registerPlugin(ScrollTrigger);

  // Intersection Observer for basic scroll reveals
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        const elements = entry.target.querySelectorAll('.word, .letter');
        elements.forEach((el, index) => {
          el.style.transitionDelay = (index * 0.05) + 's';
        });
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal-fade, .reveal-up').forEach(el => observer.observe(el));

  initHeroTypingAnimation();
  initPremiumHeadings();
  initProductCardsAnimation();
  initStickyHorizontalGallery();
  initHeroSequence();
  initCounterAnimation();
  initAboutCategoryGallery();
  initIndexCategoryGallery();
});

function initHeroTypingAnimation() {
  const h1 = document.querySelector('.hero-typing-anim');
  if (!h1) return;
  
  const mediaQueryReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (mediaQueryReducedMotion.matches) return;

  function wrapCharacters(node) {
    if (node.nodeType === Node.TEXT_NODE) {
      const chars = node.textContent.split('');
      const fragment = document.createDocumentFragment();
      chars.forEach(char => {
        if (char.trim() === '') {
          fragment.appendChild(document.createTextNode(char));
        } else {
          const span = document.createElement('span');
          span.textContent = char;
          span.style.opacity = '0';
          span.className = 'typing-char inline-block';
          fragment.appendChild(span);
        }
      });
      node.parentNode.replaceChild(fragment, node);
    } else if (node.nodeType === Node.ELEMENT_NODE) {
      if (node.tagName.toLowerCase() !== 'br') {
        Array.from(node.childNodes).forEach(wrapCharacters);
      }
    }
  }
  
  wrapCharacters(h1);
  
  const chars = h1.querySelectorAll('.typing-char');
  if (chars.length === 0) return;
  
  setTimeout(() => {
    let delay = 0;
    chars.forEach((char) => {
      setTimeout(() => {
        char.style.opacity = '1';
        char.style.transition = 'opacity 0.1s ease-out';
      }, delay);
      delay += 50;
    });
  }, 400);
}

function initHeroSequence() {
  if (typeof gsap === 'undefined') return;
  const h1 = document.querySelector('.hero-typing-anim');
  if (!h1) return;
  
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
  
  const eyebrow = h1.previousElementSibling;
  const subtextSpans = document.querySelectorAll('h1 + p span');
  const ctas = document.querySelector('h1 + p + div');
  const pulseText = document.querySelector('.animate-pulse');
  
  // Remove Tailwind's opacity-0 which acts as !important or gets stuck
  const elements = [eyebrow, ...subtextSpans, ctas, pulseText].filter(Boolean);
  elements.forEach(el => el.classList.remove('opacity-0'));
  
  // Set initial states explicitly via GSAP
  if (eyebrow) gsap.set(eyebrow, { opacity: 0, y: 20 });
  if (subtextSpans.length) gsap.set(subtextSpans, { opacity: 0, y: 15, filter: 'blur(4px)' });
  if (ctas) gsap.set(ctas, { opacity: 0, y: 20 });
  if (pulseText) gsap.set(pulseText, { opacity: 0, y: 20 });
  
  // Sequence
  if (eyebrow) tl.to(eyebrow, { opacity: 1, y: 0, duration: 0.8 }, 0.2);
  
  // Typing animation handles H1, so we just wait a bit for it to finish typing before animating rest
  if (subtextSpans.length) {
    tl.to(subtextSpans, { 
      opacity: 1, 
      y: 0, 
      filter: 'blur(0px)', 
      duration: 0.8, 
      stagger: 0.05 
    }, 1.5); // wait 1.5s for typing
  }
  
  if (ctas) tl.to(ctas, { opacity: 1, y: 0, duration: 0.8 }, "-=0.4");
  if (pulseText) tl.to(pulseText, { opacity: 1, y: 0, duration: 1 }, "-=0.2");
}

function initPremiumHeadings() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
  const headings = document.querySelectorAll('.anim-heading');
  if (!headings.length) return;
  
  const mediaQueryReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (mediaQueryReducedMotion.matches) return;

  headings.forEach(heading => {
    if (heading.dataset.splitted) return;
    
    function wrapSafe(node) {
      if (node.nodeType === Node.TEXT_NODE) {
        const chars = node.textContent.split('');
        const fragment = document.createDocumentFragment();
        chars.forEach(char => {
          if (char.trim() === '') {
            fragment.appendChild(document.createTextNode(char));
          } else {
            const span = document.createElement('span');
            span.textContent = char;
            span.className = 'inline-block anim-char';
            fragment.appendChild(span);
          }
        });
        node.parentNode.replaceChild(fragment, node);
      } else if (node.nodeType === Node.ELEMENT_NODE) {
        if (node.tagName.toLowerCase() !== 'br') {
          Array.from(node.childNodes).forEach(wrapSafe);
        }
      }
    }
    
    wrapSafe(heading);
    heading.dataset.splitted = 'true';
    
    gsap.from(heading.querySelectorAll('.anim-char'), {
      scrollTrigger: { trigger: heading, start: 'top 85%' },
      opacity: 0,
      y: 25,
      filter: 'blur(8px)',
      duration: 0.8,
      stagger: 0.02,
      ease: 'power2.out'
    });
  });
}

function initProductCardsAnimation() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
  const cards = document.querySelectorAll('.product-nav-item');
  if (!cards.length) return;
  
  const mediaQueryReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (mediaQueryReducedMotion.matches) return;
  
  gsap.from(cards, {
    scrollTrigger: {
      trigger: '#product-showcase-stage',
      start: 'top 85%',
      once: true
    },
    opacity: 0,
    y: 30,
    duration: 0.6,
    stagger: 0.1,
    ease: 'power2.out'
  });
}

function initStickyHorizontalGallery() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
  
  const stage = document.querySelector('.horizontal-stage, #product-showcase-stage');
  const track = document.querySelector('.horizontal-track, #product-showcase-track');
  
  if (!stage || !track) return;

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
        let mm = gsap.matchMedia();
        
        mm.add({
          isDesktop: "(min-width: 1024px)",
          isMobile: "(max-width: 1023px)",
          reduceMotion: "(prefers-reduced-motion: reduce)"
        }, (context) => {
          let { isDesktop, reduceMotion } = context.conditions;
          
          if (isDesktop && !reduceMotion) {
            const getDistance = () => Math.max(0, track.scrollWidth - stage.clientWidth);
            
            if (getDistance() > 0) {
              gsap.to(track, {
                x: () => -getDistance(),
                ease: "none",
                scrollTrigger: {
                  trigger: stage,
                  start: "top top",
                  end: () => "+=" + getDistance(),
                  pin: true,
                  scrub: 1,
                  invalidateOnRefresh: true,
                  anticipatePin: 1
                }
              });
            }
          } else {
            // Revert on mobile: horizontal scroll natively
            gsap.set(track, { clearProps: "all" });
            track.style.overflowX = 'auto';
            track.style.overflowY = 'hidden';
            track.style.scrollSnapType = 'x mandatory';
            track.querySelectorAll('.relative.z-10').forEach(el => {
                el.style.scrollSnapAlign = 'center';
            });
          }
        });
        
        ScrollTrigger.refresh();
      }, 100);
    });
  });
}
function initCounterAnimation() {
  const counters = document.querySelectorAll('[data-count]');
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = +el.dataset.count;
      const suffix = el.dataset.suffix || '';
      const prefix = el.dataset.prefix || '';
      let start = 0;
      const duration = 2000;
      const startTime = performance.now();
      
      if (reduced) { el.textContent = prefix + target + suffix; obs.unobserve(el); return; }
      
      function update(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = prefix + Math.floor(eased * target) + suffix;
        if (progress < 1) requestAnimationFrame(update);
        else { el.textContent = prefix + target + suffix; obs.unobserve(el); }
      }
      requestAnimationFrame(update);
    });
  }, { threshold: 0.5 });
  
  counters.forEach(el => obs.observe(el));
}

/**
 * About page: categories sticky horizontal scroll.
 * Uses CSS sticky (the inner div) + GSAP ScrollTrigger to drive translateX.
 * The outer section gets height = track scrollWidth − viewport width
 * so the scroll distance maps exactly to the horizontal travel distance.
 */
function initAboutCategoryGallery() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const stage = document.getElementById('about-cat-stage');
  const track = document.getElementById('about-cat-track');
  if (!stage || !track) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduced) return;

  function setup() {
    // Only pin-scroll on desktop; mobile gets native touch-scroll
    const mm = gsap.matchMedia();

    mm.add('(min-width: 1024px)', () => {
      // Wait one rAF so layout is stable
      requestAnimationFrame(() => {
        const trackW   = track.scrollWidth;
        const viewW    = window.innerWidth;
        const distance = trackW - viewW + 64; // 64 = 2×px-8 padding

        if (distance <= 0) return;

        // Set section height so normal page scroll = horizontal travel
        stage.style.height = (window.innerHeight + distance) + 'px';

        gsap.to(track, {
          x: -distance,
          ease: 'none',
          scrollTrigger: {
            trigger: stage,
            start: 'top top',
            end: () => '+=' + distance,
            scrub: 1.2,
            invalidateOnRefresh: true,
            // no pin needed — inner div uses CSS sticky
          }
        });

        ScrollTrigger.refresh();
      });

      // cleanup on matchMedia revert
      return () => {
        stage.style.height = '';
        gsap.set(track, { clearProps: 'x' });
        ScrollTrigger.getAll()
          .filter(st => st.vars.trigger === stage)
          .forEach(st => st.kill());
      };
    });

    // Mobile: native horizontal scroll with snap
    mm.add('(max-width: 1023px)', () => {
      track.style.overflowX = 'auto';
      track.style.scrollSnapType = 'x mandatory';
      track.querySelectorAll('.about-cat-card').forEach(c => {
        c.style.scrollSnapAlign = 'start';
      });
      stage.style.height = '';
      return () => {
        track.style.overflowX = '';
        track.style.scrollSnapType = '';
      };
    });
  }

  // Wait for images to load so widths are accurate
  const imgs = track.querySelectorAll('img');
  const promises = Array.from(imgs).map(img => img.complete
    ? Promise.resolve()
    : new Promise(r => { img.onload = r; img.onerror = r; })
  );
  Promise.all(promises).then(setup);
}

/* Index page categories sticky horizontal scroll */
function initIndexCategoryGallery() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
  const stage = document.getElementById('idx-cat-stage');
  const track = document.getElementById('idx-cat-track');
  if (!stage || !track) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  // Hover: scale image
  track.querySelectorAll('.idx-cat-card').forEach(card => {
    const img = card.querySelector('img');
    if (!img) return;
    card.addEventListener('mouseenter', () => { img.style.transform = 'scale(1.07)'; });
    card.addEventListener('mouseleave', () => { img.style.transform = ''; });
    // Arrow nudge
    const arrow = card.querySelector('.cat-explore span');
    if (arrow) {
      card.addEventListener('mouseenter', () => { arrow.style.transform = 'translateX(5px)'; });
      card.addEventListener('mouseleave', () => { arrow.style.transform = ''; });
    }
  });

  // Drag-to-scroll on mobile
  let isDown = false, startX, scrollLeft;
  track.addEventListener('mousedown', e => { isDown = true; track.style.cursor = 'grabbing'; startX = e.pageX - track.offsetLeft; scrollLeft = track.scrollLeft; });
  track.addEventListener('mouseleave', () => { isDown = false; track.style.cursor = 'grab'; });
  track.addEventListener('mouseup', () => { isDown = false; track.style.cursor = 'grab'; });
  track.addEventListener('mousemove', e => { if (!isDown) return; e.preventDefault(); const x = e.pageX - track.offsetLeft; track.scrollLeft = scrollLeft - (x - startX) * 1.5; });

  function setup() {
    const mm = gsap.matchMedia();
    mm.add('(min-width: 1024px)', () => {
      requestAnimationFrame(() => {
        const trackW   = track.scrollWidth;
        const viewW    = window.innerWidth;
        const padding  = 128; // 2 × 4rem padding
        const distance = trackW - viewW + padding;
        if (distance <= 0) return;

        stage.style.height = (window.innerHeight + distance) + 'px';

        gsap.to(track, {
          x: -distance,
          ease: 'none',
          scrollTrigger: {
            trigger: stage,
            start: 'top top',
            end: () => '+=' + distance,
            scrub: 1.0,
            invalidateOnRefresh: true,
          }
        });
        ScrollTrigger.refresh();
      });
      return () => {
        stage.style.height = '';
        gsap.set(track, { clearProps: 'x' });
        ScrollTrigger.getAll().filter(st => st.vars.trigger === stage).forEach(st => st.kill());
      };
    });
    mm.add('(max-width: 1023px)', () => {
      track.style.overflowX = 'auto';
      track.style.scrollSnapType = 'x mandatory';
      track.querySelectorAll('.idx-cat-card').forEach(c => c.style.scrollSnapAlign = 'start');
      stage.style.height = 'auto';
      return () => { track.style.overflowX = ''; stage.style.height = ''; };
    });
  }

  const imgs = track.querySelectorAll('img');
  const promises = Array.from(imgs).map(img => img.complete
    ? Promise.resolve()
    : new Promise(r => { img.onload = r; img.onerror = r; })
  );
  Promise.all(promises).then(setup);
}
