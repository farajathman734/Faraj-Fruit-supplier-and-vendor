<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 6 – UPDATE Operation
// ============================================
require_once 'config/db.php';
require_once 'includes/header.php';

$success = $error = '';
$product = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)$_POST['product_id'];
    $name      = mysqli_real_escape_string($conn, $_POST['name']);
    $category  = mysqli_real_escape_string($conn, $_POST['category']);
    $retail    = (float)$_POST['price_retail'];
    $wholesale = (float)$_POST['price_wholesale'];
    $stock     = (int)$_POST['stock'];

    if (empty($name)) {
        $error = "Product name is required.";
    } else {
        $sql = "UPDATE products
                SET name='$name', category='$category',
                    price_retail='$retail', price_wholesale='$wholesale', stock='$stock'
                WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Product '$name' updated successfully!";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
    // Reload product after update
    $id = (int)$_POST['product_id'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
}

// Load product by GET id
if (isset($_GET['id']) && !$product) {
    $id      = (int)$_GET['id'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
}

$all_products = mysqli_query($conn, "SELECT id, name FROM products ORDER BY name ASC");
?>

<div class="container">
  <h2>✏️ UPDATE Operation – Edit Product</h2>

  <div class="code-box">
    // PHP UPDATE query – UPDATE Operation<br>
    $sql = "UPDATE products<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SET name='$name', category='$category', price_retail='$retail'<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WHERE id=$id";<br>
    mysqli_query($conn, $sql);
  </div>

  <?php if ($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>

  <!-- Select Product Dropdown -->
  <div class="card">
    <label style="font-weight:bold;font-size:13px;color:#555;display:block;margin-bottom:8px;">Select a Product to Edit:</label>
    <select onchange="window.location='update.php?id='+this.value" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;">
      <option value="">-- Choose Product --</option>
      <?php while ($p = mysqli_fetch_assoc($all_products)): ?>
      <option value="<?= $p['id'] ?>" <?= (isset($product) && $product['id']==$p['id'])?'selected':'' ?>><?= htmlspecialchars($p['name']) ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <?php if ($product): ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

    <!-- Edit Form -->
    <div class="card">
      <h3>Editing: <?= htmlspecialchars($product['name']) ?></h3>
      <form method="POST">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <div class="form-group">
          <label>Product Name *</label>
          <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="form-group">
          <label>Category</label>
          <select name="category">
            <?php foreach(['Tropical','Citrus','Temperate','Melons','Berries'] as $cat): ?>
            <option value="<?= $cat ?>" <?= $product['category']===$cat?'selected':'' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
          <div class="form-group">
            <label>Retail Price (KES)</label>
            <input type="number" step="0.01" name="price_retail" value="<?= $product['price_retail'] ?>" required>
          </div>
          <div class="form-group">
            <label>Wholesale Price (KES)</label>
            <input type="number" step="0.01" name="price_wholesale" value="<?= $product['price_wholesale'] ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Stock Quantity</label>
          <input type="number" name="stock" value="<?= $product['stock'] ?>">
        </div>
        <button type="submit" class="btn btn-amber" style="width:100%;">Update Product</button>
      </form>
    </div>

    <!-- Current Values -->
    <div class="card">
      <h3>Current Product Details</h3>
      <table style="font-size:14px;">
        <tr><td style="color:#888;padding:8px 0;">ID</td><td><strong>#<?= $product['id'] ?></strong></td></tr>
        <tr><td style="color:#888;padding:8px 0;">Name</td><td><strong><?= htmlspecialchars($product['name']) ?></strong></td></tr>
        <tr><td style="color:#888;padding:8px 0;">Category</td><td><?= htmlspecialchars($product['category']) ?></td></tr>
        <tr><td style="color:#888;padding:8px 0;">Retail Price</td><td>KES <?= number_format($product['price_retail'],2) ?></td></tr>
        <tr><td style="color:#888;padding:8px 0;">Wholesale Price</td><td>KES <?= number_format($product['price_wholesale'],2) ?></td></tr>
        <tr><td style="color:#888;padding:8px 0;">Stock</td><td><?= $product['stock'] ?> units</td></tr>
      </table>
      <div style="margin-top:16px;">
        <a href="read.php" class="btn btn-outline">← Back to Products</a>
      </div>
    </div>

  </div>
  <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
