<?php
session_start();
require_once '../includes/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}

$user_id = $_SESSION['user_id'];
$orders  = mysqli_query($conn, "SELECT o.*, d.status as delivery_status, d.estimated_date, d.city FROM orders o LEFT JOIN delivery d ON o.id=d.order_id WHERE o.user_id=$user_id ORDER BY o.created_at DESC");
$colors  = ['pending'=>'#f0a500','processing'=>'#2196f3','shipped'=>'#9c27b0','delivered'=>'#2e8b4a','cancelled'=>'#cc0000'];
$dcolors = ['pending'=>'#f0a500','dispatched'=>'#2196f3','in_transit'=>'#9c27b0','delivered'=>'#2e8b4a','failed'=>'#cc0000'];
?>

<div style="background:var(--green-dark);color:white;padding:50px 40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:34px;margin-bottom:8px;">My Orders</h1>
  <p style="opacity:0.85;">Track all your orders and delivery status</p>
</div>

<div class="section" style="max-width:900px;">
  <?php if ($orders && mysqli_num_rows($orders) > 0):
    while ($row = mysqli_fetch_assoc($orders)):
      // Get order items
      $items = mysqli_query($conn, "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id={$row['id']}");
  ?>
  <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);margin-bottom:20px;">

    <!-- Order Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
      <div>
        <span style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--green-dark);">Order #<?= $row['id'] ?></span>
        <span style="color:#888;font-size:13px;margin-left:12px;"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></span>
      </div>
      <div style="display:flex;gap:10px;align-items:center;">
        <span style="background:<?= $colors[$row['status']] ?>;color:white;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">Order: <?= ucfirst($row['status']) ?></span>
        <?php if ($row['delivery_status']): ?>
        <span style="background:<?= $dcolors[$row['delivery_status']] ?? '#888' ?>;color:white;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">Delivery: <?= ucfirst(str_replace('_',' ',$row['delivery_status'])) ?></span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Order Items -->
    <div style="border-top:1px solid var(--border);padding-top:14px;margin-bottom:14px;">
      <?php while ($item = mysqli_fetch_assoc($items)): ?>
      <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #f5f5f5;">
        <span>🍊 <?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
        <span style="font-weight:600;">KES <?= number_format($item['subtotal'],2) ?></span>
      </div>
      <?php endwhile; ?>
    </div>

    <!-- Order Footer -->
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
      <div style="font-size:13px;color:#888;">
        <?php if ($row['city']): ?>📍 <?= htmlspecialchars($row['city']) ?><?php endif; ?>
        <?php if ($row['estimated_date']): ?> · Est. <?= date('d M Y', strtotime($row['estimated_date'])) ?><?php endif; ?>
        <?php if ($row['discount'] > 0): ?> · <span style="color:var(--green-mid);">Saved KES <?= number_format($row['discount'],2) ?></span><?php endif; ?>
      </div>
      <div style="font-weight:700;font-size:16px;color:var(--green-dark);">Total: KES <?= number_format($row['total'],2) ?></div>
    </div>

    <!-- Review Button (only for delivered orders) -->
    <?php if ($row['status'] === 'delivered'): ?>
    <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
      <a href="reviews.php?order_id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">⭐ Leave a Review</a>
    </div>
    <?php endif; ?>

  </div>
  <?php endwhile; else: ?>
  <div style="text-align:center;padding:60px 20px;">
    <div style="font-size:56px;margin-bottom:16px;">📦</div>
    <h3 style="color:#888;margin-bottom:16px;">No orders yet</h3>
    <a href="products.php" class="btn btn-primary">Start Shopping</a>
  </div>
  <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>