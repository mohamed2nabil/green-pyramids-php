<?php
// ponytail: DB-driven catalog with pagination, MasonryGallery-inspired visual cards, GSAP CardSwap hero, and progressive AJAX filtering.
require_once "includes/db.php";

// Helper functions for category slugification and season formatting
if (!function_exists('slugify_category')) {
    function slugify_category(string $name): string {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
}

if (!function_exists('get_product_season')) {
    function get_product_season(array $p): string {
        $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $avail = [];
        foreach ($months as $idx => $m) {
            if (!empty($p['avail_' . $m])) {
                $avail[] = $idx;
            }
        }
        $count = count($avail);
        if ($count === 12) return 'Year-round';
        if ($count === 0) return 'Check Availability';
        if ($count === 1) return $monthNames[$avail[0]];

        $first = $monthNames[$avail[0]];
        $last = $monthNames[end($avail)];
        return $first . ' – ' . $last;
    }
}

// 1. Fetch DB categories
$categoriesRes = $conn ? $conn->query("SELECT category_id, category_name FROM categories ORDER BY category_id ASC") : null;
$dbCategories = [];
if ($categoriesRes) {
    while ($cat = $categoriesRes->fetch_assoc()) {
        $dbCategories[] = $cat;
    }
}

// 2. Define canonical filter tabs
$filterTabs = [
    ['slug' => 'all', 'label' => 'All Products', 'cat_ids' => []],
    ['slug' => 'fruits', 'label' => 'Fruits', 'cat_ids' => []],
    ['slug' => 'vegetables', 'label' => 'Vegetables', 'cat_ids' => []],
    ['slug' => 'citrus', 'label' => 'Citrus', 'cat_ids' => []],
    ['slug' => 'dates', 'label' => 'Dates', 'cat_ids' => []],
];

// Map DB categories into standard filter tabs
$mappedCatIds = [];
foreach ($dbCategories as $cat) {
    $id = (int)$cat['category_id'];
    $name = strtolower($cat['category_name']);
    
    if (strpos($name, 'fruit') !== false) {
        $filterTabs[1]['cat_ids'][] = $id;
        $mappedCatIds[] = $id;
    } elseif (strpos($name, 'veg') !== false) {
        $filterTabs[2]['cat_ids'][] = $id;
        $mappedCatIds[] = $id;
    } elseif (strpos($name, 'citrus') !== false) {
        $filterTabs[3]['cat_ids'][] = $id;
        $mappedCatIds[] = $id;
    } elseif (strpos($name, 'date') !== false || strpos($name, 'season') !== false || strpos($name, 'crop') !== false) {
        $filterTabs[4]['cat_ids'][] = $id;
        $mappedCatIds[] = $id;
    }
}

// Append any unmapped categories from DB as extra tabs
foreach ($dbCategories as $cat) {
    $id = (int)$cat['category_id'];
    if (!in_array($id, $mappedCatIds)) {
        $filterTabs[] = [
            'slug' => slugify_category($cat['category_name']),
            'label' => ucwords($cat['category_name']),
            'cat_ids' => [$id]
        ];
    }
}

// 3. Resolve selected category filter tab
$rawCategory = strtolower(trim($_GET['category'] ?? 'all'));
$activeTabIndex = 0; // Default 'all'

foreach ($filterTabs as $index => &$tab) {
    $tabSlug = $tab['slug'];
    $tabLabelSlug = slugify_category($tab['label']);
    
    $isMatch = false;
    if ($rawCategory === $tabSlug || $rawCategory === $tabLabelSlug || ($rawCategory === 'seasonal-crops' && $tabSlug === 'dates')) {
        $isMatch = true;
    } elseif ($tabSlug === 'fruits' && (strpos($rawCategory, 'fruit') !== false)) {
        $isMatch = true;
    } elseif ($tabSlug === 'vegetables' && (strpos($rawCategory, 'veg') !== false)) {
        $isMatch = true;
    } elseif ($tabSlug === 'citrus' && (strpos($rawCategory, 'citrus') !== false)) {
        $isMatch = true;
    } elseif ($tabSlug === 'dates' && (strpos($rawCategory, 'date') !== false || strpos($rawCategory, 'season') !== false || strpos($rawCategory, 'crop') !== false)) {
        $isMatch = true;
    } elseif (is_numeric($rawCategory) && in_array((int)$rawCategory, $tab['cat_ids'])) {
        $isMatch = true;
    }

    if ($isMatch) {
        $activeTabIndex = $index;
        $tab['active'] = true;
    } else {
        $tab['active'] = false;
    }
}
unset($tab);

// Set chosen tab active
foreach ($filterTabs as $index => &$t) {
    $t['active'] = ($index === $activeTabIndex);
}
unset($t);

$activeTab = $filterTabs[$activeTabIndex];
$targetCatIds = $activeTab['cat_ids'];

// 4. Pagination calculation to split loading across pages
$perPage = 12; // Load 12 products per page to prevent bandwidth overload
$currentPageNum = max(1, (int)($_GET['page'] ?? 1));

// Count total products for active category filter
$countSql = "SELECT COUNT(*) as total FROM products p WHERE p.is_active = 1 AND p.is_visible = 1";
$countParams = [];
$countTypes = "";

if (!empty($targetCatIds)) {
    $inClause = implode(',', array_fill(0, count($targetCatIds), '?'));
    $countSql .= " AND p.category_id IN ($inClause)";
    $countTypes .= str_repeat('i', count($targetCatIds));
    $countParams = $targetCatIds;
}

$countStmt = $conn->prepare($countSql);
if (!empty($countParams)) {
    $countStmt->bind_param($countTypes, ...$countParams);
}
$countStmt->execute();
$totalFilteredProducts = (int)$countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = max(1, (int)ceil($totalFilteredProducts / $perPage));
if ($currentPageNum > $totalPages) {
    $currentPageNum = $totalPages;
}
$offset = ($currentPageNum - 1) * $perPage;

// 5. Fetch Paginated Products from Database
$sql = "SELECT p.*, c.category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        WHERE p.is_active = 1 AND p.is_visible = 1";

$params = [];
$types = "";

if (!empty($targetCatIds)) {
    $inClause = implode(',', array_fill(0, count($targetCatIds), '?'));
    $sql .= " AND p.category_id IN ($inClause)";
    $types .= str_repeat('i', count($targetCatIds));
    $params = $targetCatIds;
}

$sql .= " ORDER BY p.product_id ASC LIMIT ? OFFSET ?";
$types .= "ii";
$params[] = $perPage;
$params[] = $offset;

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$productsResult = $stmt->get_result();
$products = [];
while ($row = $productsResult->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();

// Total visible count across entire DB for Hero stats
$totalCountRes = $conn->query("SELECT COUNT(*) as cnt FROM products WHERE is_active = 1 AND is_visible = 1");
$totalProductsCount = $totalCountRes ? (int)$totalCountRes->fetch_assoc()['cnt'] : $totalFilteredProducts;

// Fetch custom Hero Cards from product_hero_cards table
$heroCards = [];
if (isset($conn)) {
    $r = $conn->query("SELECT * FROM product_hero_cards ORDER BY sort_order ASC LIMIT 4");
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $heroCards[] = $row;
        }
    }
}
if (empty($heroCards) && isset($conn)) {
    $fallbackRes = $conn->query("SELECT product_id as id, name as title, 'Produce' as category, image_path, CONCAT('productdetail.php?id=', slug) as link_url, 1 as sort_order FROM products WHERE is_active = 1 AND is_visible = 1 LIMIT 4");
    if ($fallbackRes) {
        while ($row = $fallbackRes->fetch_assoc()) {
            $heroCards[] = $row;
        }
    }
}

