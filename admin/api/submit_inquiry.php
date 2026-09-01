<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/../../includes/db.php';

if (!isset($conn) || !$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$company = trim($_POST['company'] ?? '');
$country = trim($_POST['country'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
    echo json_encode(['success' => false, 'message' => 'Name, email, subject, and message are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Generate avatar initials from name
$words    = explode(' ', $name);
$initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

// Pick a random avatar color
$colors = ['#4CAF50', '#2196F3', '#FF9800', '#9C27B0', '#F44336', '#00BCD4', '#FF5722'];
$color  = $colors[array_rand($colors)];

$stmt = $conn->prepare(
    "INSERT INTO inquiries (sender_name, sender_email, company_name, country_code, subject, message, avatar_initials, avatar_color, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'New')"
);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

if (!$stmt->bind_param('ssssssss', $name, $email, $company, $country, $subject, $message, $initials, $color)) {
    echo json_encode(['success' => false, 'message' => 'Binding error: ' . $stmt->error]);
    $stmt->close();
    exit;
}

if ($stmt->execute()) {
    $inquiry_id = $stmt->insert_id;
    
    // Get admin email from site_settings
    $settings_result = $conn->query("SELECT footer_email FROM site_settings LIMIT 1");
    $admin_email = ($settings_result && $settings_result->num_rows > 0) 
        ? $settings_result->fetch_assoc()['footer_email'] 
        : 'info@greenlightexport.com';
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Send notification email to admin
    $admin_subject = "[New Inquiry] " . $subject;
    $admin_body = "
    <html>
    <body style='font-family: Arial, sans-serif; color: #333;'>
        <h2>New Inquiry Received</h2>
        <p><strong>From:</strong> {$name} ({$email})</p>
        " . (!empty($company) ? "<p><strong>Company:</strong> {$company}</p>" : "") . "
        " . (!empty($country) ? "<p><strong>Country:</strong> {$country}</p>" : "") . "
        <p><strong>Subject:</strong> {$subject}</p>
        <hr>
        <h3>Message:</h3>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
        <hr>
        <p><small>Inquiry ID: #{$inquiry_id}</small></p>
    </body>
    </html>";
    
    @mail($admin_email, $admin_subject, $admin_body, $headers);
    
    // Send confirmation email to user
    $user_subject = "Thank you for your inquiry - Green Light for Export";
    $user_body = "
    <html>
    <body style='font-family: Arial, sans-serif; color: #333;'>
        <h2>Thank you, {$name}!</h2>
        <p>We have received your inquiry and will get back to you shortly.</p>
        <p><strong>Your Inquiry Details:</strong></p>
        <ul>
            <li><strong>Subject:</strong> {$subject}</li>
            <li><strong>Reference ID:</strong> #{$inquiry_id}</li>
        </ul>
        <p>Our team will review your message and respond within 24-48 hours.</p>
        <hr>
        <p style='color: #999; font-size: 12px;'>
            Green Light for Export<br>
            Premium Agricultural Exports<br>
            <a href='https://greenlightexport.com'>www.greenlightexport.com</a>
        </p>
    </body>
    </html>";
    
    $user_headers = "MIME-Version: 1.0" . "\r\n";
    $user_headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $user_headers .= "From: " . $admin_email . "\r\n";
    
    @mail($email, $user_subject, $user_body, $user_headers);
    
    echo json_encode(['success' => true, 'message' => 'Your inquiry has been sent successfully! You will receive a confirmation email shortly.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save inquiry: ' . $stmt->error]);
}

$stmt->close();
