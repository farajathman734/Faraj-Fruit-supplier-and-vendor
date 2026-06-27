<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 7 – Logout / Session Destroy
// ============================================
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
?>
