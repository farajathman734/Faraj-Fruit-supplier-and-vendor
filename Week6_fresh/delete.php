<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 6 – DELETE Operation
// ============================================
require_once 'config/db.php';
require_once 'includes/header.php';

$success = $error = '';
$product = null;

// Confirm and execute delete
if (isset($_GET['confirm_delete']) && isset($_GET['id'])) {
    $id  = (int)$_GET['id'];
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM products WHERE id=$id"));
    if ($row) {
        $name = $row['name'];
        if (mysqli_query($conn, "DELETE FROM products WHERE id=$id")) {
            $success = "✅ Product '$name' has been deleted successfully.";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    } else {
        $error = "❌ Product not found.";
    }
}

// Load product for confirmation
if (isset($_GET['id']) && !isset($_GET['confirm_delete'])) {
    $id      = (int)$_GET['id'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
}

$all_products = mysqli_query($conn, "SELECT * FROM products ORDER BY name ASC");
$emojis = ['Banana'=>'🍌','Mango'=>'🥭','Apple'=>'🍎','Watermelon'=>'🍉','Pineapple'=>'🍍','Pawpaw'=>'🍈','Orange'=>'🍊','Lemon'=>'🍋','Passion Fruit'=>'🟣'];
?>

<div class="container">
  <h2>🗑️ DELETE Operation – Remove Product</h2>

  <div class="code-box">
    // PHP DELETE query – DELETE Operation<br>
    $sql = "DELETE FROM products WHERE id=$id";<br>
    mysqli_query($conn, $sql);<br>
    echo "Record Deleted";
  </div>

  <?php if ($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>

  <!-- Confirmation Box -->
  <?php if ($product): ?>
  <div class="card" style="border:2px solid #cc0000;background:#fff9f9;">
    <h3 style="color:#cc0000;">⚠️ Confirm Deletion</h3>
    <p style="margin:12px 0;color:#555;">Are you sure you want to delete this product? <strong>This action cannot be undone.</strong></p>
    <div style="background:white;border-radius:6px;padding:14px;margin-bottom:16px;border:1px solid #eee;">
      <p style="font-size:16px;font-weight:bold;"><?= $emojis[$product['name']] ?? '🍊' ?> <?= htmlspecialchars($product['name']) ?></p>
      <p style="color:#888;font-size:13px;margin-top:4px;"><?= htmlspecialchars($product['category']) ?> &nbsp;•&nbsp; Retail: KES <?= number_format($product['price_retail'],2) ?> &nbsp;•&nbsp; Stock: <?= $product['stock'] ?> units</p>
    </div>
    <div style="display:flex;gap:12px;">
      <a href="delete.php?id=<?= $product['id'] ?>&confirm_delete=1" class="btn btn-red" style="flex:1;text-align:center;">✅ Yes, Delete It</a>
      <a href="delete.php" class="btn btn-outline" style="flex:1;text-align:center;">❌ Cancel</a>
    </div>
  </div>
  <?php endif; ?>

  <!-- Products List -->
  <div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:16px 20px;background:#f9f9f9;border-bottom:1px solid #eee;">
      <strong style="color:#1a5c2e;">All Products – Click Delete to Remove</strong>
    </div>
    <table>
      <thead>
        <tr><th>Product</th><th>Category</th><th>Retail Price</th><th>Stock</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($all_products)):
          $emoji = $emojis[$row['name']] ?? '🍊';
        ?>
        <tr>
          <td><strong><?= $emoji ?> <?= htmlspecialchars($row['name']) ?></strong></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td>KES <?= number_format($row['price_retail'],2) ?></td>
          <td><?= $row['stock'] ?> units</td>
          <td><a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-red" style="font-size:12px;padding:5px 12px;">🗑 Delete</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div style="text-align:center;margin-top:20px;">
    <a href="read.php" class="btn btn-outline">← Back to All Products</a>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
