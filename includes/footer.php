<footer class="bg-gradient-footer text-[#F6F3EC] border-t border-[#D8C7A1]/20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10 lg:py-14">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
      <!-- Col 1: Brand -->
      <div class="col-span-2 lg:col-span-1 mb-2 lg:mb-0">
        <div class="flex items-center mb-3">
          <img src="assets/Logo.svg" alt="Green Pyramids" width="115" height="46" class="h-11 w-auto object-contain" loading="lazy" decoding="async">
        </div>
        <p class="text-xs sm:text-sm text-[#F6F3EC]/70 leading-relaxed max-w-sm">Premium Egyptian agricultural crops sourced, packed, and delivered to global markets with uncompromising quality.</p>
        <div class="flex gap-4 mt-3">
          <a href="#" class="text-xs text-[#8FAE5D] hover:text-[#D8C7A1] transition-colors tracking-wide font-medium">LinkedIn &rarr;</a>
          <a href="#" class="text-xs text-[#8FAE5D] hover:text-[#D8C7A1] transition-colors tracking-wide font-medium">WhatsApp &rarr;</a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="col-span-1">
        <h4 class="text-[11px] tracking-[0.2em] uppercase text-[#D8C7A1] mb-3 font-semibold">Quick Links</h4>
        <ul class="space-y-1.5 sm:space-y-2">
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="index.php">Home</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="about.php">About Us</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="products.php">Products</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="process.php">Our Process</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="quality.php">Quality</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="contact.php">Contact</a></li>
        </ul>
      </div>

      <!-- Col 3: Products -->
      <div class="col-span-1">
        <h4 class="text-[11px] tracking-[0.2em] uppercase text-[#D8C7A1] mb-3 font-semibold">Products</h4>
        <ul class="space-y-1.5 sm:space-y-2">
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="products.php">Fresh Fruits</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="products.php">Fresh Vegetables</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="products.php">Citrus</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="products.php">Seasonal Crops</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="products.php">Egyptian Mango</a></li>
          <li><a class="text-xs sm:text-sm text-[#F6F3EC]/70 hover:text-[#F6F3EC] transition-colors" href="products.php">Pomegranate</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact -->
      <div class="col-span-2 lg:col-span-1 mt-1 lg:mt-0">
        <h4 class="text-[11px] tracking-[0.2em] uppercase text-[#D8C7A1] mb-3 font-semibold">Contact</h4>
        <ul class="space-y-2.5">
          <li class="flex items-start gap-2"><span class="text-[#8FAE5D] text-xs">✉</span><span class="text-xs text-[#F6F3EC]/70 break-all">info@greenpyramids.eg</span></li>
          <li class="flex items-start gap-2"><span class="text-[#8FAE5D] text-xs">☎</span><span class="text-xs text-[#F6F3EC]/70">+20 (2) 000-0000</span></li>
          <li class="flex items-start gap-2"><span class="text-[#8FAE5D] text-xs">◑</span><span class="text-xs text-[#F6F3EC]/70">WhatsApp Available</span></li>
          <li class="flex items-start gap-2"><span class="text-[#8FAE5D] text-xs">◎</span><span class="text-xs text-[#F6F3EC]/70">Cairo, Egypt</span></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-[#F6F3EC]/10 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <p class="text-xs text-[#F6F3EC]/40 tracking-wide">© <?= date('Y') ?> Green Pyramids for Exporting Agricultural Crops. All Rights Reserved.</p>
      <p class="text-xs text-[#F6F3EC]/30 tracking-wide">Egypt · Global Markets</p>
    </div>
  </div>
</footer>
</main>
<?php if ($currentPage === "index.php"): ?><script defer src="assets/js/hero.js?v=<?= isset($assetVersion) ? $assetVersion('assets/js/hero.js') : '1' ?>"></script><?php endif; ?>
</body>
</html>
