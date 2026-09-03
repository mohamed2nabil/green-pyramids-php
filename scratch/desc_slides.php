<?php require "includes/db.php"; $r = $conn->query("DESCRIBE hero_slides"); while($row = $r->fetch_assoc()) echo $row["Field"]."\n"; ?>
