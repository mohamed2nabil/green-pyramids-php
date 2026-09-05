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
    $name = trim($_POST['name'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $export_grade = trim($_POST['export_grade'] ?? '');
    $hs_code = trim($_POST['hs_code'] ?? '');
    $variety = trim($_POST['variety'] ?? '');
    $sizes = trim($_POST['sizes'] ?? '');
    $packaging_types = trim($_POST['packaging_types'] ?? '');
    $shipping_method = trim($_POST['shipping_method'] ?? '');
    $container_capacity = trim($_POST['container_capacity'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $avail = $_POST['availability'] ?? [];
    $avail_jan = (!empty($avail['jan']) && (string)$avail['jan'] === '1') ? 1 : 0;
    $avail_feb = (!empty($avail['feb']) && (string)$avail['feb'] === '1') ? 1 : 0;
    $avail_mar = (!empty($avail['mar']) && (string)$avail['mar'] === '1') ? 1 : 0;
    $avail_apr = (!empty($avail['apr']) && (string)$avail['apr'] === '1') ? 1 : 0;
    $avail_may = (!empty($avail['may']) && (string)$avail['may'] === '1') ? 1 : 0;
    $avail_jun = (!empty($avail['jun']) && (string)$avail['jun'] === '1') ? 1 : 0;
    $avail_jul = (!empty($avail['jul']) && (string)$avail['jul'] === '1') ? 1 : 0;
    $avail_aug = (!empty($avail['aug']) && (string)$avail['aug'] === '1') ? 1 : 0;
    $avail_sep = (!empty($avail['sep']) && (string)$avail['sep'] === '1') ? 1 : 0;
    $avail_oct = (!empty($avail['oct']) && (string)$avail['oct'] === '1') ? 1 : 0;
    $avail_nov = (!empty($avail['nov']) && (string)$avail['nov'] === '1') ? 1 : 0;
    $avail_dec = (!empty($avail['dec']) && (string)$avail['dec'] === '1') ? 1 : 0;

    if (empty($name) || $category_id <= 0 || empty($export_grade)) {
        throw new Exception('Product name, category, and export grade are required');
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
            if (function_exists('imagecreatetruecolor') && function_exists('getimagesize')) {
                list($width, $height, $type) = getimagesize($target_path);
                if ($width > 800) {
                    $new_w = 800;
                    $new_h = intval($height * (800 / $width));
                    $dst = imagecreatetruecolor($new_w, $new_h);
                    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                    }
                    $src = match($type) {
                        IMAGETYPE_JPEG => function_exists('imagecreatefromjpeg') ? imagecreatefromjpeg($target_path) : null,
                        IMAGETYPE_PNG => function_exists('imagecreatefrompng') ? imagecreatefrompng($target_path) : null,
                        IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($target_path) : null,
                        default => null
                    };
                    if ($src) {
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
                        match($type) {
                            IMAGETYPE_JPEG => imagejpeg($dst, $target_path, 85),
                            IMAGETYPE_PNG => imagepng($dst, $target_path, 8),
                            IMAGETYPE_WEBP => imagewebp($dst, $target_path, 85),
                            default => null
                        };
                        imagedestroy($src);
                        imagedestroy($dst);
                    }
                }
            }
            $image_path = 'assets/images/products/' . $filename;
        } else {
            throw new Exception('Failed to upload image');
        }
    }

    if ($product_id) {
        // Update existing product
        $sql = "UPDATE products SET 
                name = ?, category_id = ?, export_grade = ?, hs_code = ?, variety = ?, sizes = ?,
                packaging_types = ?, shipping_method = ?, container_capacity = ?, description = ?,
                avail_jan = ?, avail_feb = ?, avail_mar = ?, avail_apr = ?, avail_may = ?, avail_jun = ?,
                avail_jul = ?, avail_aug = ?, avail_sep = ?, avail_oct = ?, avail_nov = ?, avail_dec = ?";
        
        $params = [$name, $category_id, $export_grade, $hs_code, $variety, $sizes,
                   $packaging_types, $shipping_method, $container_capacity, $description,
                   $avail_jan, $avail_feb, $avail_mar, $avail_apr, $avail_may, $avail_jun,
                   $avail_jul, $avail_aug, $avail_sep, $avail_oct, $avail_nov, $avail_dec];
        $types = 'sissssssssiiiiiiiiiiii';
        
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
        
        $conn->begin_transaction();
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update product: ' . $stmt->error);
        }
        $conn->commit();
        
        $response['message'] = 'Product updated successfully';
    } else {
        // Insert new product
        $sku = 'SKU-' . strtoupper(substr(md5(uniqid()), 0, 8)); // Generate a random SKU

        $sql = "INSERT INTO products (name, category_id, export_grade, hs_code, variety, sizes, packaging_types, shipping_method, container_capacity, description, image_path, sku,
                avail_jan, avail_feb, avail_mar, avail_apr, avail_may, avail_jun, avail_jul, avail_aug, avail_sep, avail_oct, avail_nov, avail_dec) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sissssssssssiiiiiiiiiiii', 
            $name, $category_id, $export_grade, $hs_code, $variety, $sizes, $packaging_types, $shipping_method, $container_capacity, $description, $image_path, $sku,
            $avail_jan, $avail_feb, $avail_mar, $avail_apr, $avail_may, $avail_jun, $avail_jul, $avail_aug, $avail_sep, $avail_oct, $avail_nov, $avail_dec
        );
        
        $conn->begin_transaction();
        if (!$stmt->execute()) {
            throw new Exception('Failed to add product: ' . $stmt->error);
        }
        $conn->commit();
        
        $response['message'] = 'Product added successfully';
    }

    $response['success'] = true;
    
} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof mysqli) {
        @$conn->rollback();
    }
    error_log("Product save error: " . $e->getMessage());
    $response['error'] = $e->getMessage();
    $response['message'] = $e->getMessage();
}

if (ob_get_length()) {
    @ob_clean();
}
echo json_encode($response);
exit();
?>

