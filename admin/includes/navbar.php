<?php
if (!isset($settings) || !isset($contact)) {
    require_once __DIR__ . '/../../includes/db.php';
    if (isset($conn) && $conn) {
        // Ø¬Ù„Ø¨ Ø§Ù„Ø¥Ø¹Ø¯Ø§Ø¯Ø§Øª Ø§Ù„Ø¹Ø§Ù…Ø© ÙˆØ¥Ø¹Ø¯Ø§Ø¯Ø§Øª Ø§Ù„ØªÙˆØ§ØµÙ„ Ù…Ù† Ø§Ù„Ø¯Ø§ØªØ§Ø¨ÙŠØ²
        $settings = $conn->query("SELECT * FROM site_settings LIMIT 1")->fetch_assoc() ?: [];
        $contact = $conn->query("SELECT * FROM contact_settings LIMIT 1")->fetch_assoc() ?: [];
    } else {
        $settings = [];
        $contact = [];
    }
}

// --- Ù…Ø¹Ø§Ù„Ø¬Ø© Ø±Ù‚Ù… Ø§Ù„ÙˆØ§ØªØ³Ø§Ø¨ Ø¨Ø±Ù…Ø¬ÙŠØ§Ù‹ ---
// 1. ØªÙ†Ø¸ÙŠÙ Ø§Ù„Ø±Ù‚Ù… Ù…Ù† Ø£ÙŠ Ø±Ù…ÙˆØ² Ø£Ùˆ Ù…Ø³Ø§ÙØ§Øª
$wa_raw = $contact['whatsapp_number'] ?? '01555518060';
$wa_clean = preg_replace('/[^0-9]/', '', $wa_raw);

// 2. Ø§Ù„ØªØ­Ù‚Ù‚ Ù…Ù† ÙƒÙˆØ¯ Ø§Ù„Ø¯ÙˆÙ„Ø© (20 Ù„Ù…ØµØ±)
// Ø¥Ø°Ø§ ÙƒØ§Ù† Ø§Ù„Ø±Ù‚Ù… ÙŠØ¨Ø¯Ø£ Ø¨Ù€ 01 (Ø±Ù‚Ù… Ù…ØµØ±ÙŠ Ø¹Ø§Ø¯ÙŠ)ØŒ Ù†Ø¶ÙŠÙ Ù„Ù‡ 2 ÙÙŠ Ø§Ù„Ø¨Ø¯Ø§ÙŠØ© Ù„ÙŠØµØ¨Ø­ 201...
if (str_starts_with($wa_clean, '01')) {
    $wa_link = '2' . $wa_clean;
} elseif (str_starts_with($wa_clean, '1')) { 
    // Ù„Ùˆ Ø§Ù„Ø±Ù‚Ù… Ù…ÙƒØªÙˆØ¨ 1... Ø¨Ø¯ÙˆÙ† Ø§Ù„ØµÙØ± ÙˆØ¨Ø¯ÙˆÙ† Ø§Ù„Ù€ 20
    $wa_link = '20' . $wa_clean;
} else {
    // Ù„Ùˆ Ø§Ù„Ø±Ù‚Ù… Ù…ÙƒØªÙˆØ¨ Ø¨Ø§Ù„ØµÙŠØºØ© Ø§Ù„Ø¯ÙˆÙ„ÙŠØ© ÙØ¹Ù„Ø§Ù‹ (ÙŠØ¨Ø¯Ø£ Ø¨Ù€ 20) Ø£Ùˆ Ø£ÙŠ Ø±Ù‚Ù… Ø¢Ø®Ø±
    $wa_link = $wa_clean;
}
?>

