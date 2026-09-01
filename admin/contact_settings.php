<?php
require "includes/session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}

require '../includes/db.php';

// --- 1. كود الحفظ المعالج (Update Logic) ---
$update_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_settings') {
    $primary_email = $_POST['primary_email'] ?? '';
    $sales_email   = $_POST['sales_email'] ?? '';
    $general_phone = $_POST['general_phone'] ?? ''; 
    
    // حل مشكلة الواتساب: تنظيف الرقم وإضافة كود الدولة أوتوماتيكياً
    $raw_whatsapp   = $_POST['whatsapp_number'] ?? '';
    $clean_whatsapp = preg_replace('/[^0-9]/', '', $raw_whatsapp);
    if (str_starts_with($clean_whatsapp, '01')) {
        $clean_whatsapp = '2' . $clean_whatsapp;
    }

    $physical_address  = $_POST['physical_address'] ?? '';
    $google_maps_embed = $_POST['google_maps_embed'] ?? '';
    $facebook_url      = $_POST['facebook_url'] ?? '';
    $instagram_url     = $_POST['instagram_url'] ?? '';
    $linkedin_url      = $_POST['linkedin_url'] ?? '';

    $sql = "UPDATE contact_settings SET 
            primary_email = ?, sales_email = ?, general_phone = ?, 
            whatsapp_number = ?, physical_address = ?, google_maps_embed = ?, 
            facebook_url = ?, instagram_url = ?, linkedin_url = ? 
            WHERE id = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", 
        $primary_email, $sales_email, $general_phone, $clean_whatsapp, 
        $physical_address, $google_maps_embed, $facebook_url, $instagram_url, $linkedin_url
    );
    
    if ($stmt->execute()) {
        $update_message = "Settings updated successfully!";
    } else {
        $update_message = "Error updating settings: " . $conn->error;
    }
}

// --- 2. كود جلب البيانات (Fetch Logic) ---
$settings = [
    'primary_email'     => '',
    'sales_email'       => '',
    'general_phone'     => '',
    'whatsapp_number'   => '',
    'physical_address'  => '',
    'google_maps_embed' => '',
    'facebook_url'      => '',
    'instagram_url'     => '',
    'linkedin_url'      => '',
];

$res = $conn->query("SELECT * FROM contact_settings LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    foreach ($settings as $k => $_v) {
        if (isset($row[$k])) $settings[$k] = $row[$k];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Settings | Green Pyramids Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/settings.css">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
    <style>
        body, html, main, section, div, p, h1, h2, h3, h4, span, label, input, textarea { cursor: auto !important; }
        a, button, .btn-global-update, .sidebar-toggle { cursor: pointer !important; }
        .update-msg { padding: 15px; margin-bottom: 20px; border-radius: 8px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .err-msg { padding: 15px; margin-bottom: 20px; border-radius: 8px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .hint { font-size: 11px; color: #64748b; margin-top: 4px; display: block; }
    </style>
</head>
<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-backdrop" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content main-content--with-admin-bar">
        <header class="header">
            <div class="header-actions">
                <div class="user-thumb">
                    <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="User" width="32">
                </div>
            </div>
        </header>

        <section class="welcome-section">
            <h2>Contact Information & Global Settings</h2>
            <p>Manage communication channels, multiple phone numbers, and social presence.</p>
        </section>

        <div class="settings-container">
            <div class="settings-form-card">
                <?php if($update_message): ?>
                    <div class="<?php echo (strpos($update_message, 'Error') === false) ? 'update-msg' : 'err-msg'; ?>">
                        <?php echo $update_message; ?>
                    </div>
                <?php endif; ?>

                <form id="settingsForm" method="POST">
                    <input type="hidden" name="action" value="update_settings">
                    <div class="form-grid">
                        
                        <!-- Communication Channels -->
                        <div class="form-column">
                            <h3 class="form-section-title">Communication Channels</h3>
                            <div class="form-group">
                                <label>Primary Contact Email</label>
                                <input type="email" name="primary_email" value="<?php echo htmlspecialchars($settings['primary_email']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Sales & Inquiries Email</label>
                                <input type="email" name="sales_email" value="<?php echo htmlspecialchars($settings['sales_email']); ?>">
                            </div>
                            <div class="form-group">
                                <label>General Phone Lines (Multiple)</label>
                                <input type="text" name="general_phone" placeholder="010... / 012..." value="<?php echo htmlspecialchars($settings['general_phone']); ?>">
                                <span class="hint">Use " / " to separate up to 3 numbers.</span>
                            </div>
                            <div class="form-group">
                                <label>WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" placeholder="01555518060" value="<?php echo htmlspecialchars($settings['whatsapp_number']); ?>">
                                <span class="hint">Automatically formatted for web (wa.me/2015...).</span>
                            </div>
                        </div>

                        <!-- Global Presence & Social -->
                        <div class="form-column">
                            <h3 class="form-section-title">Global Presence & Social</h3>
                            <div class="form-group">
                                <label>Physical Headquarters Address</label>
                                <textarea name="physical_address" rows="3"><?php echo htmlspecialchars($settings['physical_address']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Google Maps Embed Link</label>
                                <input type="text" name="google_maps_embed" placeholder="https://www.google.com/maps/embed?..." value="<?php echo htmlspecialchars($settings['google_maps_embed']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Social Media Links</label>
                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    <div style="position: relative;">
                                        <i class="fab fa-facebook" style="position: absolute; left: 12px; top: 14px; color: #64748B;"></i>
                                        <input type="text" name="facebook_url" placeholder="Facebook URL" style="padding-left: 35px;" value="<?php echo htmlspecialchars($settings['facebook_url']); ?>">
                                    </div>
                                    <div style="position: relative;">
                                        <i class="fab fa-instagram" style="position: absolute; left: 12px; top: 14px; color: #64748B;"></i>
                                        <input type="text" name="instagram_url" placeholder="Instagram URL" style="padding-left: 35px;" value="<?php echo htmlspecialchars($settings['instagram_url']); ?>">
                                    </div>
                                    <div style="position: relative;">
                                        <i class="fab fa-linkedin" style="position: absolute; left: 12px; top: 14px; color: #64748B;"></i>
                                        <input type="text" name="linkedin_url" placeholder="LinkedIn URL" style="padding-left: 35px;" value="<?php echo htmlspecialchars($settings['linkedin_url']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-global-update">
                        <i class="fas fa-bolt"></i> Global Update
                    </button>
                </form>
            </div>

            <!-- Live Footer Preview -->
            <div class="preview-panel">
                <div class="preview-card">
                    <div class="preview-header">Live Footer Preview</div>
                    <div class="footer-preview-content" style="background: #1C3A0E; padding: 20px; border-radius: 0 0 12px 12px;">
                        <p style="color: #fff; font-weight: 700; margin-bottom: 10px;">Green Pyramids Export</p>
                        <div style="font-size: 13px; color: rgba(255,255,255,0.7); line-height: 1.6;">
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($settings['physical_address'] ?: 'Cairo, Egypt'); ?></p>
                            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($settings['primary_email']); ?></p>
                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($settings['general_phone']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
</body>
</html>



