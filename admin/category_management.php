<?php
require "includes/session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}

// Ø§Ø³ØªØ®Ø¯Ø§Ù… Ù…Ù„Ù Ø§Ù„Ø§ØªØµØ§Ù„ Ø§Ù„Ù…ÙˆØ­Ø¯ ÙˆØ§Ù„ØµØ­ÙŠØ­ Ø§Ù„Ø®Ø§Øµ Ø¨Ùƒ Ø¨Ø¯Ù„Ø§Ù‹ Ù…Ù† includes/db.php
require '../includes/db.php';

if (!isset($_SESSION["category_flash"])) {
    $_SESSION["category_flash"] = ["type" => "", "message" => ""];
}

$flash = $_SESSION["category_flash"];
$_SESSION["category_flash"] = ["type" => "", "message" => ""];

// Ø¯Ø§Ù„Ø© Ù„Ø±ÙØ¹ Ø§Ù„ØµÙˆØ±
function uploadCategoryImage(array $file): array
{
    if (($file["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ["ok" => true, "path" => null, "error" => ""];
    }

    if (($file["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ["ok" => false, "path" => null, "error" => "Image upload failed."];
    }

    $allowedMime = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp",
    ];
    $tmpPath = (string) ($file["tmp_name"] ?? "");
    $detectedMime = "";

    if (function_exists("finfo_open")) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $detectedMime = (string) finfo_file($finfo, $tmpPath);
            finfo_close($finfo);
        }
    }

    if ($detectedMime === "" || !isset($allowedMime[$detectedMime])) {
        return ["ok" => false, "path" => null, "error" => "Only JPG, PNG, JPEG, and WEBP images are allowed."];
    }

    $extension = $allowedMime[$detectedMime];
    $uploadDir = "../assets/images/categories/";

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
        return ["ok" => false, "path" => null, "error" => "Cannot create upload directory."];
    }

    try {
        $uniqueToken = bin2hex(random_bytes(8));
    } catch (Exception $exception) {
        $uniqueToken = md5(uniqid((string) mt_rand(), true));
    }

    $uniqueFileName = "cat_" . $uniqueToken . "." . $extension;
    $imagePath = $uploadDir . $uniqueFileName;

    if (!move_uploaded_file($tmpPath, $imagePath)) {
        return ["ok" => false, "path" => null, "error" => "Failed to save uploaded image."];
    }

    return ["ok" => true, "path" => $imagePath, "error" => ""];
}

// Ø§Ù„ØªØ£ÙƒØ¯ Ù…Ù† ÙˆØ¬ÙˆØ¯ Ø¹Ù…ÙˆØ¯ image_path Ø¨Ø§Ø³ØªØ®Ø¯Ø§Ù… mysqli
try {
    $check = $conn->query("SHOW COLUMNS FROM categories LIKE 'image_path'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE categories ADD image_path VARCHAR(255) NULL");
    }
} catch (Exception $exception) {
    $flash = ["type" => "error", "message" => "Unable to verify categories schema."];
}

