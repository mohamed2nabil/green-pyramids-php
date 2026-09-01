<?php
require __DIR__ . "/../includes/session.php";
require __DIR__ . '/../../includes/db.php';
require __DIR__ . "/../includes/products_helpers.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["admin_id"])) {
    echo json_encode(["success" => false, "error" => "Not authenticated."]);
    exit();
}

$pid = (int) ($_POST["product_id"] ?? 0);
if ($pid <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid product ID."]);
    exit();
}

$t = $conn->prepare("UPDATE products SET is_visible = IF(is_visible=1,0,1) WHERE product_id=?");
if (!$t) {
    echo json_encode(["success" => false, "error" => "Prepare failed."]);
    exit();
}
$t->bind_param("i", $pid);
if (!$t->execute()) {
    $t->close();
    echo json_encode(["success" => false, "error" => "Toggle failed."]);
    exit();
}
$t->close();

$selCat = trim($_POST["category"] ?? "all");
$search = trim($_POST["search"] ?? "");
$vis = trim($_POST["filter"] ?? "all");
$catId = ($selCat !== "all" && ctype_digit($selCat)) ? (int) $selCat : 0;
$products = pm_fetchProducts($conn, $catId, $search, $vis);

echo json_encode([
    "success" => true,
    "message" => "Visibility updated.",
    "rowsHtml" => pm_renderRows($products),
]);

