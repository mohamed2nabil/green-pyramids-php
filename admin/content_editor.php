<?php
require "includes/session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}

require '../includes/db.php';

// ===== Ø¬Ù„Ø¨ ÙƒÙ„ Ø£Ù‚Ø³Ø§Ù… Ø§Ù„ØµÙØ­Ø§Øª Ù…Ù† Ø§Ù„Ø¯Ø§ØªØ§ Ø¨ÙŠØ² =====
$sections = [];
if (isset($conn) && $conn) {
    $r = $conn->query("SELECT * FROM page_sections");
    if ($r && $r->num_rows > 0) {
        while ($row = $r->fetch_assoc()) {
            $sections[$row['page']][$row['section']] = $row;
        }
    }
}

// ===== Ø¬Ù„Ø¨ Ø§Ù„Ø³Ù„Ø§ÙŠØ¯Ø± Ø§Ù„Ø®Ø§Øµ Ø¨Ø§Ù„ØµÙØ­Ø© Ø§Ù„Ø±Ø¦ÙŠØ³ÙŠØ© =====
$slides = [];
if (isset($conn) && $conn) {
    $r = $conn->query("SELECT * FROM hero_slides ORDER BY sort_order");
    if ($r && $r->num_rows > 0) {
        while ($row = $r->fetch_assoc()) {
            $slides[] = $row;
        }
    }
}

// ===== ØªØ¬Ù‡ÙŠØ² Ø§Ù„Ù…ØªØºÙŠØ±Ø§Øª ÙˆØªØµØ­ÙŠØ­ ØºÙ„Ø·Ø© Ù…Ø³Ù…ÙŠØ§Øª Ø§Ù„Ø¯Ø§ØªØ§ Ø¨ÙŠØ² =====
// Ø¨Ù†Ø¬ÙŠØ¨ Ø§Ù„Ø¨ÙŠØ§Ù†Ø§Øª Ù…Ù† hero Ø£Ùˆ process Ù„Ùˆ Ù…ØªØ³Ø¬Ù„Ø© ØºÙ„Ø· ÙÙŠ Ø§Ù„Ø¯Ø§ØªØ§ Ø¨ÙŠØ²
$aboutHero     = $sections['about']['hero'] ?? $sections['about']['process'] ?? ['heading' => 'OUR LEGACY', 'subtext' => '', 'image_path' => ''];
// Ø¹Ø´Ø§Ù† Ù†Ø­ÙØ¸ Ø§Ù„ØªØ¹Ø¯ÙŠÙ„Ø§Øª ÙÙŠ Ù†ÙØ³ Ø§Ù„Ù…ÙƒØ§Ù† Ø§Ù„Ù‚Ø¯ÙŠÙ… Ù„Ùˆ Ù…ÙˆØ¬ÙˆØ¯
$aboutSectionKey = isset($sections['about']['process']) ? 'process' : 'hero'; 

$processHero   = $sections['process']['hero']  ?? ['heading' => '', 'subtext' => '', 'image_path' => ''];
$processIntro  = $sections['process']['intro'] ?? ['heading' => '', 'subtext' => '', 'image_path' => ''];
$productHero   = $sections['production']['hero'] ?? ['heading' => '', 'subtext' => '', 'image_path' => ''];
$contactHero   = $sections['contact']['hero'] ?? ['heading' => 'Contact Us', 'subtext' => 'Get in touch with our team', 'image_path' => ''];

// ===== Ø¥Ø¯Ø®Ø§Ù„ Ø§Ù„Ø£Ù‚Ø³Ø§Ù… ØªÙ„Ù‚Ø§Ø¦ÙŠØ§Ù‹ Ù„Ùˆ Ù…Ø´ Ù…ÙˆØ¬ÙˆØ¯Ø© ÙÙŠ Ø§Ù„Ø¯Ø§ØªØ§ Ø¨ÙŠØ² =====
$needsReload = false;
if (isset($conn) && $conn) {
    if (empty($sections['about']['hero']) && empty($sections['about']['process'])) {
        $conn->query("INSERT IGNORE INTO page_sections (page, section, heading, subtext) VALUES ('about', 'hero', 'OUR LEGACY', 'Rooted in tradition...')");
        $needsReload = true;
    }
    if (empty($sections['contact']['hero'])) {
        $conn->query("INSERT IGNORE INTO page_sections (page, section, heading, subtext) VALUES ('contact', 'hero', 'Contact Us', 'Get in touch with our team')");
        $needsReload = true;
    }

    if ($needsReload) {
        $r = $conn->query("SELECT * FROM page_sections");
        if ($r) {
            while ($row = $r->fetch_assoc()) {
                $sections[$row['page']][$row['section']] = $row;
            }
            $aboutHero   = $sections['about']['hero'] ?? $aboutHero;
            $contactHero = $sections['contact']['hero'] ?? $contactHero;
        }
    }
}

