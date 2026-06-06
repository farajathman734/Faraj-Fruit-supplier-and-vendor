<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 5 – Task 4: Final System Integration
// Complete System Overview & Testing
// ============================================

session_start();
require_once 'config/db.php';

// Gather system stats
$products_count  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products"));
$users_count     = @mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users")) ?: 0;
$orders_count    = @mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders")) ?: 0;
$messages_count  = @mysqli_num_rows(mysqli_query($conn, "SELECT id FROM messages")) ?: 0;
$reviews_count   = @mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reviews")) ?: 0;
$revenue         = @mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as rev FROM orders WHERE status != 'cancelled'"))['rev'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Task 4: Final Integration – Faraj Week 5</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div style="background:linear-gradient(135deg,#1a5c2e,#2e8b4a);color:white;padding:50px 40px;text-align:center;">
  <div style="font-size:48px;margin-bottom:12px;">🍊</div>
  <h1 style="font-family:'Playfair Display',serif;font-size:36px;margin-bottom:10px;">Week 5 – Task 4</h1>
  <p style="opacity:0.85;font-size:17px;">Final System Integration – Faraj Fruit Supplier & Vendor</p>
  <p style="opacity:0.7;font-size:13px;margin-top:8px;">BIT3208 – Advanced Web Design and Development</p>
</div>

<div class="section" style="max-width:1000px;">

  <!-- Live System Stats -->
  <h2 style="font-family:'Playfair Display',serif;color:#1a5c2e;margin-bottom:20px;text-align:center;">Live System Statistics</h2>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px;">
    <?php foreach([
      ['🍊','Products',$products_count,'In database'],
      ['👥','Users',$users_count,'Registered'],
      ['📦','Orders',$orders_count,'Placed'],
      ['✉️','Messages',$messages_count,'Received'],
      ['⭐','Reviews',$reviews_count,'Submitted'],
      ['💰','Revenue','KES '.number_format($revenue,2),'Total'],
    ] as $s): ?>
    <div style="background:white;border-radius:12px;padding:20px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
      <div style="font-size:28px;margin-bottom:8px;"><?= $s[0] ?></div>
      <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.5px;"><?= $s[1] ?></div>
      <div style="font-size:22px;font-weight:700;color:#1a5c2e;margin:4px 0;"><?= $s[2] ?></div>
      <div style="font-size:11px;color:#aaa;"><?= $s[3] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Weekly Progress Summary -->
  <h2 style="font-family:'Playfair Display',serif;color:#1a5c2e;margin-bottom:20px;text-align:center;">Project Weekly Summary</h2>
  <div style="background:white;border-radius:14px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;margin-bottom:32px;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr style="background:#1a5c2e;color:white;">
          <th style="padding:14px 20px;text-align:left;font-size:13px;">Week</th>
          <th style="padding:14px 20px;text-align:left;font-size:13px;">Focus</th>
          <th style="padding:14px 20px;text-align:left;font-size:13px;">What Was Built</th>
          <th style="padding:14px 20px;text-align:left;font-size:13px;">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach([
          ['Week 1','Environment Setup','XAMPP, PHP, MySQL, faraj_db, Hello World page'],
          ['Week 2','UI Design','Wireframes, color theme, HTML/CSS for all 7 pages'],
          ['Week 3','JS & PHP Basics','Form validation, live search, coupon system, checkout'],
          ['Week 4','Authentication','Login, register, sessions, role-based access, dashboard'],
          ['Week 5','Security','Sanitization, hashing, access control, final integration'],
        ] as $i => [$week,$focus,$built]): ?>
        <tr style="border-bottom:1px solid #f0f0f0;background:<?= $i%2===0?'#f9fafb':'white' ?>;">
          <td style="padding:14px 20px;font-weight:700;color:#1a5c2e;"><?= $week ?></td>
          <td style="padding:14px 20px;font-weight:600;color:#2e8b4a;"><?= $focus ?></td>
          <td style="padding:14px 20px;font-size:13px;color:#555;"><?= $built ?></td>
          <td style="padding:14px 20px;"><span style="background:#e8f5ec;color:#1a5c2e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">✅ Complete</span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Security Checklist -->
  <h2 style="font-family:'Playfair Display',serif;color:#1a5c2e;margin-bottom:20px;text-align:center;">Security Implementation Checklist</h2>
  <div style="background:white;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;margin-bottom:32px;">
    <?php foreach([
      ['password_hash() & password_verify()', 'All passwords hashed using bcrypt via PASSWORD_DEFAULT', true],
      ['mysqli_real_escape_string()', 'All user inputs sanitized before DB queries', true],
      ['htmlspecialchars()', 'All DB output escaped to prevent XSS attacks', true],
      ['session_start() & $_SESSION', 'Session-based authentication on all protected pages', true],
      ['Role-Based Access Control', 'Admin pages check $_SESSION[\'role\'] === \'admin\'', true],
      ['session_destroy()', 'Complete session termination on logout', true],
      ['JS Form Validation', 'Client-side validation before form submission', true],
      ['Server-side PHP Validation', 'Double validation on server even if JS is bypassed', true],
    ] as [$feature, $desc, $done]): ?>
    <div style="display:flex;align-items:flex-start;gap:14px;padding:12px 0;border-bottom:1px solid #f0f0f0;">
      <span style="font-size:18px;flex-shrink:0;"><?= $done ? '✅' : '⏳' ?></span>
      <div>
        <div style="font-weight:600;font-size:14px;color:#1a5c2e;"><?= $feature ?></div>
        <div style="font-size:12px;color:#888;margin-top:2px;"><?= $desc ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Test All Pages -->
  <h2 style="font-family:'Playfair Display',serif;color:#1a5c2e;margin-bottom:20px;text-align:center;">Test All System Pages</h2>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:32px;">
    <?php foreach([
      ['🏠','Homepage','/faraj/index.php'],
      ['🍊','Products','/faraj/pages/products.php'],
      ['🛒','Cart','/faraj/pages/cart.php'],
      ['📝','Register','/faraj/pages/register.php'],
      ['🔑','Login','/faraj/pages/login.php'],
      ['👤','Profile','/faraj/pages/profile.php'],
      ['📦','Orders','/faraj/pages/order_history.php'],
      ['📊','Admin','/faraj/pages/admin.php'],
      ['📈','Reports','/faraj/pages/admin_reports.php'],
    ] as [$icon,$label,$url]): ?>
    <a href="<?= $url ?>" style="display:block;background:white;border-radius:10px;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.06);border:1px solid #e0e0e0;text-decoration:none;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
      <div style="font-size:24px;margin-bottom:6px;"><?= $icon ?></div>
      <div style="font-size:13px;font-weight:600;color:#1a5c2e;"><?= $label ?></div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Week 5 Tasks -->
  <h2 style="font-family:'Playfair Display',serif;color:#1a5c2e;margin-bottom:20px;text-align:center;">Week 5 Task Pages</h2>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
    <?php foreach([
      ['🛡️','Task 1: Sanitization','task1_sanitization.php'],
      ['🔐','Task 2: Password Hashing','task2_hashing.php'],
      ['🔒','Task 3: Access Control','task3_access_control.php'],
    ] as [$icon,$label,$url]): ?>
    <a href="<?= $url ?>" style="display:block;background:#e8f5ec;border-radius:10px;padding:20px;text-align:center;text-decoration:none;border:2px solid #2e8b4a;">
      <div style="font-size:28px;margin-bottom:8px;"><?= $icon ?></div>
      <div style="font-size:13px;font-weight:600;color:#1a5c2e;"><?= $label ?></div>
    </a>
    <?php endforeach; ?>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
