<?php
// Unified database connection
require_once __DIR__ . '/path_helpers.php';
$host = "localhost";
$user = "root";
$password = "";
$database = "green_pyramids";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Global functions for resolving media paths if needed
if (!function_exists('resolve_media_path')) {
    function resolve_media_path($db_path) {
        if (empty($db_path)) return "";
        
        // If it starts with http, it's external
        if (strpos($db_path, "http") === 0) return $db_path;
        
        // Return relative to site root by prefixing with /
        // e.g., "assets/images/products/img.jpg" -> "/assets/images/products/img.jpg"
        // Adjust for subfolder (e.g. localhost/Green Pyramids)
        // A safer way is to ensure we just output the path and let the caller prepend the site base if needed, or figure out the base.
        // For now, return what's in the db. We will store it exactly as "assets/images/...".
        return $db_path;
    }
}
?>
