<?php require "includes/db.php"; $conn->query("ALTER TABLE products ADD COLUMN is_featured TINYINT(1) DEFAULT 0"); echo "Done"; ?>
