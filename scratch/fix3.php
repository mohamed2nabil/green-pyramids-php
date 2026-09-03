<?php
$f = "admin/content_editor.php";
$c = file_get_contents($f);

$s = strpos($c, "<div class=\"section-card\" data-page=\"process\" data-section=\"hero\">");
if ($s !== false) {
    $e = strpos($c, "<div class=\"form-group\">", $s + 50); // This is the start of Hero Title group
    if ($e !== false) {
        $c = substr($c, 0, $s + 66) . "\n                    <h3 class=\"section-title\">Hero Section Text</h3>\n                    " . substr($c, $e);
        file_put_contents($f, $c);
        echo "Fixed Process tab hero image field!";
    } else { echo "End not found"; }
} else { echo "Start not found"; }
?>
