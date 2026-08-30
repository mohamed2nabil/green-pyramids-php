<?php
require_once __DIR__ . '/includes/db.php';

$settings = [];
$products = [];

if (isset($conn) && $conn) {
    $settingsResult = $conn->query("SELECT * FROM site_settings LIMIT 1");
    if ($settingsResult) {
        $settings = $settingsResult->fetch_assoc() ?: [];
    }

    // Category 2 for Fruits
    $prods_query = "
        SELECT p.*, c.category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE (p.category_id = 2 OR c.category_name LIKE '%Fruits%') AND p.is_visible = 1
        ORDER BY p.product_id ASC
    ";
    
    $prods_stmt = $conn->query($prods_query);
    if ($prods_stmt) {
        while ($row = $prods_stmt->fetch_assoc()) {
            $products[] = $row;
        }
    }
}

// Helpers
if (!function_exists('resolveImage')) {
    function resolveImage($path, $default) {
        return asset_url($path, $default);
    }
}

if (!function_exists('isProductInSeason')) {
    function isProductInSeason($product) {
        $currentMonth = strtolower(date('M'));
        $columnName = 'avail_' . $currentMonth;
        return isset($product[$columnName]) && $product[$columnName] == 1;
    }
}

if (!function_exists('getSeasonText')) {
    function getSeasonText($p) {
        $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $available = [];
        foreach ($months as $m) {
            if (isset($p["avail_$m"]) && $p["avail_$m"] == 1) {
                $available[] = ucfirst($m);
            }
        }
        if (count($available) == 12) return 'Year-round';
        if (count($available) == 0) return 'Check Availability';
        if (count($available) == 1) return $available[0];
        return $available[0] . ' - ' . end($available);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruits | <?= htmlspecialchars($settings['site_title'] ?? 'Green Pyramids for Export') ?></title>
    
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg?v=1.1">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=1.1">
    <link rel="stylesheet" href="css/fruits.css?v=1.1">
</head>

<body class="fruits-body">
    
    <div class="fruits-nav-wrapper">
        <?php include 'includes/navbar.php'; ?>
    </div>

    <main class="fruits-main">

        <!-- ══ HERO SECTION ══ -->
        <section class="fruits-hero">
            <div class="fruits-hero-bg"></div>

            <div id="fruits-3d-container">
                <canvas id="fruits-3d-canvas"></canvas>
            </div>

            <div class="fruits-hero-content reveal-up">
                <h1 class="fruits-title">Fruits</h1>
                <p class="fruits-subtitle">Premium seasonal harvests from Egypt's fertile Nile valley</p>
            </div>

            <div class="fruits-wave-divider">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,0 C200,100 400,20 600,80 C800,140 1000,60 1200,100 L1200,0 L0,0 Z" opacity=".2" class="shape-fill"></path>
                    <path d="M0,0 C150,120 350,50 600,100 C850,150 1050,40 1200,110 L1200,0 L0,0 Z" opacity=".4" class="shape-fill"></path>
                    <path d="M0,0 C300,130 900,-30 1200,100 L1200,0 L0,0 Z" class="shape-fill"></path>
                </svg>
            </div>
        </section>

        <!-- ══ PRODUCTS GRID ══ -->
        <section class="fruits-products">
            <div class="fruits-container">
                <div class="fruits-grid">

                    <?php foreach ($products as $i => $product): 
                        $delay = ($i % 3) * 100;
                        $img_url = resolveImage($product['image_path'] ?? '', 'assets/images/fruits/hero.png');
                        $season_text = getSeasonText($product);
                    ?>
                    <div class="fruit-card reveal-up" style="transition-delay: <?= $delay ?>ms;">
                        <div class="fruit-card-img">
                            <?php if (isProductInSeason($product)): ?>
                                <span class="in-season-tag">IN SEASON</span>
                            <?php endif; ?>
                            <img src="<?= htmlspecialchars($img_url) ?>" alt="<?= htmlspecialchars($product['name'] ?? 'Product') ?>">
                        </div>
                        <div class="fruit-card-info">
                            <span class="fruit-season"><?= htmlspecialchars($season_text) ?></span>
                            <h3><?= htmlspecialchars($product['name'] ?? 'Unknown') ?></h3>
                            <p><?= htmlspecialchars($product['description'] ?? 'Premium quality freshly harvested.') ?></p>
                            <a href="product.php?id=<?= $product['product_id'] ?>" class="btn-pill">Explore</a>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="js/fruits.js?v=1.1"></script>
</body>
</html>

