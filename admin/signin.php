<?php
session_start();
require '../includes/db.php';

$error = "";

// --- 1. CREATE TEMP ADMIN WITH HASH (AUTO INSERT ONCE) ---
$temp_email = "medo@gmail.com";
$check_stmt = $conn->prepare("SELECT admin_id FROM admins WHERE email = ?");
if ($check_stmt) {
    $check_stmt->bind_param("s", $temp_email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($check_result->num_rows === 0) {
        $temp_password = password_hash("123456", PASSWORD_DEFAULT);
        $temp_name = "Medo";
        $insert_stmt = $conn->prepare("INSERT INTO admins (email, password_hash, full_name) VALUES (?, ?, ?)");
        if ($insert_stmt) {
            $insert_stmt->bind_param("sss", $temp_email, $temp_password, $temp_name);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
    }
    $check_stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($admin = $result->fetch_assoc()) {
            if (password_verify($password, $admin["password_hash"])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['full_name'] = $admin['full_name'];
                $_SESSION['avatar_url'] = $admin['avatar_url'];
                $_SESSION["email"] = $admin["email"] ?? "";
                
                header("Location: admin_overview.php");
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In | Green Pyramids</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/auth.css">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
</head>

<body>

<div class="auth-card">

    <!-- Logo -->
    <div class="auth-logo">
        <h2>Green Pyramids</h2>
        <h1>Green Pyramids</h1>
    </div>

    <div class="auth-header">
        <h2>Welcome Back</h2>
        <p>Access the admin dashboard to manage your platform.</p>
    </div>

    <!-- �� هنا التعديل المهم -->
    <?php if(!empty($error)): ?>
        <div style="color: red; text-align: center; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="signin.php">

        <!-- Email -->
        <div class="form-group">
            <label>Email Address</label>
            <div class="input-wrapper">
                <i class="fas fa-envelope"></i>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="admin@greenpyramids.eg" 
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required>
            </div>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="••••••••" 
                    value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>"
                    required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
        </div>

        <!-- Remember -->
        <div class="form-options">
            <label class="remember-me">
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </div>

        <!-- Button -->
        <button type="submit" class="btn-auth">Sign In</button>

    </form>

</div>

<!-- اختياري: بس للـ eye toggle -->
<script>
const toggle = document.getElementById("togglePassword");
const password = document.querySelector("input[name='password']");

toggle.addEventListener("click", () => {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    toggle.classList.toggle("fa-eye-slash");
});
</script>

</body>
</html>




