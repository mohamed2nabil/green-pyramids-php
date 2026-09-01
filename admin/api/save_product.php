<?php
error_reporting(0);
header('Content-Type: application/json');
require_once '../../includes/db.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get form data
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $product_name = trim($_POST['product_name'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $unit = trim($_POST['unit'] ?? '');
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);
    $is_visible = isset($_POST['is_visible']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (empty($product_name) || $category_id <= 0) {
        throw new Exception('Product name and category are required');
    }

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../assets/images/products/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            throw new Exception('Invalid image format. Allowed: JPG, PNG, GIF, WebP');
        }

        // Generate unique filename
        $filename = uniqid('product_') . '.' . $file_extension;
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = '../assets/images/products/' . $filename;
        } else {
            throw new Exception('Failed to upload image');
        }
    }

    if ($product_id) {
        // Update existing product
        $sql = "UPDATE products SET 
                product_name = ?, 
                category_id = ?, 
                description = ?, 
                price = ?, 
                unit = ?, 
                stock_quantity = ?, 
                is_visible = ?, 
                is_active = ?";
        
        $params = [$product_name, $category_id, $description, $price, $unit, $stock_quantity, $is_visible, $is_active];
        $types = 'sisdsiii';
        
        if (!empty($image_path)) {
            $sql .= ", image_path = ?";
            $params[] = $image_path;
            $types .= 's';
        }
        
        $sql .= " WHERE product_id = ?";
        $params[] = $product_id;
        $types .= 'i';
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update product: ' . $stmt->error);
        }
        
        $response['message'] = 'Product updated successfully';
    } else {
        // Insert new product
        $sql = "INSERT INTO products (product_name, category_id, description, price, unit, stock_quantity, image_path, is_visible, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sisdsisii', 
            $product_name, 
            $category_id, 
            $description, 
            $price, 
            $unit, 
            $stock_quantity, 
            $image_path, 
            $is_visible, 
            $is_active
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to add product: ' . $stmt->error);
        }
        
        $response['message'] = 'Product added successfully';
    }

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>

