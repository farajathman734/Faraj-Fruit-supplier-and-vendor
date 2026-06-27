<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 6 – Database Connection
// BIT3208 – Advanced Web Design & Development
// ============================================
$conn = mysqli_connect("localhost", "root", "", "faraj_db");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
