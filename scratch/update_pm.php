<?php
$f = "admin/product_management.php";
$c = file_get_contents($f);

// In Quick Add modal
$c = str_replace(
    "<input id=\"quickVisible\" type=\"checkbox\" name=\"is_visible\" value=\"1\" checked>",
    "<input id=\"quickVisible\" type=\"checkbox\" name=\"is_visible\" value=\"1\" checked>
                                      <span>Visible</span>
                                  </label>
                              </div>
                              <div class=\"form-group\">
                                  <label class=\"checkbox-label\">
                                      <input id=\"quickFeatured\" type=\"checkbox\" name=\"is_featured\" value=\"1\">
                                      <span>Featured in Hero Carousel</span>",
    $c
);

// In Edit modal
$c = str_replace(
    "<input id=\"edit_is_visible\" type=\"checkbox\" name=\"is_visible\" value=\"1\">",
    "<input id=\"edit_is_visible\" type=\"checkbox\" name=\"is_visible\" value=\"1\">
                                  <span>Visible</span>
                              </label>
                          </div>
                          <div class=\"form-group\">
                              <label class=\"checkbox-label\">
                                  <input id=\"edit_is_featured\" type=\"checkbox\" name=\"is_featured\" value=\"1\">
                                  <span>Featured in Hero Carousel</span>",
    $c
);

file_put_contents($f, $c);
echo "Updated product_management.php\n";
?>
