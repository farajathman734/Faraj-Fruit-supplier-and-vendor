<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faraj Fruit Supplier – Week 6 CRUD</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: Arial, sans-serif; background:#f5f5f5; color:#333; }
    header { background:#1a5c2e; color:white; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; }
    header .logo { font-size:20px; font-weight:bold; }
    header nav a { color:white; text-decoration:none; margin-left:20px; font-size:14px; }
    header nav a:hover { color:#f0a500; }
    .container { max-width:1100px; margin:30px auto; padding:0 20px; }
    .card { background:white; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.08); margin-bottom:24px; }
    h2 { color:#1a5c2e; margin-bottom:16px; font-size:22px; }
    h3 { color:#1a5c2e; margin-bottom:12px; }
    table { width:100%; border-collapse:collapse; }
    th { background:#1a5c2e; color:white; padding:12px 14px; text-align:left; font-size:13px; }
    td { padding:11px 14px; border-bottom:1px solid #eee; font-size:14px; }
    tr:hover td { background:#f9f9f9; }
    .form-group { margin-bottom:14px; }
    .form-group label { display:block; font-size:13px; font-weight:bold; margin-bottom:5px; color:#555; }
    .form-group input, .form-group select { width:100%; padding:9px 12px; border:1px solid #ddd; border-radius:6px; font-size:14px; }
    .btn { display:inline-block; padding:9px 20px; border-radius:6px; text-decoration:none; font-size:14px; font-weight:bold; cursor:pointer; border:none; }
    .btn-green { background:#1a5c2e; color:white; }
    .btn-amber { background:#f0a500; color:white; }
    .btn-red { background:#cc0000; color:white; }
    .btn-outline { background:white; border:2px solid #1a5c2e; color:#1a5c2e; }
    .alert-success { background:#e8f5ec; color:#1a5c2e; padding:12px 16px; border-radius:6px; margin-bottom:16px; font-weight:bold; border-left:4px solid #1a5c2e; }
    .alert-error { background:#fdecea; color:#cc0000; padding:12px 16px; border-radius:6px; margin-bottom:16px; border-left:4px solid #cc0000; }
    .code-box { background:#1a1a2e; color:#a8e6bf; font-family:monospace; font-size:12px; padding:16px; border-radius:8px; margin-bottom:20px; line-height:1.8; }
    footer { background:#1a5c2e; color:rgba(255,255,255,0.7); text-align:center; padding:20px; margin-top:50px; font-size:13px; }
  </style>
</head>
<body>
<header>
  <div class="logo">🍊 Faraj Fruit Supplier & Vendor</div>
  <nav>
    <a href="index.php">Dashboard</a>
    <a href="create.php">Add Product</a>
    <a href="read.php">View Products</a>
    <a href="update.php">Edit Product</a>
    <a href="delete.php">Delete Product</a>
  </nav>
</header>
