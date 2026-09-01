<?php
$c = file_get_contents('admin/includes/sidebar.php');
$c = str_replace('<li class="nav-item">
                <a href="inquiry_inbox.php"', '<li class="nav-item">
                <a href="reviews.php" class="nav-link">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="inquiry_inbox.php"', $c);
file_put_contents('admin/includes/sidebar.php', $c);
?>
