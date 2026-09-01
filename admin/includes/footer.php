<?php
if (!isset($settings) || !isset($contact)) {
    require_once __DIR__ . '/../../includes/db.php';
    if (isset($conn) && $conn) {
        $settings = $conn->query("SELECT * FROM site_settings LIMIT 1")->fetch_assoc() ?: [];
        $contact = $conn->query("SELECT * FROM contact_settings LIMIT 1")->fetch_assoc() ?: [];
    } else {
        $settings = [];
        $contact = [];
    }
}
?>
<footer class="footer">
    <div class="footer-container">

        <!-- LEFT -->
        <div class="footer-left">
            <h2>Green Pyramids</h2>
            <p>Egypt's premier exporter of high-quality agricultural products.</p>
        </div>

        <!-- RIGHT -->
        <div class="footer-columns">

            <!-- QUICK LINKS -->
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/index.php">Home</a></li>
                    <li><a href="/about.php">About Us</a></li>
                    <li><a href="/production.php">Products</a></li>
                    <li><a href="/process.php">process</a></li>
                    <li><a href="/contact.php">Contact Us</a></li>
                </ul>
            </div>

            <!-- PRODUCTS -->
            <div class="footer-col">
                <h4>Products</h4>
                <ul>
                    <li><a href="/production.php">Fresh Fruits</a></li>
                    <li><a href="/production.php">Fresh Vegetables</a></li>
                    <li><a href="/production.php">Citrus</a></li>
                    <li><a href="/production.php">Dates</a></li>               
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="footer-col">
                <h4>Contact Info</h4>
                <ul>
                    <li><?= htmlspecialchars($contact['primary_email'] ?? 'info@greenlightexport.com') ?></li>
                    <li><?= htmlspecialchars($contact['general_phone'] ?? '+20 123 456 7890') ?></li>
                    <li><?= htmlspecialchars($contact['physical_address'] ?? 'Cairo, Egypt') ?></li>
                </ul>
            </div>

        </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom">
        &copy; <?= date("Y") ?> Green Pyramids for Export. All rights reserved.
    </div>
</footer>

<style>
/* إجبار المتصفح على إظهار الماوس العادي في الفوتر */
.footer {
    background: #588b3c !important;
    color: #fff;
    padding: 60px 80px 20px !important;
    margin-top: 0 !important;
    cursor: auto !important; 
}
.footer * {
    cursor: auto !important;
}

.footer-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px; 
}

/* تحديد مساحة ثابتة للقسم الأيسر عشان ميضغطش على القوائم */
.footer-left {
    flex: 0 0 32%; 
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

/* الحل النهائي لضبط حجم ومكان اللوجو */
.footer-left img {
    width: 280px !important; /* تكبير اللوجو */
    height: auto !important;
    max-width: none !important;
    margin-top: -65px !important; /* رفع اللوجو لأعلى بشكل ملحوظ */
    margin-left: -20px !important; /* سحب اللوجو لليسار لتعويض الحواف الشفافة */
    margin-bottom: 0px !important;
    display: block !important;
}

.footer-left p {
    font-size: 14px !important;
    opacity: 0.85;
    line-height: 1.6 !important;
    margin: 0 !important;
    padding-right: 20px;
}

/* إعطاء باقي المساحة للقوائم وتوزيعها براحتها */
.footer-columns {
    flex: 0 0 65%; 
    display: flex;
    justify-content: space-between;
    margin-top: 0; 
}

.footer-col h4 {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 16px;
    color: #fff;
}

.footer-col ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-col ul li {
    margin-bottom: 8px;
    font-size: 14px;
}

/* إجبار ظهور سهم الكليك (علامة اليد) على اللينكات */
.footer-col a, .footer-col a * {
    color: #fff;
    text-decoration: none;
    opacity: 0.85;
    cursor: pointer !important; 
}

.footer-col a:hover {
    opacity: 1;
}

.footer-bottom {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.2);
    font-size: 14px;
    opacity: 0.8;
}

section + .footer {
    margin-top: 0 !important;
}

/* ====== MOBILE RESPONSIVENESS ====== */
@media (max-width: 768px) {
    .footer {
        padding: 50px 20px 20px !important;
    }

    .footer-container {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        gap: 30px !important;
    }

    .footer-left {
        flex: 1 1 100% !important;
        align-items: center !important;
        text-align: center !important;
        max-width: 100% !important;
        margin: 0 auto !important;
    }

    .footer-left img {
        width: 220px !important;
        margin-top: -20px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        margin-bottom: 10px !important;
    }

    .footer-left p {
        padding: 0 10px !important;
    }

    .footer-columns {
        flex: 1 1 100% !important;
        width: 100% !important;
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 30px 15px !important;
        margin-top: 20px !important;
    }

    .footer-col {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .footer-col:nth-child(3) {
        grid-column: span 2;
    }

    .footer-col h4 {
        font-size: 16px !important;
        margin-bottom: 12px !important;
    }
}
</style>


