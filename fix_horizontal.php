<?php
$c = file_get_contents('index.php');

$pattern = '/<div class="grid grid-cols-2 md:grid-cols-3 gap-y-16 gap-x-8 max-w-5xl mx-auto">(.*?)<\/div>\s*<div class="mt-14 pt-8 border-t border-\[\#F6F3EC\]\/8">/s';

// We want to transform the items into flex items. 
// But wait, the items themselves are <div class="relative z-10">...</div>. 
// I will just change the wrapper to flex.

$replacement = <<<EOD
<div class="horizontal-stage w-full relative h-[60vh] lg:h-[80vh] flex items-center overflow-hidden">
    <div class="horizontal-track flex flex-nowrap gap-16 lg:gap-32 px-6 lg:px-[10vw] min-w-max items-center h-full">
        
    </div>
</div>
<div class="mt-14 pt-8 border-t border-[#F6F3EC]/8 max-w-7xl mx-auto px-6 lg:px-10 text-center">
EOD;

$c = preg_replace($pattern, $replacement, $c);

// Also need to add classes to each item inside to make them wide enough in horizontal mode
$c = str_replace('<div class="relative z-10">', '<div class="relative z-10 w-[280px] lg:w-[400px] flex-shrink-0">', $c);

file_put_contents('index.php', $c);
?>
