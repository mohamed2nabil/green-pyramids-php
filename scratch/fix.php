<?php
$f = "admin/content_editor.php";
$c = file_get_contents($f);
$parts = explode("<div id=\"product-tab\"", $c);
if(count($parts) == 2) {
    $append = <<<EOD
                <!-- Process Steps -->
                <h2 class="section-title" style="margin-top: 30px;">Journey Steps</h2>
                <?php for(\$i=1; \$i<=6; \$i++): 
                    \$stepData = \$sections["process"]["step{\$i}"] ?? ["heading"=>"", "subtext"=>""];
                ?>
                <div class="section-card" data-page="process" data-section="step<?= \$i ?>">
                    <h3 class="section-title">Step <?= \$i ?></h3>
                    <div class="form-group">
                        <label>Step Title</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars(\$stepData["heading"] ?? "") ?>" data-page="process" data-section="step<?= \$i ?>">
                    </div>
                    <div class="form-group">
                        <label>Step Description</label>
                        <textarea class="section-subtext" data-page="process" data-section="step<?= \$i ?>"><?= htmlspecialchars(\$stepData["subtext"] ?? "") ?></textarea>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            
            <div id="product-tab"
EOD;
    $c = $parts[0] . $append . $parts[1];
    // Remove the original closing div before product-tab to prevent double closing
    $c = str_replace("</div>\r\n\r\n            <div id=\"product-tab\"", "<div id=\"product-tab\"", $c);
    file_put_contents($f, $c);
    echo "Success";
}
?>
