<?php include "includes/header.php"; ?>
<?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
<div class="fixed top-24 left-1/2 -translate-x-1/2 z-[200] bg-[#e8f5e9] text-green-800 border border-green-200 px-6 py-3 rounded-full shadow-lg text-sm flex items-center gap-3">
  <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
  Thank you for your review! It will be published once approved.
  <button onclick="this.parentElement.style.display='none'" class="ml-2 font-bold opacity-60 hover:opacity-100">&times;</button>
</div>
<?php endif; ?>

<link rel="preload" as="image" href="assets/images/static/hero_background.png"/>


<?php 
$homeSections = [];
if (isset($conn)) {
    $r = $conn->query("SELECT * FROM page_sections WHERE page='home'");
    if ($r && $r->num_rows > 0) {
        while ($row = $r->fetch_assoc()) {
            $homeSections[$row['section']] = $row;
        }
    }
}
$homeHero = $homeSections['hero'] ?? ['heading' => $siteSettings['hero_title'] ?? "From Egyptian Soil\nTo Global Markets.", 'subtext' => $siteSettings['hero_subtitle'] ?? "Connecting world markets...", 'image_path' => ''];
$homeOverline = $homeSections['hero_overline'] ?? ['heading' => 'Egyptian Agricultural Exports'];
$kpis = [
    $homeSections['kpi1'] ?? ['heading' => '15+', 'subtext' => 'Years Exporting', 'image_path' => 'Nile Delta & Upper Egypt'],
    $homeSections['kpi2'] ?? ['heading' => '30+', 'subtext' => 'Global Markets', 'image_path' => 'EU, Gulf & Worldwide'],
    $homeSections['kpi3'] ?? ['heading' => '50K+', 'subtext' => 'Tons Shipped', 'image_path' => 'Harvested to order'],
    $homeSections['kpi4'] ?? ['heading' => '100%', 'subtext' => 'Cold Chain Integrity', 'image_path' => 'Export-grade certified']
];
?>
<div class="bg-[#F6F3EC]">
  <!-- 1. HERO SECTION WITH 3D TUBES CANVAS -->
  <section id="hero-section" class="relative w-full overflow-hidden bg-[#050c0a] flex items-center justify-center cursor-pointer select-none" style="min-height: 92vh; min-height: 92dvh;">
    <!-- Agricultural farm and pyramids background photo (clearly visible with opacity-65) -->
    <?php $resolvedBg = !empty($homeHero['image_path']) ? asset_url($homeHero['image_path']) : 'assets/images/static/hero_background.png'; ?>
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-65 pointer-events-none" style="background-image:url('<?= htmlspecialchars($resolvedBg) ?>')"></div>
    <!-- Rich dark-to-emerald gradient gradation -->
    <div class="absolute inset-0 bg-gradient-hero pointer-events-none"></div>
    <!-- 3D Neon Tubes Canvas — blend mode set in main.css since tailwind.css lacks mix-blend-screen -->
    <canvas id="hero-canvas" class="absolute inset-0 w-full h-full block z-0" style="touch-action:none;opacity:0"></canvas>

    <!-- Hero Content Overlay (pt-32 sm:pt-36 ensures plenty of clearance below the 80px navbar) -->
    <div class="relative z-20 w-full max-w-5xl mx-auto px-4 sm:px-6 pt-32 sm:pt-40 pb-20 sm:pb-28 flex flex-col items-center justify-center text-center">
      <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-5">
        <div class="w-4 sm:w-6 h-px bg-[#8FAE5D]"></div>
        <p class="text-[9px] sm:text-[12px] tracking-[0.2em] sm:tracking-[0.3em] uppercase text-[#8FAE5D] font-semibold drop-shadow"><?= htmlspecialchars($homeOverline['heading'] ?? 'Egyptian Agricultural Exports') ?></p>
        <div class="w-4 sm:w-6 h-px bg-[#8FAE5D]"></div>
      </div>

      <h1 class="anim-heading font-serif text-4xl sm:text-6xl lg:text-7xl xl:text-[80px] text-[#F6F3EC] leading-[1.08] max-w-4xl mx-auto drop-shadow-2xl mb-4 sm:mb-6 tracking-tight">
        <?= nl2br(htmlspecialchars($homeHero['heading'] ?? "Egyptian Agricultural Exporter\nFrom Egyptian Soil to Global Markets.")) ?>
      </h1>

      <p class="text-[#F6F3EC] font-medium text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed mb-6 sm:mb-8 drop-shadow-lg">
        <?= nl2br(htmlspecialchars($homeHero['subtext'])) ?>
      </p>

      <div class="relative z-30 flex flex-col sm:flex-row items-center justify-center gap-4 mb-5 sm:mb-6 w-full sm:w-auto">
        <a class="relative z-30 w-full sm:w-auto px-8 py-3.5 bg-[#8FAE5D] text-[#173F35] text-[13px] font-semibold tracking-wide rounded-full hover:bg-[#F6F3EC] transition-all duration-300 shadow-xl cursor-pointer" href="products.php" onclick="window.location.href='products.php'">Explore Catalog</a>
        <a class="relative z-30 w-full sm:w-auto px-8 py-3.5 bg-white/15 backdrop-blur-md text-[#F6F3EC] border border-white/25 text-[13px] font-semibold tracking-wide rounded-full hover:bg-white/30 transition-all duration-300 shadow-xl cursor-pointer" href="contact.php" onclick="window.location.href='contact.php'">Request a Quote</a>
      </div>

      <div class="text-[#8FAE5D] text-[11px] tracking-[0.2em] uppercase select-none flex items-center gap-2 pointer-events-none font-medium drop-shadow">
        <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#8FAE5D] animate-ping"></span>
        Move cursor to interact · Click to randomize neon colors
      </div>
    </div>
  </section>

  <!-- 2. ACHIEVEMENTS / STATS BAR WITH COLOR GRADIENTS -->
  <div class="bg-gradient-stats shadow-lg relative z-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 grid grid-cols-2 lg:grid-cols-4">
      <?php foreach($kpis as $kpi): ?>
      <div class="px-4 sm:px-5 py-4 first:pl-0 last:pr-0">
        <div class="flex items-baseline gap-1 mb-0.5">
          <span class="anim-counter text-gradient-gold font-serif text-3xl sm:text-4xl font-bold leading-none" data-count="<?= preg_replace('/[^0-9]/', '', $kpi['heading']) ?>" data-suffix="<?= preg_replace('/[0-9]/', '', $kpi['heading']) ?>"><?= htmlspecialchars($kpi['heading']) ?></span>
        </div>
        <p class="text-[10px] tracking-[0.13em] uppercase text-[#F6F3EC] font-semibold"><?= htmlspecialchars($kpi['subtext']) ?></p>
        <p class="text-[9px] text-[#8FAE5D] tracking-wide mt-0.5"><?= htmlspecialchars($kpi['image_path']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- 3. WHO WE ARE SECTION -->
  <section class="py-10 lg:py-12 max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
      <div>
        <img src="assets/images/static/hero_background.png" alt="Egyptian agricultural fields" class="w-full aspect-[4/3] object-cover rounded-2xl shadow-xl" loading="lazy" decoding="async"/>
        <!-- Founded badge — inline below image, not absolute -->
        <div class="flex justify-end mt-3 pr-2">
          <div class="bg-[#F6F3EC] border border-[#D8C7A1]/60 rounded-2xl px-5 py-3 shadow-lg">
            <div class="flex items-center gap-3">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><polygon points="12,2 2,21 12,21" fill="#1f5245"></polygon><polygon points="12,2 22,21 12,21" fill="#0d2a24"></polygon><polygon points="12,2 22,21 22,21" fill="none" stroke="#8FAE5D" stroke-width="0.9"></polygon></svg>
              <div>
                <p class="text-[9px] tracking-[0.18em] uppercase text-[#173F35]/50 font-medium">Founded</p>
                <p class="anim-counter font-serif text-2xl lg:text-3xl text-[#173F35] leading-none" data-count="2010">2010</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div>
        <div class="flex items-center gap-2.5 mb-3">
          <div class="w-4 h-px bg-[#8FAE5D]"></div>
          <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D] font-semibold">Who We Are</p>
        </div>
        <h2 class="anim-heading font-serif text-3xl sm:text-4xl lg:text-5xl text-[#173F35] leading-tight mb-4">Growing Quality. Delivering Trust.</h2>
        <p class="text-[#173F35]/70 text-sm sm:text-base leading-relaxed mb-4">Green Pyramids is an Egyptian agricultural export company connecting the world to Egypt's finest fresh produce. We partner with trusted farms across the Nile Delta and Upper Egypt, applying strict quality standards from field to final delivery.</p>
        <p class="text-[#173F35]/70 text-sm sm:text-base leading-relaxed mb-6">Our clients include importers, distributors, wholesalers, and supermarket chains across Europe, the Gulf, the Middle East, and Asia.</p>
        <a href="about.php" class="inline-flex items-center gap-2 text-[#173F35] font-semibold text-sm hover:text-[#8FAE5D] transition-colors group">
          Discover Green Pyramids <span class="group-hover:translate-x-1.5 transition-transform">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- 4. CATEGORIES HORIZONTAL SMOOTH CAROUSEL -->
  <?php
  if (!isset($conn)) require_once 'includes/db.php';
  if (!function_exists('slugify_category')) {
    function slugify_category(string $n): string {
      return trim(preg_replace('/[^a-z0-9]+/','-',strtolower(trim($n))),'-');
    }
  }
  $idxCats = [];
  $idxCatRes = $conn ? $conn->query("SELECT category_id, category_name, image_path FROM categories ORDER BY category_id ASC") : null;
  if ($idxCatRes) { while ($c = $idxCatRes->fetch_assoc()) $idxCats[] = $c; }
  ?>
  <?php if (!empty($idxCats)): ?>
  <!-- 4. CATEGORIES — HORIZONTAL SCROLL GALLERY -->
  <section id="idx-cat-section" class="relative bg-gradient-category text-[#F6F3EC] border-y border-[#D8C7A1]/20 shadow-2xl">
    <!-- Pinned container -->
    <div id="idx-cat-pinned" class="w-full flex flex-col justify-center overflow-hidden py-12 lg:py-16 min-h-screen">
      <!-- Header -->
      <div class="max-w-7xl mx-auto px-6 lg:px-10 mb-8 w-full flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <div class="w-4 h-px bg-[#8FAE5D]"></div>
            <p class="text-[9px] sm:text-[11px] tracking-[0.25em] uppercase text-[#8FAE5D] font-semibold">What We Export</p>
          </div>
          <h2 class="anim-heading font-serif text-3xl sm:text-4xl lg:text-5xl text-[#F6F3EC] leading-tight">Our Categories</h2>
        </div>
        <a href="products.php" class="inline-flex items-center gap-2 text-[#8FAE5D] hover:text-[#D8C7A1] text-sm tracking-wide transition-colors font-medium">
          View All Products <span class="text-lg">&rarr;</span>
        </a>
      </div>
      
      <!-- Horizontal track container -->
      <div class="w-full px-6 lg:px-10">
        <div id="idx-cat-track" class="w-full">
          <?php foreach ($idxCats as $cat):
            $catImg  = htmlspecialchars($cat['image_path'] ?? '');
            $catLbl  = htmlspecialchars(ucwords($cat['category_name']));
            $catUrl  = 'products.php?category='.(int)$cat['category_id'].'&page=1';
          ?>
          <a href="<?= $catUrl ?>" class="idx-cat-card group">
            <?php if ($catImg): ?>
              <img src="<?= $catImg ?>" alt="<?= $catLbl ?>" loading="lazy" decoding="async"/>
            <?php else: ?>
              <div class="absolute inset-0 bg-[#1f5245]"></div>
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-t from-[#071410]/95 via-[#071410]/40 to-transparent pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 right-0 p-5 z-10">
              <p class="font-serif text-xl text-[#F6F3EC] leading-tight mb-1.5"><?= $catLbl ?></p>
              <p class="text-[9px] tracking-[0.15em] uppercase text-[#8FAE5D] flex items-center gap-1.5 font-semibold group-hover:translate-x-1.5 transition-transform">
                Explore Catalog &rarr;
              </p>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5. REVIEWS / TESTIMONIALS INFINITE MARQUEE -->
  <section class="py-12 lg:py-16 bg-[#F6F3EC] overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 mb-12 text-center">
      <div class="flex items-center justify-center gap-3 mb-4">
        <div class="w-5 h-px bg-[#8FAE5D]"></div>
        <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D] font-semibold">Testimonials</p>
        <div class="w-5 h-px bg-[#8FAE5D]"></div>
      </div>
      <h2 class="anim-heading font-serif text-3xl lg:text-5xl text-[#173F35] mb-6">What Our Clients Say</h2>
      <button onclick="document.getElementById('reviewModal').classList.remove('hidden')" class="px-6 py-2.5 border border-[#D8C7A1] text-[#173F35] text-[13px] font-medium tracking-wide rounded-full hover:bg-[#D8C7A1] transition-all duration-300">Leave a Review</button>
    </div>
    
    <div class="marquee-container relative">
      <div class="absolute left-0 top-0 bottom-0 w-16 md:w-32 bg-gradient-to-r from-[#F6F3EC] to-transparent z-10 pointer-events-none"></div>
      <div class="absolute right-0 top-0 bottom-0 w-16 md:w-32 bg-gradient-to-l from-[#F6F3EC] to-transparent z-10 pointer-events-none"></div>
      <div class="marquee-track px-4">
        <?php
        require_once "includes/db.php";
        $res = $conn ? $conn->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY created_at DESC LIMIT 6") : null;
        $reviews = [];
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $reviews[] = $row;
            }
        }
        
        if (empty($reviews)) {
            $reviews = [
                ['rating' => 5, 'review' => 'Excellent quality and timely delivery. The best Egyptian produce we have sourced.', 'client_name' => 'Michael T.', 'company' => 'EuroFresh', 'country' => 'UK'],
                ['rating' => 5, 'review' => 'Professional export process and transparent communication from field to port.', 'client_name' => 'Sarah J.', 'company' => 'Global Foods', 'country' => 'UAE'],
                ['rating' => 5, 'review' => 'Consistent sizing and great cold chain management. Highly recommend.', 'client_name' => 'Ahmed K.', 'company' => 'FreshGate', 'country' => 'KSA'],
                ['rating' => 5, 'review' => 'Green Pyramids delivers exactly what they promise. Premium export quality.', 'client_name' => 'Elena R.', 'company' => 'AgriTrade', 'country' => 'Italy']
            ];
        }
        
        // Duplicate for continuous infinite marquee loop
        $display_reviews = array_merge($reviews, $reviews);
        
        foreach ($display_reviews as $row):
        ?>
        <div class="testimonial-card flex-shrink-0 w-[248px] sm:w-[280px] bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-[#D8C7A1]/30 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
          <div class="flex gap-1 mb-4 text-[#D8C7A1]">
            <?php for($i = 0; $i < min(5, (int)$row['rating']); $i++): ?>
            <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
            <?php endfor; ?>
          </div>
          <p class="text-[#173F35]/75 italic text-[13px] mb-4 leading-relaxed">"<?= htmlspecialchars($row['review']) ?>"</p>
          <div class="border-t border-[#173F35]/10 pt-4">
            <p class="font-serif text-[#173F35] font-medium text-[15px]"><?= htmlspecialchars($row['client_name']) ?></p>
            <p class="text-xs text-[#173F35]/50 mt-0.5"><?= htmlspecialchars($row['company'] ?? '') ?><?= (!empty($row['company']) && !empty($row['country'])) ? ' &bull; ' : '' ?><?= htmlspecialchars($row['country'] ?? '') ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 6. PARTNER WITH US (CONTAINED FLOATING CARD - NOT STUCK TO FOOTER) -->
  <section class="py-16 lg:py-24 px-6 lg:px-10 bg-[#F6F3EC]">
    <div class="max-w-4xl mx-auto bg-[#173F35] text-[#F6F3EC] rounded-3xl p-8 sm:p-12 lg:p-16 text-center shadow-xl border border-[#D8C7A1]/20 relative overflow-hidden">
      <!-- Faint decorative background gradient -->
      <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-[#8FAE5D]/10 blur-3xl pointer-events-none"></div>
      
      <div class="relative z-10">
        <div class="inline-flex items-center justify-center gap-3 mb-5 px-3.5 py-1.5 rounded-full border border-[#8FAE5D]/30 bg-white/5 backdrop-blur-sm">
          <span class="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]"></span>
          <p class="text-[9px] tracking-[0.25em] uppercase text-[#8FAE5D] font-semibold">Partner With Us</p>
          <span class="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]"></span>
        </div>

        <h2 class="anim-heading font-serif text-3xl sm:text-4xl lg:text-5xl text-[#F6F3EC] leading-[1.15] max-w-2xl mx-auto mb-5 tracking-tight">
          Looking for a Reliable Egyptian Export Partner?
        </h2>

        <p class="text-[#F6F3EC]/70 text-sm sm:text-base max-w-xl mx-auto leading-relaxed mb-8">
          Tell us what products you need and our export team will prepare a customized quotation with full specification and logistics details.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
          <a class="w-full sm:w-auto px-8 py-3.5 bg-[#8FAE5D] text-[#173F35] text-[13px] font-semibold tracking-wide rounded-full hover:bg-[#F6F3EC] transition-all duration-300 shadow-md hover:-translate-y-0.5" href="contact.php">Request Your Quote</a>
          <a class="w-full sm:w-auto px-8 py-3.5 border border-[#F6F3EC]/30 text-[#F6F3EC] text-[13px] font-medium tracking-wide rounded-full hover:bg-white/10 transition-all duration-300 backdrop-blur-sm hover:-translate-y-0.5" href="contact.php">Contact Our Team</a>
        </div>
      </div>
    </div>
  </section>
</div>


<!-- Review Modal -->
<div id="reviewModal" class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-[#F6F3EC] rounded-2xl w-full max-w-md p-5 sm:p-6 relative shadow-2xl border border-[#D8C7A1]/30">
    <button type="button" onclick="document.getElementById('reviewModal').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-black/5 hover:bg-black/10 text-[#173F35] transition-colors text-xl leading-none">&times;</button>
    <div class="flex items-center gap-2 mb-1">
      <div class="w-3 h-px bg-[#8FAE5D]"></div>
      <p class="text-[9px] tracking-[0.2em] uppercase text-[#8FAE5D] font-semibold">Feedback</p>
    </div>
    <h3 class="font-serif text-2xl text-[#173F35] mb-6">Leave a Review</h3>
    <form action="api/submit_review.php" method="POST" class="space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div>
          <label class="block text-[9px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-1">Name *</label>
          <input type="text" name="client_name" required class="w-full px-4 py-2 bg-white border border-[#D8C7A1]/50 rounded-xl text-[12px] text-[#173F35] focus:outline-none focus:border-[#173F35]/50" />
        </div>
        <div>
          <label class="block text-[9px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-1">Company</label>
          <input type="text" name="company" class="w-full px-4 py-2 bg-white border border-[#D8C7A1]/50 rounded-xl text-[12px] text-[#173F35] focus:outline-none focus:border-[#173F35]/50" />
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div>
          <label class="block text-[9px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-1">Country</label>
          <input type="text" name="country" class="w-full px-4 py-2 bg-white border border-[#D8C7A1]/50 rounded-xl text-[12px] text-[#173F35] focus:outline-none focus:border-[#173F35]/50" />
        </div>
        <div>
          <label class="block text-[9px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-1">Rating *</label>
          <select name="rating" required class="w-full px-4 py-2 bg-white border border-[#D8C7A1]/50 rounded-xl text-[12px] text-[#173F35] focus:outline-none focus:border-[#173F35]/50">
            <option value="5">★★★★★ (5/5)</option>
            <option value="4">★★★★☆ (4/5)</option>
            <option value="3">★★★☆☆ (3/5)</option>
            <option value="2">★★☆☆☆ (2/5)</option>
            <option value="1">★☆☆☆☆ (1/5)</option>
          </select>
        </div>
      </div>
      <div>
        <label class="block text-[9px] tracking-[0.18em] uppercase text-[#173F35]/45 mb-1">Review *</label>
        <textarea name="review" required rows="4" class="w-full px-4 py-2 bg-white border border-[#D8C7A1]/50 rounded-xl text-[12px] text-[#173F35] focus:outline-none focus:border-[#173F35]/50 resize-none"></textarea>
      </div>
      <button type="submit" class="w-full py-3.5 mt-2 bg-[#173F35] text-[#F6F3EC] font-medium tracking-wide rounded-full hover:bg-[#8FAE5D] hover:text-[#173F35] transition-colors duration-200 text-[13px]">Submit Review</button>
    </form>
  </div>
</div>

<?php include "includes/footer.php"; ?>
