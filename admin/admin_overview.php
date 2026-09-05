<?php
require "includes/session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}

require '../includes/db.php';

function dashboard_count(mysqli $conn, string $sql): int
{
    try {
        $result = $conn->query($sql);
        if (!$result) {
            return 0;
        }

        $row = $result->fetch_assoc();
        return (int)($row['total'] ?? 0);
    } catch (Throwable $e) {
        error_log('Dashboard metric query failed: ' . $e->getMessage());
        return 0;
    }
}

function dashboard_recent_inquiries(mysqli $conn): array
{
    try {
        $result = $conn->query("
            SELECT inquiry_id, sender_name, subject, avatar_initials, avatar_color, created_at
            FROM inquiries
            ORDER BY created_at DESC
            LIMIT 3
        ");

        if (!$result) {
            return [];
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    } catch (Throwable $e) {
        error_log('Recent inquiries query failed: ' . $e->getMessage());
        return [];
    }
}

$total_products = dashboard_count($conn, "SELECT COUNT(*) as total FROM products WHERE is_active = 1");
$active_inquiries = dashboard_count($conn, "SELECT COUNT(*) as total FROM inquiries WHERE status = 'New'");
$total_inquiries = dashboard_count($conn, "SELECT COUNT(*) as total FROM inquiries");
$recent_inquiries = dashboard_recent_inquiries($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Overview | Green Pyramids Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/overview.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
</head>

<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <div class="sidebar-backdrop" aria-hidden="true" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content admin-overview">
        <!-- Page Header -->
        <header class="overview-hero">
            <div>
                <span class="eyebrow">ADMIN DASHBOARD</span>
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Administrator'); ?></h1>
                <p>Monitor your business activity and website performance.</p>
            </div>

            <div class="overview-controls" aria-label="Analytics controls">
                <div class="user-thumb">
                    <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="User" width="32">
                </div>
            </div>
        </header>

        <!-- Business Snapshot Section -->
        <section class="business-grid" aria-label="Business overview">
            <article class="metric-card business-card">
                <div class="metric-topline">
                    <span class="metric-icon metric-icon-green"><i class="fas fa-boxes-stacked" aria-hidden="true"></i></span>
                    <span class="metric-pill">Catalog</span>
                </div>
                <span class="metric-label">TOTAL PRODUCTS</span>
                <strong class="metric-value"><?php echo number_format($total_products); ?></strong>
                <span class="metric-note">Products available in catalog</span>
            </article>

            <article class="metric-card business-card">
                <div class="metric-topline">
                    <span class="metric-icon metric-icon-gold"><i class="fas fa-envelope-open-text" aria-hidden="true"></i></span>
                    <span class="metric-pill metric-pill-warm">Pending</span>
                </div>
                <span class="metric-label">ACTIVE INQUIRIES</span>
                <strong class="metric-value"><?php echo number_format($active_inquiries); ?></strong>
                <span class="metric-note">Messages requiring attention</span>
            </article>

            <article class="metric-card actions-card" style="grid-column: span 2;">
                <div>
                    <span class="metric-label" style="color: var(--ov-gold);">QUICK ACTIONS</span>
                    <h2>Manage the essentials</h2>
                    <p>Jump straight into updates.</p>
                </div>
                <div class="action-stack" style="display: flex; gap: 10px;">
                    <a href="product_management.php" class="btn btn-gold" style="flex:1;">
                        <i class="fas fa-plus-circle" aria-hidden="true"></i>
                        Add New Product
                    </a>
                    <a href="content_editor.php" class="btn btn-outline" style="flex:1;">
                        <i class="fas fa-magic" aria-hidden="true"></i>
                        Edit Home Page
                    </a>
                </div>
            </article>
        </section>

        <!-- Operations Section (Recent Inquiries & Inbox Snapshot) -->
        <section class="operations-grid" aria-label="Operations">
            <article class="dashboard-card">
                <div class="table-header">
                    <div>
                        <h3>Recent Inquiries</h3>
                        <p>Inbound customer message stream</p>
                    </div>
                    <a href="inquiry_inbox.php" class="view-all">View All Messages</a>
                </div>
                <div class="table-responsive">
                    <div style="overflow-x:auto; width:100%;"><table style="min-width: 700px;">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_inquiries)): ?>
                                <tr>
                                    <td colspan="3" class="empty-cell">No inquiries yet. New customer messages will appear here.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_inquiries as $inq):
                                    $initials = htmlspecialchars($inq['avatar_initials'] ?? '??');
                                    $color = htmlspecialchars($inq['avatar_color'] ?? '#1A3022');
                                    $name = htmlspecialchars($inq['sender_name'] ?? 'Unknown sender');
                                    $raw_subject = (string)($inq['subject'] ?? '');
                                    $short_subject = strlen($raw_subject) > 35 ? substr($raw_subject, 0, 35) . '...' : $raw_subject;
                                    $subject = htmlspecialchars($short_subject);
                                    $date = date('d M, h:i A', strtotime($inq['created_at'] ?? 'now'));
                                ?>
                                    <tr>
                                        <td>
                                            <div class="sender-cell">
                                                <div class="avatar-sm" style="background-color: <?php echo $color; ?>; color:#fff;">
                                                    <?php echo $initials; ?>
                                                </div>
                                                <span><?php echo $name; ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo $subject; ?></td>
                                        <td class="date-cell"><?php echo $date; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table></div>
                </div>
            </article>

            <article class="dashboard-card business-snapshot">
                <span class="eyebrow">Inbox Snapshot</span>
                <strong><?php echo number_format($total_inquiries); ?></strong>
                <p>Total inquiries captured through the website.</p>
                <a href="inquiry_inbox.php" class="text-link">Review inbox <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            </article>
        </section>

        <!-- Footer -->
        <footer class="page-footer">
            <p>&copy; <?php echo date('Y'); ?> Green Pyramids Admin. All rights reserved.</p>
            <div class="footer-links">
                <span>Privacy Policy</span>
                <span>System Status</span>
            </div>
        </footer>
    </main>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
</body>

</html>
