document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 30) {
        header.classList.add('bg-[#F6F3EC]/95', 'backdrop-blur-md', 'shadow-sm', 'border-b', 'border-[#D8C7A1]/30');
        header.classList.remove('bg-transparent');
        // We'd also need to toggle text colors if we want, but since they are statically rendered,
        // it might be easier to just let CSS handle it or toggle a 'scrolled' class
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('bg-[#F6F3EC]/95', 'backdrop-blur-md', 'shadow-sm', 'border-b', 'border-[#D8C7A1]/30', 'is-scrolled');
        header.classList.add('bg-transparent');
      }
    }, { passive: true });
  }
});
