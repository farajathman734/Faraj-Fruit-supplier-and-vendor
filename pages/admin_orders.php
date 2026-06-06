<?php
session_start();
require_once '../config/db.php';

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $id     = (int)$_POST['order_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$id");
    header('Location: admin_orders.php?msg=updated'); exit;
}

$filter  = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$where   = $filter ? "WHERE o.status='$filter'" : '';
$orders  = mysqli_query($conn, "SELECT o.*, u.name as customer, u.phone as customer_phone FROM orders o LEFT JOIN users u ON o.user_id=u.id $where ORDER BY o.created_at DESC");
$colors  = ['pending'=>'#f0a500','processing'=>'#2196f3','shipped'=>'#9c27b0','delivered'=>'#2e8b4a','cancelled'=>'#cc0000'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Orders – Faraj Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
  <div class="logo">🍊 Faraj Admin</div>
  <nav><a href="../index.php">View Site</a><a href="logout.php" style="color:rgba(255,255,255,0.6);">Logout</a></nav>
</header>

<div class="admin-layout">
  <aside class="sidebar">
    <div class="menu-title">Main Menu</div>
    <a href="admin.php">📊 Dashboard</a>
    <a href="admin_products.php">🍊 Products</a>
    <a href="admin_orders.php" class="active">📦 Orders</a>
    <a href="admin_users.php">👥 Customers</a>
    <a href="admin_messages.php">✉️ Messages</a>
    <div class="menu-title" style="margin-top:20px;">Settings</div>
    <a href="logout.php">🚪 Logout</a>
  </aside>

  <main class="admin-content">
    <h2 style="font-family:'Playfair Display',serif;color:var(--green-dark);margin-bottom:24px;">📦 Manage Orders</h2>

    <!-- Filter Tabs -->
    <div style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;">
      <?php foreach([''=>'All','pending'=>'Pending','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'] as $val=>$label): ?>
      <a href="?status=<?= $val ?>" class="btn btn-sm <?= $filter===$val ? 'btn-secondary' : '' ?>" style="<?= $filter!==$val ? 'color:var(--green-dark);border:2px solid var(--green-dark);' : '' ?>"><?= $label ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (isset($_GET['msg'])): ?>
      <div style="background:#e8f5ec;color:var(--green-dark);padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;font-weight:600;">✅ Order status updated.</div>
    <?php endif; ?>

    <div style="background:white;border-radius:var(--radius-lg);box-shadow:var(--shadow);overflow:hidden;border:1px solid var(--border);">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--green-dark);color:white;">
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Order ID</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Customer</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Total (KES)</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Type</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Status</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Date</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;">Update</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders && mysqli_num_rows($orders) > 0):
            while ($row = mysqli_fetch_assoc($orders)): ?>
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:12px 16px;font-weight:700;font-size:14px;">#<?= $row['id'] ?></td>
            <td style="padding:12px 16px;font-size:13px;">
              <div><?= htmlspecialchars($row['customer'] ?? 'Guest') ?></div>
              <div style="color:#888;font-size:12px;"><?= htmlspecialchars($row['customer_phone'] ?? '') ?></div>
            </td>
            <td style="padding:12px 16px;font-size:14px;font-weight:600;"><?= number_format($row['total'],2) ?></td>
            <td style="padding:12px 16px;font-size:13px;"><?= ucfirst($row['order_type']) ?></td>
            <td style="padding:12px 16px;">
              <span style="background:<?= $colors[$row['status']] ?>;color:white;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;"><?= ucfirst($row['status']) ?></span>
            </td>
            <td style="padding:12px 16px;font-size:12px;color:#888;"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            <td style="padding:12px 16px;">
              <form method="POST" style="display:flex;gap:6px;align-items:center;">
                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                <select name="status" style="font-size:12px;padding:4px 8px;border:1px solid var(--border);border-radius:4px;">
                  <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                  <option value="<?= $s ?>" <?= $row['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Save</button>
              </form>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="7" style="padding:20px;text-align:center;color:#888;">No orders found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
