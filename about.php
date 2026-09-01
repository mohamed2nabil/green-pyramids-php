<?php 
$currentPage = "about.php";
include "includes/header.php"; 
?>
<main class="w-full relative bg-[#F9F8F6] text-[#173F35] font-sans selection:bg-[#8FAE5D]/30">

  <!-- 1. Premium Hero -->
  <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img src="assets/images/hero-farm.jpg" alt="Egyptian agricultural farm" class="w-full h-full object-cover object-center opacity-80" />
      <div class="absolute inset-0 bg-gradient-to-b from-[#F9F8F6]/90 via-[#F9F8F6]/70 to-[#F9F8F6]"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-6 lg:px-10 relative z-10 text-center flex flex-col items-center">
      <div class="inline-flex items-center gap-3 mb-6 reveal-up">
        <div class="w-8 h-px bg-[#8FAE5D]"></div>
        <p class="text-[12px] tracking-[0.25em] uppercase text-[#8FAE5D] font-medium">Our Legacy</p>
        <div class="w-8 h-px bg-[#8FAE5D]"></div>
      </div>
      <h1 class="hero-typing-anim font-serif text-5xl sm:text-7xl lg:text-[5.5rem] text-[#173F35] leading-[1.05] max-w-4xl mx-auto mb-8 drop-shadow-sm">Rooted in Deep Soil.</h1>
      <p class="text-[#173F35]/80 text-lg lg:text-xl max-w-2xl mx-auto leading-relaxed reveal-fade">
        Connecting the world to the finest fresh produce from Egypt's most fertile lands, engineered for global export.
      </p>
    </div>
  </section>

  <!-- 2. Our Story -->
  <section class="py-20 lg:py-32 max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
      <div class="relative rounded-2xl overflow-hidden aspect-[4/5] lg:aspect-square reveal-up shadow-xl">
        <img src="assets/images/product-harvest.jpg" alt="Egyptian farm harvest" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105"/>
        <div class="absolute inset-0 bg-[#173F35]/5 pointer-events-none"></div>
      </div>
      <div>
        <div class="flex items-center gap-3 mb-6 reveal-up">
          <div class="w-6 h-px bg-[#8FAE5D]"></div>
          <p class="text-[11px] tracking-[0.25em] uppercase text-[#8FAE5D]">Introduction</p>
        </div>
        <h2 class="anim-heading font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.1] mb-8">A Vision Built On Reliability.</h2>
        <div class="space-y-6 text-[#173F35]/75 leading-relaxed text-[16px] reveal-up" style="transition-delay: 100ms;">
          <p>Green Pyramids was founded with a clear vision: to bring the best of Egyptian agriculture to global markets with the professionalism and reliability that international buyers deserve.</p>
          <p>We specialize in sourcing, sorting, packing, and exporting premium fresh fruits and vegetables.</p>
          <p>We work closely with a network of trusted Egyptian farms - carefully selected based on soil quality, farming practices, and yield consistency.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. Egyptian Agricultural Advantage -->
  <section class="py-24 lg:py-36 bg-[#EBE7DF]">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 text-center mb-16">
      <h2 class="anim-heading font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.1] max-w-3xl mx-auto">The Egyptian Advantage</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6 lg:px-10">
      <div class="bg-[#F9F8F6] p-10 rounded-2xl reveal-up border border-[#173F35]/5 shadow-sm hover:shadow-md transition-shadow">
        <h3 class="anim-heading font-serif text-2xl md:text-3xl tracking-tight transition-colors duration-500 hover:text-[#8FAE5D] text-[#173F35] mb-4">Fertile Lands</h3>
        <p class="text-[#173F35]/70 text-[15px] leading-relaxed">The Nile Delta provides incredibly rich alluvial soil, producing crops with exceptional taste, size, and nutritional value.</p>
      </div>
      <div class="bg-[#F9F8F6] p-10 rounded-2xl reveal-up border border-[#173F35]/5 shadow-sm hover:shadow-md transition-shadow" style="transition-delay: 100ms;">
        <h3 class="anim-heading font-serif text-2xl md:text-3xl tracking-tight transition-colors duration-500 hover:text-[#8FAE5D] text-[#173F35] mb-4">Ideal Climate</h3>
        <p class="text-[#173F35]/70 text-[15px] leading-relaxed">Abundant sunshine and favorable weather allow for extended growing seasons and early harvests compared to Europe.</p>
      </div>
      <div class="bg-[#F9F8F6] p-10 rounded-2xl reveal-up border border-[#173F35]/5 shadow-sm hover:shadow-md transition-shadow" style="transition-delay: 200ms;">
        <h3 class="anim-heading font-serif text-2xl md:text-3xl tracking-tight transition-colors duration-500 hover:text-[#8FAE5D] text-[#173F35] mb-4">Strategic Location</h3>
        <p class="text-[#173F35]/70 text-[15px] leading-relaxed">Situated at the crossroads of three continents, enabling fast transit times and fresh delivery to global markets.</p>
      </div>
    </div>
  </section>

  <!-- 4. Quality Commitment & Values -->
  <section class="py-24 lg:py-36 bg-[#173F35] text-[#F6F3EC] relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-10">
      <img src="assets/images/process-facility.jpg" alt="Agricultural background" class="w-full h-full object-cover grayscale mix-blend-overlay" />
    </div>
    <div class="max-w-7xl mx-auto px-6 lg:px-10 relative z-10">
      <div class="text-center mb-20">
        <div class="flex items-center justify-center gap-3 mb-6 reveal-up">
          <div class="w-6 h-px bg-[#8FAE5D]"></div>
          <p class="text-[11px] tracking-[0.25em] uppercase text-[#8FAE5D]">Our Foundation</p>
          <div class="w-6 h-px bg-[#8FAE5D]"></div>
        </div>
        <h2 class="anim-heading font-serif text-4xl lg:text-5xl leading-[1.1] max-w-2xl mx-auto">Our Core Values</h2>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
        <div class="reveal-up">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6" data-count="1" data-prefix="0">01</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">Quality First</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">Every product we export meets strict international quality standards before it leaves Egyptian soil.</p>
        </div>
        <div class="reveal-up" style="transition-delay: 100ms;">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6">02</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">Transparency</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">We operate with full visibility across our supply chain - from farm to destination.</p>
        </div>
        <div class="reveal-up" style="transition-delay: 200ms;">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6">03</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">Partnership</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">We build long-term relationships based on trust, reliability, and mutual growth.</p>
        </div>
        <div class="reveal-up" style="transition-delay: 300ms;">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6">04</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">Sustainability</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">We work with farms that adopt responsible practices for the long-term health of Egyptian land.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 5. Partnership / CTA -->
  <section class="py-24 lg:py-32 bg-[#F9F8F6]">
    <div class="max-w-4xl mx-auto px-6 lg:px-10 text-center">
      <h2 class="anim-heading font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.1] mb-8">Work With Us</h2>
      <p class="text-[#173F35]/70 text-lg mb-10 reveal-fade">Source Premium Egyptian Produce for your market.</p>
      <a class="inline-flex items-center gap-2 px-10 py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full hover:bg-[#173F35] hover:text-[#F6F3EC] transition-all duration-300 reveal-up" href="contact.php">
        Request a Quote<span class="ml-1">&rarr;</span>
      </a>
    </div>
  </section>

</main>
<?php include "includes/footer.php"; ?>
