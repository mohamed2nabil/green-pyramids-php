<?php
$id = isset($_GET['id']) ? strtolower($_GET['id']) : '';

require_once 'includes/db.php';

$db_product = null;
if ($conn) {
    $query = "SELECT p.*, c.category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.category_id 
              WHERE p.slug = ? AND p.is_visible = 1 LIMIT 1";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $db_result = $stmt->get_result();
        $db_product = $db_result->fetch_assoc();
    }
}


$related_products = [];
if ($conn && $db_product) {
    $rel_query = "SELECT p.*, c.category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.category_id 
                  WHERE p.category_id = ? AND p.product_id != ? AND p.is_visible = 1 
                  LIMIT 3";
    $rel_stmt = $conn->prepare($rel_query);
    if ($rel_stmt) {
        $rel_stmt->bind_param("ii", $db_product['category_id'], $db_product['product_id']);
        $rel_stmt->execute();
        $rel_result = $rel_stmt->get_result();
        while ($row = $rel_result->fetch_assoc()) {
            $related_products[] = $row;
        }
    }
}

if ($db_product) {
    $product = [
        "name" => $db_product['name'],
        "cat" => $db_product['category_name'] ?? 'Fresh Produce',
        "origin" => "Egypt",
        "season" => "Check Availability",
        "packaging" => explode("\n", $db_product['packaging_types']),
        "sizes" => explode(",", $db_product['sizes']),
        "desc" => $db_product['description'],
        "img" => asset_url($db_product['image_path']),
    ];
} else {
    $product = [
        "name" => ucwords(str_replace('-', ' ', $id)),
        "cat" => "Egyptian Produce",
        "origin" => "Egypt",
        "season" => "Seasonal",
        "packaging" => ["4 kg Carton", "5 kg Carton"],
        "sizes" => ["Large", "Medium"],
        "desc" => "Premium quality Egyptian agricultural produce, carefully selected and packed for international export markets.",
        "img" => asset_url('assets/images/default-product.png'),
    ];
}

$pageTitle = htmlspecialchars($product['name']) . " | Fresh " . htmlspecialchars($product['cat']) . " Exporter | Green Pyramids";
$pageDesc = mb_substr(strip_tags($product['desc']), 0, 155) . "...";
$pageCanonical = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/productdetail.php?id=' . urlencode($id);

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http');
$domain = ($_SERVER['HTTP_HOST'] ?? 'localhost');

