<?php require "includes/db.php"; $r = $conn->query("DESCRIBE stats_strip"); while($row=$r->fetch_assoc()) print_r($row); ?>
