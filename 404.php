<?php
$pageTitle = "Page Not Found | Green Pyramids";
$pageDesc = "The requested page could not be found.";
if (!headers_sent()) {
    header("HTTP/1.0 404 Not Found");
}
include 'includes/header.php';
?>
<div class="bg-[#F6F3EC] min-h-screen pt-32 pb-16 px-6 flex items-center justify-center text-center">
    <div>
        <h1 class="font-serif text-6xl text-[#173F35] mb-4">404</h1>
        <h2 class="text-2xl text-[#173F35] mb-6">Page Not Found</h2>
        <p class="text-[#173F35]/70 mb-8 max-w-md mx-auto">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="/" class="inline-block px-8 py-3 bg-[#173F35] text-[#F6F3EC] rounded-full hover:bg-[#8FAE5D] transition-colors">Return Home</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
