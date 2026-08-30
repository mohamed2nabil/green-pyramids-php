<?php
require "includes/session.php";

// Detect AJAX as early as possible (before redirects)
$__isAjaxEarly = (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower((string) $_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest")
    || (isset($_REQUEST["ajax"]) && $_REQUEST["ajax"] === "1");

if (!isset($_SESSION["admin_id"])) {
    if ($__isAjaxEarly) {
        if (ob_get_length()) {
            @ob_clean();
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            "error" => "Not authenticated. Please sign in again.",
            "success" => false,
            "rowsHtml" => "",
        ]);
        exit();
    }
    header("Location: signin.php");
    exit();
}

require '../includes/db.php';
require "includes/products_helpers.php";

// ===== Page Sections (About + Process + Product) =====
$sections = [];
$r = $conn->query("SELECT * FROM page_sections");
if ($r) while ($row = $r->fetch_assoc()) $sections[$row['page']][$row['section']] = $row;

// ===== Hero Slides (Home Page) =====
$slides = [];
$r = $conn->query("SELECT * FROM hero_slides ORDER BY sort_order");
if ($r) while ($row = $r->fetch_assoc()) $slides[] = $row;

/* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
   REQUEST PARSING
â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
$selCat = trim($_REQUEST["category"] ?? "all");
$search = trim($_REQUEST["search"]   ?? "");
$vis    = trim($_REQUEST["filter"]   ?? "all");
$catId  = ($selCat !== "all" && ctype_digit($selCat)) ? (int)$selCat : 0;
$isAjax = !empty($_SERVER["HTTP_X_REQUESTED_WITH"])
       || (isset($_REQUEST["ajax"]) && $_REQUEST["ajax"] === "1");

/* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
   LOAD CATEGORIES & MONTHS
â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
$categories = [];
$cst = $conn->prepare("SELECT category_id, category_name FROM categories ORDER BY category_name");
if ($cst) {
    $cst->execute();
    $cr = $cst->get_result();
    while ($r = $cr->fetch_assoc()) $categories[] = $r;
    $cst->close();
}

// Ù…ØµÙÙˆÙØ© Ø§Ù„Ø´Ù‡ÙˆØ± (ØªØ³ØªØ®Ø¯Ù… ÙÙŠ Ø§Ù„Ø¥Ø¶Ø§ÙØ© ÙˆØ§Ù„ØªØ¹Ø¯ÙŠÙ„)
$months = [
    'jan' => 'January', 'feb' => 'February', 'mar' => 'March', 
    'apr' => 'April', 'may' => 'May', 'jun' => 'June', 
    'jul' => 'July', 'aug' => 'August', 'sep' => 'September', 
    'oct' => 'October', 'nov' => 'November', 'dec' => 'December'
];

/* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
   POST HANDLER
â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
$products = pm_fetchProducts($conn, $catId, $search, $vis);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management | Green Pyramids Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css?v=1.1">
    <link rel="stylesheet" href="../assets/css/products.css?v=1.1">
    <link rel="icon" href="../assets/images/favicon.svg">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
    <style>
        /* Responsive table */
        .table-responsive, .product-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .product-table { min-width: 580px; }

        /* Header layout */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            gap: 8px;
            padding: 10px 16px;
        }
        .search-bar-wrap { display: flex; align-items: center; gap: 6px; flex: 1 1 180px; min-width: 0; }
        .search-bar-wrap input { flex: 1; background: none; border: none; outline: none; font-size: 14px; }
        .header-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-left: auto; }

        /* Feedback */
        #ajaxFeedback {
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 14px;
            display: none;
        }
        #ajaxFeedback.ok  { background: #d4edda; color: #155724; display: block; }
        #ajaxFeedback.err { background: #f8d7da; color: #721c24; display: block; }

        /* Mobile */
        @media (max-width: 768px) {
            .products-container { display: flex !important; flex-direction: column !important; }
            .side-panel         { width: 100% !important; max-width: 100% !important; }
            .page-toolbar       { flex-direction: column; align-items: flex-start; gap: 10px; }
            .btn.btn-gold       { width: 100%; text-align: center; }
            .modal-card         { width: 95vw !important; max-width: 95vw !important; }
        }
    </style>
