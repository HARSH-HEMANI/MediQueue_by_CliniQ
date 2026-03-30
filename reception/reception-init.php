<?php

/**
 * reception-init.php
 * Included at the top of all dashboard files.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Adjusted path to look one directory up for db.php
require_once __DIR__ . "/../db.php";

/**
 * AUTHENTICATION GUARD
 * Checks if a receptionist is logged in.
 */
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: hospital_login.php?error=not_logged_in");
    exit();
}

// Common variables
$reception_id = $_SESSION['receptionist_id'];
$reception_name = $_SESSION['receptionist_name'] ?? 'Receptionist';

date_default_timezone_set('Asia/Kolkata');
