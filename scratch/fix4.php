<?php
$f = "admin/content_editor.php";
$c = file_get_contents($f);

$s = strpos($c, "<div class=\"section-card\" data-page=\"process\" data-section=\"hero\">");
$e = strpos($c, "<div class=\"form-group\">", $s + 150); // skip the first form-group

if ($s !== false && $e !== false) {
    $c = substr($c, 0, $s + 66) . "\n                    <h3 class=\"section-title\">Hero Section Text</h3>\n                    " . substr($c, $e);
    file_put_contents($f, $c);
    echo "Fixed properly!";
} else { echo "Not found"; }
?>
