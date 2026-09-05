<?php
require "includes/session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}

require '../includes/db.php';

// ===== جلب كل أقسام الصفحات من الداتا بيز =====
$sections = [];
if (isset($conn) && $conn) {
    $r = $conn->query("SELECT * FROM page_sections");
    if ($r && $r->num_rows > 0) {
        while ($row = $r->fetch_assoc()) {
            $sections[$row['page']][$row['section']] = $row;
        }
    }
}

// ===== جلب السلايدر الخاص بالصفحة الرئيسية =====
$slides = [];
if (isset($conn) && $conn) {
    $r = $conn->query("SELECT * FROM hero_slides ORDER BY sort_order");
    if ($r && $r->num_rows > 0) {
        while ($row = $r->fetch_assoc()) {
            $slides[] = $row;
        }
    }
}

// ===== About Page Sections =====
$aboutHero  = $sections['about']['hero'] ?? ['heading' => 'From the Land of Pyramids to Global Markets.', 'subtext' => "Connecting the world to the finest fresh produce from Egypt's most fertile lands, engineered for global export.", 'image_path' => 'assets/images/hero-farm.jpg'];
$aboutIntro = $sections['about']['intro'] ?? ['heading' => 'A Vision Built On Reliability.', 'subtext' => "After more than 20 years of hands-on agricultural experience, we saw one clear problem: importers struggle to find reliable suppliers they can trust.\n\nSo we built Green Pyramids to solve that.\n\nWe combine deep farming expertise with strict quality control and reliable sourcing — giving our partners consistent access to premium Egyptian produce without the usual risks of inconsistency, delays, or poor quality.", 'image_path' => 'assets/images/product-harvest.jpg'];

$processHero   = $sections['process']['hero']  ?? ['heading' => '', 'subtext' => '', 'image_path' => ''];
$processIntro  = $sections['process']['intro'] ?? ['heading' => '', 'subtext' => '', 'image_path' => ''];
$productHero   = $sections['production']['hero'] ?? ['heading' => '', 'subtext' => '', 'image_path' => ''];
$contactHero   = $sections['contact']['hero'] ?? ['heading' => 'Contact Us', 'subtext' => 'Get in touch with our team', 'image_path' => ''];

// ===== إدخال الأقسام تلقائياً لو مش موجودة في الداتا بيز =====
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

