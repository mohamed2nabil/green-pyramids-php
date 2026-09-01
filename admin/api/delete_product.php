<?php
error_reporting(0);
header('Content-Type: application/json');
require_once '../../includes/db.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = intval($input['product_id'] ?? 0);

    if ($product_id <= 0) {
        throw new Exception('Invalid product ID');
    }

    // First get the image path to delete the file
    $stmt = $conn->prepare("SELECT image_path FROM products WHERE product_id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Delete the product
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param('i', $product_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to delete product: ' . $stmt->error);
        }
        
        // Delete the image file if it exists
        if (!empty($product['image_path']) && file_exists('../' . $product['image_path'])) {
            unlink('../' . $product['image_path']);
        }
        
        $response['success'] = true;
        $response['message'] = 'Product deleted successfully';
    } else {
        throw new Exception('Product not found');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
