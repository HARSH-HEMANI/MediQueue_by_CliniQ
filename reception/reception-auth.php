<?php
session_start();

if (!isset($_SESSION['receptionist_id'])) {
    header("Location: hospital_login.php");
    exit();
}
