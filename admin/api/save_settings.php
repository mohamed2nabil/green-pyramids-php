<?php
require "../includes/session.php";
require '../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// âœ… Ø§Ø³ØªÙ„Ø§Ù… Ø§Ù„Ø¨ÙŠØ§Ù†Ø§Øª Ù…Ù† FormData
$values = [
    'primary_email'     => $_POST['primary_email'] ?? '',
    'sales_email'       => $_POST['sales_email'] ?? '',
    'general_phone'     => $_POST['general_phone'] ?? '',
    'whatsapp_number'   => $_POST['whatsapp_number'] ?? '',
    'physical_address'  => $_POST['physical_address'] ?? '',
    'google_maps_embed' => $_POST['google_maps_embed'] ?? '',
    'facebook_url'      => $_POST['facebook_url'] ?? '',
    'instagram_url'     => $_POST['instagram_url'] ?? '',
    'linkedin_url'      => $_POST['linkedin_url'] ?? ''
];

// ØªÙ†Ø¸ÙŠÙ Ø§Ù„Ø¨ÙŠØ§Ù†Ø§Øª
foreach ($values as $k => $v) {
    $values[$k] = trim($v);
}

// check if exists
$res = $conn->query("SELECT id FROM contact_settings LIMIT 1");

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $id  = (int)$row['id'];

    $stmt = $conn->prepare("
        UPDATE contact_settings SET
            primary_email = ?,
            sales_email = ?,
            general_phone = ?,
            whatsapp_number = ?,
            physical_address = ?,
            google_maps_embed = ?,
            facebook_url = ?,
            instagram_url = ?,
            linkedin_url = ?,
            updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssssssssi",
        $values['primary_email'],
        $values['sales_email'],
        $values['general_phone'],
        $values['whatsapp_number'],
        $values['physical_address'],
        $values['google_maps_embed'],
        $values['facebook_url'],
        $values['instagram_url'],
        $values['linkedin_url'],
        $id
    );

} else {

    $stmt = $conn->prepare("
        INSERT INTO contact_settings (
            primary_email, sales_email, general_phone, whatsapp_number,
            physical_address, google_maps_embed,
            facebook_url, instagram_url, linkedin_url, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param(
        "sssssssss",
        $values['primary_email'],
        $values['sales_email'],
        $values['general_phone'],
        $values['whatsapp_number'],
        $values['physical_address'],
        $values['google_maps_embed'],
        $values['facebook_url'],
        $values['instagram_url'],
        $values['linkedin_url']
    );
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
