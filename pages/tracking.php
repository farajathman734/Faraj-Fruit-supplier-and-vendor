<?php
session_start();
require_once '../includes/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}

$user_id  = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

$delivery = null;
$order    = null;

if ($order_id) {
    $order    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT o.*, u.name as customer FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=$order_id AND o.user_id=$user_id"));
    $delivery = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM delivery WHERE order_id=$order_id"));
}

$steps = ['pending'=>0,'dispatched'=>1,'in_transit'=>2,'delivered'=>3];
$current_step = $delivery ? ($steps[$delivery['status']] ?? 0) : 0;
?>

<div style="background:var(--green-dark);color:white;padding:50px 40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:34px;margin-bottom:8px;">Track Delivery</h1>
  <p style="opacity:0.85;">Real-time delivery status for your order</p>
</div>

<div class="section" style="max-width:700px;">

  <!-- Search Order -->
  <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);margin-bottom:24px;">
    <h3 style="color:var(--green-dark);margin-bottom:14px;font-size:15px;">🔍 Enter Order ID</h3>
    <form method="GET" style="display:flex;gap:10px;">
      <input type="number" name="order_id" placeholder="e.g. 1" value="<?= $order_id ?: '' ?>" style="flex:1;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius);font-size:14px;">
      <button type="submit" class="btn btn-primary">Track</button>
    </form>
  </div>

  <?php if ($order && $delivery): ?>

  <!-- Order Info -->
  <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);margin-bottom:24px;">
    <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
      <div>
        <h3 style="color:var(--green-dark);font-size:18px;">Order #<?= $order['id'] ?></h3>
        <p style="color:#888;font-size:13px;">Placed on <?= date('d M Y', strtotime($order['created_at'])) ?></p>
      </div>
      <div style="text-align:right;">
        <div style="font-weight:700;font-size:16px;color:var(--green-dark);">KES <?= number_format($order['total'],2) ?></div>
        <div style="font-size:12px;color:#888;"><?= ucfirst($order['order_type']) ?> order</div>
      </div>
    </div>

    <!-- Delivery Info -->
    <div style="background:var(--green-light);border-radius:var(--radius);padding:14px 16px;margin-bottom:20px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:13px;">
        <div><span style="color:#888;">Recipient:</span> <strong><?= htmlspecialchars($delivery['recipient_name']) ?></strong></div>
        <div><span style="color:#888;">Phone:</span> <strong><?= htmlspecialchars($delivery['phone']) ?></strong></div>
        <div><span style="color:#888;">Address:</span> <strong><?= htmlspecialchars($delivery['address']) ?></strong></div>
        <div><span style="color:#888;">City:</span> <strong><?= htmlspecialchars($delivery['city']) ?></strong></div>
        <div><span style="color:#888;">Method:</span> <strong><?= ucfirst($delivery['delivery_method']) ?></strong></div>
        <?php if ($delivery['estimated_date']): ?>
        <div><span style="color:#888;">Est. Arrival:</span> <strong><?= date('d M Y', strtotime($delivery['estimated_date'])) ?></strong></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Progress Tracker -->
    <h4 style="color:var(--green-dark);margin-bottom:20px;font-size:14px;">Delivery Progress</h4>
    <div style="position:relative;padding:0 20px;">
      <!-- Progress Line -->
      <div style="position:absolute;top:20px;left:60px;right:60px;height:4px;background:#e0e0e0;border-radius:2px;z-index:0;">
        <div style="height:100%;background:var(--green-mid);border-radius:2px;width:<?= $current_step === 0 ? '0' : ($current_step === 1 ? '33%' : ($current_step === 2 ? '66%' : '100%')) ?>;transition:width 0.5s;"></div>
      </div>
      <!-- Steps -->
      <div style="display:flex;justify-content:space-between;position:relative;z-index:1;">
        <?php
        $track_steps = [
          ['pending',   '📋', 'Order Placed'],
          ['dispatched','🚀', 'Dispatched'],
          ['in_transit','🚚', 'In Transit'],
          ['delivered', '✅', 'Delivered'],
        ];
        foreach ($track_steps as $i => [$key, $icon, $label]):
          $done   = $i <= $current_step;
          $active = $i === $current_step;
        ?>
        <div style="text-align:center;width:70px;">
          <div style="width:40px;height:40px;border-radius:50%;background:<?= $done ? 'var(--green-mid)' : '#e0e0e0' ?>;display:flex;align-items:center;justify-content:center;font-size:18px;margin:0 auto 8px;border:3px solid <?= $active ? 'var(--green-dark)' : 'transparent' ?>;"><?= $icon ?></div>
          <div style="font-size:11px;color:<?= $done ? 'var(--green-dark)' : '#aaa' ?>;font-weight:<?= $active ? '700' : '400' ?>;"><?= $label ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

  <?php elseif ($order_id && !$order): ?>
  <div style="background:#fdecea;border-left:5px solid #cc0000;padding:16px 20px;border-radius:var(--radius);color:#cc0000;">
    ❌ Order #<?= $order_id ?> not found or does not belong to your account.
  </div>
  <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>