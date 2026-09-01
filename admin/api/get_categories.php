<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

if (!isset($conn) || !$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$query = "SELECT category_id, category_name, image_path FROM categories";
$result = $conn->query($query);

$categories = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

echo json_encode(["success" => true, "data" => $categories]);
?>
