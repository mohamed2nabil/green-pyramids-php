<?php
require '../includes/db.php';

$conn->query('UPDATE page_sections SET section="process", heading="Our Process", subtext="How we bring your vision to life." WHERE page="about" AND section="hero"');
echo 'Updated about section to process\n';

$conn->close();
?>
