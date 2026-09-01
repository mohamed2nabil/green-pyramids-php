<?php
require '../includes/db.php';

echo "Database check:\n";

$r = $conn->query('SELECT COUNT(*) as count FROM page_sections WHERE page="about"');
if ($r) {
    $row = $r->fetch_assoc();
    echo "About sections: " . $row['count'] . "\n";
} else {
    echo "Error checking about sections: " . $conn->error . "\n";
}

$r = $conn->query('SELECT COUNT(*) as count FROM hero_slides');
if ($r) {
    $row = $r->fetch_assoc();
    echo "Hero slides: " . $row['count'] . "\n";
} else {
    echo "Error checking hero slides: " . $conn->error . "\n";
}

$r = $conn->query('SELECT * FROM page_sections WHERE page="about"');
if ($r) {
    echo "About page data:\n";
    while ($row = $r->fetch_assoc()) {
        echo "Section: {$row['section']}, Heading: {$row['heading']}, Image: {$row['image_path']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

$conn->close();
?>
