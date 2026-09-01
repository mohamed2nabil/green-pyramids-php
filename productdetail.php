<?php include 'includes/header.php'; ?>
$id<?php\n = isset($_GET['id']) ? strtolower($_GET['id']) : '';

require_once 'includes/db.php';

$query = "SELECT p.*, c.category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          WHERE p.slug = ? AND p.is_visible = 1 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$db_result = $stmt->get_result();
$db_product = $db_result->fetch_assoc();

if ($db_product) {
    $product = [
        "name" => $db_product['name'],
        "cat" => $db_product['category_name'] ?? 'Fresh Produce',
        "origin" => "Egypt",
        "season" => "Check Availability", // Could map avail_jan, avail_feb etc to a string if needed
        "packaging" => explode("\n", $db_product['packaging_types']),
        "sizes" => explode(",", $db_product['sizes']),
        "desc" => $db_product['description'],
        "img" => asset_url($db_product['image_path']),
        "galleryImgs" => [
            "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=500&h=400&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1652211955967-99c892925469?w=500&h=400&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1759272840538-ae4b07214c71?w=500&h=400&fit=crop&auto=format",
        ],
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
        "img" => "https://images.unsplash.com/photo-1605027990121-cbae9e0642df?w=900&h=1100&fit=crop&auto=format",
        "galleryImgs" => [
            "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=500&h=400&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1652211955967-99c892925469?w=500&h=400&fit=crop&auto=format",
            "https://images.unsplash.com/photo-1759272840538-ae4b07214c71?w=500&h=400&fit=crop&auto=format",
        ],
    ];
}
?>

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
              <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover" />
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

      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 border-t border-[#D8C7A1]/40">
        <p class="text-xs tracking-[0.25em] uppercase text-[#8FAE5D] mb-6">Product Highlights</p>
        <div class="grid grid-cols-3 gap-4">
          <?php foreach ($product['galleryImgs'] as $img): ?>
            <div class="aspect-[4/3] rounded-xl overflow-hidden bg-[#D8C7A1]/20">
              <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            </div>
          <?php endforeach; ?>
        </div>
        <div class="flex gap-8 mt-6">
          <?php foreach (["Harvesting", "Quality Sorting", "Export Packing"] as $label): ?>
            <span class="text-xs text-[#173F35]/40 tracking-wide"><?php echo htmlspecialchars($label); ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10">
        <a href="products.php" class="text-sm text-[#173F35]/50 hover:text-[#173F35] transition-colors flex items-center gap-2">
          &larr; Back to Products
        </a>
      </div>
    </div>
  
<?php include 'includes/footer.php'; ?>
