<footer class="bg-gradient-footer text-[#F6F3EC] border-t border-[#D8C7A1]/30">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 lg:py-16">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
      <!-- Col 1: Brand -->
      <div class="col-span-2 lg:col-span-1 mb-4 lg:mb-0">
        <div class="flex items-center mb-5">
          <img src="assets/Logo.svg" alt="Green Pyramids" width="130" height="52" class="h-12 w-auto object-contain" loading="lazy" decoding="async">
        </div>
        <p class="text-[13px] text-[#F6F3EC]/70 leading-relaxed max-w-sm font-light">Premium Egyptian agricultural crops sourced, packed, and delivered to global markets with uncompromising quality.</p>
        <div class="flex gap-5 mt-5">
          <a href="#" class="text-[11px] uppercase tracking-widest text-[#D8C7A1] hover:text-white transition-colors font-medium border-b border-[#D8C7A1]/30 hover:border-white pb-0.5">LinkedIn</a>
          <a href="#" class="text-[11px] uppercase tracking-widest text-[#D8C7A1] hover:text-white transition-colors font-medium border-b border-[#D8C7A1]/30 hover:border-white pb-0.5">WhatsApp</a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="col-span-1">
        <h4 class="text-[11px] tracking-[0.2em] uppercase text-[#D8C7A1] mb-5 font-semibold flex items-center gap-2">
          <span class="w-2 h-px bg-[#D8C7A1]/50"></span> Quick Links
        </h4>
        <ul class="space-y-3">
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="index.php">Home</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="about.php">About Us</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="products.php">Products</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="process.php">Our Process</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="quality.php">Quality</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="contact.php">Contact</a></li>
        </ul>
      </div>

      <!-- Col 3: Products -->
      <div class="col-span-1">
        <h4 class="text-[11px] tracking-[0.2em] uppercase text-[#D8C7A1] mb-5 font-semibold flex items-center gap-2">
          <span class="w-2 h-px bg-[#D8C7A1]/50"></span> Products
        </h4>
        <ul class="space-y-3">
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="products.php">Fresh Fruits</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="products.php">Fresh Vegetables</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="products.php">Citrus</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="products.php">Seasonal Crops</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="products.php">Egyptian Mango</a></li>
          <li><a class="text-[13px] text-[#F6F3EC]/70 hover:text-[#D8C7A1] transition-colors" href="products.php">Pomegranate</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact -->
      <div class="col-span-2 lg:col-span-1 mt-2 lg:mt-0">
        <h4 class="text-[11px] tracking-[0.2em] uppercase text-[#D8C7A1] mb-5 font-semibold flex items-center gap-2">
          <span class="w-2 h-px bg-[#D8C7A1]/50"></span> Contact
        </h4>
        <ul class="space-y-4">
          <li class="flex items-start gap-3"><span class="text-[#D8C7A1] text-sm mt-0.5">✉</span><span class="text-[13px] text-[#F6F3EC]/80 break-all"><?= htmlspecialchars($global_email) ?></span></li>
          <li class="flex items-start gap-3"><span class="text-[#D8C7A1] text-sm mt-0.5">☎</span><span class="text-[13px] text-[#F6F3EC]/80"><?= htmlspecialchars($global_phone) ?></span></li>
          <li class="flex items-start gap-3"><span class="text-[#D8C7A1] text-sm mt-0.5">◑</span><span class="text-[13px] text-[#F6F3EC]/80">WhatsApp Available</span></li>
          <li class="flex items-start gap-3"><span class="text-[#D8C7A1] text-sm mt-0.5">◎</span><span class="text-[13px] text-[#F6F3EC]/80"><?= htmlspecialchars($global_location) ?></span></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-[#D8C7A1]/20 mt-12 pt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <p class="text-[11px] text-[#F6F3EC]/40 tracking-widest uppercase">© <?= date('Y') ?> <?= htmlspecialchars($global_title) ?>. All Rights Reserved.</p>
      <p class="text-[11px] text-[#F6F3EC]/40 tracking-widest uppercase">Egypt · Global Markets</p>
    </div>
  </div>
</footer>
</main>
<?php if ($currentPage === "index.php"): ?><script defer src="assets/js/hero.js?v=<?= isset($assetVersion) ? $assetVersion('assets/js/hero.js') : '1' ?>"></script><?php endif; ?>
</body>
</html>
