<aside class="sidebar">

    <!-- ”· Branding -->
    <div class="sidebar-branding">
        <div class="logo-container">
            <h2>Green Pyramids</h2>
        </div>
        <div class="branding-text">
            <h1>Green Pyramids</h1>
            <p>Admin Dashboard</p>
        </div>
    </div>

    <!-- ”· Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav-list">

            <li class="nav-item">
                <a href="admin_overview.php" class="nav-link">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="product_management.php" class="nav-link">
                    <i class="fas fa-box"></i>
                    <span>Product Management</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="content_editor.php" class="nav-link">
                    <i class="fas fa-edit"></i>
                    <span>Content Editor</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="contact_settings.php" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Contact Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="reviews.php" class="nav-link">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="inquiry_inbox.php" class="nav-link">
                    <i class="fas fa-inbox"></i>
                    <span>Inquiry Inbox</span>
                </a>
            </li>

            <!-- âœ… NEW: Admin Settings -->
            <li class="nav-item">
                <a href="admin_settings.php" class="nav-link">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="api/logout.php" class="nav-link" style="color:red;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>
    </nav>

    <!-- ”· Footer / Profile -->
    <div class="sidebar-footer">
        <div class="user-profile">

            <div class="user-avatar">
                <!-- Default Avatar -->
                <img src="<?php echo htmlspecialchars(asset_url($_SESSION['avatar_url'] ?? '', 'assets/user.png')); ?>" alt="Admin Avatar" class="avatar-img">
            </div>

            <div class="user-info">
                <h4 id="adminName"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin User'); ?></h4>
                <p id="adminRole">Administrator</p>
            </div>

        </div>
    </div>

</aside>



