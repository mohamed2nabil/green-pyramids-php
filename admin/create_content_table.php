<?php
require '../includes/db.php';

$sql = "CREATE TABLE IF NOT EXISTS content_pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(50) NOT NULL,
    section VARCHAR(100) NOT NULL,
    title VARCHAR(255),
    subtitle TEXT,
    description TEXT,
    image_path VARCHAR(255),
    stats_years VARCHAR(50),
    stats_tons VARCHAR(50),
    stats_ports VARCHAR(50),
    stats_trust VARCHAR(50),
    mission TEXT,
    vision TEXT,
    legacy TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_page_section (page, section)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table content_pages created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
