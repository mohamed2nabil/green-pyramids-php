<?php
// تم إزالة session_start() لأنها موجودة داخل ملف session.php
require "includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin.php");
    exit();
}
require '../includes/db.php';

$message = "";
$success = "";
$error = "";

$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if (!$admin) {
    $admin = [
        'full_name' => $_SESSION['full_name'] ?? 'Admin',
        'email' => '',
        'avatar_url' => null
    ];
}

$admin_full_name = $admin['full_name'] ?? '';
$admin_email = $admin['email'] ?? '';
$admin_avatar = $admin['avatar_url'] ?? '';

// ─────────────────────────────────────────────────────────────────────────────
// معالجة الطلبات (POST Requests)
// ─────────────────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. إضافة مستخدم جديد
    if (isset($_POST['add_user'])) {
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';

        if (empty($full_name) || empty($email) || empty($password) || empty($role)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            $stmt = $conn->prepare("SELECT admin_id FROM admins WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "Email already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // توليد اسم مستخدم أساسي
                $base_username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $full_name));
                if (empty($base_username)) {
                    $base_username = "user";
                }
                $username = $base_username;
                
                // التأكد من أن اسم المستخدم غير مكرر
                $check_user = $conn->prepare("SELECT admin_id FROM admins WHERE username = ?");
                $counter = 1;
                while (true) {
                    $check_user->bind_param("s", $username);
                    $check_user->execute();
                    $check_user->store_result();
                    if ($check_user->num_rows > 0) {
                        $username = $base_username . $counter;
                        $counter++;
                    } else {
                        break;
                    }
                }
                $check_user->close();

                $insert_stmt = $conn->prepare("
                    INSERT INTO admins (full_name, username, email, password_hash, role, is_active, created_at)
                    VALUES (?, ?, ?, ?, ?, 1, NOW())
                ");
                $insert_stmt->bind_param("sssss", $full_name, $username, $email, $hashed_password, $role);
                
                if (@$insert_stmt->execute()) {
                    $success = "User added successfully";
                } else {
                    $error = "Failed to add user. Check database constraints.";
                }
                $insert_stmt->close();
            }
            $stmt->close();
        }
    } 
    // 2. مسح مستخدم (Revoke Access)
    elseif (isset($_POST['delete_user']) && !empty($_POST['delete_user_id'])) {
        $user_id_to_delete = (int)$_POST['delete_user_id'];
        
        // منع الإدمن من مسح نفسه
        if ($user_id_to_delete === $_SESSION['admin_id']) {
            $error = "You cannot revoke your own access.";
        } else {
            // مسح صورة الأفاتار من السيرفر لو موجودة (اختياري بس عشان ننضف الملفات)
            $av_stmt = $conn->prepare("SELECT avatar_url FROM admins WHERE admin_id = ?");
            $av_stmt->bind_param("i", $user_id_to_delete);
            $av_stmt->execute();
            $av_res = $av_stmt->get_result();
            if ($av_row = $av_res->fetch_assoc()) {
                if (!empty($av_row['avatar_url']) && file_exists($av_row['avatar_url'])) {
                    @unlink($av_row['avatar_url']);
                }
            }
            $av_stmt->close();

            // مسح المستخدم من قاعدة البيانات
            $del_stmt = $conn->prepare("DELETE FROM admins WHERE admin_id = ?");
            $del_stmt->bind_param("i", $user_id_to_delete);
            if ($del_stmt->execute()) {
                $success = "User access revoked successfully.";
            } else {
                $error = "Failed to revoke user access.";
            }
            $del_stmt->close();
        }
    }
    // 3. مسح الصورة الشخصية
    elseif (isset($_POST['delete_avatar'])) {
        $avatar = $admin['avatar_url'] ?? '';
        
        if ($avatar && file_exists($avatar)) {
            unlink($avatar);
        }
        
        $stmt = $conn->prepare("UPDATE admins SET avatar_url=NULL WHERE admin_id=?");
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['avatar_url'] = null;
        $admin_avatar = null;
        $admin['avatar_url'] = null;
        $message = "Avatar deleted successfully!";
    } 
    // 4. تحديث بيانات الحساب الشخصي
    else {
        $new_name = $_POST["fullName"] ?? $admin_full_name;
        $new_email = $_POST["email"] ?? $admin_email;
        
        $current_password = $_POST["current_password"] ?? "";
        $new_password = $_POST["new_password"] ?? "";
        $confirm_password = $_POST["confirm_password"] ?? "";
        
        $avatar_url = $admin_avatar;
        
        // Handle Avatar Upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $target_dir = "../assets/images/avatars/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name = "admin_" . $_SESSION['admin_id'] . ".jpg";
            $target_file = $target_dir . $file_name;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("UPDATE admins SET avatar_url=? WHERE admin_id=?");
                $stmt->bind_param("si", $target_file, $_SESSION['admin_id']);
                $stmt->execute();
                $stmt->close();
                
                $_SESSION['avatar_url'] = $target_file;
                $avatar_url = $target_file;
                $admin_avatar = $target_file;
                $admin['avatar_url'] = $target_file;
            } else {
                $error = "Error uploading file.";
            }
        }

        // Handle Password Update
        $password_update_query = "";
        $password_hash = $admin["password_hash"] ?? "";
        
        if (!empty($new_password) || !empty($current_password)) {
            if (password_verify($current_password, $password_hash)) {
                if ($new_password === $confirm_password && !empty($new_password)) {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $password_update_query = ", password_hash = ?";
                } else {
                    $error = "New passwords do not match or cannot be empty.";
                }
            } else {
                $error = "Current password is incorrect.";
            }
        }

        // Update Database
        if (empty($error) && isset($_POST["fullName"])) {
            if (!empty($password_update_query)) {
                $stmt = $conn->prepare("UPDATE admins SET full_name = ?, email = ?, avatar_url = ?, password_hash = ? WHERE admin_id = ?");
                $stmt->bind_param("ssssi", $new_name, $new_email, $avatar_url, $password_hash, $admin_id);
            } else {
                $stmt = $conn->prepare("UPDATE admins SET full_name = ?, email = ?, avatar_url = ? WHERE admin_id = ?");
                $stmt->bind_param("sssi", $new_name, $new_email, $avatar_url, $admin_id);
            }
            
            if ($stmt) {
                if ($stmt->execute()) {
                    $_SESSION["full_name"] = $new_name;
                    $_SESSION["email"] = $new_email;
                    $_SESSION["avatar_url"] = $avatar_url;
                    $admin_full_name = $new_name;
                    $admin_email = $new_email;
                    $admin_avatar = $avatar_url;
                    $message = "Profile updated successfully!";
                } else {
                    $error = "Database error.";
                }
                $stmt->close();
            }
        }
    }
}

