<?php
require "includes/session.php";
if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}
require '../includes/db.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_review'])) {
        $client_name = trim($_POST['client_name'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $review = trim($_POST['review'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        $status = trim($_POST['status'] ?? 'pending');
        
        if ($client_name !== '' && $review !== '') {
            $stmt = $conn->prepare("INSERT INTO testimonials (client_name, company, country, review, rating, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssis", $client_name, $company, $country, $review, $rating, $status);
            $stmt->execute();
            header("Location: reviews.php");
            exit();
        } else {
            $msg = "Name and Review are required.";
        }
    } elseif (isset($_POST['update_review'])) {
        $review_id = (int)$_POST['id'];
        $client_name = trim($_POST['client_name'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $review = trim($_POST['review'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        $status = trim($_POST['status'] ?? 'pending');
        
        if ($client_name !== '' && $review !== '') {
            $stmt = $conn->prepare("UPDATE testimonials SET client_name=?, company=?, country=?, review=?, rating=?, status=? WHERE id=?");
            $stmt->bind_param("ssssisi", $client_name, $company, $country, $review, $rating, $status, $review_id);
            $stmt->execute();
            header("Location: reviews.php");
            exit();
        } else {
            $msg = "Name and Review are required.";
        }
    } elseif (isset($_POST['delete_review'])) {
        $review_id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM testimonials WHERE id=?");
        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        header("Location: reviews.php");
        exit();
    } elseif (isset($_POST['toggle_status'])) {
        $review_id = (int)$_POST['id'];
        $new_status = $_POST['status'] === 'approved' ? 'hidden' : 'approved'; // toggle between approved and hidden
        $stmt = $conn->prepare("UPDATE testimonials SET status=? WHERE id=?");
        $stmt->bind_param("si", $new_status, $review_id);
        $stmt->execute();
        header("Location: reviews.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews Management | Green Pyramids Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .admin-main { flex: 1; padding: 2rem; margin-left: 260px; max-width: calc(100vw - 260px); overflow-x: hidden; }
        @media (max-width: 1024px) { .admin-main { margin-left: 0; max-width: 100vw; padding: 1rem; margin-top:60px; } }
        .table-wrapper { width: 100%; overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .admin-table { width: 100%; min-width: 800px; border-collapse: collapse; }
        .admin-table th, .admin-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eaeaea; }
        .admin-table th { background: #f8f9fa; font-weight: 600; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; text-decoration:none; display:inline-block; }
        .btn-primary { background: #173F35; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-warning { background: #ffc107; color: #212529; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-backdrop"></div>
    
    <?php include 'includes/sidebar.php'; ?>

    <main class="admin-main">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
            <h1 style="font-size:24px; color:#173F35;">Testimonials / Reviews</h1>
            <?php if ($action === 'list'): ?>
                <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            <?php else: ?>
                <a href="reviews.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to List</a>
            <?php endif; ?>
        </div>

        <?php if ($msg): ?>
            <div style="padding:1rem; background:#fee2e2; color:#b91c1c; border-radius:4px; margin-bottom:1rem;">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company/Country</th>
                            <th style="width:40%">Review</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");
                        while ($row = $res->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['client_name']) ?></td>
                            <td><?= htmlspecialchars($row['company'] ?? '') ?>, <?= htmlspecialchars($row['country'] ?? '') ?></td>
                            <td style="font-size:0.9rem; color:#555;"><?= htmlspecialchars($row['review']) ?></td>
                            <td><?= $row['rating'] ?>/5</td>
                            <td>
                                <span style="padding:4px 8px; border-radius:4px; font-size:12px; 
                                    <?= $row['status'] === 'approved' ? 'background:#d1fae5;color:#065f46;' : ($row['status'] === 'hidden' ? 'background:#fee2e2;color:#991b1b;' : 'background:#fef3c7;color:#92400e;') ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="status" value="<?= $row['status'] ?>">
                                        <button type="submit" name="toggle_status" class="btn btn-warning" style="padding:4px 8px; font-size:12px;">
                                            <?= $row['status'] === 'approved' ? 'Hide' : 'Approve' ?>
                                        </button>
                                    </form>
                                    <a href="?action=edit&id=<?= $row['id'] ?>" class="btn btn-primary" style="padding:4px 8px; font-size:12px;">Edit</a>
                                    <form method="POST" onsubmit="return confirm('Delete this review?');">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="delete_review" class="btn btn-danger" style="padding:4px 8px; font-size:12px;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($action === 'add' || ($action === 'edit' && $id > 0)): ?>
            <?php
            $editData = ['client_name'=>'', 'company'=>'', 'country'=>'', 'review'=>'', 'rating'=>5, 'status'=>'pending'];
            if ($action === 'edit') {
                $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) $editData = $res->fetch_assoc();
            }
            ?>
            <div style="background:#fff; padding:2rem; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); max-width:800px;">
                <form method="POST">
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Client Name *</label>
                        <input type="text" name="client_name" class="form-control" value="<?= htmlspecialchars($editData['client_name']) ?>" required>
                    </div>
                    
                    <div style="display:flex; gap:1rem;">
                        <div class="form-group" style="flex:1;">
                            <label>Company</label>
                            <input type="text" name="company" class="form-control" value="<?= htmlspecialchars($editData['company'] ?? '') ?>">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Country</label>
                            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($editData['country'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Review *</label>
                        <textarea name="review" class="form-control" rows="5" required><?= htmlspecialchars($editData['review']) ?></textarea>
                    </div>
                    
                    <div style="display:flex; gap:1rem;">
                        <div class="form-group" style="flex:1;">
                            <label>Rating (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" class="form-control" value="<?= $editData['rating'] ?>">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="pending" <?= $editData['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $editData['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="hidden" <?= $editData['status'] === 'hidden' ? 'selected' : '' ?>>Hidden</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" name="<?= $action === 'add' ? 'create_review' : 'update_review' ?>" class="btn btn-primary" style="margin-top:1rem;">
                        <?= $action === 'add' ? 'Save Review' : 'Update Review' ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>
