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

$total_products = dashboard_count($conn, "SELECT COUNT(*) as total FROM products");
$active_inquiries = dashboard_count($conn, "SELECT COUNT(*) as total FROM inquiries WHERE status = 'pending'");
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
                <label for="ga-date-range">Analytics range</label>
                <select id="ga-date-range">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="last_7_days">Last 7 Days</option>
                    <option value="last_30_days" selected>Last 30 Days</option>
                    <option value="this_month">This Month</option>
                    <option value="previous_month">Previous Month</option>
                </select>
                <button type="button" id="ga-refresh" class="icon-button" aria-label="Refresh analytics" title="Refresh analytics">
                    <i class="fas fa-rotate-right" aria-hidden="true"></i>
                </button>
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

            <article class="metric-card business-card">
                <div class="metric-topline">
                    <span class="metric-icon metric-icon-blue"><i class="fas fa-chart-line" aria-hidden="true"></i></span>
                    <span id="ga-business-sessions-change" class="metric-pill">GA4</span>
                </div>
                <span class="metric-label">WEBSITE SESSIONS</span>
                <strong id="ga-business-sessions" class="metric-value" data-skeleton="true">--</strong>
                <span id="ga-business-sessions-note" class="metric-note">Selected analytics period</span>
            </article>

            <article class="metric-card actions-card">
                <div>
                    <span class="metric-label" style="color: var(--ov-gold);">QUICK ACTIONS</span>
                    <h2>Manage the essentials</h2>
                    <p>Jump straight into updates.</p>
                </div>
                <div class="action-stack">
                    <a href="product_management.php" class="btn btn-gold">
                        <i class="fas fa-plus-circle" aria-hidden="true"></i>
                        Add New Product
                    </a>
                    <a href="content_editor.php" class="btn btn-outline">
                        <i class="fas fa-magic" aria-hidden="true"></i>
                        Edit Home Page
                    </a>
                </div>
            </article>
        </section>

        <!-- Website Analytics Section -->
        <section class="analytics-panel" aria-labelledby="analytics-heading">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">GOOGLE ANALYTICS 4</span>
                    <h2 id="analytics-heading">Website Analytics</h2>
                    <p>Live visitor traffic & engagement overview</p>
                </div>
                <p id="ga-status" class="analytics-status" aria-live="polite">Loading analytics data</p>
            </div>

            <!-- GA4 Essential KPI Grid (4 Cards) -->
            <div class="ga-kpi-grid" aria-label="Analytics KPI cards">
                <article class="metric-card ga-kpi-card" data-ga-card>
                    <div class="metric-topline">
                        <span class="metric-label">ACTIVE USERS</span>
                        <span class="signal-dot" aria-hidden="true"></span>
                    </div>
                    <strong id="ga-active-users" class="metric-value" data-skeleton="true">--</strong>
                    <span id="ga-active-users-note" class="metric-note">vs previous period</span>
                </article>

                <article class="metric-card ga-kpi-card" data-ga-card>
                    <div class="metric-topline">
                        <span class="metric-label">NEW USERS</span>
                        <span class="metric-mini-icon"><i class="fas fa-user-plus" aria-hidden="true"></i></span>
                    </div>
                    <strong id="ga-new-users" class="metric-value" data-skeleton="true">--</strong>
                    <span id="ga-new-users-note" class="metric-note">vs previous period</span>
                </article>

                <article class="metric-card ga-kpi-card" data-ga-card>
                    <div class="metric-topline">
                        <span class="metric-label">PAGE VIEWS</span>
                        <span class="metric-mini-icon"><i class="fas fa-eye" aria-hidden="true"></i></span>
                    </div>
                    <strong id="ga-page-views" class="metric-value" data-skeleton="true">--</strong>
                    <span id="ga-page-views-note" class="metric-note">vs previous period</span>
                </article>

                <article class="metric-card ga-kpi-card" data-ga-card>
                    <div class="metric-topline">
                        <span class="metric-label">AVG. ENGAGEMENT TIME</span>
                        <span class="metric-mini-icon"><i class="fas fa-clock" aria-hidden="true"></i></span>
                    </div>
                    <strong id="ga-average-session-duration" class="metric-value" data-skeleton="true">--</strong>
                    <span id="ga-average-session-duration-note" class="metric-note">Per session</span>
                </article>
            </div>

            <!-- Traffic Overview & Countries Grid -->
            <div class="analytics-two-column">
                <article class="dashboard-card chart-card">
                    <div class="card-heading">
                        <div>
                            <h3>Traffic Overview</h3>
                            <p>Visitors & sessions timeline</p>
                        </div>
                        <div class="chart-legend" aria-hidden="true">
                            <span><i class="legend-dot legend-users"></i>Users</span>
                            <span><i class="legend-dot legend-sessions"></i>Sessions</span>
                        </div>
                    </div>
                    <div class="chart-shell">
                        <canvas id="ga-traffic-chart" aria-label="Traffic overview chart"></canvas>
                        <div id="ga-traffic-empty" class="compact-empty" hidden>
                            <i class="fas fa-chart-area" aria-hidden="true"></i>
                            <strong>No traffic data for this period</strong>
                            <span>Visitor activity will appear here once GA4 records data.</span>
                        </div>
                    </div>
                </article>

                <article class="dashboard-card">
                    <div class="card-heading">
                        <div>
                            <h3>Visitor Countries</h3>
                            <p>Where visitors are coming from</p>
                        </div>
                    </div>
                    <div id="ga-geography" class="ranked-list" data-loading="true"></div>
                </article>
            </div>

            <!-- Secondary Analytics: Top Pages & Devices -->
            <div class="analytics-two-column">
                <article class="dashboard-card">
                    <div class="card-heading">
                        <div>
                            <h3>Top Pages</h3>
                            <p>Most visited pages</p>
                        </div>
                    </div>
                    <div id="ga-top-pages" class="data-list" data-loading="true"></div>
                </article>

                <article class="dashboard-card">
                    <div class="card-heading">
                        <div>
                            <h3>Devices & Traffic Sources</h3>
                            <p>Device breakdown & acquisition channels</p>
                        </div>
                    </div>
                    <div class="device-layout">
                        <div class="device-chart-shell">
                            <canvas id="ga-devices-chart" aria-label="Device breakdown chart"></canvas>
                            <div id="ga-devices-empty" class="compact-empty compact-empty-small" hidden>
                                <i class="fas fa-mobile-screen-button" aria-hidden="true"></i>
                                <strong>No device data</strong>
                            </div>
                        </div>
                        <div id="ga-devices-list" class="legend-list" data-loading="true"></div>
                    </div>
                    <div id="ga-traffic-sources" class="ranked-list" style="margin-top: 16px;" data-loading="true"></div>
                </article>
            </div>
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
                    <table>
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
                    </table>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script src="../assets/js/analytics-dashboard.js"></script>
</body>

</html>
