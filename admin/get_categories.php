<?php
error_reporting(0);
header('Content-Type: application/json');
require_once '../includes/db.php';

$query = "SELECT category_id, category_name, image_path FROM categories";
$result = $conn->query($query); // Fixed: This must be $result, not $process

if (!$result) {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
    exit;
}

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
echo json_encode($categories);
?>
