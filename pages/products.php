<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/header.php';
require_once '../config/db.php';

$emojis = ['Banana'=>'🍌','Mango'=>'🥭','Apple'=>'🍎','Watermelon'=>'🍉','Pineapple'=>'🍍','Pawpaw'=>'🍈','Orange'=>'🍊','Lemon'=>'🍋'];
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$where = $category ? "WHERE category='$category'" : '';
$result = mysqli_query($conn, "SELECT * FROM products $where");
?>

<div style="background:#1a5c2e;color:white;padding:40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:34px;margin-bottom:10px;">Our Products</h1>
  <p style="opacity:0.85;">Fresh fruits available in retail and wholesale quantities</p>
</div>

<div style="max-width:1100px;margin:0 auto;padding:40px 20px;">

  <!-- Filter Bar -->
  <div style="display:flex;gap:10px;margin-bottom:28px;flex-wrap:wrap;">
    <a href="products.php" style="display:inline-block;padding:8px 18px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:600;background:<?= !$category?'#1a5c2e':'white' ?>;color:<?= !$category?'white':'#1a5c2e' ?>;border:2px solid #1a5c2e;">All</a>
    <a href="?category=Tropical" style="display:inline-block;padding:8px 18px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:600;background:<?= $category==='Tropical'?'#1a5c2e':'white' ?>;color:<?= $category==='Tropical'?'white':'#1a5c2e' ?>;border:2px solid #1a5c2e;">Tropical</a>
    <a href="?category=Temperate" style="display:inline-block;padding:8px 18px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:600;background:<?= $category==='Temperate'?'#1a5c2e':'white' ?>;color:<?= $category==='Temperate'?'white':'#1a5c2e' ?>;border:2px solid #1a5c2e;">Temperate</a>
    <a href="?category=Melons" style="display:inline-block;padding:8px 18px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:600;background:<?= $category==='Melons'?'#1a5c2e':'white' ?>;color:<?= $category==='Melons'?'white':'#1a5c2e' ?>;border:2px solid #1a5c2e;">Melons</a>
    <a href="?category=Citrus" style="display:inline-block;padding:8px 18px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:600;background:<?= $category==='Citrus'?'#1a5c2e':'white' ?>;color:<?= $category==='Citrus'?'white':'#1a5c2e' ?>;border:2px solid #1a5c2e;">Citrus</a>
  </div>

  <!-- Products Grid -->
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
    <?php if ($result && mysqli_num_rows($result) > 0):
      while ($row = mysqli_fetch_assoc($result)):
        $emoji = $emojis[$row['name']] ?? '🍊';
    ?>
    <div style="background:white;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
      <!-- Image Box -->
      <div style="background:#f2f9f4;height:160px;display:flex;align-items:center;justify-content:center;font-size:64px;">
        <?= $emoji ?>
      </div>
      <!-- Product Info -->
      <div style="padding:16px;">
        <span style="display:inline-block;background:#e8f5ec;color:#1a5c2e;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;margin-bottom:8px;"><?= htmlspecialchars($row['category']) ?></span>
        <h4 style="font-size:16px;font-weight:700;color:#1a1a1a;margin-bottom:8px;"><?= htmlspecialchars($row['name']) ?></h4>
        <p style="font-size:13px;color:#555;margin-bottom:4px;">Retail: <strong>KES <?= number_format($row['price_retail'],2) ?></strong></p>
        <p style="font-size:13px;color:#2e8b4a;margin-bottom:4px;font-weight:600;">Wholesale: KES <?= number_format($row['price_wholesale'],2) ?></p>
        <p style="font-size:12px;color:#999;margin-bottom:12px;">Stock: <?= $row['stock'] ?> units</p>
        <a href="cart.php?add=<?= $row['id'] ?>" style="display:block;background:#f0a500;color:white;text-align:center;padding:10px;border-radius:6px;text-decoration:none;font-weight:600;font-size:14px;">Add to Cart</a>
      </div>
    </div>
    <?php endwhile; else: ?>
      <p style="color:#888;grid-column:1/-1;text-align:center;padding:40px;">No products found.</p>
    <?php endif; ?>
  </div>

</div>

<?php require_once '../includes/footer.php'; ?>
