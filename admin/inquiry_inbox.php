<?php
require "includes/session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: signin.php");
    exit();
}
?>
<?php
require '../includes/db.php';

$search_term = trim($_GET['search'] ?? '');
$selected_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$inquiries = [];

function inquiry_row_id(array $row): int
{
    foreach (['id', 'inquiry_id', 'inquiryId'] as $key) {
        if (isset($row[$key]) && (string)$row[$key] !== '') {
            return (int)$row[$key];
        }
    }
    return 0;
}

function inquiry_id_column(array $row): ?string
{
    foreach (['id', 'inquiry_id', 'inquiryId'] as $key) {
        if (array_key_exists($key, $row)) {
            return $key;
        }
    }
    return null;
}

// Delete inquiry (admin action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $return_search = trim($_POST['return_search'] ?? '');
    $return_id = (int)($_POST['return_id'] ?? 0);
    $delete_col = (string)($_POST['delete_col'] ?? 'id');
    $allowed_cols = ['id', 'inquiry_id', 'inquiryId'];
    if ($delete_id > 0 && in_array($delete_col, $allowed_cols, true)) {
        $del = $conn->prepare("DELETE FROM inquiries WHERE {$delete_col} = ?");
        $del->bind_param("i", $delete_id);
        $del->execute();
        $del->close();
    }

    $redirect = "inquiry_inbox.php";
    $qs = [];
    if ($return_search !== '') {
        $qs['search'] = $return_search;
    }
    if ($return_id > 0) {
        $qs['id'] = $return_id;
    }
    if (!empty($qs)) {
        $redirect .= "?" . http_build_query($qs);
    }
    header("Location: " . $redirect);
    exit();
}

if ($search_term !== '') {
    $like_search = "%" . $search_term . "%";
    $stmt = $conn->prepare("SELECT * FROM inquiries WHERE sender_name LIKE ? OR sender_email LIKE ? OR company_name LIKE ? OR subject LIKE ? OR message LIKE ? ORDER BY created_at DESC");
    $stmt->bind_param("sssss", $like_search, $like_search, $like_search, $like_search, $like_search);
} else {
    $stmt = $conn->prepare("SELECT * FROM inquiries ORDER BY created_at DESC");
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $inquiries[] = $row;
}
$stmt->close();

$pk_col = !empty($inquiries) ? inquiry_id_column($inquiries[0]) : null;

$active_inquiry = null;
if (!empty($inquiries)) {
    if ($selected_id > 0) {
        foreach ($inquiries as $inq) {
            if (inquiry_row_id($inq) === $selected_id) {
                $active_inquiry = $inq;
                break;
            }
        }
    }
    $active_inquiry = $active_inquiry ?? $inquiries[0];
}

