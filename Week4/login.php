<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 4 – Task 3: Login with Sessions
// Simple Authentication System
// ============================================

session_start();
require_once 'config/db.php';

// If already logged in redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Query DB for user
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify password using password_verify()
            if (password_verify($password, $user['password'])) {
                // Create session variables
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email']= $user['email'];
                $_SESSION['role']      = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
            } else {
                $error = 'Incorrect password. Please try again.';
            }
        } else {
            $error = 'No account found with that email address.';
        }
    }
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
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
  <script src="/faraj/assets/js/validation.js" defer></script>
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🍊</div>
    <h2>Welcome Back</h2>
    <p>Login to your Faraj account</p>

    <?php if ($error): ?>
      <div style="background:#fdecea;color:#cc0000;padding:12px 16px;border-radius:8px;font-size:14px;margin-bottom:16px;">❌ <?= $error ?></div>
    <?php endif; ?>

    <!-- form method POST – Week 4 Task 3 -->
    <form method="POST" onsubmit="validateLogin(event)">
      <div class="form-group">
        <label>Email Address</label>
        <input type="text" id="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Login</button>
    </form>

    <p style="text-align:center;margin-top:20px;font-size:13px;color:#888;">
      Don't have an account? <a href="register.php" style="color:#2e8b4a;font-weight:600;">Register here</a>
    </p>
  </div>
</div>
</body>
</html>
