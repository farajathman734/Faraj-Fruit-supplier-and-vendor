<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 6 – CREATE Operation (INSERT)
// ============================================
require_once 'config/db.php';
require_once 'includes/header.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = mysqli_real_escape_string($conn, $_POST['name']);
    $category  = mysqli_real_escape_string($conn, $_POST['category']);
    $retail    = (float)$_POST['price_retail'];
    $wholesale = (float)$_POST['price_wholesale'];
    $stock     = (int)$_POST['stock'];

    if (empty($name)) {
        $error = "Product name is required.";
    } elseif ($retail <= 0) {
        $error = "Retail price must be greater than 0.";
    } else {
        $sql = "INSERT INTO products (name, category, price_retail, price_wholesale, stock)
                VALUES ('$name', '$category', '$retail', '$wholesale', '$stock')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Product '$name' added successfully! ID: " . mysqli_insert_id($conn);
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container">
  <h2>➕ CREATE Operation – Add New Product</h2>

  <div class="code-box">
    // PHP INSERT query – CREATE Operation<br>
    $sql = "INSERT INTO products (name, category, price_retail, price_wholesale, stock)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VALUES ('$name', '$category', '$retail', '$wholesale', '$stock')";<br>
    mysqli_query($conn, $sql);
  </div>

  <?php if ($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

    <!-- Form -->
    <div class="card">
      <h3>New Fruit Product Form</h3>
      <form method="POST">
        <div class="form-group">
          <label>Product Name *</label>
          <input type="text" name="name" placeholder="e.g. Strawberry" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Category</label>
          <select name="category">
            <option value="Tropical">Tropical</option>
            <option value="Citrus">Citrus</option>
            <option value="Temperate">Temperate</option>
            <option value="Melons">Melons</option>
            <option value="Berries">Berries</option>
          </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
          <div class="form-group">
            <label>Retail Price (KES) *</label>
            <input type="number" step="0.01" name="price_retail" placeholder="e.g. 25.00" required value="<?= htmlspecialchars($_POST['price_retail'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Wholesale Price (KES)</label>
            <input type="number" step="0.01" name="price_wholesale" placeholder="e.g. 18.00" value="<?= htmlspecialchars($_POST['price_wholesale'] ?? '') ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Stock Quantity</label>
          <input type="number" name="stock" placeholder="e.g. 100" value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>">
        </div>
        <button type="submit" class="btn btn-green" style="width:100%;">Save Product to Database</button>
      </form>
    </div>

    <!-- Explanation -->
    <div class="card">
      <h3>How CREATE Works</h3>
      <ol style="line-height:2.2;font-size:14px;padding-left:18px;">
        <li>User fills in the product form</li>
        <li>Form is submitted using <strong>method="POST"</strong></li>
        <li>PHP receives data via <strong>$_POST</strong></li>
        <li>Input is sanitized using <strong>mysqli_real_escape_string()</strong></li>
        <li>SQL <strong>INSERT</strong> query is built</li>
        <li>Query is executed using <strong>mysqli_query()</strong></li>
        <li>Success or error message is shown</li>
      </ol>
      <div style="margin-top:16px;padding:12px;background:#f5f5f5;border-radius:6px;font-size:13px;color:#555;">
        <strong>Input Validation:</strong><br>
        ✅ Product name required<br>
        ✅ Price must be greater than 0<br>
        ✅ Input sanitized against SQL injection
      </div>
      <div style="margin-top:12px;">
        <a href="read.php" class="btn btn-outline">View All Products →</a>
      </div>
    </div>

  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
