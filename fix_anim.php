<?php
$c = file_get_contents('assets/js/animations.js');
// Remove old initAnimatedHeadings
$c = preg_replace('/function initAnimatedHeadings\(\).*?\}\s*\n(?=function)/s', '', $c);
// Remove duplicate calls from DOMContentLoaded at top
$c = str_replace('initAnimatedHeadings();', '', $c);
file_put_contents('assets/js/animations.js', $c);
?>