// Ù…Ø¹Ø§Ù„Ø¬Ø© Ø·Ù„Ø¨Ø§Øª Ø§Ù„Ù€ POST (Ø¥Ø¶Ø§ÙØ©ØŒ ØªØ¹Ø¯ÙŠÙ„ØŒ Ø­Ø°Ù)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = trim($_POST["action"] ?? "");
    $name = trim($_POST["category_name"] ?? "");
    $categoryId = (int) ($_POST["category_id"] ?? 0);

    try {
        if ($action === "add_category") {
            if ($name === "") {
                $_SESSION["category_flash"] = ["type" => "error", "message" => "Category name cannot be empty."];
            } else {
                $upload = uploadCategoryImage($_FILES["category_image"] ?? []);
                if (!$upload["ok"]) {
                    $_SESSION["category_flash"] = ["type" => "error", "message" => $upload["error"]];
                } else {
                    // Ø§Ø³ØªØ®Ø¯Ø§Ù… Prepared Statement Ù…Ø¹ mysqli Ù„Ù„Ø¥Ø¶Ø§ÙØ©
                    $stmt = $conn->prepare("INSERT INTO categories (category_name, image_path) VALUES (?, ?)");
                    $path = $upload["path"];
                    $stmt->bind_param("ss", $name, $path);
                    $stmt->execute();
                    $stmt->close();
                    $_SESSION["category_flash"] = ["type" => "success", "message" => "Category added successfully."];
                }
            }
        } elseif ($action === "update_category") {
            if ($categoryId <= 0 || $name === "") {
                $_SESSION["category_flash"] = ["type" => "error", "message" => "Invalid category update request."];
            } else {
                // Ø¬Ù„Ø¨ Ø§Ù„ØµÙˆØ±Ø© Ø§Ù„Ø­Ø§Ù„ÙŠØ©
                $stmt = $conn->prepare("SELECT image_path FROM categories WHERE category_id = ?");
                $stmt->bind_param("i", $categoryId);
                $stmt->execute();
                $result = $stmt->get_result();
                $existingRow = $result->fetch_assoc();
                $stmt->close();

                if (!$existingRow) {
                    $_SESSION["category_flash"] = ["type" => "error", "message" => "Category not found."];
                } else {
                    $currentImagePath = (string) ($existingRow["image_path"] ?? "");
                    $upload = uploadCategoryImage($_FILES["category_image"] ?? []);

                    if (!$upload["ok"]) {
                        $_SESSION["category_flash"] = ["type" => "error", "message" => $upload["error"]];
                    } else {
                        $newImagePath = $upload["path"] ?? $currentImagePath;
                        
                        // ØªØ­Ø¯ÙŠØ« Ø§Ù„Ø¨ÙŠØ§Ù†Ø§Øª
                        $updateStmt = $conn->prepare("UPDATE categories SET category_name = ?, image_path = ? WHERE category_id = ?");
                        $updateStmt->bind_param("ssi", $name, $newImagePath, $categoryId);
                        $updateStmt->execute();
                        $updateStmt->close();

                        // Ø­Ø°Ù Ø§Ù„ØµÙˆØ±Ø© Ø§Ù„Ù‚Ø¯ÙŠÙ…Ø© Ø¥Ø°Ø§ ØªÙ… Ø±ÙØ¹ ØµÙˆØ±Ø© Ø¬Ø¯ÙŠØ¯Ø©
                        if (($upload["path"] ?? null) && $currentImagePath !== "" && $currentImagePath !== $newImagePath && file_exists($currentImagePath)) {
                            unlink($currentImagePath);
                        }

                        $_SESSION["category_flash"] = ["type" => "success", "message" => "Category updated successfully."];
                    }
                }
            }
        } elseif ($action === "delete_category") {
            if ($categoryId <= 0) {
                $_SESSION["category_flash"] = ["type" => "error", "message" => "Invalid category delete request."];
            } else {
                $stmt = $conn->prepare("SELECT image_path FROM categories WHERE category_id = ?");
                $stmt->bind_param("i", $categoryId);
                $stmt->execute();
                $result = $stmt->get_result();
                $existingRow = $result->fetch_assoc();
                $stmt->close();

                $delStmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
                $delStmt->bind_param("i", $categoryId);
                $delStmt->execute();
                $delStmt->close();

                $imagePath = (string) ($existingRow["image_path"] ?? "");
                if ($imagePath !== "" && file_exists($imagePath)) {
                    unlink($imagePath);
                }

                $_SESSION["category_flash"] = ["type" => "success", "message" => "Category deleted successfully."];
            }
        }
    } catch (Exception $exception) {
        $_SESSION["category_flash"] = ["type" => "error", "message" => "Database operation failed: " . $exception->getMessage()];
    }

    header("Location: category_management.php");
    exit();
}

