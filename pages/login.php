<?php
session_start();
require_once '../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $result   = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['role']      = $row['role'];
            header('Location: ' . ($row['role'] === 'admin' ? 'admin.php' : '../index.php'));
            exit;
        }
    }
    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Login – Faraj Fruit Supplier</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="../assets/js/validation.js" defer></script>
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🍊</div>
    <h2>Welcome Back</h2>
    <p>Login to your Faraj account</p>
    <?php if ($error): ?>
      <div style="background:#fdecea;color:#cc0000;padding:10px 14px;border-radius:6px;font-size:14px;margin-bottom:16px;"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" onsubmit="validateLogin(event)">
      <div class="form-group">
        <label>Email Address</label>
        <input type="text" id="email" name="email" placeholder="you@example.com">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Login</button>
    </form>
    <p style="text-align:center;margin-top:20px;font-size:13px;color:#888;">
      Don't have an account? <a href="register.php" style="color:#2e8b4a;font-weight:600;">Register</a>
    </p>
  </div>
</div>
</body>
</html>
