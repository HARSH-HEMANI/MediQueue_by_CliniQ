<?php
session_start();

$demo = include "./doc-credential.php";

$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

if ($email === $demo['email'] && $password === $demo['password']) {

    // FIX: Regenerate session ID after login to prevent session fixation attacks
    session_regenerate_id(true);

    $_SESSION['doctor_id']   = 1;
    $_SESSION['doctor_name'] = $demo['name'];

    header("Location: doctor.php");
    exit();
}

header("Location: login.php?error=1");
exit();
