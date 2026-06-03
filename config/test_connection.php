<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Database Connection Test
// Take your Fig 5 screenshot from this page
// ============================================

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'faraj_db';

$conn = mysqli_connect($host, $user, $pass, $db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DB Connection Test – Faraj</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f0f9f4;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 40px 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        h2 { color: #1a5c2e; margin-bottom: 10px; }
        .success { color: #2e8b4a; font-size: 18px; font-weight: bold; }
        .error   { color: #cc0000; font-size: 18px; font-weight: bold; }
        .info    { color: #555; font-size: 14px; margin-top: 15px; }
        .badge {
            display: inline-block;
            background: #e8f5ec;
            color: #1a5c2e;
            border-radius: 20px;
            padding: 6px 18px;
            margin-top: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>🍊 Faraj Fruit Supplier</h2>
    <p style="color:#888; margin-bottom:20px;">Database Connection Test</p>

    <?php if ($conn): ?>
        <p class="success">✅ Connected Successfully!</p>
        <p class="info">PHP connected to MySQL database:</p>
        <span class="badge">📦 faraj_db</span>
        <p class="info" style="margin-top:20px;">
            Host: <strong><?= $host ?></strong><br>
            User: <strong><?= $user ?></strong><br>
            MySQL Version: <strong><?= mysqli_get_server_info($conn) ?></strong>
        </p>
    <?php else: ?>
        <p class="error">❌ Connection Failed</p>
        <p class="info"><?= mysqli_connect_error() ?></p>
    <?php endif; ?>
</div>
</body>
</html>
