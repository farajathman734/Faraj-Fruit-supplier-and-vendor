<?php
session_start();
require_once '../config/db.php';

// ── Summary Stats ──
$total_revenue  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as rev FROM orders WHERE status != 'cancelled'"))['rev'] ?? 0;
$total_orders   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders"));
$total_customers= mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='customer'"));
$total_products = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products"));

// ── Monthly Revenue (last 6 months) ──
$monthly = mysqli_query($conn, "SELECT DATE_FORMAT(created_at,'%b %Y') as month, SUM(total) as revenue, COUNT(*) as orders FROM orders WHERE status != 'cancelled' AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY DATE_FORMAT(created_at,'%Y-%m') ORDER BY created_at ASC");

// ── Top Selling Products ──
$top_products = mysqli_query($conn, "SELECT p.name, p.category, SUM(oi.quantity) as total_sold, SUM(oi.subtotal) as revenue FROM order_items oi JOIN products p ON oi.product_id=p.id GROUP BY oi.product_id ORDER BY total_sold DESC LIMIT 6");

// ── Orders by Status ──
$status_stats = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM orders GROUP BY status");

// ── Low Stock Alert ──
$low_stock = mysqli_query($conn, "SELECT * FROM products WHERE stock < 20 ORDER BY stock ASC");

