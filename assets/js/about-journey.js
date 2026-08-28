document.addEventListener('DOMContentLoaded', () => {
  const journeyPath = document.querySelector('.journey-line-path');
  if (!journeyPath) return;

  const pathLength = journeyPath.getTotalLength();
  journeyPath.style.strokeDasharray = pathLength;
  journeyPath.style.strokeDashoffset = pathLength;

  let ticking = false;
  const onScroll = () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const scrollY = window.scrollY;
        const height = document.documentElement.scrollHeight - window.innerHeight;
        const scrollProgress = height > 0 ? Math.min(1, Math.max(0, scrollY / height)) : 0;
        journeyPath.style.strokeDashoffset = pathLength - (scrollProgress * pathLength);
        ticking = false;
      });
      ticking = true;
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
});
