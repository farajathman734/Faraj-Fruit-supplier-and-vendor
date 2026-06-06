<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}

require_once '../includes/header.php';
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$success = $error = '';

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $type  = mysqli_real_escape_string($conn, $_POST['customer_type']);
    mysqli_query($conn, "UPDATE users SET name='$name', phone='$phone', customer_type='$type' WHERE id=$user_id");
    $_SESSION['user_name'] = $name;
    $success = 'Profile updated successfully.';
}

// Update password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $row     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM users WHERE id=$user_id"));
    if (!password_verify($current, $row['password'])) {
        $error = 'Current password is incorrect.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id=$user_id");
        $success = 'Password changed successfully.';
    }
}

$user        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id"));
$orders      = mysqli_query($conn, "SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC LIMIT 5");
$order_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders WHERE user_id=$user_id"));
$colors      = ['pending'=>'#f0a500','processing'=>'#2196f3','shipped'=>'#9c27b0','delivered'=>'#2e8b4a','cancelled'=>'#cc0000'];
?>

<div style="background:var(--green-dark);color:white;padding:50px 40px;text-align:center;">
  <div style="font-size:56px;margin-bottom:12px;">👤</div>
  <h1 style="font-family:'Playfair Display',serif;font-size:32px;margin-bottom:6px;"><?= htmlspecialchars($user['name']) ?></h1>
  <p style="opacity:0.8;"><?= htmlspecialchars($user['email']) ?> · <?= ucfirst($user['customer_type']) ?> Customer</p>
</div>

<div class="section" style="max-width:900px;">

  <?php if ($success): ?>
    <div style="background:#e8f5ec;border-left:5px solid var(--green-mid);padding:14px 18px;border-radius:var(--radius);margin-bottom:20px;color:var(--green-dark);font-weight:600;">✅ <?= $success ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div style="background:#fdecea;border-left:5px solid #cc0000;padding:14px 18px;border-radius:var(--radius);margin-bottom:20px;color:#cc0000;">❌ <?= $error ?></div>
  <?php endif; ?>

  <!-- Stats -->
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px;">
    <?php foreach([
      ['📦', $order_count, 'Total Orders'],
      ['🛒', ucfirst($user['customer_type']), 'Account Type'],
      ['📅', date('M Y', strtotime($user['created_at'])), 'Member Since'],
    ] as $s): ?>
    <div style="background:white;border-radius:var(--radius-lg);padding:20px;text-align:center;box-shadow:var(--shadow);border:1px solid var(--border);">
      <div style="font-size:28px;margin-bottom:8px;"><?= $s[0] ?></div>
      <div style="font-size:20px;font-weight:700;color:var(--green-dark);"><?= $s[1] ?></div>
      <div style="font-size:12px;color:#888;margin-top:4px;"><?= $s[2] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    <!-- Update Profile -->
    <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
      <h3 style="color:var(--green-dark);margin-bottom:18px;font-size:16px;">✏️ Update Profile</h3>
      <form method="POST">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background:#f5f5f5;color:#888;">
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Account Type</label>
          <select name="customer_type">
            <option value="retail" <?= $user['customer_type']==='retail'?'selected':'' ?>>Retail</option>
            <option value="wholesale" <?= $user['customer_type']==='wholesale'?'selected':'' ?>>Wholesale</option>
          </select>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary" style="width:100%;">Save Changes</button>
      </form>
    </div>

    <!-- Change Password -->
    <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
      <h3 style="color:var(--green-dark);margin-bottom:18px;font-size:16px;">🔒 Change Password</h3>
      <form method="POST">
        <div class="form-group">
          <label>Current Password</label>
          <input type="password" name="current_password" placeholder="••••••••" required>
        </div>
        <div class="form-group">
          <label>New Password</label>
          <input type="password" name="new_password" placeholder="Min 8 characters" required>
        </div>
        <div class="form-group">
          <label>Confirm New Password</label>
          <input type="password" name="confirm_password" placeholder="Repeat new password" required>
        </div>
        <button type="submit" name="update_password" class="btn btn-secondary" style="width:100%;margin-top:8px;">Change Password</button>
      </form>
    </div>

  </div>

  <!-- Recent Orders -->
  <div style="margin-top:28px;background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <h3 style="color:var(--green-dark);font-size:16px;">📦 Recent Orders</h3>
      <a href="order_history.php" style="color:var(--green-mid);font-size:13px;font-weight:600;">View All →</a>
    </div>
    <?php if ($orders && mysqli_num_rows($orders) > 0):
      while ($row = mysqli_fetch_assoc($orders)): ?>
    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);">
      <div>
        <span style="font-weight:600;font-size:14px;">Order #<?= $row['id'] ?></span>
        <span style="color:#888;font-size:12px;margin-left:10px;"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
      </div>
      <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-weight:600;font-size:14px;">KES <?= number_format($row['total'],2) ?></span>
        <span style="background:<?= $colors[$row['status']] ?>;color:white;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;"><?= ucfirst($row['status']) ?></span>
      </div>
    </div>
    <?php endwhile; else: ?>
    <p style="color:#888;text-align:center;padding:20px 0;">No orders yet. <a href="products.php" style="color:var(--green-mid);">Start shopping</a></p>
    <?php endif; ?>
  </div>

</div>

<?php require_once '../includes/footer.php'; ?>