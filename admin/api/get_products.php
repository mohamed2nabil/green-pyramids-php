<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

if (!isset($conn) || !$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$query = "SELECT products.*, categories.category_name, categories.image_path as category_image 
          FROM products 
          JOIN categories ON products.category_id = categories.category_id";
$result = $conn->query($query);

$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode(["success" => true, "data" => $products]);
?>
