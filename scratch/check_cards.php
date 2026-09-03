<?php require "includes/db.php"; $r = $conn->query("SELECT * FROM product_hero_cards"); while($row = $r->fetch_assoc()) { echo json_encode($row) . "\n"; } ?>
