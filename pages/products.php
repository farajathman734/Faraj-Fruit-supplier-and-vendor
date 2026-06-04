<?php
require_once '../includes/header.php';
require_once '../config/db.php';

$emojis = ['Banana'=>'🍌','Mango'=>'🥭','Apple'=>'🍎','Watermelon'=>'🍉'];
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$where = $category ? "WHERE category='$category'" : '';
$result = mysqli_query($conn, "SELECT * FROM products $where");
?>

<div style="background:var(--green-dark);color:white;padding:40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:34px;margin-bottom:10px;">Our Products</h1>
  <p style="opacity:0.85;">Fresh fruits available in retail and wholesale quantities</p>
</div>

<div class="section">
  <!-- Filter Bar -->
  <div style="display:flex;gap:10px;margin-bottom:28px;flex-wrap:wrap;">
    <a href="products.php" class="btn <?= !$category ? 'btn-secondary' : 'btn-outline' ?>" style="<?= !$category ? '' : 'color:#1a5c2e;border-color:#1a5c2e;' ?>">All</a>
    <a href="?category=Tropical" class="btn <?= $category==='Tropical' ? 'btn-secondary' : 'btn-outline' ?>" style="<?= $category==='Tropical' ? '' : 'color:#1a5c2e;border-color:#1a5c2e;' ?>">Tropical</a>
    <a href="?category=Temperate" class="btn <?= $category==='Temperate' ? 'btn-secondary' : 'btn-outline' ?>" style="<?= $category==='Temperate' ? '' : 'color:#1a5c2e;border-color:#1a5c2e;' ?>">Temperate</a>
    <a href="?category=Melons" class="btn <?= $category==='Melons' ? 'btn-secondary' : 'btn-outline' ?>" style="<?= $category==='Melons' ? '' : 'color:#1a5c2e;border-color:#1a5c2e;' ?>">Melons</a>
  </div>

  <div class="product-grid">
    <?php if ($result && mysqli_num_rows($result) > 0):
      while ($row = mysqli_fetch_assoc($result)):
        $emoji = $emojis[$row['name']] ?? '🍊';
    ?>
    <div class="product-card">
      <div class="product-img"><?= $emoji ?></div>
      <div class="product-info">
        <span class="badge"><?= htmlspecialchars($row['category']) ?></span>
        <h4><?= htmlspecialchars($row['name']) ?></h4>
        <div class="prices">
          <span class="price-retail">Retail: KES <?= number_format($row['price_retail'],2) ?></span>
        </div>
        <div class="prices">
          <span class="price-wholesale">Wholesale: KES <?= number_format($row['price_wholesale'],2) ?></span>
        </div>
        <p style="font-size:12px;color:#888;margin-bottom:10px;">Stock: <?= $row['stock'] ?> units</p>
        <a href="cart.php?add=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Add to Cart</a>
      </div>
    </div>
    <?php endwhile; else: ?>
      <p style="color:#888;">No products found.</p>
    <?php endif; ?>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
