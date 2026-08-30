<?php
require "includes/db.php";
$tables = $conn->query("SHOW TABLES");
$dbTables = [];
while($row = $tables->fetch_array()) {
    $tableName = $row[0];
    $cols = $conn->query("SHOW COLUMNS FROM `$tableName`");
    $columns = [];
    while($col = $cols->fetch_assoc()) {
        if (strpos($col["Field"], "image") !== false || strpos($col["Field"], "photo") !== false || strpos($col["Field"], "avatar") !== false || strpos($col["Field"], "icon") !== false) {
            $columns[] = $col["Field"];
        }
    }
    if (!empty($columns)) {
        $dbTables[$tableName] = $columns;
    }
}
echo json_encode($dbTables, JSON_PRETTY_PRINT);
?>
