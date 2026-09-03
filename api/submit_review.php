<?php
session_start();
require_once '../includes/db.php';

// ponytail: basic validation and immediate redirect. No complex error rendering, just try/catch to 'index.php'.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = trim($_POST['client_name'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);
    $review = trim($_POST['review'] ?? '');

    if (!empty($client_name) && !empty($review) && $conn) {
        try {
            $stmt = $conn->prepare("INSERT INTO testimonials (client_name, company, country, rating, review, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            if ($stmt) {
                $stmt->bind_param("sssis", $client_name, $company, $country, $rating, $review);
                $stmt->execute();
            }
        } catch (Exception $e) {
            // Silently fail to not break UI if DB is down, just redirect back
        }
    }
}

header("Location: ../index.php?review=success");
exit;
