<?php
$f = "admin/api/save_content.php";
$c = file_get_contents($f);

$append = <<<EOD

    // ===========================
    // UPDATE PHCARD
    // ===========================
    if (\$action === "update_phcard") {
        \$card_id = intval(\$_POST["card_id"] ?? 0);
        \$field = \$_POST["field"] ?? "";
        \$value = \$_POST["value"] ?? "";
        if (!in_array(\$field, ["title", "category", "link_url"])) throw new Exception("Invalid field");
        
        \$stmt = \$conn->prepare("UPDATE product_hero_cards SET \$field = ? WHERE id = ?");
        \$stmt->bind_param("si", \$value, \$card_id);
        \$stmt->execute();
        echo json_encode(["success" => true]);
        exit;
    }

    if (\$action === "upload_phcard_image") {
        \$card_id = intval(\$_POST["card_id"] ?? 0);
        if (!isset(\$_FILES["image"]) || \$_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("Invalid image upload");
        }
        
        \$ext = strtolower(pathinfo(\$_FILES["image"]["name"], PATHINFO_EXTENSION));
        if (!in_array(\$ext, ["jpg", "jpeg", "png", "webp"])) throw new Exception("Invalid format");
        
        \$filename = uniqid("phcard_") . "." . \$ext;
        \$path = "../../assets/images/products/" . \$filename;
        
        if (move_uploaded_file(\$_FILES["image"]["tmp_name"], \$path)) {
            \$db_path = "assets/images/products/" . \$filename;
            \$stmt = \$conn->prepare("UPDATE product_hero_cards SET image_path = ? WHERE id = ?");
            \$stmt->bind_param("si", \$db_path, \$card_id);
            \$stmt->execute();
            echo json_encode(["success" => true, "image_path" => \$db_path]);
        } else {
            throw new Exception("Move failed");
        }
        exit;
    }
EOD;

// Insert before the generic catch block if it exists
if (strpos($c, "} catch (Exception \$e) {") !== false) {
    $c = str_replace("} catch (Exception \$e) {", $append . "\n} catch (Exception \$e) {", $c);
} else {
    $c .= $append;
}

file_put_contents($f, $c);
echo "Updated save_content.php";
?>
