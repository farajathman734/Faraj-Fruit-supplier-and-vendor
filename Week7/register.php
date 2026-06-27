<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 7 – User Registration
// password_hash() demonstration
// ============================================
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $raw_pass = $_POST['password'];

    // Validation
    if (empty($name) || empty($email) || empty($raw_pass)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($raw_pass) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Check duplicate email
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "This email is already registered.";
        } else {
            // Hash the password
            $hashed = password_hash($raw_pass, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password, role)
                    VALUES ('$name', '$email', '$hashed', 'customer')";

            if (mysqli_query($conn, $sql)) {
                $success = "✅ Account created successfully! <a href='login.php' style='color:#1a5c2e;font-weight:bold;'>Login here →</a>";
            } else {
                $error = "❌ " . mysqli_error($conn);
            }
        }
    }
}
require_once 'includes/header.php';
?>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🍊</div>
    <h2>Create Account</h2>
    <p>Faraj Fruit Supplier – Week 7 Registration</p>

    <!-- Code Demo -->
    <div class="code-box">
      // Week 7 – password_hash() Demo<br>
      $hashed = password_hash($password, PASSWORD_DEFAULT);<br>
      // Stores: $2y$10$... (bcrypt)
    </div>

    <?php if ($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert-error">❌ <?= $error ?></div><?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Full Name *</label>
        <input type="text" name="fullname" placeholder="e.g. John Doe" required value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Email Address *</label>
        <input type="email" name="email" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password * (min 8 characters)</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-green" style="width:100%;margin-top:8px;">Register</button>
    </form>

    <p style="text-align:center;margin-top:16px;font-size:13px;color:#888;">
      Already have an account? <a href="login.php" style="color:#1a5c2e;font-weight:bold;">Login here</a>
    </p>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
