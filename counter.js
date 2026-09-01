function initCounterAnimation() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
  const counters = document.querySelectorAll('.anim-counter');
  if (!counters.length) return;

  const mediaQueryReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (mediaQueryReducedMotion.matches) return;

  counters.forEach(counter => {
    const text = counter.textContent.trim();
    // Parse out prefix, number, and suffix
    const match = text.match(/^([^\d]*)(\d+(?:\.\d+)?)([^\d]*)$/);
    if (!match) return;

    const prefix = match[1] || '';
    const number = parseFloat(match[2]);
    const suffix = match[3] || '';
    const isFloat = text.includes('.');
    const decimals = isFloat ? match[2].split('.')[1].length : 0;

    // Proxy object for tweening
    const proxy = { val: 0 };
    counter.textContent = prefix + '0' + suffix;

    gsap.to(proxy, {
      scrollTrigger: {
        trigger: counter,
        start: 'top 90%',
        once: true
      },
      val: number,
      duration: 2,
      ease: 'power2.out',
      onUpdate: function() {
        const currentVal = proxy.val.toFixed(decimals);
        counter.textContent = prefix + currentVal + suffix;
      }
    });
  });
}
