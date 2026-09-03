<?php
$f = "products.php";
$c = file_get_contents($f);
$s = strpos($c, "<h1 class=\"anim-heading");
$e = strpos($c, "</p>", $s) + 4;
$rep = <<<EOD
<h1 class="anim-heading font-serif text-4xl sm:text-5xl lg:text-5xl text-[#F6F3EC] leading-[1.1] mb-4 lg:mb-6 break-words" style="hyphens: none; word-break: break-word;">
          <?= htmlspecialchars(\$productionHero["heading"] ?? "Egyptian Fresh Produce") ?>
        </h1>
        <p class="anim-heading text-[#F6F3EC]/70 text-[14px] lg:text-[15px] max-w-sm leading-relaxed mb-8">
          <?= htmlspecialchars(\$productionHero["subtext"] ?? "Explore our selection of premium agricultural crops prepared for international markets.") ?>
        </p>
EOD;
file_put_contents($f, substr($c, 0, $s) . $rep . substr($c, $e));
echo "Fixed";
?>