// ── Recent Revenue ──
$this_month  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as rev FROM orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW()) AND status != 'cancelled'"))['rev'] ?? 0;
$last_month  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as rev FROM orders WHERE MONTH(created_at)=MONTH(NOW()-INTERVAL 1 MONTH) AND YEAR(created_at)=YEAR(NOW()-INTERVAL 1 MONTH) AND status != 'cancelled'"))['rev'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Reports – Faraj Admin</title>
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
    <a href="admin_orders.php">📦 Orders</a>
    <a href="admin_users.php">👥 Customers</a>
    <a href="admin_messages.php">✉️ Messages</a>
    <a href="admin_reports.php" class="active">📈 Reports</a>
    <div class="menu-title" style="margin-top:20px;">Settings</div>
    <a href="logout.php">🚪 Logout</a>
  </aside>

  <main class="admin-content">
    <h2 style="font-family:'Playfair Display',serif;color:var(--green-dark);margin-bottom:24px;">📈 Sales Reports</h2>

    <!-- Summary Cards -->
    <div class="stat-cards" style="margin-bottom:28px;">
      <?php foreach([
        ['Total Revenue','KES '.number_format($total_revenue,2),'All time','💰'],
        ['This Month','KES '.number_format($this_month,2),'Current month','📅'],
        ['Last Month','KES '.number_format($last_month,2),'Previous month','📆'],
        ['Total Orders',$total_orders,'All time','📦'],
        ['Customers',$total_customers,'Registered','👥'],
        ['Products',$total_products,'In inventory','🍊'],
      ] as $s): ?>
      <div class="stat-card">
        <div style="font-size:24px;margin-bottom:6px;"><?= $s[3] ?></div>
        <div class="stat-label"><?= $s[0] ?></div>
        <div class="stat-value" style="font-size:18px;"><?= $s[1] ?></div>
        <div class="stat-sub"><?= $s[2] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px;">

      <!-- Orders by Status -->
      <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
        <h3 style="color:var(--green-dark);margin-bottom:16px;font-size:15px;">📦 Orders by Status</h3>
        <?php
        $colors = ['pending'=>'#f0a500','processing'=>'#2196f3','shipped'=>'#9c27b0','delivered'=>'#2e8b4a','cancelled'=>'#cc0000'];
        while ($s = mysqli_fetch_assoc($status_stats)): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:12px;height:12px;border-radius:50%;background:<?= $colors[$s['status']] ?? '#888' ?>;"></div>
            <span style="font-size:14px;"><?= ucfirst($s['status']) ?></span>
          </div>
          <span style="font-weight:700;font-size:14px;color:var(--green-dark);"><?= $s['count'] ?></span>
        </div>
        <?php endwhile; ?>
      </div>

      <!-- Monthly Revenue Table -->
      <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
        <h3 style="color:var(--green-dark);margin-bottom:16px;font-size:15px;">📅 Monthly Revenue</h3>
        <?php if ($monthly && mysqli_num_rows($monthly) > 0):
          while ($m = mysqli_fetch_assoc($monthly)): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;font-size:13px;">
          <span style="color:#555;"><?= $m['month'] ?></span>
          <div style="display:flex;gap:16px;">
            <span style="color:#888;"><?= $m['orders'] ?> orders</span>
            <span style="font-weight:700;color:var(--green-dark);">KES <?= number_format($m['revenue'],2) ?></span>
          </div>
        </div>
        <?php endwhile; else: ?>
        <p style="color:#888;font-size:13px;">No revenue data yet.</p>
        <?php endif; ?>
      </div>

    </div>

    <!-- Top Selling Products -->
    <div style="background:white;border-radius:var(--radius-lg);box-shadow:var(--shadow);overflow:hidden;border:1px solid var(--border);margin-bottom:28px;">
      <div style="padding:16px 20px;border-bottom:1px solid var(--border);">
        <h3 style="color:var(--green-dark);font-size:15px;">🏆 Top Selling Products</h3>
      </div>
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--green-light);">
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--green-dark);">Product</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--green-dark);">Category</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--green-dark);">Units Sold</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:var(--green-dark);">Revenue (KES)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $emojis = ['Banana'=>'🍌','Mango'=>'🥭','Apple'=>'🍎','Watermelon'=>'🍉','Pineapple'=>'🍍','Pawpaw'=>'🍈','Orange'=>'🍊','Lemon'=>'🍋'];
          if ($top_products && mysqli_num_rows($top_products) > 0):
            while ($p = mysqli_fetch_assoc($top_products)): ?>
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:12px 16px;font-size:14px;font-weight:600;"><?= $emojis[$p['name']] ?? '🍊' ?> <?= htmlspecialchars($p['name']) ?></td>
            <td style="padding:12px 16px;font-size:13px;color:#888;"><?= htmlspecialchars($p['category']) ?></td>
            <td style="padding:12px 16px;font-size:14px;"><?= $p['total_sold'] ?></td>
            <td style="padding:12px 16px;font-size:14px;color:var(--green-mid);font-weight:600;"><?= number_format($p['revenue'],2) ?></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="4" style="padding:20px;text-align:center;color:#888;">No sales data yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Low Stock Alert -->
    <div style="background:white;border-radius:var(--radius-lg);box-shadow:var(--shadow);overflow:hidden;border:1px solid var(--border);">
      <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
        <h3 style="color:#cc0000;font-size:15px;">⚠️ Low Stock Alert</h3>
        <span style="background:#fdecea;color:#cc0000;font-size:11px;padding:2px 8px;border-radius:10px;font-weight:600;">Stock below 20 units</span>
      </div>
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:#fdecea;">
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:#cc0000;">Product</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:#cc0000;">Category</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:#cc0000;">Stock Left</th>
            <th style="padding:12px 16px;text-align:left;font-size:13px;color:#cc0000;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($low_stock && mysqli_num_rows($low_stock) > 0):
            while ($p = mysqli_fetch_assoc($low_stock)): ?>
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:12px 16px;font-size:14px;font-weight:600;"><?= $emojis[$p['name']] ?? '🍊' ?> <?= htmlspecialchars($p['name']) ?></td>
            <td style="padding:12px 16px;font-size:13px;color:#888;"><?= htmlspecialchars($p['category']) ?></td>
            <td style="padding:12px 16px;">
              <span style="background:#fdecea;color:#cc0000;font-weight:700;padding:3px 10px;border-radius:20px;font-size:13px;"><?= $p['stock'] ?> units</span>
            </td>
            <td style="padding:12px 16px;">
              <a href="admin_products.php?edit=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Restock</a>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="4" style="padding:20px;text-align:center;color:#888;">✅ All products are well stocked.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>
</body>
</html>