<!-- Navigation Bar -->
<nav id="navbar">
    <div class="nav-container">
        <!-- LOGO -->
        <a href="/index.php" class="nav-logo" tabindex="0">
            <h2>Green Pyramids</h2>
        </a>

        <!-- DESKTOP LINKS -->
        <ul class="nav-links" role="menubar">
            <li role="none"><a href="/index.php" role="menuitem">Home</a></li>
            <li role="none"><a href="/production.php" role="menuitem">Products</a></li>
            <li role="none"><a href="/about.php" role="menuitem">About</a></li>
            <li role="none"><a href="/process.php" role="menuitem">Process</a></li>
            <li role="none"><a href="/contact.php" role="menuitem">Contact</a></li>
        </ul>

        <!-- DESKTOP ACTIONS -->
        <div class="nav-actions">

            <!-- WhatsApp (Ø§Ù„Ø¯ÙŠÙ†Ø§Ù…ÙŠÙƒÙŠ Ø§Ù„Ù…ØµÙ„Ø­) -->
            <a href="https://wa.me/<?= htmlspecialchars($wa_link) ?>" 
               class="nav-icon-btn" 
               target="_blank">
                <img src="<?= htmlspecialchars(asset_url('assets/images/whatsapp.png')) ?>" alt="WhatsApp icon" class="nav-icon-img">
                <span class="nav-icon-tooltip">Chat on WhatsApp</span>
            </a>

            <!-- Email (ÙŠØ³Ø­Ø¨ Ù…Ù† Ø¹Ù…ÙˆØ¯ primary_email) -->
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($contact['primary_email'] ?? 'export@greenlight-eg.com') ?>&su=Inquiry&body=Hello, I want to contact you"
               class="nav-icon-btn" 
               target="_blank">
                <img src="<?= htmlspecialchars(asset_url('assets/images/email.png')) ?>" alt="Email icon" class="nav-icon-img">
                <span class="nav-icon-tooltip">Send Email</span>
            </a>

            <!-- Quote -->
            <a href="/contact.php" class="btn-quote cta-button">
                Get a Quote
            </a>

        </div>

        <!-- MOBILE HAMBURGER BUTTON -->
        <button id="menu-toggle" class="hamburger" aria-label="Toggle Menu" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" x2="20" y1="12" y2="12" />
                <line x1="4" x2="20" y1="6" y2="6" />
                <line x1="4" x2="20" y1="18" y2="18" />
            </svg>
        </button>

        <!-- MOBILE MENU LIST -->
        <ul id="mobile-menu" class="mobile-menu" role="menu">
            <li role="none"><a href="/index.php" role="menuitem">Home</a></li>
            <li role="none"><a href="/production.php" role="menuitem">Products</a></li>
            <li role="none"><a href="/about.php" role="menuitem">About</a></li>
            <li role="none"><a href="/process.php" role="menuitem">Process</a></li>
            <li role="none"><a href="/contact.php" role="menuitem">Contact</a></li>
        </ul>
    </div>
</nav>

<style>
/* ====== BASE NAVBAR ====== */
#navbar {
    background: transparent;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 999;
    padding: 0 20px;
    transition: all 0.3s ease-in-out;
}

#navbar.scrolled {
    background: #588b3c ;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 85px;
    max-width: 1200px;
    margin: 0 auto;
    transition: height 0.3s ease-in-out;
}

#navbar.scrolled .nav-container {
    height: 65px;
}

/* ====== LOGO ====== */
.nav-logo {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.nav-logo img {
    height:200px;
    width: auto;
    object-fit: contain;
    margin: 0;
    transition: height 0.3s ease-in-out;
}

#navbar.scrolled .nav-logo img {
    height: 180px;
}

/* ====== DESKTOP LINKS & ACTIONS ====== */
.nav-links {
    display: flex;
    gap: 25px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-links a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 15px;
    cursor: pointer;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-icon-btn {
    cursor: pointer;
    position: relative;
    display: flex;
}

.nav-icon-img {
    width: 22px;
    height: 22px;
}

.nav-icon-tooltip {
    position: absolute;
    bottom: -35px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.8);
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: 0.3s;
}

.nav-icon-btn:hover .nav-icon-tooltip {
    opacity: 1;
    visibility: visible;
}

.btn-quote {
    border: 1px solid #fff;
    padding: 6px 16px;
    border-radius: 6px;
    color: #fff;
    text-decoration: none;
    transition: 0.3s;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
}

.btn-quote:hover {
    background: #fff;
    color: #155724;
}

/* ====== MOBILE HAMBURGER ====== */
.hamburger {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    color: #fff;
}

/* ====== MOBILE RESPONSIVENESS ====== */
@media (max-width: 768px) {
    .nav-links, .nav-actions {
        display: none;
    }

    .nav-logo img {
        height: 118px; 
    }

    #navbar.scrolled .nav-logo img {
        height: 84px;
    }

    .hamburger {
        display: block;
    }

    .mobile-menu {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #155724;
        padding: 20px 0;
        list-style: none;
        margin: 0;
        gap: 20px;
        align-items: center;
        transform: translateY(-110%);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease-in-out;
        z-index: -1;
    }

    .mobile-menu.active {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }

    .mobile-menu a {
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        font-weight: 500;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("navbar");
    
    // Ø³ØªØ§ÙŠÙ„ Ø¹Ù†Ø¯ Ø§Ù„Ø³ÙƒØ±ÙˆÙ„
    window.addEventListener("scroll", function() {
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });

    // Ø§Ù„Ù…Ù†ÙŠÙˆ ÙÙŠ Ø§Ù„Ù…ÙˆØ¨Ø§ÙŠÙ„
    const menuToggle = document.getElementById("menu-toggle");
    const mobileMenu = document.getElementById("mobile-menu");

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener("click", function(e) {
            e.preventDefault();
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            mobileMenu.classList.toggle("active");
            
            if (!navbar.classList.contains("scrolled")) {
                navbar.style.background = !expanded ? "#155724" : "transparent";
            }
        });
    }
});
</script>



