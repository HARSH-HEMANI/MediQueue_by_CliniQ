<?php
// admin-init.php
session_start();

// 1. Security Check: Kick out unauthorized users instantly
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// 2. Establish Database Connection (Adjust path if necessary)
require_once "../db.php"; 
?>