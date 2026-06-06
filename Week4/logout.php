<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 4 – Logout (Session Destroy)
// ============================================

session_start();
session_unset();
session_destroy();

header('Location: login.php');
exit;
?>