$unread_count = 0;
foreach ($inquiries as $inq) {
    $status = strtoupper(trim((string)($inq['status'] ?? 'NEW')));
    if ($status !== 'READ' && $status !== 'ARCHIVED') {
        $unread_count++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Inbox | Green Pyramids Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/inbox.css">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
</head>
<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <div class="sidebar-backdrop" aria-hidden="true" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Header -->
        <header class="header" style="margin-bottom: 10px;">
            <form method="GET" class="search-bar">
                <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;"><i class="fas fa-search"></i></button>
                <input type="text" name="search" placeholder="Search leads..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </form>
            <div class="header-actions">
                <div class="header-icons">
                    <a href="admin_settings.php"><img src="../assets/settings.png" alt="Settings" style="width: 20px; height: 20px; opacity: 0.7;"></a>
                </div>
                <div class="user-thumb">
                    <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="User" width="32">
                </div>
            </div>
        </header>

        <div class="inbox-container">
            <!-- Middle Pane: Inquiry List -->
            <div class="inquiry-list-pane">
                <div class="inbox-header">
                    <div class="inbox-title-row">
                        <h3>Inquiry Inbox</h3>
                        <span class="unread-badge"><?php echo (int)$unread_count; ?> UNREAD</span>
                    </div>
                    <form method="GET" class="inbox-search">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search inquiries..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        <?php if (!empty($selected_id)): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$selected_id; ?>">
                        <?php endif; ?>
                    </form>
                </div>
                <div class="lead-cards-container">
                    <?php if (empty($inquiries)): ?>
                        <div class="lead-card active">
                            <div class="lead-card-body">
                                <div class="lead-info">
                                    <h4>No inquiries found</h4>
                                    <p>Try a different search term.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($inquiries as $index => $inquiry): ?>
                            <?php
                            $name = trim((string)($inquiry['sender_name'] ?? ''));
                            $initials = '';
                            foreach (explode(' ', $name) as $part) {
                                if ($part !== '') {
                                    $initials .= strtoupper(substr($part, 0, 1));
                                }
                            }
                            $lead_id = inquiry_row_id($inquiry) ?: ($index + 1);
                            $is_active = !empty($active_inquiry) && inquiry_row_id($active_inquiry) === $lead_id;
                            ?>
                            <div class="lead-card <?php echo $is_active ? 'active' : ''; ?>" data-id="<?php echo $lead_id; ?>">
                                <div class="lead-card-header">
                                    <span>LEAD #<?php echo $lead_id; ?></span>
                                    <span><?php echo htmlspecialchars($inquiry['created_at'] ?? ''); ?></span>
                                </div>
                                <div class="lead-card-body">
                                    <div class="initials-badge"><?php echo htmlspecialchars($initials ?: 'NA'); ?></div>
                                    <div class="lead-info">
                                        <h4><?php echo !empty($name) ? htmlspecialchars($name) : 'â€”'; ?></h4>
                                        <p><?php echo htmlspecialchars(substr($inquiry['message'] ?? '', 0, 65)); ?>...</p>
                                    </div>
                                </div>
                                <div class="status-row">
                                    <span class="status-badge status-new"><?php echo strtoupper(htmlspecialchars($inquiry['status'] ?? 'NEW')); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Pane: Message Detail -->
            <div class="message-detail-pane">
                <div class="detail-toolbar">
                    <button class="tool-btn"><i class="fas fa-check"></i> Mark as Read</button>
                    <button class="tool-btn"><i class="fas fa-archive"></i> Archive</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo !empty($active_inquiry) ? inquiry_row_id($active_inquiry) : 0; ?>">
                        <input type="hidden" name="delete_col" value="<?php echo htmlspecialchars($pk_col ?? 'id'); ?>">
                        <input type="hidden" name="return_search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        <input type="hidden" name="return_id" value="<?php echo (int)($selected_id ?? 0); ?>">
                        <button class="tool-btn" id="deleteBtn" style="color: #EF4444;" type="submit" <?php echo empty($active_inquiry) ? 'disabled' : ''; ?>>
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
                <div class="message-content-area">
                    <h2 class="message-subject"><?php echo !empty($active_inquiry) ? (!empty($active_inquiry['subject']) ? htmlspecialchars($active_inquiry['subject']) : 'â€”') : 'Select an inquiry'; ?></h2>
                    
                    <div class="sender-info-card">
                        <div class="info-item">
                            <label>Sender Name</label>
                            <span><?php echo !empty($active_inquiry) ? (!empty($active_inquiry['sender_name']) ? htmlspecialchars($active_inquiry['sender_name']) : 'â€”') : 'â€”'; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Company</label>
                            <span><?php echo !empty($active_inquiry) ? (!empty($active_inquiry['company_name']) ? htmlspecialchars($active_inquiry['company_name']) : 'â€”') : 'â€”'; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Email Address</label>
                            <span><?php echo !empty($active_inquiry) ? (!empty($active_inquiry['sender_email']) ? htmlspecialchars($active_inquiry['sender_email']) : 'â€”') : 'â€”'; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Phone Number</label>
                            <span><?php echo htmlspecialchars($active_inquiry['phone'] ?? '-'); ?></span>
                        </div>
                    </div>

                    <div class="message-body">
                        <p>
                            <?php
                            if (empty($active_inquiry)) {
                                echo 'No message selected.';
                            } elseif (!empty($active_inquiry['message'])) {
                                echo nl2br(htmlspecialchars($active_inquiry['message']));
                            } else {
                                echo 'â€”';
                            }
                            ?>
                        </p>
                    </div>

                    <div class="attachments-section">
                        <h5>Attachments (2)</h5>
                        <div class="attachment-list">
                            <div class="attachment-item">
                                <i class="fas fa-file-pdf"></i>
                                <span>Procurement_Specs.pdf</span>
                            </div>
                            <div class="attachment-item">
                                <i class="fas fa-file-image"></i>
                                <span>Quality_Standards.jpg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/js/sidebar.js"></script>
    <script>
        // Confirm delete
        const deleteBtn = document.getElementById('deleteBtn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', (e) => {
                if (!confirm('Do you want to delete this inquiry?')) {
                    e.preventDefault();
                }
            });
        }

        // Lead selection (loads selected inquiry into detail pane)
        document.querySelectorAll('.lead-card[data-id]').forEach(card => {
            card.addEventListener('click', () => {
                const id = card.getAttribute('data-id');
                const params = new URLSearchParams(window.location.search);
                params.set('id', id);
                window.location.href = 'inquiry_inbox.php?' + params.toString();
            });
        });
    </script>
    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
</body>
</html>



