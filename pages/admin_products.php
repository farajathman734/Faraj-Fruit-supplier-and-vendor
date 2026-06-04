<?php
session_start();
require_once '../config/db.php';

$success = $error = '';

// DELETE product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header('Location: admin_products.php?msg=deleted'); exit;
}

// ADD or EDIT product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = mysqli_real_escape_string($conn, $_POST['name']);
    $cat_id     = (int)$_POST['category_id'];
    $category   = mysqli_real_escape_string($conn, $_POST['category']);
    $desc       = mysqli_real_escape_string($conn, $_POST['description']);
    $retail     = (float)$_POST['price_retail'];
    $wholesale  = (float)$_POST['price_wholesale'];
    $min_qty    = (int)$_POST['min_wholesale_qty'];
    $stock      = (int)$_POST['stock'];

    if (isset($_POST['product_id']) && $_POST['product_id']) {
        $pid = (int)$_POST['product_id'];
        mysqli_query($conn, "UPDATE products SET name='$name', category_id=$cat_id, category='$category', description='$desc', price_retail=$retail, price_wholesale=$wholesale, min_wholesale_qty=$min_qty, stock=$stock WHERE id=$pid");
        $success = 'Product updated successfully.';
    } else {
        mysqli_query($conn, "INSERT INTO products (name,category_id,category,description,price_retail,price_wholesale,min_wholesale_qty,stock) VALUES ('$name',$cat_id,'$category','$desc',$retail,$wholesale,$min_qty,$stock)");
        $success = 'Product added successfully.';
    }
}

// GET product for editing
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=" . (int)$_GET['edit']));
}

$products   = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Manage Products – Faraj Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
  <div class="logo">🍊 Faraj Admin</div>
  <nav><a href="../index.php">View Site</a><a href="logout.php" style="color:rgba(255,255,255,0.6);">Logout</a></nav>
</header>

<div class="admin-layout">
  <aside class="sidebar">
    <div class="menu-title">Main Menu</div>
    <a href="admin.php">📊 Dashboard</a>
    <a href="admin_products.php" class="active">🍊 Products</a>
    <a href="admin_orders.php">📦 Orders</a>
    <a href="admin_users.php">👥 Customers</a>
    <a href="admin_messages.php">✉️ Messages</a>
    <div class="menu-title" style="margin-top:20px;">Settings</div>
    <a href="logout.php">🚪 Logout</a>
  </aside>

  <main class="admin-content">
    <h2 style="font-family:'Playfair Display',serif;color:var(--green-dark);margin-bottom:24px;">🍊 Manage Products</h2>

    <?php if ($success): ?>
      <div style="background:#e8f5ec;color:var(--green-dark);padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;font-weight:600;">✅ <?= $success ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:28px;align-items:start;">

      <!-- Products Table -->
      <div style="background:white;border-radius:var(--radius-lg);box-shadow:var(--shadow);overflow:hidden;border:1px solid var(--border);">
        <table style="width:100%;border-collapse:collapse;">
          <thead>
            <tr style="background:var(--green-dark);color:white;">
              <th style="padding:12px 16px;text-align:left;font-size:13px;">Product</th>
              <th style="padding:12px 16px;text-align:left;font-size:13px;">Retail</th>
              <th style="padding:12px 16px;text-align:left;font-size:13px;">Wholesale</th>
              <th style="padding:12px 16px;text-align:left;font-size:13px;">Stock</th>
              <th style="padding:12px 16px;text-align:left;font-size:13px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <tr style="border-bottom:1px solid var(--border);">
              <td style="padding:12px 16px;">
                <div style="font-weight:600;font-size:14px;"><?= htmlspecialchars($row['name']) ?></div>
                <div style="font-size:12px;color:#888;"><?= htmlspecialchars($row['category']) ?></div>
              </td>
              <td style="padding:12px 16px;font-size:13px;">KES <?= number_format($row['price_retail'],2) ?></td>
              <td style="padding:12px 16px;font-size:13px;color:var(--green-mid);font-weight:600;">KES <?= number_format($row['price_wholesale'],2) ?></td>
              <td style="padding:12px 16px;font-size:13px;"><?= $row['stock'] ?></td>
              <td style="padding:12px 16px;">
                <a href="?edit=<?= $row['id'] ?>" style="color:var(--green-mid);font-size:13px;font-weight:600;margin-right:10px;">✏️ Edit</a>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')" style="color:#cc0000;font-size:13px;font-weight:600;">🗑 Delete</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Add/Edit Form -->
      <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
        <h3 style="color:var(--green-dark);margin-bottom:18px;font-size:16px;"><?= $edit_product ? '✏️ Edit Product' : '+ Add New Product' ?></h3>
        <form method="POST">
          <input type="hidden" name="product_id" value="<?= $edit_product['id'] ?? '' ?>">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_product['name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="category_id" onchange="updateCategoryName(this)">
              <?php
              mysqli_data_seek($categories, 0);
              while ($cat = mysqli_fetch_assoc($categories)):
              ?>
              <option value="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['name']) ?>" <?= ($edit_product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
              <?php endwhile; ?>
            </select>
            <input type="hidden" name="category" id="category_name" value="<?= htmlspecialchars($edit_product['category'] ?? 'Tropical') ?>">
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="2"><?= htmlspecialchars($edit_product['description'] ?? '') ?></textarea>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="form-group">
              <label>Retail Price (KES)</label>
              <input type="number" name="price_retail" step="0.01" value="<?= $edit_product['price_retail'] ?? '' ?>" required>
            </div>
            <div class="form-group">
              <label>Wholesale Price (KES)</label>
              <input type="number" name="price_wholesale" step="0.01" value="<?= $edit_product['price_wholesale'] ?? '' ?>" required>
            </div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="form-group">
              <label>Min Wholesale Qty</label>
              <input type="number" name="min_wholesale_qty" value="<?= $edit_product['min_wholesale_qty'] ?? 10 ?>">
            </div>
            <div class="form-group">
              <label>Stock</label>
              <input type="number" name="stock" value="<?= $edit_product['stock'] ?? 0 ?>">
            </div>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;"><?= $edit_product ? 'Update Product' : 'Add Product' ?></button>
          <?php if ($edit_product): ?>
            <a href="admin_products.php" class="btn" style="width:100%;display:block;text-align:center;margin-top:8px;color:var(--green-dark);border:2px solid var(--green-dark);">Cancel</a>
          <?php endif; ?>
        </form>
      </div>

    </div>
  </main>
</div>
<script>
function updateCategoryName(select) {
    document.getElementById('category_name').value = select.options[select.selectedIndex].dataset.name;
}
</script>
</body>
</html>
