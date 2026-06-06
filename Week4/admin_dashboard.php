<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 4 – Admin Dashboard
// Role-Based Access Control
// ============================================

session_start();
require_once 'config/db.php';

// Role-based access – only admin allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); exit;
}

$products_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products"));
$users_count    = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$orders_count   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders"));
$revenue        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as rev FROM orders WHERE status != 'cancelled'"))['rev'] ?? 0;
$recent_orders  = mysqli_query($conn, "SELECT o.*, u.name as customer FROM orders o LEFT JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 5");
$colors         = ['pending'=>'#f0a500','processing'=>'#2196f3','shipped'=>'#9c27b0','delivered'=>'#2e8b4a','cancelled'=>'#cc0000'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Dashboard – Faraj</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>

<header>
  <div class="logo">🍊 Faraj Admin Panel</div>
  <nav>
    <a href="/faraj/index.php">View Site</a>
    <a href="logout.php" style="color:rgba(255,255,255,0.6);">Logout</a>
  </nav>
</header>

<div class="admin-layout">
  <aside class="sidebar">
    <div class="menu-title">Main Menu</div>
    <a href="admin_dashboard.php" class="active">📊 Dashboard</a>
    <a href="/faraj/pages/admin_products.php">🍊 Products</a>
    <a href="/faraj/pages/admin_orders.php">📦 Orders</a>
    <a href="/faraj/pages/admin_users.php">👥 Customers</a>
    <a href="/faraj/pages/admin_messages.php">✉️ Messages</a>
    <a href="/faraj/pages/admin_reports.php">📈 Reports</a>
    <div class="menu-title" style="margin-top:20px;">Settings</div>
    <a href="logout.php">🚪 Logout</a>
  </aside>

  <main class="admin-content">
    <h2 style="font-family:'Playfair Display',serif;color:#1a5c2e;margin-bottom:8px;">Dashboard Overview</h2>
    <p style="color:#888;margin-bottom:24px;">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>. You are logged in as <strong>Admin</strong>.</p>

    <!-- Stat Cards -->
    <div class="stat-cards">
      <?php foreach([
        ['🍊','Total Products',$products_count,'In inventory'],
        ['👥','Registered Users',$users_count,'Retail & wholesale'],
        ['📦','Total Orders',$orders_count,'All time'],
        ['💰','Revenue','KES '.number_format($revenue,2),'From all orders'],
      ] as $s): ?>
      <div class="stat-card">
        <div style="font-size:28px;margin-bottom:8px;"><?= $s[0] ?></div>
        <div class="stat-label"><?= $s[1] ?></div>
        <div class="stat-value" style="font-size:20px;"><?= $s[2] ?></div>
        <div class="stat-sub"><?= $s[3] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Quick Actions -->
    <div style="display:flex;gap:12px;margin-bottom:28px;flex-wrap:wrap;">
      <a href="/faraj/pages/admin_products.php?action=add" class="btn btn-primary btn-sm">+ Add Product</a>
      <a href="/faraj/pages/admin_orders.php" class="btn btn-secondary btn-sm">View Orders</a>
      <a href="/faraj/pages/admin_messages.php" class="btn btn-secondary btn-sm">View Messages</a>
    </div>

    <!-- Recent Orders Table -->
    <h3 style="color:#1a5c2e;margin-bottom:16px;">Recent Orders</h3>
    <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.08);overflow:hidden;border:1px solid #e0e0e0;">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:#1a5c2e;color:white;">
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Order ID</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Customer</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Total</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Status</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($recent_orders && mysqli_num_rows($recent_orders) > 0):
            while ($row = mysqli_fetch_assoc($recent_orders)): ?>
          <tr style="border-bottom:1px solid #f0f0f0;">
            <td style="padding:12px 16px;font-weight:700;">#<?= $row['id'] ?></td>
            <td style="padding:12px 16px;font-size:13px;"><?= htmlspecialchars($row['customer'] ?? 'Guest') ?></td>
            <td style="padding:12px 16px;font-size:14px;">KES <?= number_format($row['total'],2) ?></td>
            <td style="padding:12px 16px;">
              <span style="background:<?= $colors[$row['status']] ?>;color:white;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;"><?= ucfirst($row['status']) ?></span>
            </td>
            <td style="padding:12px 16px;font-size:12px;color:#888;"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="5" style="padding:20px;text-align:center;color:#888;">No orders yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>
</body>
</html>