</head>
<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <div class="sidebar-backdrop" aria-hidden="true" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">

        <header class="header">
            <div class="search-bar-wrap">
                <button type="button" id="searchBtn" style="background:none;border:none;color:inherit;cursor:pointer;">
                    <i class="fas fa-search"></i>
                </button>
                <input type="text" id="searchInput" placeholder="Search products..."
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="header-actions">
                <div class="header-icons">
                    <a href="admin_settings.php">
                        <img src="../assets/settings.png" alt="Settings" style="width:20px;height:20px;opacity:0.7;">
                    </a>
                </div>
                <div class="user-thumb">
                    <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="User" width="32">
                </div>
            </div>
        </header>

        <section class="welcome-section page-toolbar">
            <div>
                <h2>Product Management</h2>
                <p>Manage product data, media, technical specifications, and export visibility.</p>
            </div>
            <button type="button" class="btn btn-gold" id="scrollToAddBtn">
                <i class="fas fa-plus"></i> ADD NEW PRODUCT
            </button>
        </section>

        <div class="products-container">

            <div class="registry-card">
                <div class="registry-header">
                    <h3>Active Registry</h3>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="categoryFilter">Filter by Category</label>
                            <select id="categoryFilter">
                                <option value="all">All Categories</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo (int)$c["category_id"]; ?>"
                                        <?php echo (string)$catId === (string)$c["category_id"] ? "selected" : ""; ?>>
                                        <?php echo htmlspecialchars($c["category_name"]); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter">
                                <option value="all"     <?php echo $vis === "all"     ? "selected" : ""; ?>>All</option>
                                <option value="visible" <?php echo $vis === "visible" ? "selected" : ""; ?>>Visible</option>
                                <option value="hidden"  <?php echo $vis === "hidden"  ? "selected" : ""; ?>>Hidden</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="ajaxFeedback"></div>

                <div class="table-responsive product-table-wrap">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Image</th><th>Product</th><th>Category</th>
                                <th>Export Grade</th><th>Status</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody"><?php echo pm_renderRows($products); ?></tbody>
                    </table>
                </div>
            </div>

            <aside class="side-panel">
                <div class="manifest-card">
                    <h3>Quick Add Product</h3>
                    <p class="panel-subtext">Create a new entry with structured product details and image.</p>

                    <form id="quickAddForm" novalidate>
                        <div class="field-group">
                            <h4>Basic Info</h4>
                            <div class="form-group">
                                <label for="quickTitle">Product Name</label>
                                <input id="quickTitle" type="text" name="name" placeholder="Enter product name..." required>
                            </div>
                            <div class="form-group">
                                <label for="quickImage">Product Image</label>
                                <input id="quickImage" type="file" name="image"
                                       accept="image/jpeg,image/png,image/webp" required>
                            </div>
                            <div class="image-preview-block">
                                <span>Image preview</span>
                                <img id="quickImagePreview" src="../assets/images/default-product.png" alt="Preview">
                            </div>
                        </div>

                        <div class="field-group">
                            <h4>Classification</h4>
                            <div class="form-group">
                                <label for="quickCategory">Category</label>
                                <select id="quickCategory" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?php echo (int)$c["category_id"]; ?>">
                                            <?php echo htmlspecialchars($c["category_name"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quickGrade">Export Grade</label>
                                <select id="quickGrade" name="export_grade" required>
                                    <option value="">Select Grade</option>
                                    <option value="A Grade">A Grade</option>
                                    <option value="AA Grade">AA Grade</option>
                                    <option value="Premium">Premium</option>
                                </select>
                            </div>
                        </div>

                        <div class="field-group">
                            <h4>Technical Specifications</h4>
                            <div class="form-group">
                                <label>HS Code</label>
                                <input type="text" name="hs_code" placeholder="e.g. 070310">
                            </div>
                            <div class="form-group">
                                <label>Variety</label>
                                <input type="text" name="variety" placeholder="e.g. Italian Red Onion">
                            </div>
                            <div class="form-group">
                                <label>Sizes</label>
                                <input type="text" name="sizes" placeholder="e.g. (40/60), (50/70)">
                            </div>
                            <div class="form-group">
                                <label>Types of Packaging</label>
                                <textarea name="packaging_types" placeholder="10 Kg - Mesh bag&#10;25 Kg - Mesh bag"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Shipping</label>
                                <input type="text" name="shipping_method" placeholder="e.g. Reefer Container, Sea shipment">
                            </div>
                            <div class="form-group">
                                <label>Container Capacity</label>
                                <input type="text" name="container_capacity" placeholder="e.g. (40 feet): 25-28 Tons">
                            </div>
                        </div>

                        <div class="field-group">
                            <h4>Description & Seasonality</h4>
                            <div class="form-group">
                                <label for="quickDescription">Product Details</label>
                                <textarea id="quickDescription" name="description" placeholder="Regional origin and packaging specifics..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>Seasonality (Availability)</label>
                                <div class="seasonality-wrapper">
                                    <div class="seasonality-display" id="quickSeasonalityDisplay">
                                        <span class="seasonality-placeholder">Select Availability Months...</span>
                                    </div>
                                    <div class="seasonality-dropdown" id="quickSeasonalityDropdown">
                                        <?php foreach($months as $key => $name): ?>
                                            <label class="month-item">
                                                <input type="hidden" name="availability[<?php echo $key; ?>]" value="0">
                                                <input type="checkbox" name="availability[<?php echo $key; ?>]" value="1">
                                                <span><?php echo $name; ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="quickAddBtn" class="btn-finalize">Add Product</button>
                    </form>
                </div>

                <div class="insight-card">
                    <h4>Global Trade Insight</h4>
                    <p>Export demand is rising for premium frozen produce. Keep product media and category tags current to improve procurement decisions.</p>
                </div>
            </aside>
        </div>
    </main>

    <div class="modal-overlay" id="deleteModal" aria-hidden="true">
        <div class="modal-card delete-modal">
            <h3>Delete Product</h3>
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="cancelDelete">Cancel</button>
                <button type="button" class="btn-danger"    id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="editModal" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Edit Product</h3>
                <button type="button" class="modal-close" id="closeEditModal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editProductForm" novalidate>
                <input type="hidden" name="action"     value="update_product">
                <input type="hidden" name="product_id" id="editProductId">

                <div class="field-group">
                    <h4>Basic Info</h4>
                    <div class="form-group">
                        <label for="editTitle">Product Title</label>
                        <input id="editTitle" type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editImage">Replace Image (optional)</label>
                        <input id="editImage" type="file" name="image"
                               accept="image/jpeg,image/png,image/webp">
                    </div>
                    <div class="image-preview-block">
                        <span>Current image</span>
                        <img id="editImagePreview" src="../assets/images/default-product.png" alt="Current image">
                    </div>
                </div>

                <div class="field-group">
                    <h4>Classification</h4>
                    <div class="form-group">
                        <label for="editCategory">Category</label>
                        <select id="editCategory" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo (int)$c["category_id"]; ?>">
                                    <?php echo htmlspecialchars($c["category_name"]); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editGrade">Export Grade</label>
                        <select id="editGrade" name="export_grade" required>
                            <option value="">Select Grade</option>
                            <option value="A Grade">A Grade</option>
                            <option value="AA Grade">AA Grade</option>
                            <option value="Premium">Premium</option>
                        </select>
                    </div>
                </div>

                <div class="field-group">
                    <h4>Technical Specifications</h4>
                    <div class="form-group">
                        <label>HS Code</label>
                        <input type="text" name="hs_code" id="edit_hs_code" placeholder="e.g. 070310">
                    </div>
                    <div class="form-group">
                        <label>Variety</label>
                        <input type="text" name="variety" id="edit_variety" placeholder="e.g. Italian Red Onion">
                    </div>
                    <div class="form-group">
                        <label>Sizes</label>
                        <input type="text" name="sizes" id="edit_sizes" placeholder="e.g. (40/60), (50/70)">
                    </div>
                    <div class="form-group">
                        <label>Types of Packaging</label>
                        <textarea name="packaging_types" id="edit_packaging_types" placeholder="10 Kg - Mesh bag&#10;25 Kg - Mesh bag"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Shipping</label>
                        <input type="text" name="shipping_method" id="edit_shipping_method" placeholder="e.g. Reefer Container, Sea shipment">
                    </div>
                    <div class="form-group">
                        <label>Container Capacity</label>
                        <input type="text" name="container_capacity" id="edit_container_capacity" placeholder="e.g. (40 feet): 25-28 Tons">
                    </div>
                </div>

                <div class="field-group">
                    <h4>Description & Seasonality</h4>
                    <div class="form-group">
                        <label for="editDescription">Product Details</label>
                        <textarea id="editDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Seasonality (Availability)</label>
                        <div class="seasonality-wrapper">
                            <div class="seasonality-display" id="editSeasonalityDisplay">
                                <span class="seasonality-placeholder">Select Availability Months...</span>
                            </div>
                            <div class="seasonality-dropdown" id="editSeasonalityDropdown">
                                <?php foreach($months as $key => $name): ?>
                                    <label class="month-item">
                                        <input type="hidden" name="availability[<?php echo $key; ?>]" value="0">
                                        <input type="checkbox" name="availability[<?php echo $key; ?>]" value="1" id="edit_avail_<?php echo $key; ?>">
                                        <span><?php echo $name; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="cancelEdit">Cancel</button>
                <button type="submit" form="editProductForm" class="btn-finalize" id="saveEditBtn">Save Changes</button>
            </div>
        </div>
    </div>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
    <script src="../assets/js/product-management.js?v=1.1"></script>
</body>
</html>




