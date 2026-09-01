document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 30) {
        header.classList.add('bg-white/95', 'backdrop-blur-md', 'shadow-sm', 'is-scrolled');
        header.classList.remove('bg-transparent', 'bg-[#173F35]', 'border-[#D8C7A1]/20', 'bg-[#173F35]/85', 'backdrop-blur-sm');
        
        document.querySelectorAll('.nav-link').forEach(el => el.style.color = '#173F35');
        const navBrandText = document.querySelector('.nav-brand-text');
        if (navBrandText) navBrandText.style.color = '#173F35';
        const poly1 = document.querySelector('.nav-brand-poly1');
        if (poly1) poly1.style.fill = '#8FAE5D';
        const poly2 = document.querySelector('.nav-brand-poly2');
        if (poly2) poly2.style.fill = '#173F35';
        const line1 = document.querySelector('.nav-brand-line1');
        if (line1) line1.style.stroke = '#173F35';
        const line2 = document.querySelector('.nav-brand-line2');
        if (line2) line2.style.stroke = '#173F35';
        const mobileBtn = document.querySelector('.nav-mobile-btn');
        if (mobileBtn) mobileBtn.style.color = '#173F35';
      } else {
        header.classList.remove('bg-white/95', 'backdrop-blur-md', 'shadow-sm', 'is-scrolled');
        const isIndex = window.location.pathname.includes('index.php') || window.location.pathname === '/' || window.location.pathname.endsWith('/');
        if (isIndex) {
            header.classList.add('bg-transparent');
        } else {
            header.classList.add('bg-[#173F35]');
        }
        header.classList.add('border-[#D8C7A1]/20');

        document.querySelectorAll('.nav-link').forEach(el => el.style.color = '');
        const navBrandText = document.querySelector('.nav-brand-text');
        if (navBrandText) navBrandText.style.color = '';
        const poly1 = document.querySelector('.nav-brand-poly1');
        if (poly1) poly1.style.fill = '';
        const poly2 = document.querySelector('.nav-brand-poly2');
        if (poly2) poly2.style.fill = '';
        const line1 = document.querySelector('.nav-brand-line1');
        if (line1) line1.style.stroke = '';
        const line2 = document.querySelector('.nav-brand-line2');
        if (line2) line2.style.stroke = '';
        const mobileBtn = document.querySelector('.nav-mobile-btn');
        if (mobileBtn) mobileBtn.style.color = '';
      }
    }, { passive: true });
  }

  // Mobile menu logic
  const menuBtn = document.getElementById('mobile-menu-btn');
  const drawer = document.getElementById('mobile-menu-drawer');
  const overlay = document.getElementById('mobile-menu-overlay');

  if (menuBtn && drawer && overlay) {
    let isOpen = false;

    function toggleMenu() {
      isOpen = !isOpen;
      menuBtn.setAttribute('aria-expanded', isOpen);
      
      if (isOpen) {
        drawer.classList.remove('translate-x-full');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        document.body.style.overflow = 'hidden'; // prevent scrolling
        
        // Transform hamburger to X
        const spans = menuBtn.querySelectorAll('span');
        if (spans.length === 3) {
          spans[0].style.transform = 'translateY(7px) rotate(45deg)';
          spans[1].style.opacity = '0';
          spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
        }
      } else {
        drawer.classList.add('translate-x-full');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
        
        // Transform X back to hamburger
        const spans = menuBtn.querySelectorAll('span');
        if (spans.length === 3) {
          spans[0].style.transform = 'none';
          spans[1].style.opacity = '1';
          spans[2].style.transform = 'none';
        }
      }
    }

    menuBtn.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && isOpen) {
        toggleMenu();
      }
    });
  }
});
