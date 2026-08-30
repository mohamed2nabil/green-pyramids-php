<?php
require "includes/db.php";

$imageExts = ["jpg","jpeg","png","gif","webp","svg"];
function getFiles($dir) {
    global $imageExts;
    $result = [];
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, [".", ".."])) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result = array_merge($result, getFiles($dir . DIRECTORY_SEPARATOR . $value));
            } else {
                $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                if (in_array($ext, $imageExts)) {
                    $result[] = $dir . DIRECTORY_SEPARATOR . $value;
                }
            }
        }
    }
    return $result;
}
$allImagesRaw = getFiles(".");
$imageFiles = [];
foreach ($allImagesRaw as $path) {
    // remove leading .\ or ./
    $cleanPath = ltrim(str_replace("\\", "/", $path), "./");
    $imageFiles[] = $cleanPath;
}

$tablesToCheck = [
    "admins" => "avatar_url",
    "categories" => "image_path",
    "hero_slides" => "image_path",
    "page_sections" => "image_path",
    "products" => "image_path",
    "site_settings" => "hero_image"
];

$totalPaths = 0;
$correctPaths = 0;
$brokenPaths = 0;
$matchedPaths = 0;
$unresolvedPaths = 0;

$report = "";
$mappingTable = "";

foreach ($tablesToCheck as $tableName => $imgCol) {
    $res = $conn->query("SHOW COLUMNS FROM `$tableName`");
    if (!$res) continue;
    $idCol = "";
    while ($row = $res->fetch_assoc()) {
        if (empty($idCol)) $idCol = $row["Field"];
        if ($row["Key"] == "PRI") {
            $idCol = $row["Field"];
            break;
        }
    }

    $res = $conn->query("SELECT `$idCol`, `$imgCol` FROM `$tableName`");
    if (!$res) continue;
    
    while ($row = $res->fetch_assoc()) {
        $dbPath = $row[$imgCol];
        if (empty($dbPath)) continue;
        
        $totalPaths++;
        $fileExists = false;
        $actualPath = "";
        $suggestedPath = "";
        $status = "";
        
        $dbPathFixed = str_replace("\\", "/", ltrim($dbPath, "/"));
        if (in_array($dbPathFixed, $imageFiles)) {
            $fileExists = true;
            $actualPath = $dbPathFixed;
            $status = "CORRECT - NO CHANGE NEEDED";
            $correctPaths++;
        } else {
            $brokenPaths++;
            $base = basename($dbPathFixed);
            $matches = [];
            foreach ($imageFiles as $img) {
                if (basename($img) === $base) {
                    $matches[] = $img;
                }
            }
            if (count($matches) === 1) {
                $actualPath = $matches[0];
                $suggestedPath = $actualPath;
                $status = "MATCHED";
                $matchedPaths++;
                $mappingTable .= "TABLE: $tableName | ROW ID: {$row[$idCol]} | OLD: $dbPath | NEW: $suggestedPath\n";
            } elseif (count($matches) > 1) {
                $bestMatch = "";
                foreach ($matches as $m) {
                    if (strpos($m, "assets/images/$tableName") !== false) {
                        $bestMatch = $m;
                        break;
                    }
                }
                if ($bestMatch) {
                    $actualPath = $bestMatch;
                    $suggestedPath = $actualPath;
                    $status = "MATCHED (From Multiple)";
                    $matchedPaths++;
                    $mappingTable .= "TABLE: $tableName | ROW ID: {$row[$idCol]} | OLD: $dbPath | NEW: $suggestedPath\n";
                } else {
                    $actualPath = "MULTIPLE FOUND: " . implode(", ", $matches);
                    $status = "UNRESOLVED - DO NOT GUESS";
                    $unresolvedPaths++;
                }
            } else {
                $status = "UNRESOLVED - DO NOT GUESS";
                $unresolvedPaths++;
            }
        }
        
        $report .= "TABLE: $tableName\n";
        $report .= "ROW ID: {$row[$idCol]}\n";
        $report .= "CURRENT DATABASE PATH: $dbPath\n";
        $report .= "FILE EXISTS: " . ($fileExists ? "YES" : "NO") . "\n";
        if ($actualPath) $report .= "ACTUAL FILE PATH IF FOUND: $actualPath\n";
        if ($suggestedPath && $status !== "CORRECT - NO CHANGE NEEDED") $report .= "SUGGESTED NEW DATABASE PATH: $suggestedPath\n";
        $report .= "STATUS: $status\n\n";
    }
}

file_put_contents("audit_report.txt", $report . "=== METRICS ===\nA) Total database image paths checked: $totalPaths\nB) Correct paths: $correctPaths\nC) Broken paths: $brokenPaths\nD) Successfully matched old-to-new paths: $matchedPaths\nE) Unresolved paths: $unresolvedPaths\n\n=== MAPPING TABLE ===\n$mappingTable");
echo "DONE";
?>
