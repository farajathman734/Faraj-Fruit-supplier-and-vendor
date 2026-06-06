<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Faraj Fruit Supplier & Vendor</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>
<header>
  <div class="logo">🍊 Faraj Fruit Supplier</div>
  <nav>
    <a href="/faraj/index.php">Home</a>
    <a href="/faraj/pages/products.php">Products</a>
    <a href="/faraj/pages/cart.php">🛒 Cart</a>
    <a href="/faraj/pages/contact.php">Contact</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="/faraj/pages/profile.php">👤 <?= htmlspecialchars($_SESSION['user_name']) ?></a>
      <a href="/faraj/pages/logout.php" style="color:rgba(255,255,255,0.6);">Logout</a>
    <?php else: ?>
      <a href="/faraj/pages/login.php" style="background:var(--amber);padding:6px 16px;border-radius:6px;">Login</a>
    <?php endif; ?>
  </nav>
</header>
