<?php
require '../includes/db.php';

$sql = "CREATE TABLE IF NOT EXISTS contact_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    primary_email VARCHAR(255),
    sales_email VARCHAR(255),
    general_phone VARCHAR(255),
    whatsapp_number VARCHAR(255),
    physical_address TEXT,
    google_maps_embed TEXT,
    facebook_url VARCHAR(255),
    instagram_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table contact_settings created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
