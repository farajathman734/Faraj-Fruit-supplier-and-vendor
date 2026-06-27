<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 7 – Database Connection
// BIT3208 – User Authentication & Sessions
// ============================================
$conn = mysqli_connect("localhost", "root", "", "faraj_db");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
