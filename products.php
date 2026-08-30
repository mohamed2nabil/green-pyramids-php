<?php include "includes/header.php"; ?>
<div class="bg-[#F6F3EC] min-h-screen">
  <!-- Hero Section -->
  <div class="bg-[#173F35] pt-12 relative overflow-hidden flex flex-col lg:flex-row min-h-[60vh] lg:h-[70vh]">
    <div class="lg:w-[45%] z-10 flex flex-col justify-center px-6 lg:px-16 py-12 lg:py-16 bg-[#173F35]">
      <div class="flex items-center gap-3 mb-4 lg:mb-5">
        <div class="w-5 h-px bg-[#8FAE5D]"></div>
        <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Our Export Catalog</p>
      </div>
      <h1 class="font-serif text-4xl sm:text-5xl lg:text-[60px] text-[#F6F3EC] leading-[1.05] mb-4 lg:mb-6">
        Egyptian<br/>Fresh Produce
      </h1>
      <p class="text-[#F6F3EC]/70 text-[14px] lg:text-[15px] max-w-sm leading-relaxed mb-8">
        Explore our selection of premium agricultural crops prepared for international markets.
      </p>
      <div class="flex gap-8">
        <div>
          <p class="font-serif text-2xl lg:text-3xl text-[#F6F3EC]">12+</p>
          <p class="text-[9px] lg:text-[10px] tracking-[0.18em] uppercase text-[#8FAE5D] mt-1">Export-ready</p>
        </div>
        <div>
          <p class="font-serif text-2xl lg:text-3xl text-[#F6F3EC]">12</p>
          <p class="text-[9px] lg:text-[10px] tracking-[0.18em] uppercase text-[#8FAE5D] mt-1">Global Markets</p>
        </div>
      </div>
    </div>

    <div class="lg:w-[55%] relative flex-1 min-h-[40vh] lg:min-h-full bg-gradient-to-br from-[#173F35] via-[#0d2a24] to-[#1f5245] overflow-hidden flex items-center justify-center p-8">
      <div class="grid grid-cols-2 gap-4 max-w-lg w-full">
        <div class="relative rounded-2xl overflow-hidden shadow-xl aspect-[3/4] group">
          <img src="https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=500&h=600&fit=crop&auto=format" alt="Egyptian Mango" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div class="absolute inset-0 bg-gradient-to-t from-[#0d2a24]/90 via-transparent to-transparent p-4 flex flex-col justify-end">
            <span class="text-[9px] uppercase tracking-widest text-[#8FAE5D]">Fruits</span>
            <h3 class="font-serif text-lg text-white">Egyptian Mango</h3>
          </div>
        </div>
        <div class="relative rounded-2xl overflow-hidden shadow-xl aspect-[3/4] group mt-6">
          <img src="https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=500&h=600&fit=crop&auto=format" alt="Navel Orange" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div class="absolute inset-0 bg-gradient-to-t from-[#0d2a24]/90 via-transparent to-transparent p-4 flex flex-col justify-end">
            <span class="text-[9px] uppercase tracking-widest text-[#8FAE5D]">Citrus</span>
            <h3 class="font-serif text-lg text-white">Navel Orange</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Category Filter Bar -->
  <div class="sticky top-[80px] z-30 bg-[#F6F3EC] border-b border-[#D8C7A1]/50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="flex items-center gap-2 overflow-x-auto py-3.5 scrollbar-hide">
        <button class="flex-shrink-0 px-5 py-2 rounded-full text-[12px] tracking-wide transition-colors duration-200 bg-[#173F35] text-[#F6F3EC] font-medium">All Products</button>
        <button class="flex-shrink-0 px-5 py-2 rounded-full text-[12px] tracking-wide transition-colors duration-200 bg-[#D8C7A1]/25 text-[#173F35]/70 hover:bg-[#D8C7A1]/50">Fruits</button>
        <button class="flex-shrink-0 px-5 py-2 rounded-full text-[12px] tracking-wide transition-colors duration-200 bg-[#D8C7A1]/25 text-[#173F35]/70 hover:bg-[#D8C7A1]/50">Vegetables</button>
        <button class="flex-shrink-0 px-5 py-2 rounded-full text-[12px] tracking-wide transition-colors duration-200 bg-[#D8C7A1]/25 text-[#173F35]/70 hover:bg-[#D8C7A1]/50">Citrus</button>
        <button class="flex-shrink-0 px-5 py-2 rounded-full text-[12px] tracking-wide transition-colors duration-200 bg-[#D8C7A1]/25 text-[#173F35]/70 hover:bg-[#D8C7A1]/50">Seasonal Crops</button>
      </div>
    </div>
  </div>

  <!-- Product Catalog Grid -->
  <div class="max-w-7xl mx-auto px-6 lg:px-10 py-14">
    <p class="text-[11px] text-[#173F35]/50 mb-8 tracking-wide uppercase font-semibold">12 Export Products</p>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
      <a class="group bg-white p-3 rounded-2xl border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all" href="productdetail.php?id=egyptian-mango">
        <div class="relative overflow-hidden rounded-xl bg-[#D8C7A1]/20 mb-3.5 aspect-[3/4]">
          <img src="https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=500&h=600&fit=crop&auto=format" alt="Egyptian Mango" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div class="absolute top-3 left-3">
            <span class="text-[9px] tracking-[0.15em] uppercase bg-[#173F35] text-[#F6F3EC] px-2.5 py-1 rounded-full font-medium">Fruits</span>
          </div>
        </div>
        <h3 class="font-semibold text-[#173F35] text-[14px] mb-0.5">Egyptian Mango</h3>
        <p class="text-[11px] text-[#8FAE5D] font-medium">Season: May – Sep</p>
      </a>

      <a class="group bg-white p-3 rounded-2xl border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all" href="productdetail.php?id=pomegranate">
        <div class="relative overflow-hidden rounded-xl bg-[#D8C7A1]/20 mb-3.5 aspect-[3/4]">
          <img src="https://images.unsplash.com/photo-1701294878194-2aa42434e9af?w=500&h=600&fit=crop&auto=format" alt="Pomegranate" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div class="absolute top-3 left-3">
            <span class="text-[9px] tracking-[0.15em] uppercase bg-[#173F35] text-[#F6F3EC] px-2.5 py-1 rounded-full font-medium">Fruits</span>
          </div>
        </div>
        <h3 class="font-semibold text-[#173F35] text-[14px] mb-0.5">Pomegranate</h3>
        <p class="text-[11px] text-[#8FAE5D] font-medium">Season: Sep – Jan</p>
      </a>

      <a class="group bg-white p-3 rounded-2xl border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all" href="productdetail.php?id=navel-orange">
        <div class="relative overflow-hidden rounded-xl bg-[#D8C7A1]/20 mb-3.5 aspect-[3/4]">
          <img src="https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=500&h=600&fit=crop&auto=format" alt="Navel Orange" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div class="absolute top-3 left-3">
            <span class="text-[9px] tracking-[0.15em] uppercase bg-[#173F35] text-[#F6F3EC] px-2.5 py-1 rounded-full font-medium">Citrus</span>
          </div>
        </div>
        <h3 class="font-semibold text-[#173F35] text-[14px] mb-0.5">Navel Orange</h3>
        <p class="text-[11px] text-[#8FAE5D] font-medium">Season: Nov – Apr</p>
      </a>

      <a class="group bg-white p-3 rounded-2xl border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all" href="productdetail.php?id=strawberry">
        <div class="relative overflow-hidden rounded-xl bg-[#D8C7A1]/20 mb-3.5 aspect-[3/4]">
          <img src="https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=500&h=600&fit=crop&auto=format" alt="Strawberry" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div class="absolute top-3 left-3">
            <span class="text-[9px] tracking-[0.15em] uppercase bg-[#173F35] text-[#F6F3EC] px-2.5 py-1 rounded-full font-medium">Fruits</span>
          </div>
        </div>
        <h3 class="font-semibold text-[#173F35] text-[14px] mb-0.5">Strawberry</h3>
        <p class="text-[11px] text-[#8FAE5D] font-medium">Season: Dec – Apr</p>
      </a>
    </div>
  </div>

  <div class="bg-[#173F35] py-16 text-center">
    <div class="flex items-center justify-center gap-3 mb-4">
      <div class="w-5 h-px bg-[#8FAE5D]"></div>
      <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Custom B2B Export Orders</p>
      <div class="w-5 h-px bg-[#8FAE5D]"></div>
    </div>
    <h2 class="font-serif text-3xl lg:text-4xl text-[#F6F3EC] mb-4">Looking for custom agricultural produce?</h2>
    <p class="text-[#F6F3EC]/60 mb-8 max-w-md mx-auto text-[14px] leading-relaxed">
      Contact our export team — we source and package a wide range of Egyptian crops on request.
    </p>
    <a class="inline-block px-8 py-4 bg-[#8FAE5D] text-[#173F35] font-semibold tracking-wide rounded-full hover:bg-[#F6F3EC] transition-colors text-[13px]" href="contact.php">Request B2B Quote</a>
  </div>
</div>
<?php include "includes/footer.php"; ?>