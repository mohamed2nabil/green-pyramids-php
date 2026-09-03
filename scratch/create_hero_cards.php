<?php
require "includes/db.php";
$conn->query("CREATE TABLE IF NOT EXISTS product_hero_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    link_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0
)");
$conn->query("INSERT INTO product_hero_cards (title, category, image_path, link_url, sort_order) VALUES 
    ('Premium Navel Oranges', 'Citrus', 'assets/images/products/product_1.png', 'productdetail.php?id=1', 1),
    ('Fresh Strawberries', 'Fruits', 'assets/images/products/product_2.png', 'productdetail.php?id=2', 2),
    ('Export Quality Garlic', 'Vegetables', 'assets/images/products/product_3.png', 'productdetail.php?id=3', 3),
    ('Medjool Dates', 'Seasonal', 'assets/images/products/product_4.png', 'productdetail.php?id=4', 4)
");
echo "Done";
?>
