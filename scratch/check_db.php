<?php require "includes/db.php"; $r = $conn->query("SELECT heading FROM page_sections WHERE page=\"production\" AND section=\"hero\""); echo json_encode($r->fetch_assoc()); ?>