// ===== Ø§Ù„Ø¯Ø§Ù„Ø© Ø§Ù„Ø³Ø­Ø±ÙŠØ© Ù„Ø¶Ø¨Ø· Ù…Ø³Ø§Ø±Ø§Øª Ø§Ù„ØµÙˆØ± (Ø¨Ø¯ÙˆÙ† ÙƒØ³ÙˆØ±) =====
function resolveAdminImage($path) {
    return asset_url($path);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Editor | Green Pyramids Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
    <style>
        :root { --primary: #1A3022; --success: #10B981; --error: #EF4444; --border: #E2E8F0; --bg-light: #F8FAFC; --text-primary: #1E293B; --text-secondary: #64748B; }
        * { box-sizing: border-box; }
        .editor-container { display: grid; grid-template-columns: 1fr; gap: 24px; padding: 24px; max-width: 900px; margin: 0 auto; width: 100%; }
        .editor-tabs { display: flex; gap: 12px; border-bottom: 2px solid var(--border); margin-bottom: 24px; flex-wrap: wrap; max-width: 900px; margin: 0 auto; padding: 0 24px; width: 100%; }
        .tab-btn { padding: 12px 20px; background: none; border: none; font-size: 15px; font-weight: 600; color: var(--text-secondary); cursor: pointer; position: relative; transition: color 0.2s; }
        .tab-btn.active { color: var(--primary); }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: var(--primary); }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .slide-card, .section-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 24px; transition: box-shadow 0.2s; margin-bottom: 20px; }
        .section-title { font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0 0 20px 0; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .form-group label { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .form-group input, .form-group textarea { padding: 12px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; transition: border-color 0.2s; width: 100%; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .image-uploader { border: 2px dashed var(--border); border-radius: 10px; padding: 24px; text-align: center; cursor: pointer; background: var(--bg-light); }
        .image-uploader img { max-width: 100%; border-radius: 8px; margin-top: 10px; }
        .editor-header-wrapper { max-width: 900px; margin: 0 auto; padding: 0 24px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; }
        .editor-header-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn-publish { background: var(--primary); color: #fff; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-discard { background: var(--border); color: var(--text-primary); padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .sync-indicator { font-size: 12px; color: var(--text-secondary); margin-top: 6px; }
    </style>
</head>

<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-backdrop" aria-hidden="true" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">
        <header class="header" style="margin-bottom: 20px;">
            <div class="editor-header-wrapper">
                <div>
                    <h1 style="margin: 0 0 8px 0; font-size: 24px;">Content Editor</h1>
                    <p style="margin: 0; color: var(--text-secondary); font-size: 14px;">Manage all your website content and images</p>
                </div>
                <div class="editor-header-actions">
                    <button class="btn-discard" onclick="location.reload()"><i class="fas fa-undo"></i> Discard</button>
                    <button class="btn-publish" id="publishBtn"><i class="fas fa-rocket"></i> Publish Live</button>
                </div>
            </div>
        </header>

        <div style="margin-bottom: 20px;">
            <div class="editor-tabs">
                <button class="tab-btn active" data-tab="home">Home Page</button>
                <button class="tab-btn" data-tab="about">About Page</button>
                <button class="tab-btn" data-tab="process">Process Page</button>
                <button class="tab-btn" data-tab="product">Product Page</button>
                <!-- Ø§Ù„ØªØ¨ÙˆÙŠØ¨Ø© Ø§Ù„Ø¬Ø¯ÙŠØ¯Ø© Ù„ØµÙØ­Ø© Ø§Ù„ØªÙˆØ§ØµÙ„ -->
                <button class="tab-btn" data-tab="contact">Contact Page</button>
            </div>
        </div>

        <div class="editor-container">
            <!-- ================= HOME TAB ================= -->
            <div id="home-tab" class="tab-content active">
                <h2 class="section-title" style="margin-top: 0;">  Hero Slides</h2>
                <?php foreach ($slides as $slide): ?>
                    <div class="slide-card" data-slide-id="<?= $slide['slide_id'] ?>">
                        <div class="form-group">
                            <label>Featured Image</label>
                            <div class="image-uploader" onclick="this.querySelector('input').click()">
                                <input type="file" class="slide-image-input" accept="image/*" style="display:none" data-slide-id="<?= $slide['slide_id'] ?>">
                                <?php $resolvedImage = resolveAdminImage($slide['image_path'] ?? ''); ?>
                                <?php if (!empty($resolvedImage)): ?>
                                    <img class="slide-preview" src="<?= htmlspecialchars($resolvedImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <p>Click to upload image</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Slide Title</label>
                            <input type="text" class="slide-heading" value="<?= htmlspecialchars($slide['heading'] ?? '') ?>" data-slide-id="<?= $slide['slide_id'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Slide Description</label>
                            <textarea class="slide-subtext" data-slide-id="<?= $slide['slide_id'] ?>"><?= htmlspecialchars($slide['subtext'] ?? '') ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- ================= ABOUT TAB ================= -->
            <div id="about-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">“ About Page Content</h2>
                
                <div class="section-card" data-page="about" data-section="<?= $aboutSectionKey ?>">
                    <h3 class="section-title">About Hero (Our Legacy)</h3>
                    <div class="form-group">
                        <label>Background Image</label>
                        <div class="image-uploader" onclick="this.querySelector('input').click()">
                            <input type="file" class="section-image-input" accept="image/*" style="display:none" data-page="about" data-section="<?= $aboutSectionKey ?>">
                            <?php $resolvedImage = resolveAdminImage($aboutHero['image_path'] ?? ''); ?>
                            <?php if (!empty($resolvedImage)): ?>
                                <img class="section-preview" src="<?= htmlspecialchars($resolvedImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <p>Click to upload image</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Main Title (e.g. OUR LEGACY)</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($aboutHero['heading'] ?? '') ?>" data-page="about" data-section="<?= $aboutSectionKey ?>">
                    </div>
                    <div class="form-group">
                        <label>Subtext (Description under the title)</label>
                        <textarea class="section-subtext" data-page="about" data-section="<?= $aboutSectionKey ?>"><?= htmlspecialchars($aboutHero['subtext'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= PROCESS TAB ================= -->
            <div id="process-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">âš™ï¸Process Page Sections</h2>
                
                <div class="section-card" data-page="process" data-section="hero">
                    <h3 class="section-title">Hero Section</h3>
                    <div class="form-group">
                        <label>Hero Background Image</label>
                        <div class="image-uploader" onclick="this.querySelector('input').click()">
                            <input type="file" class="section-image-input" accept="image/*" style="display:none" data-page="process" data-section="hero">
                            <?php $resolvedImage = resolveAdminImage($processHero['image_path'] ?? ''); ?>
                            <?php if (!empty($resolvedImage)): ?>
                                <img class="section-preview" src="<?= htmlspecialchars($resolvedImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <p>Click to upload hero image</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Hero Title</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($processHero['heading'] ?? '') ?>" data-page="process" data-section="hero">
                    </div>
                </div>

                <div class="section-card" data-page="process" data-section="intro">
                    <h3 class="section-title">Intro Section (Below Image)</h3>
                    <div class="form-group">
                        <label>Intro Title</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($processIntro['heading'] ?? '') ?>" data-page="process" data-section="intro">
                    </div>
                    <div class="form-group">
                        <label>Intro Description</label>
                        <textarea class="section-subtext" data-page="process" data-section="intro"><?= htmlspecialchars($processIntro['subtext'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= PRODUCT TAB ================= -->
            <div id="product-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">“¦ Product Page Hero</h2>
                <div class="section-card" data-page="production" data-section="hero">
                    <h3 class="section-title">Main Hero Content</h3>
                    <div class="form-group">
                        <label>Hero Image</label>
                        <div class="image-uploader" onclick="this.querySelector('input').click()">
                            <input type="file" class="section-image-input" accept="image/*" style="display:none" data-page="production" data-section="hero">
                            <?php $resolvedImage = resolveAdminImage($productHero['image_path'] ?? ''); ?>
                            <?php if (!empty($resolvedImage)): ?>
                                <img class="section-preview" src="<?= htmlspecialchars($resolvedImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <p>Click to upload product image</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Main Heading</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($productHero['heading'] ?? '') ?>" data-page="production" data-section="hero">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="section-subtext" data-page="production" data-section="hero"><?= htmlspecialchars($productHero['subtext'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= CONTACT TAB ================= -->
            <div id="contact-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">“ž Contact Page Hero</h2>
                
                <div class="section-card" data-page="contact" data-section="hero">
                    <h3 class="section-title">Contact Hero Image</h3>
                    <div class="form-group">
                        <label>Hero Background Image</label>
                        <div class="image-uploader" onclick="this.querySelector('input').click()">
                            <input type="file" class="section-image-input" accept="image/*" style="display:none" data-page="contact" data-section="hero">
                            <?php $resolvedImage = resolveAdminImage($contactHero['image_path'] ?? ''); ?>
                            <?php if (!empty($resolvedImage)): ?>
                                <img class="section-preview" src="<?= htmlspecialchars($resolvedImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <p>Click to upload image</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Main Heading</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($contactHero['heading'] ?? '') ?>" data-page="contact" data-section="hero">
                    </div>
                    <div class="form-group">
                        <label>Subtext</label>
                        <textarea class="section-subtext" data-page="contact" data-section="hero"><?= htmlspecialchars($contactHero['subtext'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
    <script src="../assets/js/editor.js?v=1.1"></script>

    <!-- ÙƒÙˆØ¯ Ø¥Ø¶Ø§ÙÙŠ Ù„ØªØ´ØºÙŠÙ„ Ø§Ù„ØªØ¨ÙˆÙŠØ¨Ø§Øª Ø§Ù„Ø¬Ø¯ÙŠØ¯Ø© Ø¨Ø³Ù„Ø§Ø³Ø© -->
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(btn.dataset.tab + '-tab').classList.add('active');
            });
        });
    </script>
</body>
</html>



