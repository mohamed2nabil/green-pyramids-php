<?php
$topic = isset($_GET['topic']) ? strtolower(trim($_GET['topic'])) : '';
if (empty($topic)) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

$topicName = ucwords(str_replace('-', ' ', $topic));

$pageTitle = "{$topicName} | B2B Agricultural Export Insights | Green Pyramids";
$pageDesc = "Read our comprehensive guide on {$topicName}. Green Pyramids shares expert insights on Egyptian agricultural exports, quality standards, and seasonal availability.";
$pageCanonical = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $_SERVER['REQUEST_URI'];

include 'includes/header.php';
?>
<div class="bg-[#F6F3EC] min-h-screen pt-28 pb-16 px-6 lg:px-10">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex items-center gap-2 text-sm text-[#173F35]/50">
            <a href="/" class="hover:text-[#173F35] transition-colors">Home</a>
            <span>/</span>
            <span class="text-[#173F35]">Insights</span>
        </div>
        
        <h1 class="font-serif text-4xl lg:text-5xl text-[#173F35] mb-6"><?php echo htmlspecialchars($topicName); ?></h1>
        
        <div class="prose prose-lg prose-[#173F35] max-w-none">
            <p class="text-[#173F35]/70 leading-relaxed mb-6">
                Understanding the intricacies of <strong><?php echo htmlspecialchars($topicName); ?></strong> is crucial for successful international agricultural trade. At Green Pyramids, we ensure our partners are well-informed about the best practices and standards in the Egyptian export market.
            </p>
            <h2 class="font-serif text-2xl text-[#173F35] mt-8 mb-4">Key Considerations for Importers</h2>
            <p class="text-[#173F35]/70 leading-relaxed mb-6">
                Whether you are looking at seasonal availability, cold chain requirements, or specific packaging standards, our commitment is to provide transparent and actionable information to streamline your import process from Egypt.
            </p>
            
            <div class="bg-white p-6 rounded-xl border border-[#D8C7A1]/40 mt-8">
                <h3 class="font-serif text-xl text-[#173F35] mb-2">Need detailed specifications?</h3>
                <p class="text-[#173F35]/70 text-sm mb-4">Our quality assurance team is available to discuss your specific market requirements.</p>
                <a href="/contact.php" class="text-[#8FAE5D] font-semibold hover:underline">Contact our experts &rarr;</a>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