include 'includes/header.php';
?>
    <link rel="preload" as="image" href="<?= htmlspecialchars($product['img']) ?>" />
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "<?= htmlspecialchars($product['name']) ?>",
      "image": [
        "<?= htmlspecialchars($product['img']) ?>"
      ],
      "description": "<?= htmlspecialchars($product['desc']) ?>",
      "brand": {
        "@type": "Brand",
        "name": "Green Pyramids"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "<?= $protocol ?>://<?= $domain ?>/"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "Products",
        "item": "<?= $protocol ?>://<?= $domain ?>/products.php"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "<?= htmlspecialchars($product['name']) ?>"
      }]
    }
    </script>
    <div class="bg-[#F6F3EC] min-h-screen">
      <div class="bg-[#F6F3EC] pt-28 pb-6 px-6 lg:px-10 border-b border-[#D8C7A1]/40">
        <div class="max-w-7xl mx-auto">
          <nav class="flex items-center gap-2 text-sm text-[#173F35]/50">
            <a href="index.php" class="hover:text-[#173F35] transition-colors">Home</a>
            <span>/</span>
            <a href="products.php" class="hover:text-[#173F35] transition-colors">Products</a>
            <span>/</span>
            <span class="text-[#173F35]"><?php echo htmlspecialchars($product['name']); ?></span>
          </nav>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
          <div class="relative">
            <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-[#D8C7A1]/20">
              <img src="<?php echo htmlspecialchars($product['img']); ?>" 
                   alt="<?php echo htmlspecialchars($product['name']); ?>" 
                   loading="eager" 
                   fetchpriority="high" 
                   decoding="async" 
                   width="600" 
                   height="800" 
                   class="w-full h-full object-cover" />
            </div>
          </div>
          <div class="flex flex-col justify-center">
            <span class="text-xs tracking-[0.2em] uppercase text-[#8FAE5D] mb-4"><?php echo htmlspecialchars($product['cat']); ?></span>
            <h1 class="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.1] mb-6"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-[#173F35]/70 leading-relaxed mb-10"><?php echo htmlspecialchars($product['desc']); ?></p>

            <div class="space-y-4 mb-10">
                <div class="flex items-start gap-4 pb-4 border-b border-[#D8C7A1]/40">
                  <span class="text-xs tracking-[0.15em] uppercase text-[#173F35]/40 w-24 flex-shrink-0 mt-0.5">Origin</span>
                  <span class="text-sm text-[#173F35]"><?php echo htmlspecialchars($product['origin']); ?></span>
                </div>
                <div class="flex items-start gap-4 pb-4 border-b border-[#D8C7A1]/40">
                  <span class="text-xs tracking-[0.15em] uppercase text-[#173F35]/40 w-24 flex-shrink-0 mt-0.5">Season</span>
                  <span class="text-sm text-[#173F35]"><?php echo htmlspecialchars($product['season']); ?></span>
                </div>

              <div class="pb-4 border-b border-[#D8C7A1]/40">
                <span class="text-xs tracking-[0.15em] uppercase text-[#173F35]/40 block mb-3">Packaging</span>
                <div class="flex flex-wrap gap-2">
                  <?php foreach ($product['packaging'] as $p): ?>
                    <span class="px-4 py-2 rounded-full text-sm transition-colors border border-[#D8C7A1] text-[#173F35]/70">
                      <?php echo htmlspecialchars($p); ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="pb-4 border-b border-[#D8C7A1]/40">
                <span class="text-xs tracking-[0.15em] uppercase text-[#173F35]/40 block mb-3">Sizes / Grades</span>
                <div class="flex flex-wrap gap-2">
                  <?php foreach ($product['sizes'] as $s): ?>
                    <span class="px-3 py-1 rounded-full text-xs bg-[#D8C7A1]/30 text-[#173F35]/70"><?php echo htmlspecialchars($s); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <a
              href="contact.php?product=<?php echo urlencode($product['name']); ?>"
              class="inline-block w-full text-center py-4 bg-[#173F35] text-[#F6F3EC] font-medium tracking-wide rounded-full hover:bg-[#8FAE5D] hover:text-[#173F35] transition-colors duration-200"
            >
              Request This Product
            </a>
          </div>
        </div>
      </div>

      <?php if (!empty($related_products)): ?>
      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 border-t border-[#D8C7A1]/40">
        <p class="text-xs tracking-[0.25em] uppercase text-[#8FAE5D] mb-6">Related Products</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <?php foreach ($related_products as $rel_prod): ?>
            <a href="productdetail.php?id=<?= urlencode($rel_prod['slug']) ?>" class="group block">
              <div class="relative aspect-[4/5] rounded-2xl overflow-hidden bg-[#D8C7A1]/20 mb-6">
                <img src="<?= asset_url($rel_prod['image_path']) ?>" 
                     alt="<?= htmlspecialchars($rel_prod['name']) ?>" 
                     loading="lazy" 
                     decoding="async" 
                     width="400" 
                     height="500" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
              </div>
              <h3 class="font-serif text-2xl text-[#173F35] mb-2 group-hover:text-[#8FAE5D] transition-colors"><?= htmlspecialchars($rel_prod['name']) ?></h3>
              <p class="text-sm text-[#173F35]/50 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]"></span>
                <?= htmlspecialchars($rel_prod['category_name']) ?>
              </p>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10">
        <a href="products.php" class="text-sm text-[#173F35]/50 hover:text-[#173F35] transition-colors flex items-center gap-2">
          &larr; Back to Products
        </a>
      </div>
    </div>
  
<?php include 'includes/footer.php'; ?>
