<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Homepage – index.php (Hello World Page)
// Week 1: Environment Setup
// ============================================

require_once 'includes/header.php';
require_once 'config/db.php';
?>

<!-- ── Hero Section ── -->
<section class="hero">
    <h1>🍊 Welcome to Faraj Fruit Supplier</h1>
    <p>Your trusted source for fresh fruits — wholesale & retail quantities</p>
    <a href="pages/products.php" class="btn">Browse Products</a>
    <a href="pages/contact.php" class="btn btn-outline">Contact Us</a>
</section>

<!-- ── Features ── -->
<div class="section">
    <h2>Why Choose Faraj?</h2>
    <div class="cards">

        <div class="card">
            <div class="icon">🛒</div>
            <h3>Retail Orders</h3>
            <p>Buy fresh fruits in small quantities for personal or household use.</p>
        </div>

        <div class="card">
            <div class="icon">📦</div>
            <h3>Wholesale Orders</h3>
            <p>Bulk purchasing available for vendors, hotels, and restaurants at competitive prices.</p>
        </div>

        <div class="card">
            <div class="icon">🚚</div>
            <h3>Fast Delivery</h3>
            <p>We deliver fresh fruits directly to your door, ensuring quality every time.</p>
        </div>

        <div class="card">
            <div class="icon">✅</div>
            <h3>Quality Assured</h3>
            <p>All products are freshly sourced and quality-checked before dispatch.</p>
        </div>

    </div>
</div>

<!-- ── System Status (Week 1 Dev Note) ── -->
<div class="section" style="padding-top:0;">
    <div style="background:#e8f5ec; border-left:5px solid #2e8b4a; padding:20px 25px; border-radius:5px;">
        <h3 style="color:#1a5c2e; margin-bottom:10px;">🛠️ Week 1 – Environment Status</h3>
        <?php if ($conn): ?>
            <p style="color:#2e8b4a; font-weight:bold;">✅ PHP is running correctly</p>
            <p style="color:#2e8b4a; font-weight:bold;">✅ MySQL database (faraj_db) connected</p>
            <p style="color:#555; font-size:14px; margin-top:8px;">
                Server: <strong><?= $_SERVER['SERVER_NAME'] ?></strong> |
                PHP Version: <strong><?= phpversion() ?></strong> |
                MySQL: <strong><?= mysqli_get_server_info($conn) ?></strong>
            </p>
        <?php else: ?>
            <p style="color:red;">❌ Database not connected. Please run <code>config/faraj_db.sql</code> in phpMyAdmin first.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
