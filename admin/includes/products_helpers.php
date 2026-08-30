<?php
// Shared helpers for product management page + API.

function pm_normalizeImagePath(?string $path): string
{
    return asset_url($path);
}

function pm_categoryBadgeClass(string $name): string
{
    if ($name === "Frozen Fruits") return "badge-frozen";
    if ($name === "Grains") return "badge-grains";
    return "badge-veg";
}

function pm_fetchProducts(mysqli $conn, int $catId, string $search, string $vis): array
{
    $sql = "SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.is_active = 1";
    $types = "";
    $params = [];

    if ($catId > 0) { $sql .= " AND p.category_id = ?"; $types .= "i"; $params[] = $catId; }
    if ($search !== "") { $sql .= " AND p.name LIKE ?"; $types .= "s"; $params[] = "%" . $search . "%"; }
    if ($vis === "visible") $sql .= " AND p.is_visible = 1";
    elseif ($vis === "hidden") $sql .= " AND p.is_visible = 0";
    $sql .= " ORDER BY p.product_id DESC";

    $st = $conn->prepare($sql);
    if (!$st) return [];
    if ($params) $st->bind_param($types, ...$params);
    $st->execute();
    $res = $st->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) {
        $r["display_image"] = pm_normalizeImagePath($r["image_path"] ?? "");
        $rows[] = $r;
    }
    $st->close();
    return $rows;
}

function pm_renderRows(array $products): string
{
    ob_start();
    if (empty($products)) {
        echo '<tr><td colspan="6" class="empty-cell">No products found.</td></tr>';
    } else {
        foreach ($products as $p) {
            $cls = pm_categoryBadgeClass((string) ($p["category_name"] ?? ""));
            $visible = !empty($p["is_visible"]);
            $pid = (int) $p["product_id"];
            $name = htmlspecialchars($p["name"] ?? "", ENT_QUOTES);
            $cid = (int) ($p["category_id"] ?? 0);
            $grade = htmlspecialchars($p["export_grade"] ?? "", ENT_QUOTES);
            $desc = htmlspecialchars($p["description"] ?? "", ENT_QUOTES);
            $img = htmlspecialchars($p["display_image"] ?? "../assets/images/default-product.png", ENT_QUOTES);
            $catName = htmlspecialchars($p["category_name"] ?? "Uncategorized");
            $eyeIco = $visible ? "fa-eye" : "fa-eye-slash";
            $visCls = $visible ? "status-visible" : "status-hidden";
            $sku = htmlspecialchars($p["sku"] ?? "");

            echo <<<HTML
<tr>
  <td><img src="$img" onerror="this.src='../assets/images/default-product.png'" class="product-thumb" alt="$name"></td>
  <td><div class="product-meta"><p class="product-name">$name</p><p class="product-sku">SKU: {$sku}</p></div></td>
  <td><span class="category-badge $cls">$catName</span></td>
  <td><span class="grade-text">$grade</span></td>
  <td>
    <button type="button" class="action-btn view toggle-visibility-btn $visCls"
            data-product-id="$pid" title="Toggle visibility">
      <i class="fas $eyeIco"></i>
    </button>
  </td>
  <td>
    <div class="action-row">
      <button type="button" class="action-btn edit edit-product-btn"
              data-product-id="$pid" data-name="$name" data-category-id="$cid"
              data-grade="$grade" data-description="$desc" data-image="$img" 
              data-avail-jan="{$p['avail_jan']}" data-avail-feb="{$p['avail_feb']}" data-avail-mar="{$p['avail_mar']}"
              data-avail-apr="{$p['avail_apr']}" data-avail-may="{$p['avail_may']}" data-avail-jun="{$p['avail_jun']}"
              data-avail-jul="{$p['avail_jul']}" data-avail-aug="{$p['avail_aug']}" data-avail-sep="{$p['avail_sep']}"
              data-avail-oct="{$p['avail_oct']}" data-avail-nov="{$p['avail_nov']}" data-avail-dec="{$p['avail_dec']}"
              title="Edit">
        <i class="fas fa-pen"></i>
      </button>
      <button type="button" class="action-btn delete delete-product-btn"
              data-product-id="$pid" title="Delete">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  </td>
</tr>
HTML;
        }
    }
    return (string) ob_get_clean();
}



