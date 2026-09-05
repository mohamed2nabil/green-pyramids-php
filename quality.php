<?php 
$currentPage = "quality.php";
require_once "includes/db.php";
$qualityHero = [];
$standardsData = [];
$certsList = [];

if (isset($conn) && $conn) {
    // Hero
    $rq = $conn->query("SELECT * FROM page_sections WHERE page='quality' AND section='hero'");
    if ($rq && $rq->num_rows > 0) $qualityHero = $rq->fetch_assoc();

    // Standards 1-6
    $rs = $conn->query("SELECT * FROM page_sections WHERE page='quality' AND section LIKE 'standard%' ORDER BY section ASC");
    if ($rs && $rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $standardsData[$row['section']] = $row;
        }
    }

    // Certifications
    $chkTable = $conn->query("SHOW TABLES LIKE 'certifications'");
    if ($chkTable && $chkTable->num_rows > 0) {
        $rc = $conn->query("SELECT * FROM certifications WHERE is_active = 1 ORDER BY sort_order ASC");
        if ($rc && $rc->num_rows > 0) {
            while ($row = $rc->fetch_assoc()) {
                $certsList[] = $row;
            }
        }
    }
}

// Fallback standards if not in DB
$defaultStandards = [
    'standard1' => ['Farm Selection', 'We audit and approve farms based on soil quality, water source, pest management practices, and historical yield performance before any partnership begins.'],
    'standard2' => ['Product Inspection', 'Incoming produce is inspected at arrival in our packing facilities. Size, color, firmness, and visual quality are assessed against our export grading criteria.'],
    'standard3' => ['Sorting & Grading', 'Products are mechanically and manually sorted into export grades — ensuring uniformity that meets international market requirements.'],
    'standard4' => ['Packing Standards', 'We use export-standard cartons and packaging materials that protect produce during long-haul refrigerated transport.'],
    'standard5' => ['Cold Chain', 'Temperature-controlled storage and refrigerated transport maintain product freshness from packing house to destination port.'],
    'standard6' => ['Export Documentation', 'We prepare all required documentation including phytosanitary certificates, origin certificates, and customs clearance paperwork.']
];

// Fallback certifications if not in DB
if (empty($certsList)) {
    $certsList = [
        ['title' => 'Phytosanitary Certificate', 'image_path' => ''],
        ['title' => 'Certificate of Origin', 'image_path' => ''],
        ['title' => 'Export License', 'image_path' => ''],
        ['title' => 'GlobalG.A.P. Certified', 'image_path' => ''],
        ['title' => 'ISO 22000 Food Safety', 'image_path' => ''],
        ['title' => 'BRCGS Packaging Standard', 'image_path' => '']
    ];
}

