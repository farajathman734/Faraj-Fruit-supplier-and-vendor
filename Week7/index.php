<?php
require_once 'includes/header.php';
require_once 'config/db.php';
?>

<div class="container">

  <div class="code-box">
    // BIT3208 – Week 7: User Authentication and Session Management<br>
    // Project: Faraj Fruit Supplier and Vendor<br>
    // Key Concepts: Registration, Login, Password Hashing, Sessions, Logout
  </div>

  <h2>🔐 Week 7 – Authentication & Session Management</h2>

  <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px;">

    <a href="register.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #1a5c2e;">
      <div style="font-size:40px;margin-bottom:10px;">📝</div>
      <h3>User Registration</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">Create a new account with hashed password</p>
      <div class="code-box" style="margin-top:14px;text-align:left;">
        password_hash($password, PASSWORD_DEFAULT);<br>
        INSERT INTO users (name, email, password)
      </div>
    </a>

    <a href="login.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #f0a500;">
      <div style="font-size:40px;margin-bottom:10px;">🔑</div>
      <h3>User Login</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">Authenticate and create a session</p>
      <div class="code-box" style="margin-top:14px;text-align:left;">
        password_verify($input, $hash);<br>
        $_SESSION['user'] = $name;
      </div>
    </a>

    <a href="dashboard.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #2196f3;">
      <div style="font-size:40px;margin-bottom:10px;">🏠</div>
      <h3>Protected Dashboard</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">Only accessible when logged in</p>
      <div class="code-box" style="margin-top:14px;text-align:left;">
        if(!isset($_SESSION['w7_user'])){<br>
        &nbsp;&nbsp;header("Location: login.php");<br>
        }
      </div>
    </a>

    <a href="logout.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #cc0000;">
      <div style="font-size:40px;margin-bottom:10px;">🚪</div>
      <h3>Logout</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">Destroy session and redirect</p>
      <div class="code-box" style="margin-top:14px;text-align:left;">
        session_start();<br>
        session_destroy();<br>
        header("Location: login.php");
      </div>
    </a>

  </div>

  <!-- Current Session Status -->
  <div class="card">
    <h3>🔍 Current Session Status</h3>
    <?php if (isset($_SESSION['w7_user'])): ?>
    <div style="background:#e8f5ec;border-radius:6px;padding:16px;margin-top:12px;">
      <p style="color:#1a5c2e;font-weight:bold;margin-bottom:10px;">✅ You are logged in</p>
      <div class="code-box" style="margin:0;">
        $_SESSION['w7_user'] = "<?= htmlspecialchars($_SESSION['w7_user']) ?>"<br>
        $_SESSION['w7_email'] = "<?= htmlspecialchars($_SESSION['w7_email'] ?? '') ?>"<br>
        $_SESSION['w7_role'] = "<?= htmlspecialchars($_SESSION['w7_role'] ?? 'customer') ?>"
      </div>
    </div>
    <?php else: ?>
    <div style="background:#fdecea;border-radius:6px;padding:16px;margin-top:12px;">
      <p style="color:#cc0000;font-weight:bold;">❌ No active session – you are not logged in</p>
      <p style="font-size:13px;color:#888;margin-top:6px;">
        <a href="register.php" style="color:#1a5c2e;font-weight:bold;">Register</a> or
        <a href="login.php" style="color:#1a5c2e;font-weight:bold;">Login</a> to start a session.
      </p>
    </div>
    <?php endif; ?>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
