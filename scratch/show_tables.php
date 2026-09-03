<?php require "includes/db.php"; $r = $conn->query("SHOW TABLES"); while($row=$r->fetch_row()) echo $row[0].PHP_EOL; ?>
