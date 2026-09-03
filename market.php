<?php
$country = isset($_GET['country']) ? strtolower(trim($_GET['country'])) : '';
$product = isset($_GET['product']) ? strtolower(trim($_GET['product'])) : '';

$countryName = ucwords(str_replace('-', ' ', $country));
$productName = $product ? ucwords(str_replace('-', ' ', $product)) : 'Fresh Produce';

if (empty($country)) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

$pageTitle = "Egyptian {$productName} Export to {$countryName} | Green Pyramids";
$pageDesc = "Import premium Egyptian {$productName} to {$countryName}. Green Pyramids is a certified B2B agricultural exporter delivering fresh produce with strict cold chain integrity.";
$pageCanonical = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $_SERVER['REQUEST_URI'];

include 'includes/header.php';
?>
<div class="bg-[#F6F3EC] min-h-screen pt-28 pb-16 px-6 lg:px-10">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-serif text-4xl lg:text-5xl text-[#173F35] mb-6">Egyptian <?php echo htmlspecialchars($productName); ?> Export to <?php echo htmlspecialchars($countryName); ?></h1>
        <p class="text-[#173F35]/70 text-lg mb-8 leading-relaxed">
            Green Pyramids specializes in exporting premium Egyptian <?php echo htmlspecialchars($productName); ?> to <?php echo htmlspecialchars($countryName); ?>. We meet all local market requirements, ensuring MRL compliance, robust cold chain logistics, and perfect quality upon arrival.
        </p>
        
        <h2 class="font-serif text-2xl text-[#173F35] mb-4">Why Source from Egypt for <?php echo htmlspecialchars($countryName); ?>?</h2>
        <ul class="list-disc list-inside text-[#173F35]/70 mb-8 space-y-2">
            <li>Strategic geographic proximity ensuring shorter transit times.</li>
            <li>Year-round availability of diverse seasonal crops.</li>
            <li>Competitive pricing without compromising on international quality standards.</li>
            <li>Full traceability from farm to destination port.</li>
        </ul>

        <div class="bg-[#D8C7A1]/20 p-8 rounded-2xl">
            <h3 class="font-serif text-xl text-[#173F35] mb-3">Ready to import to <?php echo htmlspecialchars($countryName); ?>?</h3>
            <p class="text-[#173F35]/70 mb-6">Contact our export team for a custom B2B quotation tailored to your volume and packaging requirements.</p>
            <a href="/contact.php" class="inline-block px-8 py-3 bg-[#173F35] text-[#F6F3EC] rounded-full hover:bg-[#8FAE5D] transition-colors">Request a Quote</a>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
