<?php
$scriptName = basename($_SERVER['SCRIPT_NAME']);
if ($scriptName === '' || $scriptName === 'index') $scriptName = 'index.php';
$currentPage = $scriptName;

$navItems = [
    'index.php' => 'Home',
    'about.php' => 'About Us',
    'products.php' => 'Products',
    'process.php' => 'Our Process',
    'quality.php' => 'Quality',
    'contact.php' => 'Contact'
];
?>
<header id="site-header" class="group fixed top-0 left-0 right-0 z-[100] transition-all duration-300 ease-in-out <?= ($currentPage === 'index.php' || $currentPage === 'about.php') ? 'bg-transparent' : 'bg-[#173F35]' ?> shadow-md border-b border-[#D8C7A1]/20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-[80px]">
    <!-- Brand Logo -->
    <a class="flex items-center gap-3 group flex-shrink-0 transition-colors duration-300 text-[#F6F3EC] [.is-scrolled_&]:text-[#173F35]" href="index.php">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <polygon points="12,2 2,21 12,21" fill="#1f5245" class="[.is-scrolled_&]:fill-[#8FAE5D] transition-colors"></polygon>
        <polygon points="12,2 22,21 12,21" fill="#0d2a24" class="[.is-scrolled_&]:fill-[#173F35] transition-colors"></polygon>
        <line x1="12" y1="2" x2="12" y2="21" stroke="#8FAE5D" stroke-width="0.7" opacity="0.5"></line>
        <polygon points="12,2 2,21 22,21" fill="none" stroke="#8FAE5D" stroke-width="0.9" stroke-linejoin="round"></polygon>
        <line x1="7" y1="11.5" x2="17" y2="11.5" stroke="#D8C7A1" class="[.is-scrolled_&]:stroke-[#173F35] transition-colors" stroke-width="0.5" opacity="0.45"></line>
        <line x1="4.5" y1="16.5" x2="19.5" y2="16.5" stroke="#D8C7A1" class="[.is-scrolled_&]:stroke-[#173F35] transition-colors" stroke-width="0.5" opacity="0.35"></line>
      </svg>
      <div>
        <div class="font-serif text-[18px] leading-none tracking-wide text-[#F6F3EC] [.is-scrolled_&]:text-[#173F35]">Green Pyramids</div>
        <div class="text-[9px] tracking-[0.2em] uppercase mt-1 text-[#8FAE5D] font-medium">Agricultural Exports</div>
      </div>
    </a>

    <!-- Desktop Navigation Menu -->
    <nav class="hidden lg:flex items-center gap-8">
      <?php foreach ($navItems as $url => $label): 
        $isActive = ($currentPage === $url) || ($url === 'products.php' && $currentPage === 'productdetail.php');
      ?>
        <a class="text-[13px] font-medium tracking-wide transition-all duration-300 relative py-1 <?= $isActive ? 'text-[#F6F3EC] [.is-scrolled_&]:text-[#173F35] border-b border-current opacity-100' : 'text-[#F6F3EC] [.is-scrolled_&]:text-[#173F35] opacity-70 hover:opacity-100 hover:-translate-y-0.5' ?>" href="<?= $url ?>">
          <?= $label ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Header Actions -->
    <div class="flex items-center gap-5">
      <a class="hidden lg:flex px-6 py-2.5 rounded-full text-[13px] font-medium tracking-wide transition-all duration-300 bg-[#8FAE5D] text-[#173F35] hover:bg-[#F6F3EC] hover:shadow-md" href="contact.php">Request Quote</a>
      <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 flex flex-col justify-center items-center gap-[5px] rounded-full transition-colors text-[#F6F3EC] [.is-scrolled_&]:text-[#173F35] hover:bg-white/10 z-[101]" aria-label="Toggle menu">
        <span class="block h-[2px] w-5 bg-current transition-transform duration-300 origin-center"></span>
        <span class="block h-[2px] w-5 bg-current transition-opacity duration-300"></span>
        <span class="block h-[2px] w-5 bg-current transition-transform duration-300 origin-center"></span>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Navigation Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 z-[89] bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-500 lg:hidden"></div>

<!-- Mobile Navigation Drawer -->
<div id="mobile-menu-drawer" class="fixed top-0 bottom-0 right-0 z-[90] w-[min(380px,82vw)] bg-[#173F35] flex flex-col transition-transform duration-500 ease-[cubic-bezier(0.76,0,0.24,1)] translate-x-full lg:hidden shadow-2xl border-l border-[#D8C7A1]/20">
  <div class="flex items-center justify-start px-8 h-[80px] border-b border-[#F6F3EC]/10 shrink-0">
    <a class="flex items-center gap-3" href="index.php">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <polygon points="12,2 2,21 12,21" fill="#1f5245"></polygon>
        <polygon points="12,2 22,21 12,21" fill="#0d2a24"></polygon>
        <line x1="12" y1="2" x2="12" y2="21" stroke="#8FAE5D" stroke-width="0.7" opacity="0.5"></line>
        <polygon points="12,2 2,21 22,21" fill="none" stroke="#8FAE5D" stroke-width="0.9" stroke-linejoin="round"></polygon>
      </svg>
      <span class="font-serif text-[#F6F3EC] text-[16px] tracking-wide">Green Pyramids</span>
    </a>
  </div>
  <nav class="flex flex-col px-8 py-8 gap-5 overflow-y-auto grow">
    <?php foreach ($navItems as $url => $label): 
      $isActive = ($currentPage === $url) || ($url === 'products.php' && $currentPage === 'productdetail.php');
    ?>
      <a class="font-serif text-[1.75rem] leading-none transition-all duration-300 <?= $isActive ? 'text-[#F6F3EC] font-medium translate-x-2' : 'text-[#F6F3EC]/70 hover:text-[#F6F3EC] hover:translate-x-1' ?>" href="<?= $url ?>"><?= $label ?></a>
    <?php endforeach; ?>
    <div class="mt-auto pt-8 border-t border-[#F6F3EC]/10">
      <a class="inline-flex w-full justify-center py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full text-sm hover:bg-[#F6F3EC] transition-colors" href="contact.php">Request a Quote</a>
    </div>
  </nav>
</div>