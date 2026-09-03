<?php require "includes/db.php"; $r = $conn->query("SELECT * FROM site_settings"); while($row=$r->fetch_assoc()) print_r($row); ?>
