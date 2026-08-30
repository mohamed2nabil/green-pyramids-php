<?php
require '../includes/db.php';
$stmt = $conn->prepare('SELECT password_hash FROM admins WHERE id = ?');
if (!$stmt) {
    echo "Error with id: " . $conn->error . "\n";
}
$stmt2 = $conn->prepare('SELECT password_hash FROM admins WHERE admin_id = ?');
if (!$stmt2) {
    echo "Error with admin_id: " . $conn->error . "\n";
}
?>
