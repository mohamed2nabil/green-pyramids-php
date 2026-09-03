<?php
require 'includes/db.php';
$result = $conn->query('SELECT * FROM products LIMIT 5');
if (!$result) {
    echo $conn->error;
} else {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
