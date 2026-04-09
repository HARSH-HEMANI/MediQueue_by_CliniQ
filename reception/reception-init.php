<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../db.php";

// Auth check
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: hospital_login.php?error=not_logged_in");
    exit();
}

// Safe variables
$reception_id = $_SESSION['receptionist_id'];
$reception_name = $_SESSION['receptionist_name'] ?? 'Receptionist';
$clinic_id = $_SESSION['clinic_id'] ?? 0;

// Safety check
if ($clinic_id == 0) {
    die("Clinic not assigned. Please login again.");
}

date_default_timezone_set('Asia/Kolkata');