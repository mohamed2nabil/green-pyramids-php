<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: signin.php");
    exit();
}
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'], $_POST['id'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'] === 'approved' ? 'pending' : 'approved';
    $stmt = $conn->prepare("UPDATE testimonials SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    header("Location: reviews.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'], $_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: reviews.php");
    exit();
}

$res = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews Management - Green Pyramids</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body style="display:flex; background:#f4f6f9; font-family:sans-serif; margin:0;">
    <?php include 'includes/sidebar.php'; ?>
    <main style="flex:1; padding:2rem;">
        <h1 style="margin-bottom:1.5rem; font-size:1.8rem; color:#333;">Reviews / Testimonials</h1>
        <table style="width:100%; border-collapse:collapse; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); border-radius:8px; overflow:hidden;">
            <tr style="background:#f8f9fa; text-align:left; border-bottom:2px solid #eaeaea;">
                <th style="padding:1rem;">Name</th>
                <th style="padding:1rem;">Company/Country</th>
                <th style="padding:1rem; width:40%;">Review</th>
                <th style="padding:1rem;">Rating</th>
                <th style="padding:1rem;">Status</th>
                <th style="padding:1rem;">Actions</th>
            </tr>
            <?php while ($row = $res->fetch_assoc()): ?>
            <tr style="border-bottom:1px solid #eaeaea;">
                <td style="padding:1rem;"><?= htmlspecialchars($row['client_name']) ?></td>
                <td style="padding:1rem;"><?= htmlspecialchars($row['company'] ?? '') ?>, <?= htmlspecialchars($row['country'] ?? '') ?></td>
                <td style="padding:1rem; font-size:0.9rem; color:#555;"><?= htmlspecialchars($row['review']) ?></td>
                <td style="padding:1rem;"><?= $row['rating'] ?>/5</td>
                <td style="padding:1rem;">
                    <span style="padding:0.25rem 0.5rem; border-radius:4px; font-size:0.8rem; background:<?= $row['status'] === 'approved' ? '#d4edda; color:#155724' : '#fff3cd; color:#856404' ?>;">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td style="padding:1rem;">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="status" value="<?= $row['status'] ?>">
                        <button type="submit" name="toggle_status" style="padding:0.5rem 1rem; border:none; background:#007bff; color:#fff; border-radius:4px; cursor:pointer; font-size:0.85rem; margin-right:0.5rem;">
                            <?= $row['status'] === 'approved' ? 'Hide' : 'Approve' ?>
                        </button>
                    </form>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this review?');">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete" style="padding:0.5rem 1rem; border:none; background:#dc3545; color:#fff; border-radius:4px; cursor:pointer; font-size:0.85rem;">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
