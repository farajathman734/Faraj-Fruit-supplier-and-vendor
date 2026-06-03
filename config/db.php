<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Database Configuration File
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Default XAMPP username
define('DB_PASS', '');           // Default XAMPP password (empty)
define('DB_NAME', 'faraj_db');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("<p style='color:red; font-family:Arial;'>
        ❌ Connection Failed: " . mysqli_connect_error() . "
    </p>");
}
?>
