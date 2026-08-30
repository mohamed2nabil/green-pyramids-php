document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 30) {
        header.classList.add('bg-[#F6F3EC]/95', 'backdrop-blur-md', 'shadow-sm', 'is-scrolled');
        header.classList.remove('bg-transparent', 'bg-[#173F35]', 'border-[#D8C7A1]/20');
      } else {
        header.classList.remove('bg-[#F6F3EC]/95', 'backdrop-blur-md', 'shadow-sm', 'is-scrolled');
        // Restore initial bg based on page
        const isDarkHero = window.location.pathname.includes('index.php') || window.location.pathname.includes('about.php') || window.location.pathname === '/' || window.location.pathname.endsWith('/');
        if (isDarkHero) {
            header.classList.add('bg-transparent');
        } else {
            header.classList.add('bg-[#173F35]');
        }
        header.classList.add('border-[#D8C7A1]/20');
      }
    }, { passive: true });
  }
});
