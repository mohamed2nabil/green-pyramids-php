<?php require "includes/db.php"; $conn->query("UPDATE page_sections SET image_path=\"assets/images/static/hero_background.png\" WHERE page=\"home\" AND section=\"hero\""); echo "Done"; ?>