// جلب قائمة المديرين
$admins = [];
$admins_stmt = $conn->prepare("SELECT admin_id, full_name, email, role FROM admins ORDER BY created_at DESC");
$admins_stmt->execute();
$admins_result = $admins_stmt->get_result();
while ($admin_row = $admins_result->fetch_assoc()) {
    $admins[] = $admin_row;
}
$admins_stmt->close();

// Variables for form fields (to keep them filled on error)
$form_full_name = $_POST["fullName"] ?? $admin_full_name;
$form_email = $_POST["email"] ?? $admin_email;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings | Green Pyramids Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/admin_settings.css">
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
</head>
<body>
    <button type="button" class="sidebar-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <div class="sidebar-backdrop" aria-hidden="true" hidden></div>

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">
        <header class="header">
            <div class="header-actions">
                <div class="header-icons">
                    <a href="admin_settings.php"><img src="../assets/settings.png" alt="Settings" style="width: 20px; height: 20px; opacity: 0.7;"></a>
                </div>
                <div class="user-thumb">
                    <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="User" width="32">
                </div>
            </div>
        </header>

        <section class="welcome-section">
            <h2>Admin Settings</h2>
            <p>Manage your executive profile security and administrative team permissions.</p>
        </section>

        <div class="settings-grid">
            <!-- Section A: Profile Security -->
            <div class="settings-card">
                <h3>Profile & Security Settings</h3>
                <?php if (!empty($message)): ?>
                    <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form class="settings-form" id="profileForm" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <div class="avatar-upload-section">
                            <div class="avatar-preview-container">
                                <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="User" width="32">
                            </div>
                            <div class="upload-controls">
                                <input type="file" id="avatarUpload" name="avatar" accept="image/jpeg, image/png" style="display: none;">
                                <button type="button" class="btn-upload-avatar" onclick="document.getElementById('avatarUpload').click();">
                                    <i class="fas fa-cloud-upload-alt"></i> Choose Image
                                </button>
                                <div style="display: block; margin-top: 10px;">
                                    <button type="submit" name="delete_avatar" value="1" style="background:none; border:none; color:#EF4444; font-size: 0.85rem; cursor: pointer; text-decoration: underline;">
                                        Delete Image
                                    </button>
                                </div>
                                <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 8px;">
                                    JPG, PNG or GIF (Max 5MB)
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Full Name</label>
                        <div class="input-with-icon">
                            <input type="text" name="fullName" id="fullName" placeholder="Enter your full name" value="<?php echo htmlspecialchars($form_full_name); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="input-with-icon">
                            <input type="email" name="email" id="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($form_email); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Current Password</label>
                        <div class="input-with-icon">
                            <input type="password" name="current_password" class="password-field" placeholder="••••••••">
                            <i class="fas fa-eye password-toggle"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Password (Optional)</label>
                        <div class="input-with-icon">
                            <input type="password" name="new_password" class="password-field" id="newPassword" placeholder="••••••••">
                            <i class="fas fa-eye password-toggle"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <div class="input-with-icon">
                            <input type="password" name="confirm_password" class="password-field" id="confirmPassword" placeholder="••••••••">
                            <i class="fas fa-eye password-toggle"></i>
                        </div>
                        <p id="passwordError" style="color: #EF4444; font-size: 0.75rem; margin-top: 5px; display: none;">Passwords do not match.</p>
                    </div>
                    <button type="submit" class="btn-update">Update Profile</button>
                </form>
            </div>

            <!-- Section B: User Management -->
            <div class="settings-card">
                <h3>User Management & Permissions</h3>
                <form class="settings-form" id="addUserForm" method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <div class="input-with-icon">
                            <input type="text" name="full_name" placeholder="Enter full name..." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Admin Email</label>
                        <div class="input-with-icon">
                            <input type="email" name="email" placeholder="email@sovereignledger.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Role Selection</label>
                        <div class="input-with-icon">
                            <select name="role" required>
                                <option value="Logistics Director">Logistics Director</option>
                                <option value="Export Admin">Export Admin</option>
                                <option value="Verified Executive">Verified Executive</option>
                                <option value="Admin Portal">Admin Portal</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Temporary Password</label>
                        <div class="input-with-icon">
                            <input type="password" name="password" class="password-field" placeholder="••••••••" required>
                            <i class="fas fa-eye password-toggle"></i>
                        </div>
                    </div>
                    <button type="submit" name="add_user" class="btn-update" style="background: var(--gold); color: var(--emerald);">Add New User</button>
                </form>

                <div class="user-management-section">
                    <h4 style="font-family: var(--font-serif); color: var(--emerald); margin-bottom: 10px;">Current Administrators</h4>
                    <div class="user-table-container">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>Administrator</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($admins as $row) {
                                    $role_badge_style = "background: #F3F4F6; color: #374151;"; 
                                    $display_role = "Admin";
                                    
                                    if (!empty($row['role'])) {
                                        $display_role = htmlspecialchars($row['role']);
                                        if ($row['role'] === 'Verified Executive') {
                                            $role_badge_style = "background: #DCFCE7; color: #166534;";
                                        } elseif ($row['role'] === 'Logistics Director') {
                                            $role_badge_style = "background: #DBEAFE; color: #1E40AF;";
                                        } elseif ($row['role'] === 'Super Admin') {
                                            $role_badge_style = "background: #FEE2E2; color: #991B1B;";
                                        } elseif ($row['role'] === 'Export Admin') {
                                            $role_badge_style = "background: #FEF3C7; color: #92400E;";
                                        } elseif ($row['role'] === 'Admin Portal') {
                                            $role_badge_style = "background: #E0E7FF; color: #3730A3;";
                                        }
                                    }
                                    
                                    $is_current_user = ($row['admin_id'] == $_SESSION['admin_id']);
                                ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);"><?php echo htmlspecialchars($row['email']); ?></div>
                                    </td>
                                    <td><span class="role-badge" style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 500; <?php echo $role_badge_style; ?>"><?php echo $display_role; ?></span></td>
                                    <td>
                                        <?php if ($is_current_user): ?>
                                            <button class="btn-revoke" disabled style="opacity: 0.5; cursor: not-allowed;">Primary</button>
                                        <?php else: ?>
                                            <!-- زرار المسح جوة Form عشان ينفذ أمر الـ PHP -->
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to revoke access for this admin?');">
                                                <input type="hidden" name="delete_user_id" value="<?php echo (int)$row['admin_id']; ?>">
                                                <button type="submit" name="delete_user" class="btn-revoke" style="cursor: pointer; border: none; background: none; color: #EF4444; font-weight: 500; font-family: inherit; font-size: inherit;">Revoke Access</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/profile.js"></script>
    <script>
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        });

        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            const newPass = document.getElementById('newPassword');
            const confirmPass = document.getElementById('confirmPassword');
            const passError = document.getElementById('passwordError');

            profileForm.addEventListener('submit', function(e) {
                if (newPass.value || confirmPass.value) {
                    if (newPass.value !== confirmPass.value) {
                        e.preventDefault();
                        passError.style.display = 'block';
                        confirmPass.style.borderColor = '#EF4444';
                        return;
                    }
                }
                passError.style.display = 'none';
                confirmPass.style.borderColor = '';
            });
        }
    </script>
</body>
</html>



