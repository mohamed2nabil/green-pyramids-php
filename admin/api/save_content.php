<?php
/**
 * Content Editor API Endpoint
 * Handles CRUD operations for hero slides and page sections
 * 
 * Actions:
 * - update_slide: Update slide heading/subtext
 * - upload_slide_image: Upload image for a slide
 * - delete_slide: Delete a slide
 * - update_section: Update section heading/subtext
 * - upload_section_image: Upload image for a section
 */

try {
    // Session check
    session_start();
    if (!isset($_SESSION["admin_id"])) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => 'Unauthorized access. Please log in.'
        ]);
        exit;
    }

    // Database connection
    require_once dirname(__FILE__) . "/db_connection.php";
    
    // Validate DB connection before any operation
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => 'Database connection failed'
        ]);
        exit();
    }

    header('Content-Type: application/json; charset=utf-8');

    // Get and validate action
    $action = trim($_POST['action'] ?? '');
    if (empty($action)) {
        throw new Exception('Action parameter is required');
    }

    // ===========================
    // UPDATE SLIDE TEXT
    // ===========================
    if ($action === 'update_slide') {
        $slide_id = intval($_POST['slide_id'] ?? 0);
        $heading = trim($_POST['heading'] ?? '');
        $subtext = trim($_POST['subtext'] ?? '');

        if ($slide_id <= 0) {
            throw new Exception('Invalid slide ID');
        }

        // Prepare and execute statement
        $stmt = $conn->prepare("UPDATE hero_slides SET heading = ?, subtext = ?, updated_at = NOW() WHERE slide_id = ?");
        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        $stmt->bind_param("ssi", $heading, $subtext, $slide_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update slide: ' . $stmt->error);
        }

        $stmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'Slide updated successfully'
        ]);
        exit;
    }

    // ===========================
    // UPLOAD SLIDE IMAGE
    // ===========================
    if ($action === 'upload_slide_image') {
        $slide_id = intval($_POST['slide_id'] ?? 0);
        if ($slide_id <= 0) {
            throw new Exception('Invalid slide ID');
        }

        // Validate file existence
        if (!isset($_FILES['image']) || !is_array($_FILES['image'])) {
            throw new Exception('No image provided');
        }

        // Validate upload error
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errorMap = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary directory',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            ];
            throw new Exception($errorMap[$_FILES['image']['error']] ?? 'Unknown upload error');
        }

        // Create upload directory if needed
        $uploadDir = dirname(__FILE__) . '/../../assets/images/pages/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Could not create upload directory');
            }
        }

        // Validate file type and extension
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($ext, $allowed)) {
            throw new Exception('Invalid image format. Allowed: JPG, PNG, GIF, WebP');
        }

        // Validate actual file type (MIME check)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mime, $allowedMimes)) {
            throw new Exception('Invalid image MIME type: ' . $mime);
        }

        // Get current image path to delete old file
        $stmt = $conn->prepare("SELECT image_path FROM hero_slides WHERE slide_id = ?");
        $stmt->bind_param("i", $slide_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentImage = $result->fetch_assoc();
        $stmt->close();

        // Delete old image file if exists
        if ($currentImage && !empty($currentImage['image_path'])) {
            $oldFilePath = dirname(__FILE__) . '/../' . $currentImage['image_path'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        // Generate unique filename with timestamp and random component
        $filename = 'slide_' . $slide_id . '_' . time() . '_' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to save uploaded file to server');
        }

        // Save path to database (relative path)
        $dbPath = '../assets/images/pages/' . $filename;
        $stmt = $conn->prepare("UPDATE hero_slides SET image_path = ?, updated_at = NOW() WHERE slide_id = ?");
        if (!$stmt) {
            unlink($uploadPath);
            error_log("Prepare failed: " . $conn->error);
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        $stmt->bind_param("si", $dbPath, $slide_id);
        if (!$stmt->execute()) {
            unlink($uploadPath);
            error_log("Execute failed: " . $stmt->error);
            throw new Exception('Failed to update database: ' . $stmt->error);
        }

        error_log("Database updated successfully: " . $dbPath);
        $stmt->close();

        echo json_encode([
            'success' => true,
            'image_path' => $dbPath,
            'timestamp' => time(),
            'message' => 'Image uploaded successfully'
        ]);
        exit;
    }

    // ===========================
    // TOGGLE SLIDE VISIBILITY
    // ===========================
    if ($action === 'toggle_slide_visibility') {
        $slide_id = intval($_POST['slide_id'] ?? 0);
        $is_visible = intval($_POST['is_visible'] ?? 1);

        if ($slide_id <= 0) {
            throw new Exception('Invalid slide ID');
        }

        $stmt = $conn->prepare("UPDATE hero_slides SET is_visible = ?, is_active = ?, updated_at = NOW() WHERE slide_id = ?");
        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        $stmt->bind_param("iii", $is_visible, $is_visible, $slide_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to toggle visibility: ' . $stmt->error);
        }

        $stmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'Visibility updated successfully',
            'is_visible' => $is_visible
        ]);
        exit;
    }

    // ===========================
    // UPDATE SECTION TEXT
    // ===========================
    if ($action === 'update_section') {
        $page = trim($_POST['page'] ?? '');
        $section = trim($_POST['section'] ?? '');
        $heading = trim($_POST['heading'] ?? '');
        $subtext = trim($_POST['subtext'] ?? '');

        if (empty($page) || empty($section)) {
            throw new Exception('Page and section parameters are required');
        }

        // Use INSERT ... ON DUPLICATE KEY UPDATE for upsert
        $stmt = $conn->prepare("
            INSERT INTO page_sections (page, section, heading, subtext, updated_at)
            VALUES (?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE
                heading = VALUES(heading),
                subtext = VALUES(subtext),
                updated_at = NOW()
        ");
        
        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        $stmt->bind_param("ssss", $page, $section, $heading, $subtext);
        if (!$stmt->execute()) {
            throw new Exception('Failed to update section: ' . $stmt->error);
        }

        $stmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'Section updated successfully'
        ]);
        exit;
    }

    // ===========================
    // UPLOAD SECTION IMAGE
    // ===========================
    if ($action === 'upload_section_image') {
        $page = trim($_POST['page'] ?? '');
        $section = trim($_POST['section'] ?? '');

        if (empty($page) || empty($section)) {
            throw new Exception('Page and section parameters are required');
        }

        // Validate file existence
        if (!isset($_FILES['image']) || !is_array($_FILES['image'])) {
            throw new Exception('No image provided');
        }

        // Validate upload error
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errorMap = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary directory',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            ];
            throw new Exception($errorMap[$_FILES['image']['error']] ?? 'Unknown upload error');
        }

        // Create upload directory if needed
        $uploadDir = dirname(__FILE__) . '/../../assets/images/pages/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Could not create upload directory');
            }
        }

        // Validate file type and extension
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($ext, $allowed)) {
            throw new Exception('Invalid image format. Allowed: JPG, PNG, GIF, WebP');
        }

        // Validate actual file type (MIME check)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mime, $allowedMimes)) {
            throw new Exception('Invalid image MIME type: ' . $mime);
        }

        // Get current image path to delete old file
        $stmt = $conn->prepare("SELECT image_path FROM page_sections WHERE page = ? AND section = ?");
        $stmt->bind_param("ss", $page, $section);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentImage = $result->fetch_assoc();
        $stmt->close();

        // Delete old image file if exists
        if ($currentImage && !empty($currentImage['image_path'])) {
            $oldFilePath = dirname(__FILE__) . '/../' . $currentImage['image_path'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        // Generate unique filename with timestamp and random component
        $filename = $page . '_' . $section . '_' . time() . '_' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to save uploaded file to server');
        }

        // Save path to database (relative path)
        $dbPath = '../assets/images/pages/' . $filename;
        $stmt = $conn->prepare("
            INSERT INTO page_sections (page, section, image_path, updated_at)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE
                image_path = VALUES(image_path),
                updated_at = NOW()
        ");
        
        if (!$stmt) {
            unlink($uploadPath);
            throw new Exception('Database prepare error: ' . $conn->error);
        }

        $stmt->bind_param("sss", $page, $section, $dbPath);
        if (!$stmt->execute()) {
            unlink($uploadPath);
            throw new Exception('Failed to update database: ' . $stmt->error);
        }

        $stmt->close();

        echo json_encode([
            'success' => true,
            'image_path' => $dbPath,
            'timestamp' => time(),
            'message' => 'Image uploaded successfully'
        ]);
        exit;
    }

    // Unknown action
    throw new Exception('Unknown action: ' . htmlspecialchars($action));

} catch (Exception $e) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    
    // Log error for debugging
    error_log('[Content Editor Error] ' . date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . ' | User: ' . ($_SESSION['admin_id'] ?? 'unknown'));
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
} finally {
    // Close statement if exists
    if (isset($stmt) && is_object($stmt)) {
        $stmt->close();
    }
    
    // Close database connection
    if (isset($conn) && is_object($conn)) {
        $conn->close();
    }
}
?>

