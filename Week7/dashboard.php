<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 7 – Protected Dashboard
// Session Management demonstration
// ============================================
if (session_status() === PHP_SESSION_NONE) session_start();

// Protect this page – redirect if not logged in
if (!isset($_SESSION['w7_user'])) {
    header('Location: login.php'); exit;
}

require_once 'config/db.php';
require_once 'includes/header.php';

$user_name  = $_SESSION['w7_user'];
$user_email = $_SESSION['w7_email'];
$user_role  = $_SESSION['w7_role'];
$user_id    = $_SESSION['w7_id'];

// Fetch user from DB
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id"));

// Fetch some products to show dynamic content
$products = mysqli_query($conn, "SELECT * FROM products LIMIT 5");
?>

<div class="container">

  <!-- Welcome Banner -->
  <div style="background:linear-gradient(135deg,#1a5c2e,#2e8b4a);color:white;border-radius:10px;padding:30px;margin-bottom:24px;text-align:center;">
    <div style="font-size:48px;margin-bottom:10px;">👋</div>
    <h2 style="color:white;font-size:26px;margin-bottom:6px;">Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
    <p style="opacity:0.85;">You are logged into the Faraj Fruit Supplier system</p>
    <p style="opacity:0.7;font-size:13px;margin-top:6px;">Role: <?= ucfirst($user_role) ?> &nbsp;|&nbsp; <?= htmlspecialchars($user_email) ?></p>
  </div>

  <!-- Session Variables Demo -->
  <div class="card">
    <h3>📦 Active Session Variables (Week 7 Demo)</h3>
    <div class="code-box" style="margin-top:12px;">
      // These session variables were created on login<br>
      $_SESSION['w7_user']  = "<?= htmlspecialchars($user_name) ?>";<br>
      $_SESSION['w7_email'] = "<?= htmlspecialchars($user_email) ?>";<br>
      $_SESSION['w7_role']  = "<?= htmlspecialchars($user_role) ?>";<br>
      $_SESSION['w7_id']    = <?= $user_id ?>;
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px;">
      <div style="background:#e8f5ec;border-radius:6px;padding:14px;text-align:center;">
        <div style="font-size:22px;">✅</div>
        <div style="font-weight:bold;color:#1a5c2e;margin-top:6px;">Session Active</div>
        <div style="font-size:12px;color:#888;">PHP Session running</div>
      </div>
      <div style="background:#fff4e0;border-radius:6px;padding:14px;text-align:center;">
        <div style="font-size:22px;">👤</div>
        <div style="font-weight:bold;color:#b07800;margin-top:6px;"><?= ucfirst($user_role) ?></div>
        <div style="font-size:12px;color:#888;">Account role</div>
      </div>
      <div style="background:#e8f0fe;border-radius:6px;padding:14px;text-align:center;">
        <div style="font-size:22px;">🔐</div>
        <div style="font-weight:bold;color:#1a73e8;margin-top:6px;">Authenticated</div>
        <div style="font-size:12px;color:#888;">Identity verified</div>
      </div>
    </div>
  </div>

  <!-- Page Protection Demo -->
  <div class="card">
    <h3>🛡️ How This Page is Protected</h3>
    <div class="code-box" style="margin-top:12px;">
      // Week 7 – Page Protection Code<br>
      session_start();<br><br>
      if (!isset($_SESSION['w7_user'])) {<br>
      &nbsp;&nbsp;header('Location: login.php');<br>
      &nbsp;&nbsp;exit;<br>
      }<br><br>
      // Only reaches here if logged in<br>
      echo "Welcome " . $_SESSION['w7_user'];
    </div>
  </div>

  <!-- Products (Dynamic Content) -->
  <div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:16px 20px;background:#f9f9f9;border-bottom:1px solid #eee;">
      <strong style="color:#1a5c2e;">🍊 Available Products (Dynamic DB Content)</strong>
    </div>
    <table>
      <thead>
        <tr><th>Product</th><th>Category</th><th>Retail Price</th><th>Stock</th></tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
        <tr>
          <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td>KES <?= number_format($row['price_retail'],2) ?></td>
          <td><?= $row['stock'] ?> units</td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div style="text-align:center;margin-top:20px;">
    <a href="logout.php" class="btn btn-red">🚪 Logout – Destroy Session</a>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
