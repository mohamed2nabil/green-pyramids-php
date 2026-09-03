<?php
$f = "contact.php";
$c = file_get_contents($f);

// Replace hardcoded values with variables
$c = str_replace("info@greenpyramids.eg", "<?= htmlspecialchars(\$global_email) ?>", $c);
$c = str_replace("+20 (10) 000-0000", "<?= htmlspecialchars(\$global_wa) ?>", $c);
$c = str_replace("+20 (2) 000-0000", "<?= htmlspecialchars(\$global_phone) ?>", $c);
// Wait, is there a global_phone and global_wa?
// Yes: $global_phone = $contactSettings["general_phone"]; $global_wa = $contactSettings["whatsapp_number"];

$c = preg_replace("/https:\/\/wa\.me\/\d+/", "https://wa.me/<?= htmlspecialchars(preg_replace(\"/[^0-9]/\", \"\", \$global_wa)) ?>", $c);

file_put_contents($f, $c);
echo "Fixed contact sidebar variables";
?>
