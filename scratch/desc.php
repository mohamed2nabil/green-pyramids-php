<?php require "includes/db.php"; $r = $conn->query("DESCRIBE products"); while($row = $r->fetch_assoc()) { echo $row["Field"] . "\n"; } ?>
