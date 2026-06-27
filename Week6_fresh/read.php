<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 6 – READ Operation (SELECT)
// ============================================
require_once 'config/db.php';
require_once 'includes/header.php';

$search   = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

$where = [];
if ($search) $where[] = "name LIKE '%$search%'";
if ($category) $where[] = "category='$category'";
$where_sql = $where ? "WHERE " . implode(' AND ', $where) : '';

$result = mysqli_query($conn, "SELECT * FROM products $where_sql ORDER BY id DESC");
$total  = mysqli_num_rows($result);
$emojis = ['Banana'=>'🍌','Mango'=>'🥭','Apple'=>'🍎','Watermelon'=>'🍉','Pineapple'=>'🍍','Pawpaw'=>'🍈','Orange'=>'🍊','Lemon'=>'🍋','Passion Fruit'=>'🟣'];
?>

<div class="container">
  <h2>📋 READ Operation – View All Products</h2>

  <div class="code-box">
    // PHP SELECT query – READ Operation<br>
    $result = mysqli_query($conn, "SELECT * FROM products");<br>
    while ($row = mysqli_fetch_assoc($result)) {<br>
    &nbsp;&nbsp;echo $row['name'] . " - KES " . $row['price_retail'];<br>
    }
  </div>

  <!-- Search & Filter -->
  <div class="card">
    <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
      <div class="form-group" style="margin:0;flex:1;">
        <label>Search Product</label>
        <input type="text" name="search" placeholder="e.g. Mango" value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="form-group" style="margin:0;flex:1;">
        <label>Filter by Category</label>
        <select name="category">
          <option value="">All Categories</option>
          <?php foreach(['Tropical','Citrus','Temperate','Melons','Berries'] as $cat): ?>
          <option value="<?= $cat ?>" <?= $category===$cat?'selected':'' ?>><?= $cat ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-green">Search</button>
      <a href="read.php" class="btn btn-outline">Reset</a>
      <a href="create.php" class="btn btn-amber">+ Add Product</a>
    </form>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:16px 20px;background:#f9f9f9;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
      <strong style="color:#1a5c2e;">Total Records: <?= $total ?></strong>
    </div>
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Product</th><th>Category</th><th>Retail (KES)</th><th>Wholesale (KES)</th><th>Stock</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($total > 0):
          while ($row = mysqli_fetch_assoc($result)):
            $emoji = $emojis[$row['name']] ?? '🍊';
        ?>
        <tr>
          <td style="color:#aaa;font-size:12px;">#<?= $row['id'] ?></td>
          <td><strong><?= $emoji ?> <?= htmlspecialchars($row['name']) ?></strong></td>
          <td><span style="background:#e8f5ec;color:#1a5c2e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:bold;"><?= htmlspecialchars($row['category']) ?></span></td>
          <td><?= number_format($row['price_retail'],2) ?></td>
          <td style="color:#2e8b4a;font-weight:bold;"><?= number_format($row['price_wholesale'],2) ?></td>
          <td><?= $row['stock'] ?></td>
          <td>
            <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-amber btn-sm" style="font-size:12px;padding:5px 12px;">✏️ Edit</a>
            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-red btn-sm" style="font-size:12px;padding:5px 12px;margin-left:4px;" onclick="return confirm('Delete <?= htmlspecialchars($row['name']) ?>?')">🗑 Delete</a>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="7" style="text-align:center;padding:30px;color:#888;">No products found. <a href="create.php">Add one →</a></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
