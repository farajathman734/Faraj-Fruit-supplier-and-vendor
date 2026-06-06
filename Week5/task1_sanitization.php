<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 5 – Task 1: Input Sanitization
// SQL Injection Prevention & XSS Protection
// ============================================

session_start();
require_once 'config/db.php';

$results    = [];
$safe_demo  = '';
$unsafe_demo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_input   = $_POST['search_input'];

    // ── UNSAFE (DO NOT USE IN PRODUCTION) ──
    $unsafe_query = "SELECT * FROM products WHERE name LIKE '%$raw_input%'";

    // ── SAFE: using mysqli_real_escape_string ──
    $safe_input   = mysqli_real_escape_string($conn, $raw_input);
    $safe_query   = "SELECT * FROM products WHERE name LIKE '%$safe_input%'";

    $unsafe_demo  = $unsafe_query;
    $safe_demo    = $safe_query;

    // Only run the SAFE query
    $result = mysqli_query($conn, $safe_query);
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Task 1: Sanitization – Faraj Week 5</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div style="background:#1a5c2e;color:white;padding:40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:32px;margin-bottom:8px;">Week 5 – Task 1</h1>
  <p style="opacity:0.85;">Input Sanitization & SQL Injection Prevention</p>
</div>

<div class="section" style="max-width:800px;">

  <!-- Explanation Box -->
  <div style="background:#1a1a2e;border-radius:10px;padding:20px 24px;margin-bottom:24px;">
    <p style="color:#f0a500;font-size:12px;font-family:monospace;margin-bottom:10px;">// Week 5 – Security Concepts</p>
    <p style="color:#a8e6bf;font-size:13px;font-family:monospace;line-height:2;">
      // SQL Injection: attacker types: ' OR '1'='1<br>
      // XSS Attack: attacker types: &lt;script&gt;alert('hacked')&lt;/script&gt;<br><br>
      // Prevention:<br>
      $safe = mysqli_real_escape_string($conn, $_POST['input']);<br>
      $output = htmlspecialchars($data_from_db);
    </p>
  </div>

  <!-- Search Form -->
  <div style="background:white;border-radius:14px;padding:28px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;margin-bottom:24px;">
    <h3 style="color:#1a5c2e;margin-bottom:16px;">🔍 Test Sanitization – Search Products</h3>
    <p style="font-size:13px;color:#888;margin-bottom:16px;">Try typing: <code style="background:#f5f5f5;padding:2px 8px;border-radius:4px;">' OR '1'='1</code> to see SQL injection attempt being blocked</p>
    <form method="POST" style="display:flex;gap:10px;">
      <input type="text" name="search_input" placeholder="Search products..." style="flex:1;padding:10px 14px;border:1px solid #e0e0e0;border-radius:8px;font-size:14px;" value="<?= htmlspecialchars($_POST['search_input'] ?? '') ?>">
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
  </div>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

  <!-- Query Comparison -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
    <div style="background:#fdecea;border-radius:10px;padding:16px;">
      <p style="color:#cc0000;font-weight:700;font-size:13px;margin-bottom:8px;">❌ UNSAFE Query (SQL Injection Risk)</p>
      <code style="font-size:11px;color:#cc0000;word-break:break-all;"><?= htmlspecialchars($unsafe_demo) ?></code>
    </div>
    <div style="background:#e8f5ec;border-radius:10px;padding:16px;">
      <p style="color:#1a5c2e;font-weight:700;font-size:13px;margin-bottom:8px;">✅ SAFE Query (Sanitized Input)</p>
      <code style="font-size:11px;color:#1a5c2e;word-break:break-all;"><?= htmlspecialchars($safe_demo) ?></code>
    </div>
  </div>

  <!-- Results -->
  <div style="background:white;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
    <h3 style="color:#1a5c2e;margin-bottom:16px;">Search Results (<?= count($results) ?> found)</h3>
    <?php if (!empty($results)): ?>
      <?php foreach ($results as $p): ?>
      <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0f0;font-size:14px;">
        <span><strong><?= htmlspecialchars($p['name']) ?></strong> <span style="color:#888;font-size:12px;"><?= htmlspecialchars($p['category']) ?></span></span>
        <span style="color:#2e8b4a;font-weight:600;">KES <?= number_format($p['price_retail'],2) ?></span>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="color:#888;text-align:center;padding:20px 0;">No products found — and no SQL injection succeeded! ✅</p>
    <?php endif; ?>
  </div>

  <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>
