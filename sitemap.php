<?php
header("Content-Type: application/xml; charset=utf-8");
require_once "includes/db.php";

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl = $protocol . '://' . $domain;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$staticPages = [
    '/' => 1.0,
    '/about.php' => 0.8,
    '/products.php' => 0.9,
    '/process.php' => 0.8,
    '/quality.php' => 0.8,
    '/contact.php' => 0.8
];

foreach ($staticPages as $url => $priority) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($baseUrl . $url) . "</loc>\n";
    echo "    <priority>{$priority}</priority>\n";
    echo "  </url>\n";
}

if ($conn) {
    // Categories
    $catRes = $conn->query("SELECT category_name FROM categories");
    if ($catRes) {
        while ($row = $catRes->fetch_assoc()) {
            $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($row['category_name']))), '-');
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . "/category/" . $slug . "/") . "</loc>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }
    }
    
    // Products
    $prodRes = $conn->query("SELECT slug FROM products WHERE is_visible = 1");
    if ($prodRes) {
        while ($row = $prodRes->fetch_assoc()) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . "/products/" . $row['slug'] . "/") . "</loc>\n";
            echo "    <priority>0.7</priority>\n";
            echo "  </url>\n";
        }
    }
}

// Sample Market Pages
$markets = ['saudi-arabia', 'uae', 'uk', 'netherlands', 'germany'];
foreach ($markets as $market) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($baseUrl . "/markets/" . $market . "/") . "</loc>\n";
    echo "    <priority>0.6</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
