<?php 
$currentPage = "about.php";
require_once "includes/db.php";
$aboutHero = ['heading' => 'From the Land of Pyramids to Global Markets.', 'subtext' => "Connecting the world to the finest fresh produce from Egypt's most fertile lands, engineered for global export.", 'image_path' => 'assets/images/hero-farm.jpg'];
$aboutIntro = ['heading' => 'A Vision Built On Reliability.', 'subtext' => '', 'image_path' => 'assets/images/product-harvest.jpg'];

if (isset($conn) && $conn) {
    // ponytail: load all about page sections from DB
    $r = $conn->query("SELECT * FROM page_sections WHERE page='about'");
    if ($r && $r->num_rows > 0) {
        while ($row = $r->fetch_assoc()) {
            if ($row['section'] === 'hero') $aboutHero = $row;
            if ($row['section'] === 'intro') $aboutIntro = $row;
        }
    }
}
include "includes/header.php"; 
?>
<main class="w-full relative bg-[#F9F8F6] text-[#173F35] font-sans selection:bg-[#8FAE5D]/30">

  <!-- 1. Premium Hero -->
  <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    <div class="absolute inset-0 z-0">
      <?php $resolvedImage = !empty($aboutHero['image_path']) ? asset_url($aboutHero['image_path']) : 'assets/images/hero-farm.jpg'; ?>
      <img src="<?= htmlspecialchars($resolvedImage) ?>" alt="Egyptian agricultural farm" class="w-full h-full object-cover object-center opacity-80" />
      <div class="absolute inset-0 bg-gradient-to-b from-[#F9F8F6]/90 via-[#F9F8F6]/70 to-[#F9F8F6]"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-6 lg:px-10 relative z-10 text-center flex flex-col items-center">
      <div class="inline-flex items-center gap-3 mb-6 reveal-up">
        <div class="w-8 h-px bg-[#8FAE5D]"></div>
        <p class="text-[12px] tracking-[0.25em] uppercase text-[#8FAE5D] font-medium">Our Legacy</p>
        <div class="w-8 h-px bg-[#8FAE5D]"></div>
      </div>
      <h1 class="hero-typing-anim font-serif text-5xl sm:text-7xl lg:text-[5.5rem] text-[#173F35] leading-[1.05] max-w-4xl mx-auto mb-8 drop-shadow-sm"><?= htmlspecialchars($aboutHero['heading'] ?? 'From the Land of Pyramids to Global Markets.') ?></h1>
      <p class="text-[#173F35]/80 text-lg lg:text-xl max-w-2xl mx-auto leading-relaxed reveal-fade">
        <?= nl2br(htmlspecialchars($aboutHero['subtext'] ?? '')) ?>
      </p>
    </div>
  </section>

  <!-- 2. Our Story -->
  <section class="py-20 lg:py-32 max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
      <div class="relative rounded-2xl overflow-hidden aspect-[4/5] lg:aspect-square reveal-up shadow-xl">
        <?php $resolvedIntroImage = !empty($aboutIntro['image_path']) ? asset_url($aboutIntro['image_path']) : 'assets/images/product-harvest.jpg'; ?>
        <img src="<?= htmlspecialchars($resolvedIntroImage) ?>" alt="Egyptian farm harvest" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105"/>
        <div class="absolute inset-0 bg-[#173F35]/5 pointer-events-none"></div>
      </div>
      <div>
        <div class="flex items-center gap-3 mb-6 reveal-up">
          <div class="w-6 h-px bg-[#8FAE5D]"></div>
          <p class="text-[11px] tracking-[0.25em] uppercase text-[#8FAE5D]">Introduction</p>
        </div>
        <h2 class="anim-heading font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.1] mb-8"><?= htmlspecialchars($aboutIntro['heading'] ?? 'Our Story.') ?></h2>
        <div class="space-y-6 text-[#173F35]/75 leading-relaxed text-[16px] reveal-up" style="transition-delay: 100ms;">
          <?php if (!empty($aboutIntro['subtext'])): ?>
            <?php foreach (explode("\n\n", trim($aboutIntro['subtext'])) as $p): ?>
              <?php if (!empty(trim($p))): ?><p><?= nl2br(htmlspecialchars(trim($p))) ?></p><?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <p>After more than 20 years of hands-on agricultural experience, we saw one clear problem <br> importers struggle to find reliable suppliers they can trust.</p>
            <p>So we built Green Pyramids to solve that.</p>
            <p>We combine deep farming expertise with strict quality control and reliable sourcing <br> giving our partners consistent access to premium Egyptian produce without <br> the usual risks of inconsistency, delays, or poor quality.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. Egyptian Agricultural Advantage -->
  <section class="py-24 lg:py-36 bg-[#EBE7DF]">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 text-center mb-16">
      <h2 class="anim-heading font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.1] max-w-3xl mx-auto">OUR VISION</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6 lg:px-10">
      <div class="bg-[#F9F8F6] p-10 rounded-2xl reveal-up border border-[#173F35]/5 shadow-sm hover:shadow-md transition-shadow">
        <h3 class="anim-heading font-serif text-2xl md:text-3xl tracking-tight transition-colors duration-500 hover:text-[#8FAE5D] text-[#173F35] mb-4">01 — GROW</h3>
        <p class="text-[#173F35]/70 text-[15px] leading-relaxed">Growing Egypt’s finest produce for the world.</p>
      </div>
      <div class="bg-[#F9F8F6] p-10 rounded-2xl reveal-up border border-[#173F35]/5 shadow-sm hover:shadow-md transition-shadow" style="transition-delay: 100ms;">
        <h3 class="anim-heading font-serif text-2xl md:text-3xl tracking-tight transition-colors duration-500 hover:text-[#8FAE5D] text-[#173F35] mb-4">02 — CONNECT</h3>
        <p class="text-[#173F35]/70 text-[15px] leading-relaxed">Connecting trusted farms with global markets.</p>
      </div>
      <div class="bg-[#F9F8F6] p-10 rounded-2xl reveal-up border border-[#173F35]/5 shadow-sm hover:shadow-md transition-shadow" style="transition-delay: 200ms;">
        <h3 class="anim-heading font-serif text-2xl md:text-3xl tracking-tight transition-colors duration-500 hover:text-[#8FAE5D] text-[#173F35] mb-4">03 — INSPIRE</h3>
        <p class="text-[#173F35]/70 text-[15px] leading-relaxed">Setting a new standard for Egyptian fresh produce.</p>
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
        <h2 class="anim-heading font-serif text-4xl lg:text-5xl leading-[1.1] max-w-2xl mx-auto">Mission</h2>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
        <div class="reveal-up">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6" data-count="1" data-prefix="0">01</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">QUALITY</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">Every product meets our highest standards before it leaves Egypt.</p>
        </div>
        <div class="reveal-up" style="transition-delay: 100ms;">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6">02</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">TRUST</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">We build every relationship on transparency, reliability, and integrity.</p>
        </div>
        <div class="reveal-up" style="transition-delay: 200ms;">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6">03</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">CONNECTION</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">We connect Egyptian growers with buyers across global markets.</p>
        </div>
        <div class="reveal-up" style="transition-delay: 300ms;">
          <span class="block text-4xl font-serif text-[#D8C7A1] mb-6">04</span>
          <h3 class="text-xl font-medium mb-3 tracking-wide">IMPACT</h3>
          <p class="text-[14px] text-[#F6F3EC]/70 leading-relaxed">We create lasting value for farms, partners, and Egyptian agriculture.</p>
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
