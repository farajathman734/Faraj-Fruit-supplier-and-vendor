<?php
session_start();
require_once '../config/db.php';

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $type     = mysqli_real_escape_string($conn, $_POST['customer_type']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = 'Email already registered.';
    } else {
        mysqli_query($conn, "INSERT INTO users (name,email,phone,customer_type,password,role) VALUES ('$name','$email','$phone','$type','$password','customer')");
        $success = 'Account created successfully! <a href="login.php" style="color:#1a5c2e;font-weight:600;">Login here</a>';
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
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="../assets/js/validation.js" defer></script>
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🍊</div>
    <h2>Create Account</h2>
    <p>Join Faraj – buy fruits wholesale or retail</p>
    <?php if ($error): ?>
      <div style="background:#fdecea;color:#cc0000;padding:10px 14px;border-radius:6px;font-size:14px;margin-bottom:16px;"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div style="background:#e8f5ec;color:#1a5c2e;padding:10px 14px;border-radius:6px;font-size:14px;margin-bottom:16px;"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST" onsubmit="validateRegister(event)">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" id="name" name="name" placeholder="John Doe">
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="text" id="email" name="email" placeholder="you@example.com">
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <input type="text" id="phone" name="phone" placeholder="0712345678">
      </div>
      <div class="form-group">
        <label>Customer Type</label>
        <select id="customer_type" name="customer_type">
          <option value="retail">Retail (small quantities)</option>
          <option value="wholesale">Wholesale (bulk orders)</option>
        </select>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" id="password" name="password" placeholder="Min 8 characters">
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password">
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Create Account</button>
    </form>
    <p style="text-align:center;margin-top:20px;font-size:13px;color:#888;">
      Already have an account? <a href="login.php" style="color:#2e8b4a;font-weight:600;">Login</a>
    </p>
  </div>
</div>
</body>
</html>
