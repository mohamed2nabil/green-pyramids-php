<?php
// admin/api/analytics.php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../../includes/services/GoogleAnalyticsService.php';

header("Content-Type: application/json; charset=utf-8");

// 1. Session Auth Check
if (!isset($_SESSION["admin_id"])) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["success" => false, "error" => "Access denied. Please log in first."]);
    exit();
}

// 2. Validate request parameters
$action = $_GET['action'] ?? 'overview';
$range = $_GET['range'] ?? 'last_7_days';

$allowedActions = ['all', 'overview', 'timeline', 'top_pages', 'traffic_sources', 'geography', 'devices'];
$allowedRanges = ['today', 'yesterday', 'last_7_days', 'last_30_days', 'last_90_days', 'this_month', 'previous_month'];

if (!in_array($action, $allowedActions) || !in_array($range, $allowedRanges)) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["success" => false, "error" => "Invalid request action or date range."]);
    exit();
}

// Check Credentials explicitly before instantiating service to give detailed errors
if (!ga4_credentials_available()) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["success" => false, "error" => "credentials_unavailable"]);
    exit();
}

// 3. Query Google Analytics Service
try {
    $service = new GoogleAnalyticsService();
    $data = [];

    switch ($action) {
        case 'all':
            $data = [
                'overview' => $service->getOverview($range),
                'timeline' => $service->getTimeline($range),
                'top_pages' => $service->getTopPages($range),
                'traffic_sources' => $service->getTrafficSources($range),
                'geography' => $service->getCountries($range),
                'devices' => $service->getDevices($range)
            ];
            break;
        case 'overview':
            $data = $service->getOverview($range);
            break;
        case 'timeline':
            $data = $service->getTimeline($range);
            break;
        case 'top_pages':
            $data = $service->getTopPages($range);
            break;
        case 'traffic_sources':
            $data = $service->getTrafficSources($range);
            break;
        case 'geography':
            $data = $service->getCountries($range);
            break;
        case 'devices':
            $data = $service->getDevices($range);
            break;
    }

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);

} catch (Throwable $e) {
    // Log detailed error server-side (using existing admin/error_log file path)
    $logMessage = "[" . date('Y-m-d H:i:s') . "] GA4 Error: " . $e->getMessage() . "\n";
    @file_put_contents(__DIR__ . '/../error_log', $logMessage, FILE_APPEND);

    // Return safe, user-friendly error to browser
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode([
        "success" => false,
        "error" => "An error occurred while fetching analytics. Please try again later."
    ]);
}
