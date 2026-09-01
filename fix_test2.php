<?php
$c = file_get_contents('index.php');
$pattern = '/<div class="grid grid-cols-1 md:grid-cols-3 gap-8">.*?<\/div>\s*<\/div>\s*<\/section>/s';
$replacement = <<<EOD
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php
      require_once "includes/db.php";
      \$res = \$conn->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY created_at DESC LIMIT 3");
      if (\$res && \$res->num_rows > 0):
          while (\$row = \$res->fetch_assoc()):
      ?>
      <div class="bg-white p-8 rounded-2xl shadow-sm border border-[#D8C7A1]/20">
        <div class="flex gap-1 mb-6 text-[#D8C7A1]">
          <?php for(\$i = 0; \$i < \$row['rating']; \$i++): ?>
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
          <?php endfor; ?>
        </div>
        <p class="text-[#173F35]/70 italic mb-6 leading-relaxed">"<?= htmlspecialchars(\$row['review']) ?>"</p>
        <p class="font-serif text-[#173F35] font-medium">- <?= htmlspecialchars(\$row['client_name']) ?><?= \$row['company'] ? ', ' . htmlspecialchars(\$row['company']) : '' ?><?= \$row['country'] ? ', ' . htmlspecialchars(\$row['country']) : '' ?></p>
      </div>
      <?php 
          endwhile;
      else:
      ?>
          <div class="col-span-1 md:col-span-3 text-center py-12 text-[#173F35]/60 bg-white/50 rounded-2xl border border-[#D8C7A1]/20">
              <p>Hear from our clients soon. We are currently updating our reviews.</p>
          </div>
      <?php endif; ?>
    </div>
  </div>
</section>
EOD;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents('index.php', $c);
?>
