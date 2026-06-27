<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 7 – User Login & Session Creation
// password_verify() + $_SESSION demonstration
// ============================================
if (session_status() === PHP_SESSION_NONE) session_start();

// Already logged in
if (isset($_SESSION['w7_user'])) {
    header('Location: dashboard.php'); exit;
}

require_once 'config/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify password using password_verify()
            if (password_verify($password, $user['password'])) {
                // Create session variables
                $_SESSION['w7_user']  = $user['name'];
                $_SESSION['w7_email'] = $user['email'];
                $_SESSION['w7_id']    = $user['id'];
                $_SESSION['w7_role']  = $user['role'];

                header('Location: dashboard.php'); exit;
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with that email address.";
        }
    }
}
require_once 'includes/header.php';
?>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🔑</div>
    <h2>Welcome Back</h2>
    <p>Faraj Fruit Supplier – Week 7 Login</p>

    <!-- Code Demo -->
    <div class="code-box">
      // Week 7 – Session Creation Demo<br>
      if(password_verify($input, $stored_hash)){<br>
      &nbsp;&nbsp;$_SESSION['user'] = $user['name'];<br>
      &nbsp;&nbsp;header("Location: dashboard.php");<br>
      }
    </div>

    <?php if ($error): ?><div class="alert-error">❌ <?= $error ?></div><?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-amber" style="width:100%;margin-top:8px;">Login</button>
    </form>

    <p style="text-align:center;margin-top:16px;font-size:13px;color:#888;">
      Don't have an account? <a href="register.php" style="color:#1a5c2e;font-weight:bold;">Register here</a>
    </p>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
