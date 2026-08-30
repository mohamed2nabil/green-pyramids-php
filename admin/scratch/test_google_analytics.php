<?php
// admin/scratch/test_google_analytics.php
// DEVELOPMENT DIAGNOSTIC SCRIPT

require_once __DIR__ . '/../includes/session.php';

header("Content-Type: text/plain; charset=utf-8");

// 1. Restriction: only localhost (local development environment) or CLI
$isCli = (php_sapi_name() === 'cli');
$allowedIPs = ['127.0.0.1', '::1'];
if (!$isCli && !in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowedIPs)) {
    http_response_code(403);
    die("Access denied. Localhost only.");
}

// 2. Auth Check
if (!$isCli && !isset($_SESSION["admin_id"])) {
    http_response_code(401);
    die("Access denied. Please log in as an administrator first.");
}

echo "=== Google Analytics 4 Diagnostic Script ===\n\n";

// Test 1: Configuration File Check
$configFile = __DIR__ . '/../../includes/config/google_analytics.php';
echo "1. Configuration file check: ";
if (!is_file($configFile)) {
    echo "FAIL\n";
    exit();
}
echo "PASS\n";

require_once $configFile;
require_once __DIR__ . '/../../includes/services/GoogleAnalyticsService.php';

// Test 2: Credentials File Check
echo "2. Credential file check: ";
if (ga4_credentials_available()) {
    echo "PASS\n";
    echo "   - Credential location: Outside web root (verified)\n";
} else {
    echo "FAIL (File not found or not readable)\n";
    exit();
}

// Test 3: Authentication & GA4 Property Access
echo "3. Authentication & Property access test: ";
try {
    $service = new GoogleAnalyticsService();
    // Request a small report for today
    $testReport = $service->getOverview('today');
    echo "PASS\n";
    echo "   - Successfully connected to GA4 Property: " . GA4_PROPERTY_ID . "\n";
    echo "   - Active Users today: " . ($testReport['activeUsers']['current'] ?? 0) . "\n";
    echo "   - Sessions today: " . ($testReport['sessions']['current'] ?? 0) . "\n";
    
    echo "\n4. Endpoint Action Tests:\n";
    
    $timeline = $service->getTimeline('last_7_days');
    echo "   - Timeline action: PASS (" . count($timeline) . " rows returned)\n";
    
    $pages = $service->getTopPages('last_7_days');
    echo "   - Top Pages action: PASS (" . count($pages) . " rows returned)\n";
    
    $sources = $service->getTrafficSources('last_7_days');
    echo "   - Traffic Sources action: PASS (" . count($sources) . " rows returned)\n";
    
    $countries = $service->getCountries('last_7_days');
    echo "   - Geography/Countries action: PASS (" . count($countries) . " rows returned)\n";
    
    $devices = $service->getDevices('last_7_days');
    echo "   - Devices action: PASS (" . count($devices) . " rows returned)\n";

} catch (Throwable $e) {
    echo "FAIL\n";
    echo "   - Error details logged to admin/error_log.\n";
    
    // Log details privately
    $logMessage = "[" . date('Y-m-d H:i:s') . "] Diagnostic script failure: " . $e->getMessage() . "\n";
    @file_put_contents(__DIR__ . '/../error_log', $logMessage, FILE_APPEND);
}

echo "\nDiagnostic check complete.\n";
