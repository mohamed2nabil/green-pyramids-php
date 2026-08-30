<?php
require "includes/session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}
?>
<?php
require '../includes/db.php';

$settings = [
    'primary_email' => '',
    'sales_email' => '',
    'general_phone' => '',
    'whatsapp_number' => '',
    'physical_address' => '',
    'google_maps_embed' => '',
    'facebook_url' => '',
    'instagram_url' => '',
    'linkedin_url' => '',
];

$res = $conn->query("SELECT primary_email, sales_email, general_phone, whatsapp_number, physical_address, google_maps_embed, facebook_url, instagram_url, linkedin_url FROM contact_settings LIMIT 1");
if ($res) {
    $row = $res->fetch_assoc();
    if (is_array($row)) {
        foreach ($settings as $k => $_v) {
            if (array_key_exists($k, $row) && $row[$k] !== null) {
                $settings[$k] = (string)$row[$k];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Settings | Green Pyramids Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/settings.css">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
</head>
<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <div class="sidebar-backdrop" aria-hidden="true" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <!-- Main Content Area -->
    <main class="main-content main-content--with-admin-bar">
        <!-- Header -->
        <header class="header">
            <div class="header-actions">
                <div class="header-icons">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="user-thumb">
                    <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="User" width="32">
                </div>
            </div>
        </header>

        <!-- Page Title -->
        <section class="welcome-section">
            <h2>Contact Information & Global Settings</h2>
            <p>Manage your organization's public-facing communication channels and global presence parameters.</p>
        </section>

        <div class="settings-container">
            <!-- Left: Form -->
            <div class="settings-form-card">
                <form id="settingsForm">
                    <div class="form-grid">
                        <!-- Communication Channels -->
                        <div class="form-column">
                            <h3 class="form-section-title">Communication Channels</h3>
                            <div class="form-group">
                                <label for="primaryEmail">Primary Contact Email</label>
                                <input type="email" id="primaryEmail" placeholder="e.g. info@yourcompany.com" value="<?php echo htmlspecialchars($settings['primary_email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="salesEmail">Sales & Inquiries Email</label>
                                <input type="email" id="salesEmail" placeholder="e.g. sales@yourcompany.com" value="<?php echo htmlspecialchars($settings['sales_email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone">General Phone Line</label>
                                <input type="text" id="phone" placeholder="+1 (555) 000-0000" value="<?php echo htmlspecialchars($settings['general_phone']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="whatsapp">WhatsApp Business Number</label>
                                <input type="text" id="whatsapp" placeholder="+1 (555) 000-0000" value="<?php echo htmlspecialchars($settings['whatsapp_number']); ?>">
                            </div>
                        </div>

                        <!-- Global Presence -->
                        <div class="form-column">
                            <h3 class="form-section-title">Global Presence</h3>
                            <div class="form-group">
                                <label for="address">Physical Headquarters Address</label>
                                <textarea id="address" placeholder="Enter full corporate address..."><?php echo htmlspecialchars($settings['physical_address']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="mapsLink">Google Maps Embed Link</label>
                                <input type="text" id="mapsLink" placeholder="https://www.google.com/maps/embed?pb=..." value="<?php echo htmlspecialchars($settings['google_maps_embed']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Social Media Handles</label>
                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    <div style="position: relative;">
                                        <i class="fab fa-facebook" style="position: absolute; left: 12px; top: 14px; color: #64748B;"></i>
                                        <input type="text" id="facebook" placeholder="Facebook URL" style="padding-left: 35px;" value="<?php echo htmlspecialchars($settings['facebook_url']); ?>">
                                    </div>
                                    <div style="position: relative;">
                                        <i class="fab fa-instagram" style="position: absolute; left: 12px; top: 14px; color: #64748B;"></i>
                                        <input type="text" id="instagram" placeholder="Instagram URL" style="padding-left: 35px;" value="<?php echo htmlspecialchars($settings['instagram_url']); ?>">
                                    </div>
                                    <div style="position: relative;">
                                        <i class="fab fa-linkedin" style="position: absolute; left: 12px; top: 14px; color: #64748B;"></i>
                                        <input type="text" id="linkedin" placeholder="LinkedIn URL" style="padding-left: 35px;" value="<?php echo htmlspecialchars($settings['linkedin_url']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-global-update">
                        <i class="fas fa-bolt"></i>
                        Global Update
                    </button>
                </form>
            </div>

            <!-- Right: Preview & Notice -->
            <div class="preview-panel">
                <!-- Live Footer Preview -->
                <div class="preview-card">
                    <div class="preview-header">Live Footer Preview</div>
                    <div class="footer-preview-content">
                        <div class="footer-preview-logo">
                            <div class="logo-box"></div>
                            <span>Sovereign Ledger</span>
                        </div>
                        <p class="footer-preview-bio">
                            Providing elite logistics and financial oversight for global trade operations since 1994.
                        </p>
                        <div class="footer-preview-info">
                            <div><i class="fas fa-map-marker-alt" style="color: var(--gold); width: 15px;"></i> <span id="preview-address"><?php echo htmlspecialchars($settings['physical_address']); ?></span></div>
                            <div><i class="fas fa-envelope" style="color: var(--gold); width: 15px;"></i> <span id="preview-email"><?php echo htmlspecialchars($settings['primary_email']); ?></span></div>
                            <div><i class="fas fa-phone" style="color: var(--gold); width: 15px;"></i> <span id="preview-phone"><?php echo htmlspecialchars($settings['general_phone']); ?></span></div>
                        </div>
                        <div class="footer-preview-socials">
                            <i class="fab fa-facebook-f"></i>
                            <i class="fab fa-instagram"></i>
                            <i class="fab fa-linkedin-in"></i>
                            <i class="fab fa-twitter"></i>
                        </div>
                    </div>
                </div>

                <!-- Deployment Notice -->
                <div class="deployment-notice">
                    <div class="notice-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="notice-text">
                        <h5>Deployment Notice</h5>
                        <p>Updates to contact information propagate to the public portal, digital invoices, and automated reports within 5 minutes of synchronization.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Admin Bar -->
    <div class="admin-bar">
        <div>System Version: <strong>V2.4.0</strong></div>
        <div class="admin-bar-links">
            <span>Security Protocol</span>
            <span>Privacy Policy</span>
            <span>API Documentation</span>
        </div>
    </div>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
    <script src="../assets/js/settings.js"></script>
</body>
</html>




