<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 5 – Task 2: Password Hashing Demo
// password_hash() & password_verify()
// ============================================

session_start();

$hash_result   = '';
$verify_result = '';
$verify_color  = '';
$plain         = '';
$hashed        = '';

// Hash a password
if (isset($_POST['action']) && $_POST['action'] === 'hash') {
    $plain  = $_POST['plain_password'];
    $hashed = password_hash($plain, PASSWORD_DEFAULT);
    $hash_result = $hashed;
}

// Verify a password
if (isset($_POST['action']) && $_POST['action'] === 'verify') {
    $plain        = $_POST['verify_plain'];
    $hashed       = $_POST['verify_hash'];
    $is_valid     = password_verify($plain, $hashed);
    $verify_result = $is_valid ? '✅ Password MATCHES the hash!' : '❌ Password does NOT match the hash.';
    $verify_color  = $is_valid ? '#2e8b4a' : '#cc0000';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Task 2: Password Hashing – Faraj Week 5</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div style="background:#1a5c2e;color:white;padding:40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:32px;margin-bottom:8px;">Week 5 – Task 2</h1>
  <p style="opacity:0.85;">Password Hashing with password_hash() & password_verify()</p>
</div>

<div class="section" style="max-width:800px;">

  <!-- Code Demo Box -->
  <div style="background:#1a1a2e;border-radius:10px;padding:20px 24px;margin-bottom:24px;">
    <p style="color:#f0a500;font-size:12px;font-family:monospace;margin-bottom:10px;">// Week 5 – Password Security</p>
    <p style="color:#a8e6bf;font-size:13px;font-family:monospace;line-height:2;">
      // Hashing (used in register.php)<br>
      $hash = password_hash($password, PASSWORD_DEFAULT);<br>
      // Stores: $2y$10$... (bcrypt – never same twice)<br><br>
      // Verifying (used in login.php)<br>
      $valid = password_verify($input_password, $stored_hash);<br>
      // Returns: true or false
    </p>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

    <!-- Hash Generator -->
    <div style="background:white;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
      <h3 style="color:#1a5c2e;margin-bottom:16px;font-size:16px;">🔐 Step 1: Hash a Password</h3>
      <form method="POST">
        <input type="hidden" name="action" value="hash">
        <div class="form-group">
          <label>Enter Plain Password</label>
          <input type="text" name="plain_password" placeholder="e.g. mypassword123" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Generate Hash</button>
      </form>
      <?php if ($hash_result): ?>
      <div style="margin-top:16px;background:#e8f5ec;border-radius:8px;padding:14px;">
        <p style="font-size:12px;color:#1a5c2e;font-weight:700;margin-bottom:6px;">Generated Hash:</p>
        <code style="font-size:11px;color:#1a5c2e;word-break:break-all;"><?= htmlspecialchars($hash_result) ?></code>
        <p style="font-size:11px;color:#888;margin-top:8px;">Copy this hash and use it in Step 2 to verify</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- Verify Password -->
    <div style="background:white;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
      <h3 style="color:#1a5c2e;margin-bottom:16px;font-size:16px;">✅ Step 2: Verify Password</h3>
      <form method="POST">
        <input type="hidden" name="action" value="verify">
        <div class="form-group">
          <label>Plain Password</label>
          <input type="text" name="verify_plain" placeholder="e.g. mypassword123" required>
        </div>
        <div class="form-group">
          <label>Paste Hash Here</label>
          <textarea name="verify_hash" rows="3" placeholder="Paste the hash from Step 1..." style="width:100%;padding:10px;border:1px solid #e0e0e0;border-radius:8px;font-size:12px;font-family:monospace;"></textarea>
        </div>
        <button type="submit" class="btn btn-secondary" style="width:100%;">Verify Password</button>
      </form>
      <?php if ($verify_result): ?>
      <div style="margin-top:16px;background:<?= $verify_color === '#2e8b4a' ? '#e8f5ec' : '#fdecea' ?>;border-radius:8px;padding:14px;text-align:center;">
        <p style="font-weight:700;color:<?= $verify_color ?>;font-size:15px;"><?= $verify_result ?></p>
      </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- How it works in Faraj -->
  <div style="background:#f2f9f4;border-radius:14px;padding:24px;margin-top:24px;border:1px solid #2e8b4a;">
    <h3 style="color:#1a5c2e;margin-bottom:14px;font-size:15px;">How This is Used in Faraj System</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:13px;">
      <div>
        <p style="font-weight:700;color:#1a5c2e;margin-bottom:6px;">📝 register.php</p>
        <code style="background:#1a1a2e;color:#a8e6bf;padding:10px;border-radius:6px;display:block;font-size:11px;line-height:1.8;">
          $password = password_hash(<br>
          &nbsp;&nbsp;$_POST['password'],<br>
          &nbsp;&nbsp;PASSWORD_DEFAULT<br>
          );<br>
          // Save $password to DB
        </code>
      </div>
      <div>
        <p style="font-weight:700;color:#1a5c2e;margin-bottom:6px;">🔑 login.php</p>
        <code style="background:#1a1a2e;color:#a8e6bf;padding:10px;border-radius:6px;display:block;font-size:11px;line-height:1.8;">
          $valid = password_verify(<br>
          &nbsp;&nbsp;$_POST['password'],<br>
          &nbsp;&nbsp;$user['password']<br>
          );<br>
          // true = login success
        </code>
      </div>
    </div>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
