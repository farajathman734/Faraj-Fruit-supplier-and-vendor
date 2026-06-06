<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 4 – Task 3: Session-Based Welcome Page
// ============================================

session_start();
require_once 'config/db.php';

// Protect page – check session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}

$user_id     = $_SESSION['user_id'];
$user_name   = $_SESSION['user_name'];
$user_email  = $_SESSION['user_email'];
$role        = $_SESSION['role'];

// Fetch user orders count
$order_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders WHERE user_id=$user_id"));

// Fetch recent orders
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC LIMIT 3");
$colors = ['pending'=>'#f0a500','processing'=>'#2196f3','shipped'=>'#9c27b0','delivered'=>'#2e8b4a','cancelled'=>'#cc0000'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Dashboard – Faraj</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>

<header>
  <div class="logo">🍊 Faraj Fruit Supplier</div>
  <nav>
    <a href="/faraj/index.php">Home</a>
    <a href="/faraj/pages/products.php">Products</a>
    <a href="/faraj/pages/cart.php">🛒 Cart</a>
    <a href="logout.php" style="color:rgba(255,255,255,0.6);">Logout</a>
  </nav>
</header>

<!-- Welcome Banner -->
<div style="background:linear-gradient(135deg,#1a5c2e,#2e8b4a);color:white;padding:50px 40px;text-align:center;">
  <div style="font-size:48px;margin-bottom:12px;">👋</div>
  <h1 style="font-family:'Playfair Display',serif;font-size:32px;margin-bottom:8px;">
    Welcome back, <?= htmlspecialchars($user_name) ?>!
  </h1>
  <p style="opacity:0.85;font-size:16px;"><?= htmlspecialchars($user_email) ?> · <?= ucfirst($role) ?> Account</p>
  <p style="opacity:0.7;font-size:13px;margin-top:8px;">Session Active – Logged in successfully via PHP $_SESSION</p>
</div>

<div class="section" style="max-width:900px;">

  <!-- Session Info Box (Week 4 Learning Demo) -->
  <div style="background:#1a1a2e;border-radius:10px;padding:20px 24px;margin-bottom:28px;">
    <p style="color:#f0a500;font-size:12px;font-family:monospace;margin-bottom:8px;">// Week 4 – Task 3: Active Session Variables</p>
    <p style="color:#a8e6bf;font-size:13px;font-family:monospace;line-height:2;">
      $_SESSION['user_id']    = <?= $user_id ?><br>
      $_SESSION['user_name']  = "<?= htmlspecialchars($user_name) ?>"<br>
      $_SESSION['user_email'] = "<?= htmlspecialchars($user_email) ?>"<br>
      $_SESSION['role']       = "<?= $role ?>"
    </p>
  </div>

  <!-- Stats -->
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;">
    <?php foreach([
      ['📦', $order_count, 'Total Orders'],
      ['🛒', ucfirst($role), 'Account Type'],
      ['✅', 'Active', 'Session Status'],
    ] as $s): ?>
    <div style="background:white;border-radius:12px;padding:20px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
      <div style="font-size:28px;margin-bottom:8px;"><?= $s[0] ?></div>
      <div style="font-size:20px;font-weight:700;color:#1a5c2e;"><?= $s[1] ?></div>
      <div style="font-size:12px;color:#888;margin-top:4px;"><?= $s[2] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Quick Actions -->
  <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:28px;">
    <a href="/faraj/pages/products.php" class="btn btn-primary">🍊 Browse Products</a>
    <a href="/faraj/pages/cart.php" class="btn btn-secondary">🛒 View Cart</a>
    <a href="/faraj/pages/profile.php" class="btn" style="border:2px solid #1a5c2e;color:#1a5c2e;">👤 My Profile</a>
    <a href="/faraj/pages/order_history.php" class="btn" style="border:2px solid #1a5c2e;color:#1a5c2e;">📦 Order History</a>
  </div>

  <!-- Recent Orders -->
  <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
    <h3 style="color:#1a5c2e;margin-bottom:16px;font-size:16px;">📦 Recent Orders</h3>
    <?php if ($orders && mysqli_num_rows($orders) > 0):
      while ($row = mysqli_fetch_assoc($orders)): ?>
    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f0f0f0;">
      <div>
        <span style="font-weight:600;font-size:14px;">Order #<?= $row['id'] ?></span>
        <span style="color:#888;font-size:12px;margin-left:10px;"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
      </div>
      <div style="display:flex;gap:10px;align-items:center;">
        <span style="font-weight:600;">KES <?= number_format($row['total'],2) ?></span>
        <span style="background:<?= $colors[$row['status']] ?>;color:white;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;"><?= ucfirst($row['status']) ?></span>
      </div>
    </div>
    <?php endwhile; else: ?>
    <p style="color:#888;text-align:center;padding:20px 0;">No orders yet. <a href="/faraj/pages/products.php" style="color:#2e8b4a;">Start shopping →</a></p>
    <?php endif; ?>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