// ===== الدالة السحرية لضبط مسارات الصور (بدون كسور) =====
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
<style>
.image-uploader.dragover {
    border-color: #8FAE5D;
    background-color: #f0fdf4;
}
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
                <!-- التبويبة الجديدة لصفحة التواصل -->
                <button class="tab-btn" data-tab="contact">Contact Page</button>
                <button class="tab-btn" data-tab="quality">Quality Page</button>
                <button class="tab-btn" data-tab="categories">Categories</button>
            </div>
        </div>

        <div class="editor-container">
            <!-- ================= HOME TAB ================= -->
            <div id="home-tab" class="tab-content active">
                <h2 class="section-title" style="margin-top: 0;">🏠 Home Page Content</h2>
                
                <?php 
                $homeHero = $sections['home']['hero'] ?? ['heading' => '', 'subtext' => '', 'image_path' => ''];
                ?>
                <?php 
                $homeOverline = $sections['home']['hero_overline'] ?? ['heading' => 'Egyptian Agricultural Exports'];
                ?>
                <div class="section-card" data-page="home" data-section="hero_overline">
                    <h3 class="section-title">Overline Text (Small text above title)</h3>
                    <div class="form-group">
                        <label>Overline Text (Small text above title)</label>
                        <input type="text" class="section-heading" placeholder="e.g. Egyptian Agricultural Exports" value="<?= htmlspecialchars($homeOverline['heading'] ?? '') ?>" data-page="home" data-section="hero_overline">
                    </div>
                </div>
                <div class="section-card" data-page="home" data-section="hero">
                    <h3 class="section-title">Main Hero Section</h3>
                    <div class="form-group">
                        <label>Hero Background Image</label>
                        <div class="image-uploader" onclick="this.querySelector('input').click()">
                            <input type="file" class="section-image-input" accept="image/*" style="display:none" data-page="home" data-section="hero">
                            <?php $resolvedImage = resolveAdminImage($homeHero['image_path'] ?? ''); ?>
                            <?php if (!empty($resolvedImage)): ?>
                                <img class="section-preview" src="<?= htmlspecialchars($resolvedImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <p>Drag &amp; Drop or Click to upload</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Hero Title</label>
                        <textarea class="section-heading" data-page="home" data-section="hero"><?= htmlspecialchars($homeHero['heading'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Hero Subtitle</label>
                        <textarea class="section-subtext" data-page="home" data-section="hero"><?= htmlspecialchars($homeHero['subtext'] ?? '') ?></textarea>
                    </div>
                </div>

                <h2 class="section-title" style="margin-top: 30px;">KPIs / Achievements</h2>
                <?php for($i=1; $i<=4; $i++): 
                    $kpiData = $sections["home"]["kpi{$i}"] ?? ["heading"=>"", "subtext"=>"", "image_path"=>""];
                ?>
                <div class="section-card" data-page="home" data-section="kpi<?= $i ?>">
                    <h3 class="section-title">KPI <?= $i ?></h3>
                    <div class="form-group">
                        <label>Number (e.g. 15+)</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($kpiData["heading"] ?? "") ?>" data-page="home" data-section="kpi<?= $i ?>">
                    </div>
                    <div class="form-group">
                        <label>Title (e.g. Years Exporting)</label>
                        <input type="text" class="section-subtext" value="<?= htmlspecialchars($kpiData["subtext"] ?? "") ?>" data-page="home" data-section="kpi<?= $i ?>">
                    </div>
                    <div class="form-group">
                        <label>Sub-title (e.g. Nile Delta & Upper Egypt)</label>
                        <input type="text" class="section-image-text" value="<?= htmlspecialchars($kpiData["image_path"] ?? "") ?>" data-page="home" data-section="kpi<?= $i ?>">
                    </div>
                </div>
                <?php endfor; ?>

                
            </div>

            <!-- ================= ABOUT TAB ================= -->
            <div id="about-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">📝 About Page Content</h2>
                
                <div class="section-card" data-page="about" data-section="hero">
                    <h3 class="section-title">About Hero (Our Legacy)</h3>
                    <div class="form-group">
                        <label>Background Image</label>
                        <div class="image-uploader" onclick="this.querySelector('input').click()">
                            <input type="file" class="section-image-input" accept="image/*" style="display:none" data-page="about" data-section="hero">
                            <?php $resolvedHeroImage = resolveAdminImage($aboutHero['image_path'] ?? 'assets/images/hero-farm.jpg'); ?>
                            <?php if (!empty($resolvedHeroImage)): ?>
                                <img class="section-preview" src="<?= htmlspecialchars($resolvedHeroImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <p>Click to upload image</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Main Title (e.g. OUR LEGACY)</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($aboutHero['heading'] ?? '') ?>" data-page="about" data-section="hero">
                    </div>
                    <div class="form-group">
                        <label>Subtext (Description under the title)</label>
                        <textarea class="section-subtext" data-page="about" data-section="hero"><?= htmlspecialchars($aboutHero['subtext'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- ponytail: about introduction / harvest image section -->
                <div class="section-card" data-page="about" data-section="intro">
                    <h3 class="section-title">Introduction Section (A Vision Built On Reliability)</h3>
                    <div class="form-group">
                        <label>Section Image (Harvest Image / Tomato Basket)</label>
                        <div class="image-uploader" onclick="this.querySelector('input').click()">
                            <input type="file" class="section-image-input" accept="image/*" style="display:none" data-page="about" data-section="intro">
                            <?php $resolvedIntroImage = resolveAdminImage($aboutIntro['image_path'] ?? 'assets/images/product-harvest.jpg'); ?>
                            <?php if (!empty($resolvedIntroImage)): ?>
                                <img class="section-preview" src="<?= htmlspecialchars($resolvedIntroImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <p>Click to upload image</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Main Title</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($aboutIntro['heading'] ?? '') ?>" data-page="about" data-section="intro">
                    </div>
                    <div class="form-group">
                        <label>Content Description</label>
                        <textarea class="section-subtext" style="min-height: 120px;" data-page="about" data-section="intro"><?= htmlspecialchars($aboutIntro['subtext'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= PROCESS TAB ================= -->
            <div id="process-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">Process Page Sections</h2>
                
                <div class="section-card" data-page="process" data-section="hero">
                    <h3 class="section-title">Hero Section</h3>
                    <div class="form-group">
                        <label>Hero Title</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($processHero['heading'] ?? '') ?>" data-page="process" data-section="hero">
                    </div>
                </div>

                
                <h2 class="section-title" style="margin-top: 30px;">Journey Steps</h2>
                <?php for($i=1; $i<=6; $i++): 
                    $stepData = $sections["process"]["step{$i}"] ?? ["heading"=>"", "subtext"=>""];
                ?>
                <div class="section-card" data-page="process" data-section="step<?= $i ?>">
                    <h3 class="section-title">Step <?= $i ?></h3>
                    <div class="form-group">
                        <label>Step Title</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($stepData["heading"] ?? "") ?>" data-page="process" data-section="step<?= $i ?>">
                    </div>
                    <div class="form-group">
                        <label>Step Description</label>
                        <textarea class="section-subtext" data-page="process" data-section="step<?= $i ?>"><?= htmlspecialchars($stepData["subtext"] ?? "") ?></textarea>
                    </div>
                </div>
                <?php endfor; ?>

            </div>

            <!-- ================= PRODUCT TAB ================= -->
            <div id="product-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">📦 Product Page Hero</h2>
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
                <h2 class="section-title" style="margin-top: 30px;">Animated Hero Cards</h2>
                <?php 
                $productHeroCards = [];
                if (isset($conn)) {
                    $r = $conn->query("SELECT * FROM product_hero_cards ORDER BY sort_order ASC LIMIT 4");
                    if ($r) {
                        while ($row = $r->fetch_assoc()) {
                            $productHeroCards[] = $row;
                        }
                    }
                }
                foreach ($productHeroCards as $card): ?>
                    <div class="slide-card" data-phcard-id="<?= $card['id'] ?>">
                        <h3 class="section-title">Card <?= $card['sort_order'] ?></h3>
                        <div class="form-group">
                            <label>Card Image</label>
                            <div class="image-uploader" onclick="this.querySelector('input').click()">
                                <input type="file" class="phcard-image-input" accept="image/*" style="display:none" data-phcard-id="<?= $card['id'] ?>">
                                <?php $resolvedImage = resolveAdminImage($card['image_path'] ?? ''); ?>
                                <?php if (!empty($resolvedImage)): ?>
                                    <img class="phcard-preview" src="<?= htmlspecialchars($resolvedImage) ?>?t=<?= time() ?>" style="max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <p>Click to upload image</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Product Title</label>
                            <input type="text" class="phcard-title" value="<?= htmlspecialchars($card['title'] ?? '') ?>" data-phcard-id="<?= $card['id'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Category Badge</label>
                            <input type="text" class="phcard-category" value="<?= htmlspecialchars($card['category'] ?? '') ?>" data-phcard-id="<?= $card['id'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Link URL</label>
                            <input type="text" class="phcard-link" value="<?= htmlspecialchars($card['link_url'] ?? '') ?>" data-phcard-id="<?= $card['id'] ?>">
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <!-- ================= CONTACT TAB ================= -->
            <div id="contact-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">📞 Contact Page Content</h2>
                <div class="section-card" data-page="contact" data-section="hero">
                    <h3 class="section-title">Hero Section</h3>
                    <div class="form-group">
                        <label>Overline Text</label>
                        <input type="text" class="section-image-text" placeholder="e.g. GET IN TOUCH" value="<?= htmlspecialchars($contactHero['image_path'] ?? '') ?>" data-page="contact" data-section="hero">
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

            <!-- ================= QUALITY TAB ================= -->
            <div id="quality-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">⭐ Quality Page Sections</h2>
                <?php $qualityHero = $sections['quality']['hero'] ?? ['heading' => '', 'subtext' => '', 'image_path' => '']; ?>
                <div class="section-card" data-page="quality" data-section="hero">
                    <h3 class="section-title">Hero Section</h3>
                    <div class="form-group">
                        <label>Main Heading</label>
                        <input type="text" class="section-heading" value="<?= htmlspecialchars($qualityHero['heading'] ?? '') ?>" data-page="quality" data-section="hero">
                    </div>
                    <div class="form-group">
                        <label>Subtext</label>
                        <textarea class="section-subtext" data-page="quality" data-section="hero"><?= htmlspecialchars($qualityHero['subtext'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- ponytail: certifications CRUD section -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin: 35px 0 15px 0; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h2 class="section-title" style="margin: 0;">🏅 Certifications Management</h2>
                        <p style="margin: 4px 0 0 0; color: var(--text-secondary); font-size: 13px;">Manage all certificates displayed in the grid on the Quality page.</p>
                    </div>
                    <button type="button" class="btn-publish" onclick="addCertification()" style="padding: 9px 18px; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-plus"></i> Add Certification
                    </button>
                </div>

                <div id="certifications-list">
                <?php 
                $certRows = [];
                if (isset($conn) && $conn) {
                    $chk = $conn->query("SHOW TABLES LIKE 'certifications'");
                    if ($chk && $chk->num_rows > 0) {
                        $rc = $conn->query("SELECT * FROM certifications ORDER BY sort_order ASC, id ASC");
                        if ($rc) {
                            while ($row = $rc->fetch_assoc()) {
                                $certRows[] = $row;
                            }
                        }
                    }
                }
                foreach ($certRows as $cert): 
                    $hasImg = !empty($cert['image_path']) && file_exists(dirname(__FILE__) . '/../' . $cert['image_path']);
                    $certImg = $hasImg ? resolveAdminImage($cert['image_path']) : '';
                ?>
                    <div class="section-card cert-card" data-cert-id="<?= $cert['id'] ?>" style="border-left: 4px solid var(--primary);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                            <h3 class="section-title" style="margin: 0; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-certificate" style="color: #8FAE5D;"></i>
                                <span class="cert-title-display"><?= htmlspecialchars($cert['title'] ?: 'Untitled Certification') ?></span>
                            </h3>
                            <button type="button" class="btn-delete-cert" onclick="deleteCertification(<?= $cert['id'] ?>, this)" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 120px; gap: 16px;">
                            <div class="form-group">
                                <label>Certification Title</label>
                                <input type="text" class="cert-title" data-cert-id="<?= $cert['id'] ?>" value="<?= htmlspecialchars($cert['title']) ?>" placeholder="e.g. ISO 22000 Food Safety">
                            </div>
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" class="cert-sort" data-cert-id="<?= $cert['id'] ?>" value="<?= intval($cert['sort_order']) ?>" min="0">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500; font-size: 14px;">
                                <input type="checkbox" class="cert-active" data-cert-id="<?= $cert['id'] ?>" <?= !empty($cert['is_active']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--primary);">
                                <span>Active (visible on website)</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Certification Logo / Image <span style="font-weight: 400; color: var(--text-secondary); font-size: 12px;">(optional: PNG, JPG, SVG, WEBP)</span></label>
                            <div class="image-uploader" onclick="this.querySelector('input').click()" style="position: relative;">
                                <input type="file" class="cert-image-input" accept="image/*" style="display:none" data-cert-id="<?= $cert['id'] ?>">
                                <?php if (!empty($certImg)): ?>
                                    <img class="cert-preview" src="<?= htmlspecialchars($certImg) ?>?t=<?= time() ?>" style="max-height: 100px; max-width: 140px; object-fit: contain; margin: 0 auto; display: block;">
                                    <p class="uploader-hint" style="margin: 8px 0 0 0; font-size: 12px; color: var(--text-secondary);">Click to change logo</p>
                                <?php else: ?>
                                    <div class="cert-placeholder-preview" style="padding: 10px 0;">
                                        <i class="fas fa-award" style="font-size: 32px; color: #8FAE5D; margin-bottom: 8px; display: block;"></i>
                                        <p style="margin: 0; font-size: 13px; color: var(--text-secondary);">Click to upload logo/image (defaults to badge icon)</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($certImg)): ?>
                                <div class="cert-img-actions" style="margin-top: 6px; text-align: right;">
                                    <button type="button" onclick="removeCertImage(<?= $cert['id'] ?>, this)" style="background: none; border: none; color: #DC2626; font-size: 12px; cursor: pointer; text-decoration: underline;">Remove image</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>

            <div id="categories-tab" class="tab-content">
                <h2 class="section-title" style="margin-top: 0;">Category Images</h2>
                <div class="grid grid-cols-1 gap-6">
                <?php 
                $cats = [];
                if(isset($conn)){
                    $rc = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
                    if($rc) { while($row = $rc->fetch_assoc()) $cats[] = $row; }
                }
                foreach ($cats as $cat): 
                ?>
                    <div class="section-card" data-cat-id="<?= $cat['category_id'] ?>">
                        <h3 class="section-title"><?= htmlspecialchars($cat['category_name']) ?></h3>
                        <div class="form-group">
                            <div class="image-uploader" onclick="this.querySelector('input').click()">
                                <input type="file" class="cat-image-input" accept="image/*" style="display:none" data-cat-id="<?= $cat['category_id'] ?>">
                                <?php $cImage = resolveAdminImage($cat['image_path'] ?? ''); ?>
                                <?php if (!empty($cImage)): ?>
                                    <img class="cat-preview" src="<?= htmlspecialchars($cImage) ?>?t=<?= time() ?>" style="max-height: 150px; object-fit: cover;">
                                <?php else: ?>
                                    <p>Click to upload image</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
    <script src="../assets/js/editor.js?v=1.1"></script>
    <!-- ponytail: editor.js handles tabs, toasts, and certifications CRUD cleanly -->
</body>
</html>