include "includes/header.php";
$productionHero = [];
if (isset($conn)) {
    $r = $conn->query("SELECT * FROM page_sections WHERE page='production' AND section='hero'");
    if ($r && $r->num_rows > 0) $productionHero = $r->fetch_assoc();
}
?>

<div class="bg-[#F6F3EC] min-h-screen">
  <!-- Hero Section with Smart GSAP CardSwap Showcase -->
  <div class="bg-[#173F35] pt-12 relative overflow-hidden flex flex-col lg:flex-row min-h-[65vh] lg:h-[72vh]">
    <!-- Left Hero Brand Intro -->
    <div class="lg:w-[45%] z-10 flex flex-col justify-center px-6 lg:px-16 py-12 lg:py-16 bg-[#173F35]">
      <div class="flex items-center gap-3 mb-4 lg:mb-5">
        <div class="w-5 h-px bg-[#8FAE5D]"></div>
        <p class="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Our Export Catalog</p>
      </div>
      <h1 class="anim-heading font-serif text-4xl sm:text-5xl lg:text-5xl text-[#F6F3EC] leading-[1.1] mb-4 lg:mb-6 break-words" style="hyphens: none; word-break: break-word;">
          <?= htmlspecialchars($productionHero["heading"] ?? "Egyptian Fresh Produce") ?>
        </h1>
        <p class="anim-heading text-[#F6F3EC]/70 text-[14px] lg:text-[15px] max-w-sm leading-relaxed mb-8">
          <?= htmlspecialchars($productionHero["subtext"] ?? "Explore our selection of premium agricultural crops prepared for international markets.") ?>
        </p>
      <div class="flex gap-8">
        <div>
          <p class="font-serif text-2xl lg:text-3xl text-[#F6F3EC]"><?= $totalProductsCount ?>+</p>
          <p class="text-[9px] lg:text-[10px] tracking-[0.18em] uppercase text-[#8FAE5D] mt-1">Export-ready</p>
        </div>
        <div>
          <p class="font-serif text-2xl lg:text-3xl text-[#F6F3EC]"><?= count($dbCategories) ?></p>
          <p class="text-[9px] lg:text-[10px] tracking-[0.18em] uppercase text-[#8FAE5D] mt-1">Categories</p>
        </div>
      </div>
    </div>

    <!-- Right Hero Section: Perspective GSAP CardSwap Interactive Stage -->
    <div class="lg:w-[55%] relative flex-1 min-h-[420px] lg:min-h-full bg-gradient-to-br from-[#173F35] via-[#0d2a24] to-[#1f5245] overflow-hidden flex items-center justify-center p-6 sm:p-12">
      <div id="card-swap-wrapper" class="relative w-full max-w-[500px] h-[360px] sm:h-[420px] flex items-center justify-center">
        <div id="hero-card-swap-container" class="relative w-[280px] sm:w-[340px] lg:w-[380px] h-[340px] sm:h-[400px] perspective-[1200px] transform-gpu">
          <div class="absolute inset-0 [transform-style:preserve-3d]">
            <?php foreach ($heroCards as $idx => $hp): 
              $hpImg = asset_url($hp["image_path"]); 
              $hpCat = htmlspecialchars($hp["category"] ?? "Produce"); 
              $hpName = htmlspecialchars($hp["title"]); 
              $hpLink = htmlspecialchars($hp["link_url"] ?? "#"); 
              $isFront = ($idx === 0); 
            ?>
              <a href="<?= $hpLink ?>" data-swap-index="<?= $idx ?>" class="swap-card group absolute top-1/2 left-1/2 w-[280px] sm:w-[340px] lg:w-[380px] h-[320px] sm:h-[380px] rounded-2xl border border-[#D8C7A1]/30 bg-[#0d2a24] shadow-2xl overflow-hidden [transform-style:preserve-3d] [will-change:transform] [backface-visibility:hidden] cursor-pointer transition-shadow duration-300 hover:shadow-emerald-900/40 block">
                <div class="relative h-full w-full flex flex-col">
                  <div class="flex-1 overflow-hidden relative bg-[#173F35]">
                    <img src="<?= htmlspecialchars($hpImg) ?>" 
                         alt="<?= $hpName ?>" 
                         loading="<?= $isFront ? "eager" : "lazy" ?>" 
                         decoding="async"
                         <?= $isFront ? 'fetchpriority="high"' : '' ?> 
                         width="380" 
                         height="380"
                         class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-108" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0d2a24] via-[#0d2a24]/30 to-transparent"></div>
                    <div class="absolute top-4 left-4 z-10">
                      <span class="inline-block text-[9px] tracking-[0.2em] uppercase bg-[#173F35]/90 backdrop-blur-md text-[#8FAE5D] px-3 py-1 rounded-full font-semibold shadow-sm border border-[#8FAE5D]/30"><?= $hpCat ?></span>
                    </div>
                  </div>
                  <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-6 text-[#F6F3EC] bg-gradient-to-t from-[#0d2a24] via-[#0d2a24]/90 to-transparent">
                    <h3 class="font-serif text-xl sm:text-2xl text-[#F6F3EC] group-hover:text-[#8FAE5D] transition-colors duration-200 mb-1 leading-snug"><?= $hpName ?></h3>
                    <div class="flex items-center justify-between text-xs text-[#F6F3EC]/70 mt-2">
                      <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#D8C7A1] group-hover:translate-x-1 transition-transform">Explore &rarr;</span>
                    </div>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Category Filter Bar -->
  <div class="sticky top-[80px] z-30 bg-[#F6F3EC]/95 backdrop-blur-md border-b border-[#D8C7A1]/50 shadow-sm transition-all duration-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="flex items-center gap-2 overflow-x-auto py-3.5 scrollbar-hide" id="category-filter-bar">
        <?php foreach ($filterTabs as $tab): 
          $isActive = !empty($tab['active']);
          $tabUrl = "products.php?category=" . urlencode($tab['slug']) . "&page=1";
        ?>
          <a href="<?= htmlspecialchars($tabUrl) ?>" 
             data-category-slug="<?= htmlspecialchars($tab['slug']) ?>"
             class="catalog-filter-btn flex-shrink-0 px-5 py-2 rounded-full text-[12px] tracking-wide transition-all duration-200 cursor-pointer <?= $isActive ? 'bg-[#173F35] text-[#F6F3EC] font-semibold shadow-sm' : 'bg-white/80 border border-[#D8C7A1]/40 text-[#173F35]/70 hover:bg-[#173F35]/10 hover:text-[#173F35]' ?>">
            <?= htmlspecialchars($tab['label']) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Product Catalog Section -->
  <div class="max-w-7xl mx-auto px-6 lg:px-10 py-14">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-[#D8C7A1]/30 pb-4">
      <div>
        <p id="catalog-count-text" class="text-[11px] text-[#173F35]/60 tracking-wider uppercase font-semibold">
          Showing <?= count($products) ?> of <?= $totalFilteredProducts ?> <?= $activeTab['slug'] === 'all' ? 'Export Products' : htmlspecialchars($activeTab['label']) . ' Products' ?> (Page <?= $currentPageNum ?> of <?= $totalPages ?>)
        </p>
      </div>
      <span class="text-[11px] text-[#8FAE5D] tracking-widest uppercase font-medium">Certified Egyptian Origin</span>
    </div>

    <!-- MasonryGallery Inspired Product Grid Container -->
    <div id="products-grid-container" class="transition-all duration-300">
      <?php if (empty($products)): ?>
        <!-- Empty State -->
        <div class="py-20 text-center bg-white rounded-3xl border border-[#D8C7A1]/40 shadow-sm max-w-xl mx-auto px-6">
          <div class="w-16 h-16 rounded-full bg-[#F6F3EC] flex items-center justify-center mx-auto mb-5 text-[#8FAE5D]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <h3 class="font-serif text-2xl text-[#173F35] mb-2">No Export Products Found</h3>
          <p class="text-sm text-[#173F35]/60 mb-8 max-w-md mx-auto leading-relaxed">
            There are currently no products listed under the <span class="font-semibold text-[#173F35]"><?= htmlspecialchars($activeTab['label']) ?></span> category.
          </p>
          <a href="products.php" class="inline-block px-7 py-3 bg-[#173F35] text-[#F6F3EC] font-medium text-xs tracking-wider uppercase rounded-full hover:bg-[#8FAE5D] hover:text-[#173F35] transition-colors shadow-sm">
            View All Products
          </a>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
          <?php foreach ($products as $index => $p): 
            $pId = (int)$p['product_id'];
            $pName = htmlspecialchars($p['name']);
            $pSlug = htmlspecialchars($p['slug'] ?? $pId);
            $pCat = htmlspecialchars($p['category_name'] ?? 'Produce');
            $pGrade = htmlspecialchars($p['export_grade'] ?? '');
            $pVariety = htmlspecialchars($p['variety'] ?? '');
            $pImg = asset_url($p['image_path']);
            $pSeason = htmlspecialchars(get_product_season($p));
            $detailUrl = "productdetail.php?id=" . urlencode($pSlug);
            $pImgLoading = ($index < 4) ? 'eager' : 'lazy';
            $pFetchAttr = ($index < 2) ? ' fetchpriority="high"' : '';
          ?>
            <!-- MasonryGallery Inspired Card Structure -->
            <a href="<?= $detailUrl ?>" class="masonry-card group relative bg-[#0d2a24] rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 cursor-pointer text-left block">
              <div class="relative overflow-hidden bg-[#173F35] aspect-[4/5] w-full">
                <!-- Optimized Image -->
                <img src="<?= htmlspecialchars($pImg) ?>" 
                     alt="<?= $pName ?>" 
                     loading="<?= $pImgLoading ?>" 
                     decoding="async"<?= $pFetchAttr ?>
                     width="400" 
                     height="500"
                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-108" />
                
                <!-- Color Overlay Shift on Hover -->
                <div class="color-overlay absolute inset-0 bg-gradient-to-tr from-[#173F35]/60 to-[#8FAE5D]/40 opacity-0 group-hover:opacity-60 transition-opacity duration-500 pointer-events-none"></div>
                
                <!-- Dark Gradient for Readability -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#0d2a24] via-[#0d2a24]/40 to-transparent"></div>

                <!-- Category Badge Pill -->
                <div class="absolute top-3.5 left-3.5 z-10">
                  <span class="inline-block text-[9px] tracking-[0.18em] uppercase bg-[#173F35]/90 backdrop-blur-md text-[#8FAE5D] px-3 py-1 rounded-full font-semibold shadow-sm border border-[#8FAE5D]/30">
                    <?= $pCat ?>
                  </span>
                </div>

                <?php if (!empty($pGrade) && $pGrade !== 'Standard'): ?>
                <!-- Export Grade Pill -->
                <div class="absolute top-3.5 right-3.5 z-10">
                  <span class="inline-block text-[9px] tracking-[0.15em] uppercase bg-[#8FAE5D] text-[#173F35] px-2.5 py-0.5 rounded-full font-bold shadow-sm">
                    <?= $pGrade ?>
                  </span>
                </div>
                <?php endif; ?>

                <!-- Bottom Content Details -->
                <div class="absolute bottom-0 left-0 right-0 p-5 text-[#F6F3EC] z-10">
                  <?php if (!empty($pVariety) && strtolower($pVariety) !== 'n/a'): ?>
                    <p class="text-[9px] tracking-[0.2em] uppercase text-[#8FAE5D] font-semibold mb-1 truncate">
                      <?= $pVariety ?>
                    </p>
                  <?php endif; ?>
                  <h3 class="font-serif text-lg text-[#F6F3EC] group-hover:text-[#8FAE5D] transition-colors duration-200 leading-snug mb-2 font-medium">
                    <?= $pName ?>
                  </h3>

                  <div class="pt-3 border-t border-[#D8C7A1]/20 flex items-center justify-between text-xs mt-2">
                    <div class="flex items-center gap-1.5 text-[#F6F3EC]/70">
                      <svg class="w-3.5 h-3.5 text-[#8FAE5D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <span class="font-medium text-[11px]"><?= $pSeason ?></span>
                    </div>

                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#D8C7A1] group-hover:text-[#8FAE5D] group-hover:translate-x-1 transition-all">
                      Specs
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                      </svg>
                    </span>
                  </div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination Navigation Bar to split loading across pages -->
    <?php if ($totalPages > 1): ?>
    <div id="pagination-container" class="mt-14 flex items-center justify-center gap-2">
      <?php 
        $catParam = urlencode($activeTab['slug']);
        $prevPage = max(1, $currentPageNum - 1);
        $nextPage = min($totalPages, $currentPageNum + 1);
      ?>
      <!-- Previous Page -->
      <a href="products.php?category=<?= $catParam ?>&page=<?= $prevPage ?>" 
         class="page-nav-btn px-4 py-2 rounded-full text-xs font-semibold tracking-wide transition-all <?= $currentPageNum <= 1 ? 'opacity-40 pointer-events-none bg-white/60 text-[#173F35]/40 border border-[#D8C7A1]/30' : 'bg-white text-[#173F35] border border-[#D8C7A1]/50 hover:bg-[#173F35] hover:text-[#F6F3EC]' ?>">
        &larr; Prev
      </a>

      <!-- Page Numbers -->
      <?php for ($p = 1; $p <= $totalPages; $p++): 
        $isCurrent = ($p === $currentPageNum);
      ?>
        <a href="products.php?category=<?= $catParam ?>&page=<?= $p ?>" 
           class="page-nav-btn w-9 h-9 flex items-center justify-center rounded-full text-xs font-semibold tracking-wide transition-all <?= $isCurrent ? 'bg-[#173F35] text-[#F6F3EC] shadow-sm border border-[#8FAE5D]' : 'bg-white text-[#173F35]/70 border border-[#D8C7A1]/40 hover:bg-[#173F35]/10' ?>">
          <?= $p ?>
        </a>
      <?php endfor; ?>

      <!-- Next Page -->
      <a href="products.php?category=<?= $catParam ?>&page=<?= $nextPage ?>" 
         class="page-nav-btn px-4 py-2 rounded-full text-xs font-semibold tracking-wide transition-all <?= $currentPageNum >= $totalPages ? 'opacity-40 pointer-events-none bg-white/60 text-[#173F35]/40 border border-[#D8C7A1]/30' : 'bg-white text-[#173F35] border border-[#D8C7A1]/50 hover:bg-[#173F35] hover:text-[#F6F3EC]' ?>">
        Next &rarr;
      </a>
    </div>
    <?php endif; ?>
  </div>

  <!-- B2B Custom Export Orders Banner -->
  <div class="bg-[#173F35] py-16 text-center">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
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
</div>

<!-- Load GSAP Library for Animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<!-- Progressive Enhancement Category Filter, Pagination & GSAP Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. GSAP Perspective CardSwap Animation Loop in Hero
  function initHeroCardSwap() {
    if (typeof gsap === 'undefined') return;

    const container = document.getElementById('hero-card-swap-container');
    if (!container) return;

    const cards = Array.from(container.querySelectorAll('.swap-card'));
    if (cards.length < 2) return;

    const total = cards.length;
    let order = Array.from({ length: total }, (_, i) => i);
    let swapTimeline = null;
    let swapTimer = null;
    const delay = 4500;

    function getSwapConfig() {
      const isMobile = window.innerWidth < 640;
      return {
        distX: isMobile ? 22 : 36,
        distY: isMobile ? 22 : 36,
        skew: isMobile ? 3 : 5
      };
    }

    function makeSlot(i, totalCount, distX, distY) {
      return {
        x: i * distX,
        y: -i * distY,
        z: -i * distX * 1.5,
        zIndex: totalCount - i
      };
    }

    function setInitialPositions() {
      const { distX, distY, skew } = getSwapConfig();
      order.forEach((cardIdx, slotIdx) => {
        const el = cards[cardIdx];
        if (!el) return;
        const slot = makeSlot(slotIdx, total, distX, distY);
        gsap.set(el, {
          x: slot.x,
          y: slot.y,
          z: slot.z,
          xPercent: -50,
          yPercent: -50,
          skewY: skew,
          transformOrigin: 'center center',
          zIndex: slot.zIndex,
          force3D: true
        });
      });
    }

    function doSwap() {
      if (order.length < 2) return;
      const { distX, distY } = getSwapConfig();
      const frontIdx = order[0];
      const rest = order.slice(1);
      const elFront = cards[frontIdx];
      if (!elFront) return;

      swapTimeline = gsap.timeline();

      swapTimeline.to(elFront, {
        y: '+=380',
        duration: 1.5,
        ease: 'elastic.out(0.6, 0.9)'
      });

      swapTimeline.addLabel('promote', '-=1.3');

      rest.forEach((idx, i) => {
        const el = cards[idx];
        if (!el) return;
        const slot = makeSlot(i, total, distX, distY);
        swapTimeline.set(el, { zIndex: slot.zIndex }, 'promote');
        swapTimeline.to(el, {
          x: slot.x,
          y: slot.y,
          z: slot.z,
          duration: 1.5,
          ease: 'elastic.out(0.6, 0.9)'
        }, `promote+=${i * 0.12}`);
      });

      const backSlot = makeSlot(total - 1, total, distX, distY);
      swapTimeline.addLabel('return', 'promote+=0.1');
      swapTimeline.call(() => {
        gsap.set(elFront, { zIndex: backSlot.zIndex });
      }, null, 'return');

      swapTimeline.to(elFront, {
        x: backSlot.x,
        y: backSlot.y,
        z: backSlot.z,
        duration: 1.5,
        ease: 'elastic.out(0.6, 0.9)'
      }, 'return');

      swapTimeline.call(() => {
        order = [...rest, frontIdx];
      });
    }

    setInitialPositions();

    function startTimer() {
      stopTimer();
      swapTimer = setInterval(doSwap, delay);
    }

    function stopTimer() {
      if (swapTimer) clearInterval(swapTimer);
    }

    startTimer();

    container.addEventListener('mouseenter', function() {
      if (swapTimeline) swapTimeline.pause();
      stopTimer();
    });

    container.addEventListener('mouseleave', function() {
      if (swapTimeline) swapTimeline.play();
      startTimer();
    });

    const io = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          startTimer();
        } else {
          stopTimer();
        }
      });
    }, { threshold: 0.1 });
    io.observe(container);

    let resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(setInitialPositions, 150);
    });
  }

  initHeroCardSwap();

  // 2. MasonryGallery Blur-to-Focus Stagger Entrance
  function triggerMasonryStagger() {
    const cards = document.querySelectorAll('.masonry-card, .product-card');
    cards.forEach((card, index) => {
      card.style.animationDelay = (index * 70) + 'ms';
      card.classList.add('stagger-entrance');
    });
  }

  triggerMasonryStagger();

  // 3. AJAX Category Filtering & Pagination Link Interceptor
  function bindNavInterceptors() {
    const navLinks = document.querySelectorAll('.catalog-filter-btn, .page-nav-btn');
    const gridContainer = document.getElementById('products-grid-container');
    const countText = document.getElementById('catalog-count-text');
    const paginationContainer = document.getElementById('pagination-container');

    navLinks.forEach(link => {
      link.addEventListener('click', async function(e) {
        if (e.metaKey || e.ctrlKey) return;
        e.preventDefault();

        const href = this.getAttribute('href');
        if (!href) return;

        // If it's a category tab, highlight immediately
        if (this.classList.contains('catalog-filter-btn')) {
          document.querySelectorAll('.catalog-filter-btn').forEach(b => {
            b.classList.remove('bg-[#173F35]', 'text-[#F6F3EC]', 'font-semibold', 'shadow-sm');
            b.classList.add('bg-white/80', 'border', 'border-[#D8C7A1]/40', 'text-[#173F35]/70');
          });
          this.classList.remove('bg-white/80', 'border', 'border-[#D8C7A1]/40', 'text-[#173F35]/70');
          this.classList.add('bg-[#173F35]', 'text-[#F6F3EC]', 'font-semibold', 'shadow-sm');
        }

        window.history.pushState(null, '', href);

        if (gridContainer) {
          gridContainer.style.opacity = '0.3';
          gridContainer.style.transform = 'translateY(12px)';
        }

        try {
          const response = await fetch(href);
          const html = await response.text();
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');

          const newGrid = doc.getElementById('products-grid-container');
          const newCount = doc.getElementById('catalog-count-text');
          const newPag = doc.getElementById('pagination-container');

          setTimeout(() => {
            if (newGrid && gridContainer) {
              gridContainer.innerHTML = newGrid.innerHTML;
            }
            if (newCount && countText) {
              countText.innerHTML = newCount.innerHTML;
            }
            if (paginationContainer) {
              paginationContainer.innerHTML = newPag ? newPag.innerHTML : '';
            }
            if (gridContainer) {
              gridContainer.style.opacity = '1';
              gridContainer.style.transform = 'translateY(0)';
            }
            triggerMasonryStagger();
            bindNavInterceptors();
            
            // Scroll smoothly to top of catalog section
            const filterBar = document.getElementById('category-filter-bar');
            if (filterBar) {
              filterBar.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
          }, 150);
        } catch (err) {
          window.location.href = href;
        }
      });
    });
  }

  bindNavInterceptors();

  window.addEventListener('popstate', function() {
    window.location.reload();
  });
});
</script>

<?php include "includes/footer.php"; ?>
