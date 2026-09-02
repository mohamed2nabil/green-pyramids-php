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
<header id="site-header" class="group fixed top-0 left-0 right-0 z-[100] transition-all duration-300 ease-in-out shadow-md">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-[80px]">
    <a class="nav-brand flex items-center group flex-shrink-0 py-1" href="index.php" aria-label="Green Pyramids home">
      <img src="assets/Logo.svg" alt="Green Pyramids" width="118" height="48" class="h-11 md:h-12 w-auto max-w-[145px] object-contain transition-transform duration-300 group-hover:scale-105" fetchpriority="high">
    </a>

    <!-- Desktop Navigation Menu (visibility controlled by main.css) -->
    <nav id="desktop-nav">
      <?php foreach ($navItems as $url => $label): 
        $isActive = ($currentPage === $url) || ($url === 'products.php' && $currentPage === 'productdetail.php');
      ?>
        <a class="nav-link<?= $isActive ? ' is-active' : '' ?>" href="<?= $url ?>">
          <?= $label ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Header Actions -->
    <div class="flex items-center gap-4 sm:gap-5">
      <a class="quote-btn px-6 py-2.5 rounded-full text-[13px] font-medium tracking-wide transition-all duration-300 bg-[#8FAE5D] text-[#173F35] hover:bg-[#F6F3EC] hover:shadow-md flex-shrink-0" href="contact.php">Request a Quote</a>
      <button id="mobile-menu-btn" class="nav-mobile-btn lg:hidden flex flex-col justify-center items-center p-2 text-[#F6F3EC] focus:outline-none z-[101]" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu-drawer">
        <span class="block h-[2.5px] w-6 bg-[#F6F3EC] rounded-full transition-transform duration-300 origin-center"></span>
        <span class="block h-[2.5px] w-6 bg-[#F6F3EC] rounded-full transition-opacity duration-300"></span>
        <span class="block h-[2.5px] w-6 bg-[#F6F3EC] rounded-full transition-transform duration-300 origin-center"></span>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Navigation Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 z-[89] bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-500 lg:hidden"></div>

<!-- Mobile Navigation Drawer -->
<div id="mobile-menu-drawer" class="fixed top-0 bottom-0 right-0 z-[90] w-[min(380px,82vw)] bg-gradient-to-b from-[#0d2a24] to-[#173F35] flex flex-col transition-transform duration-500 ease-[cubic-bezier(0.76,0,0.24,1)] translate-x-full lg:hidden shadow-2xl border-l border-[#D8C7A1]/20">
  <div class="flex items-center justify-start px-8 h-[80px] border-b border-[#F6F3EC]/10 shrink-0">
    <a class="flex items-center" href="index.php" aria-label="Green Pyramids home">
      <img src="assets/Logo.svg" alt="Green Pyramids" width="115" height="46" class="h-11 w-auto max-w-[140px] object-contain">
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
