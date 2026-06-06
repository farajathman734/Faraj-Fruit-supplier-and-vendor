<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 4 – Task 2: Registration Form
// HTML Forms + PHP Integration
// ============================================

session_start();
require_once 'config/db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // GET form data (POST method)
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $type     = mysqli_real_escape_string($conn, $_POST['customer_type']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Server-side validation
    if (empty($name) || empty($email) || empty($_POST['password'])) {
        $error = 'All required fields must be filled in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($_POST['password']) < 8) {
        $error = 'Password must be at least 8 characters.';
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'This email is already registered. Please login.';
        } else {
            // INSERT into users table
            $query = "INSERT INTO users (name, email, phone, customer_type, password, role)
                      VALUES ('$name', '$email', '$phone', '$type', '$password', 'customer')";
            if (mysqli_query($conn, $query)) {
                $success = 'Account created successfully! <a href="login.php" style="color:#1a5c2e;font-weight:700;">Click here to login</a>';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Register – Faraj Fruit Supplier</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
  <script src="/faraj/assets/js/validation.js" defer></script>
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🍊</div>
    <h2>Create Account</h2>
    <p>Join Faraj – fresh fruits wholesale & retail</p>

    <?php if ($error): ?>
      <div style="background:#fdecea;color:#cc0000;padding:12px 16px;border-radius:8px;font-size:14px;margin-bottom:16px;">❌ <?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div style="background:#e8f5ec;color:#1a5c2e;padding:12px 16px;border-radius:8px;font-size:14px;margin-bottom:16px;">✅ <?= $success ?></div>
    <?php endif; ?>

    <!-- form method POST – Week 4 Task 2 -->
    <form method="POST" onsubmit="validateRegister(event)">
      <div class="form-group">
        <label>Full Name <span style="color:red;">*</span></label>
        <input type="text" id="name" name="name" placeholder="John Doe" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Email Address <span style="color:red;">*</span></label>
        <input type="text" id="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <input type="text" id="phone" name="phone" placeholder="0712345678" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Customer Type</label>
        <select id="customer_type" name="customer_type">
          <option value="retail">Retail (small quantities)</option>
          <option value="wholesale">Wholesale (bulk orders)</option>
        </select>
      </div>
      <div class="form-group">
        <label>Password <span style="color:red;">*</span></label>
        <input type="password" id="password" name="password" placeholder="Min 8 characters">
      </div>
      <div class="form-group">
        <label>Confirm Password <span style="color:red;">*</span></label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password">
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Create Account</button>
    </form>

    <p style="text-align:center;margin-top:20px;font-size:13px;color:#888;">
      Already have an account? <a href="login.php" style="color:#2e8b4a;font-weight:600;">Login here</a>
    </p>
  </div>
</div>
</body>
</html>