$qualityHeroImg = !empty($qualityHero['image_path']) ? asset_url($qualityHero['image_path']) : 'assets/images/pages/Sunlit Green Fields and Pyramids.png';
include "includes/header.php"; 
?>
<link rel="preload" as="image" href="<?= htmlspecialchars($qualityHeroImg) ?>" />
<div class="bg-[#F6F3EC] min-h-screen">
    <div class="bg-[#173F35] pt-[72px] relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="<?= htmlspecialchars($qualityHeroImg) ?>" alt="Quality Assurance"
                loading="eager" fetchpriority="high" decoding="async"
                class="w-full h-full object-cover opacity-25 pointer-events-none" />
        </div>
        <div
            class="absolute inset-0 bg-gradient-to-r from-[#030a08]/95 via-[#173F35]/85 to-[#173F35]/90 z-0 pointer-events-none">
        </div>
        <div class="absolute inset-0 opacity-[0.045] pointer-events-none" aria-hidden="true"><svg width="100%"
                height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#F6F3EC" stroke-width="0.5"></path>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"></rect>
            </svg></div>
        <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-[0.06] pointer-events-none hidden lg:block"
            aria-hidden="true"><svg width="280" height="280" viewBox="0 0 280 280" fill="none">
                <polygon points="140,20 20,260 260,260" stroke="#F6F3EC" stroke-width="1.2"></polygon>
                <line x1="95" y1="100" x2="185" y2="100" stroke="#F6F3EC" stroke-width="0.7"></line>
                <line x1="70" y1="155" x2="210" y2="155" stroke="#F6F3EC" stroke-width="0.7"></line>
                <line x1="42" y1="210" x2="238" y2="210" stroke="#F6F3EC" stroke-width="0.7"></line>
            </svg></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-10 pt-16 pb-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-end">
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-5 h-px bg-[#8FAE5D]"></div>
                        <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Standards</p>
                    </div>
                    <h1 class="anim-heading font-serif text-5xl lg:text-[68px] text-[#F6F3EC] leading-[1.03]">
                        <?= nl2br(htmlspecialchars($qualityHero["heading"] ?? "Our Quality\nCommitment")) ?>
                    </h1>
                </div>
                <div>
                    <p class="anim-heading text-[#F6F3EC]/70 text-[15px] leading-relaxed max-w-md">
                        <?= nl2br(htmlspecialchars($qualityHero["subtext"] ?? "Quality is not a checkpoint at the end of our process — it is embedded at every stage, from farm selection to final delivery.")) ?>
                    </p>
                    <div class="flex items-center gap-8 mt-8 pt-8 border-t border-[#F6F3EC]/10">
                        <div>
                            <p class="anim-counter font-serif text-2xl text-[#F6F3EC]" data-count="100" data-suffix="%">
                                100%</p>
                            <p class="text-[10px] tracking-[0.15em] uppercase text-[#F6F3EC]/60 mt-0.5">Traceability</p>
                        </div>
                        <div>
                            <p class="anim-counter font-serif text-2xl text-[#F6F3EC]" data-count="6">6</p>
                            <p class="text-[10px] tracking-[0.15em] uppercase text-[#F6F3EC]/60 mt-0.5">Chain stages</p>
                        </div>
                        <div>
                            <p class="anim-counter font-serif text-2xl text-[#F6F3EC]" data-count="0">0</p>
                            <p class="text-[10px] tracking-[0.15em] uppercase text-[#F6F3EC]/60 mt-0.5">Compromises</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-5 h-px bg-[#8FAE5D]"></div>
                    <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Approach</p>
                </div>
                <h2 class="font-serif text-4xl text-[#173F35] leading-[1.08] mb-7">Quality Control at Every Stage</h2>
                <p class="text-[#173F35]/65 leading-relaxed mb-5 text-[15px]">At Green Pyramids, quality is a system —
                    not a single inspection step. We apply consistent standards from the moment we select a farm partner
                    through to the final loading of export containers.</p>
                <p class="text-[#173F35]/65 leading-relaxed text-[15px]">Our quality team is present at every critical
                    stage: farm selection, harvest supervision, arrival inspection, sorting, packing, and cold chain
                    management. Every shipment is traceable back to its source farm.</p>
            </div>
                <div>
                <img src="<?= htmlspecialchars(asset_url('assets/images/pages/quailty.jpeg')) ?>"
                    alt="Quality control at packing facility" class="w-full aspect-[4/3] object-cover rounded-2xl"
                    loading="lazy" decoding="async" />
                </div>
        </div>
    </section>
    <section class="py-24 bg-[#173F35]">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-5 h-px bg-[#8FAE5D]"></div>
                <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">The System</p>
            </div>
            <h2 class="font-serif text-4xl lg:text-5xl text-[#F6F3EC] leading-[1.08] mb-14">Quality Standards</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php for($i = 1; $i <= 6; $i++): 
                    $stdKey = "standard{$i}";
                    $stdTitle = $standardsData[$stdKey]['heading'] ?? $defaultStandards[$stdKey][0];
                    $stdDesc = $standardsData[$stdKey]['subtext'] ?? $defaultStandards[$stdKey][1];
                    $numFormatted = sprintf("%02d", $i);
                ?>
                <div class="p-8 rounded-2xl border border-[#D8C7A1]/20 bg-[#F6F3EC]/[0.06] hover:bg-[#F6F3EC]/[0.10] hover:border-[#8FAE5D]/50 transition-all duration-300 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="font-serif text-4xl text-[#D8C7A1] mb-4"><?= $numFormatted ?></div>
                        <div class="w-8 h-px bg-[#8FAE5D] mb-4"></div>
                        <h3 class="font-serif text-xl text-[#F6F3EC] mb-3 font-semibold tracking-wide"><?= htmlspecialchars($stdTitle) ?></h3>
                        <p class="text-[14px] text-[#F6F3EC]/90 leading-relaxed font-light"><?= nl2br(htmlspecialchars($stdDesc)) ?></p>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>
    <section class="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-5 h-px bg-[#8FAE5D]"></div>
            <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Certifications</p>
        </div>
        <h2 class="font-serif text-4xl text-[#173F35] leading-[1.08] mb-5">Our Certifications</h2>
        <p class="text-[#173F35]/55 max-w-xl mb-12 leading-relaxed text-[14px]">Green Pyramids operates in compliance
            with international export and food safety standards. Our official certifications are maintained and renewed
            annually.</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php foreach ($certsList as $cert): 
                $certImg = !empty($cert['image_path']) ? asset_url($cert['image_path']) : '';
                $certTitle = htmlspecialchars($cert['title']);
            ?>
            <div class="rounded-2xl p-6 text-center border border-[#8FAE5D]/30 bg-white shadow-sm hover:shadow-md hover:border-[#8FAE5D] transition-all duration-300 flex flex-col items-center justify-center">
                <?php if (!empty($certImg)): ?>
                    <div class="w-16 h-16 rounded-xl overflow-hidden mb-4 p-1 bg-[#F9F8F6] border border-[#8FAE5D]/20 flex items-center justify-center">
                        <img src="<?= htmlspecialchars($certImg) ?>" alt="<?= $certTitle ?>" class="max-w-full max-h-full object-contain" />
                    </div>
                <?php else: ?>
                    <div class="w-12 h-12 rounded-full bg-[#8FAE5D]/15 mx-auto mb-4 flex items-center justify-center text-[#173F35]">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#173F35" stroke-width="1.8">
                            <path d="M12 15l-2 5l-4-2l-4 2l2-5"></path>
                            <circle cx="12" cy="9" r="6"></circle>
                        </svg>
                    </div>
                <?php endif; ?>
                <p class="text-[13px] font-medium text-[#173F35] leading-snug"><?= $certTitle ?></p>
                <span class="inline-block mt-2 text-[9px] uppercase tracking-wider text-[#8FAE5D] font-semibold bg-[#8FAE5D]/10 px-2 py-0.5 rounded-full">Certified</span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="bg-[#173F35] py-24 text-center">
        <h2 class="font-serif text-3xl lg:text-5xl text-[#F6F3EC] mb-6 max-w-xl mx-auto leading-[1.08]">Quality You Can
            Rely On, Every Shipment.</h2><a
            class="inline-block px-9 py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] transition-colors text-[13px]"
            href="contact.php">Request a Quote</a>
    </section>
</div>
<?php include "includes/footer.php"; ?>