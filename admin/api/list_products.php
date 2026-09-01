<?php
require __DIR__ . "/../includes/session.php";
require __DIR__ . '/../../includes/db.php';
require __DIR__ . "/../includes/products_helpers.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["admin_id"])) {
    echo json_encode(["success" => false, "error" => "Not authenticated."]);
    exit();
}

$selCat = trim($_GET["category"] ?? "all");
$search = trim($_GET["search"] ?? "");
$vis = trim($_GET["filter"] ?? "all");
$catId = ($selCat !== "all" && ctype_digit($selCat)) ? (int) $selCat : 0;

$products = pm_fetchProducts($conn, $catId, $search, $vis);
echo json_encode([
    "success" => true,
    "rowsHtml" => pm_renderRows($products),
]);
