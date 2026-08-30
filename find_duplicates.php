<?php
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
$allImages = getFiles(".");
$hashes = [];
$duplicates = [];
foreach ($allImages as $img) {
    $hash = md5_file($img);
    $path = ltrim(str_replace("\\", "/", $img), "./");
    if (!isset($hashes[$hash])) {
        $hashes[$hash] = [];
    }
    $hashes[$hash][] = $path;
}
foreach ($hashes as $hash => $paths) {
    if (count($paths) > 1) {
        $duplicates[] = $paths;
    }
}
echo json_encode($duplicates, JSON_PRETTY_PRINT);
?>
