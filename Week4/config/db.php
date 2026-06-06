<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 4 – Database Connection
// ============================================
$conn = mysqli_connect("localhost", "root", "", "faraj_db");

if (!$conn) {
    die("<p style='color:red;font-family:Arial;padding:20px;'>
        ❌ Connection Failed: " . mysqli_connect_error() . "
    </p>");
}
?>
