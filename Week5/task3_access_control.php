<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 5 – Task 3: Role-Based Access Control
// Session Authentication & Authorization
// ============================================

session_start();
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Task 3: Access Control – Faraj Week 5</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div style="background:#1a5c2e;color:white;padding:40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:32px;margin-bottom:8px;">Week 5 – Task 3</h1>
  <p style="opacity:0.85;">Role-Based Access Control & Session Authentication</p>
</div>

<div class="section" style="max-width:800px;">

  <!-- Code Demo Box -->
  <div style="background:#1a1a2e;border-radius:10px;padding:20px 24px;margin-bottom:24px;">
    <p style="color:#f0a500;font-size:12px;font-family:monospace;margin-bottom:10px;">// Week 5 – Access Control Code</p>
    <p style="color:#a8e6bf;font-size:13px;font-family:monospace;line-height:2;">
      session_start();<br><br>
      // Check if logged in<br>
      if (!isset($_SESSION['user_id'])) {<br>
      &nbsp;&nbsp;header('Location: login.php'); exit;<br>
      }<br><br>
      // Check if admin<br>
      if ($_SESSION['role'] !== 'admin') {<br>
      &nbsp;&nbsp;header('Location: index.php'); exit;<br>
      }
    </p>
  </div>

  <!-- Current Session Status -->
  <div style="background:white;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;margin-bottom:24px;">
    <h3 style="color:#1a5c2e;margin-bottom:16px;font-size:16px;">🔍 Your Current Session Status</h3>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div style="background:#e8f5ec;border-radius:8px;padding:16px;margin-bottom:16px;">
      <p style="color:#1a5c2e;font-weight:700;margin-bottom:10px;">✅ You are logged in</p>
      <div style="font-size:13px;font-family:monospace;line-height:2;color:#333;">
        $_SESSION['user_id']    = <?= $_SESSION['user_id'] ?><br>
        $_SESSION['user_name']  = "<?= htmlspecialchars($_SESSION['user_name']) ?>"<br>
        $_SESSION['role']       = "<?= $_SESSION['role'] ?>"
      </div>
    </div>
    <?php else: ?>
    <div style="background:#fdecea;border-radius:8px;padding:16px;margin-bottom:16px;">
      <p style="color:#cc0000;font-weight:700;">❌ You are NOT logged in — no session active</p>
      <p style="font-size:13px;color:#888;margin-top:6px;">Protected pages would redirect you to login.php</p>
    </div>
    <?php endif; ?>
  </div>

  <!-- Access Control Demo -->
  <div style="background:white;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;margin-bottom:24px;">
    <h3 style="color:#1a5c2e;margin-bottom:16px;font-size:16px;">🔐 Page Access Control in Faraj System</h3>
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
      <thead>
        <tr style="background:#1a5c2e;color:white;">
          <th style="padding:10px 14px;text-align:left;">Page</th>
          <th style="padding:10px 14px;text-align:left;">Who Can Access</th>
          <th style="padding:10px 14px;text-align:left;">Protection Code</th>
          <th style="padding:10px 14px;text-align:left;">Your Access</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $pages = [
          ['index.php', 'Everyone', 'None required', true],
          ['products.php', 'Everyone', 'None required', true],
          ['profile.php', 'Logged in users', "isset(\$_SESSION['user_id'])", isset($_SESSION['user_id'])],
          ['order_history.php', 'Logged in users', "isset(\$_SESSION['user_id'])", isset($_SESSION['user_id'])],
          ['admin.php', 'Admin only', "\$_SESSION['role'] === 'admin'", isset($_SESSION['role']) && $_SESSION['role'] === 'admin'],
          ['admin_reports.php', 'Admin only', "\$_SESSION['role'] === 'admin'", isset($_SESSION['role']) && $_SESSION['role'] === 'admin'],
        ];
        foreach ($pages as $i => [$page, $who, $code, $access]):
        ?>
        <tr style="border-bottom:1px solid #f0f0f0;background:<?= $i%2===0?'#f9f9f9':'white' ?>;">
          <td style="padding:10px 14px;font-weight:600;"><?= $page ?></td>
          <td style="padding:10px 14px;color:#555;"><?= $who ?></td>
          <td style="padding:10px 14px;font-family:monospace;font-size:11px;color:#1a5c2e;"><?= htmlspecialchars($code) ?></td>
          <td style="padding:10px 14px;">
            <span style="background:<?= $access?'#e8f5ec':'#fdecea' ?>;color:<?= $access?'#1a5c2e':'#cc0000' ?>;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
              <?= $access ? '✅ Allowed' : '❌ Blocked' ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Test Links -->
  <div style="background:white;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
    <h3 style="color:#1a5c2e;margin-bottom:16px;font-size:16px;">🧪 Test Access Control</h3>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="/faraj/pages/profile.php" class="btn btn-primary btn-sm">Test Profile (Login Required)</a>
      <a href="/faraj/pages/admin.php" class="btn btn-secondary btn-sm">Test Admin (Admin Only)</a>
      <a href="/faraj/pages/logout.php" class="btn btn-sm" style="border:2px solid #cc0000;color:#cc0000;">Logout & Test Again</a>
    </div>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
