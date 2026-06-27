<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 6 – CRUD Dashboard
// BIT3208 – Advanced Web Design & Development
// ============================================
require_once 'config/db.php';
require_once 'includes/header.php';

$total    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products"))['c'];
$tropical = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products WHERE category='Tropical'"))['c'];
$citrus   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products WHERE category='Citrus'"))['c'];
$stock    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stock) as s FROM products"))['s'];
?>

<div class="container">

  <div class="code-box">
    // BIT3208 – Week 6: Database Integration and CRUD Operations<br>
    // CRUD = Create, Read, Update, Delete<br>
    // Project: Faraj Fruit Supplier and Vendor – Product Management
  </div>

  <h2>📊 CRUD Operations Dashboard</h2>

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
    <?php foreach([
      ['🍊','Total Products',$total,'In database'],
      ['🌴','Tropical',$tropical,'products'],
      ['🍋','Citrus',$citrus,'products'],
      ['📦','Total Stock',$stock,'units'],
    ] as $s): ?>
    <div class="card" style="text-align:center;padding:20px;">
      <div style="font-size:30px;margin-bottom:8px;"><?= $s[0] ?></div>
      <div style="font-size:24px;font-weight:bold;color:#1a5c2e;"><?= $s[2] ?></div>
      <div style="font-size:12px;color:#888;"><?= $s[1] ?> <?= $s[3] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
    <a href="create.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #1a5c2e;">
      <div style="font-size:40px;margin-bottom:10px;">➕</div>
      <h3>CREATE</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">Add a new fruit product to the database</p>
      <div class="code-box" style="margin-top:12px;text-align:left;">INSERT INTO products (...) VALUES (...)</div>
    </a>
    <a href="read.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #2196f3;">
      <div style="font-size:40px;margin-bottom:10px;">📋</div>
      <h3>READ</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">View all fruit products from the database</p>
      <div class="code-box" style="margin-top:12px;text-align:left;">SELECT * FROM products</div>
    </a>
    <a href="update.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #f0a500;">
      <div style="font-size:40px;margin-bottom:10px;">✏️</div>
      <h3>UPDATE</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">Edit an existing product's details</p>
      <div class="code-box" style="margin-top:12px;text-align:left;">UPDATE products SET ... WHERE id=?</div>
    </a>
    <a href="delete.php" class="card" style="text-decoration:none;text-align:center;padding:30px;border:2px solid #cc0000;">
      <div style="font-size:40px;margin-bottom:10px;">🗑️</div>
      <h3>DELETE</h3>
      <p style="color:#888;font-size:13px;margin-top:6px;">Remove a product from the database</p>
      <div class="code-box" style="margin-top:12px;text-align:left;">DELETE FROM products WHERE id=?</div>
    </a>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
