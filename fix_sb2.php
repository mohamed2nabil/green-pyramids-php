<?php
$c = file_get_contents('admin/includes/sidebar.php');
$c = preg_replace('/<li class="nav-item">\s*<a href="inquiry_inbox\.php"/', "<li class=\"nav-item\">\n                <a href=\"reviews.php\" class=\"nav-link\">\n                    <i class=\"fas fa-star\"></i>\n                    <span>Reviews</span>\n                </a>\n            </li>\n            <li class=\"nav-item\">\n                <a href=\"inquiry_inbox.php\"", $c);
file_put_contents('admin/includes/sidebar.php', $c);
?>