// Ø¬Ù„Ø¨ Ø§Ù„Ø£Ù‚Ø³Ø§Ù… Ù„Ø¹Ø±Ø¶Ù‡Ø§ ÙÙŠ Ø§Ù„Ø¬Ø¯ÙˆÙ„
$categories = [];
try {
    $result = $conn->query("SELECT * FROM categories ORDER BY category_id DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
} catch (Exception $exception) {
    $flash = ["type" => "error", "message" => "Unable to load categories."];
}

// Ø¯Ø§Ù„Ø© Ù„Ø¶Ø¨Ø· Ù…Ø³Ø§Ø± Ø¹Ø±Ø¶ Ø§Ù„ØµÙˆØ±Ø© ÙÙŠ Ø§Ù„Ø£Ø¯Ù…Ù†
function resolveCategoryAdminImage($path) {
    return asset_url($path);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management | Green Pyramids Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/category_management.css">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
</head>
<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <div class="sidebar-backdrop" aria-hidden="true" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">
        <header class="header">
            <div class="header-spacer" aria-hidden="true"></div>
            <div class="header-actions">
                <div class="header-icons">
                    <a href="admin_settings.php"><img src="../assets/settings.png" alt="Settings" style="width: 20px; height: 20px; opacity: 0.7;"></a>
                </div>
                <div class="user-thumb">
                    <img src="<?php echo htmlspecialchars(asset_url($_SESSION["avatar_url"] ?? "", "assets/user.png")); ?>" alt="User" width="32">
                </div>
            </div>
        </header>

        <section class="welcome-section page-toolbar">
            <div>
                <h2>Category Management</h2>
                <p>Organize product groupings such as Frozen Fruits, Grains, and Vegetables.</p>
            </div>
            <button type="button" class="btn btn-add" id="openAddModal">
                <i class="fas fa-plus"></i>  Add Category
            </button>
        </section>

        <section class="category-card">
            <?php if (!empty($flash["message"])): ?>
                <div class="alert <?php echo $flash["type"] === "success" ? "alert-success" : "alert-error"; ?>">
                    <?php echo htmlspecialchars($flash["message"]); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="category-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Category Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td class="empty-state" colspan="4">No categories found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo (int) $category["category_id"]; ?></td>
                                    <td>
                                        <img
                                            src="<?php echo htmlspecialchars(resolveCategoryAdminImage($category["image_path"])); ?>"
                                            alt="<?php echo htmlspecialchars($category["category_name"]); ?>"
                                            class="category-thumb">
                                    </td>
                                    <td><?php echo htmlspecialchars($category["category_name"]); ?></td>
                                    <td>
                                        <div class="action-group">
                                            <button
                                                type="button"
                                                class="btn-action btn-edit editCategoryBtn"
                                                data-id="<?php echo (int) $category["category_id"]; ?>"
                                                data-name="<?php echo htmlspecialchars($category["category_name"], ENT_QUOTES); ?>"
                                                data-image="<?php echo htmlspecialchars(resolveCategoryAdminImage($category["image_path"]), ENT_QUOTES); ?>">
                                                Edit
                                            </button>
                                            <form method="POST" class="deleteForm" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete_category">
                                                <input type="hidden" name="category_id" value="<?php echo (int) $category["category_id"]; ?>">
                                                <button type="submit" class="btn-action btn-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div class="modal" id="categoryModal" aria-hidden="true">
        <div class="modal-content">
            <h3 id="modalTitle">Add Category</h3>
            <p>Fill in category details to update the product catalog.</p>
            <form method="POST" id="categoryForm" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="add_category">
                <input type="hidden" name="category_id" id="categoryIdInput" value="0">
                <div class="form-group">
                    <label for="categoryNameInput">Category Name</label>
                    <input type="text" id="categoryNameInput" name="category_name" maxlength="120" required>
                </div>
                <div class="form-group">
                    <label for="categoryImageInput">Category Image</label>
                    <input type="file" id="categoryImageInput" name="category_image" accept="image/*">
                </div>
                <div class="image-preview-wrap" id="imagePreviewWrap" hidden>
                    <span>Current Image</span>
                    <img id="imagePreview" src="../assets/default.png" alt="Category preview" style="max-height: 100px; display: block; margin-top: 10px;">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-action btn-cancel" id="closeModalBtn">Cancel</button>
                    <button type="submit" class="btn btn-action btn-add" id="saveBtn">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
    <script>
        (function () {
            const modal = document.getElementById("categoryModal");
            const openAddModalBtn = document.getElementById("openAddModal");
            const closeModalBtn = document.getElementById("closeModalBtn");
            const categoryNameInput = document.getElementById("categoryNameInput");
            const categoryIdInput = document.getElementById("categoryIdInput");
            const formAction = document.getElementById("formAction");
            const modalTitle = document.getElementById("modalTitle");
            const saveBtn = document.getElementById("saveBtn");
            const categoryImageInput = document.getElementById("categoryImageInput");
            const imagePreviewWrap = document.getElementById("imagePreviewWrap");
            const imagePreview = document.getElementById("imagePreview");

            function openModal(mode, id, name, imagePath) {
                if (mode === "edit") {
                    modalTitle.textContent = "Edit Category";
                    saveBtn.textContent = "Update Category";
                    formAction.value = "update_category";
                    categoryIdInput.value = String(id || 0);
                    categoryNameInput.value = name || "";
                    categoryImageInput.value = "";
                    if (imagePath && imagePath !== "../assets/default.png") {
                        imagePreview.src = imagePath;
                        imagePreviewWrap.hidden = false;
                    } else {
                        imagePreview.src = "../assets/default.png";
                        imagePreviewWrap.hidden = true;
                    }
                } else {
                    modalTitle.textContent = "Add Category";
                    saveBtn.textContent = "Save Category";
                    formAction.value = "add_category";
                    categoryIdInput.value = "0";
                    categoryNameInput.value = "";
                    categoryImageInput.value = "";
                    imagePreview.src = "../assets/default.png";
                    imagePreviewWrap.hidden = true;
                }

                modal.classList.add("is-open");
                modal.setAttribute("aria-hidden", "false");
                categoryNameInput.focus();
            }

            function closeModal() {
                modal.classList.remove("is-open");
                modal.setAttribute("aria-hidden", "true");
            }

            openAddModalBtn.addEventListener("click", function () {
                openModal("add");
            });

            closeModalBtn.addEventListener("click", closeModal);

            modal.addEventListener("click", function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.querySelectorAll(".editCategoryBtn").forEach(function (button) {
                button.addEventListener("click", function () {
                    openModal("edit", this.dataset.id, this.dataset.name, this.dataset.image || "");
                });
            });

            document.querySelectorAll(".deleteForm").forEach(function (form) {
                form.addEventListener("submit", function (event) {
                    const confirmed = window.confirm("Delete this category permanently?");
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });

            document.getElementById("categoryForm").addEventListener("submit", function (event) {
                const value = categoryNameInput.value.trim();
                if (!value) {
                    event.preventDefault();
                    window.alert("Category name cannot be empty.");
                    categoryNameInput.focus();
                    return;
                }
                categoryNameInput.value = value;
            });

            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape" && modal.classList.contains("is-open")) {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>



