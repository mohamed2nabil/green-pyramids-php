document.addEventListener('DOMContentLoaded', () => {
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        
        // Add staggered delays for letters/words
        const elements = entry.target.querySelectorAll('.word, .letter');
        elements.forEach((el, index) => {
          el.style.transitionDelay = (index * 0.05) + 's';
        });

        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.reveal-fade').forEach(el => {
    observer.observe(el);
  });
});
