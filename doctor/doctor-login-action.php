<?php
session_start();
include_once "../db.php";

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Email and password are required.";
    header("Location: login.php");
    exit();
}

$safe_email = mysqli_real_escape_string($con, $email);

// Find doctor by email (must be active / verified)
$query  = "SELECT doctor_id, full_name, email, password, is_active FROM doctors WHERE email = '$safe_email'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) === 1) {
    $doctor = mysqli_fetch_assoc($result);

    // Check if account is verified
    if ($doctor['is_active'] == 0) {
        $_SESSION['login_error'] = "Your account is not verified yet. Please check your email for the OTP.";
        header("Location: login.php");
        exit();
    }

    // Verify password
    if (password_verify($password, $doctor['password'])) {

        session_regenerate_id(true);

        $_SESSION['doctor_id']    = $doctor['doctor_id'];
        $_SESSION['doctor_name']  = $doctor['full_name'];
        $_SESSION['doctor_email'] = $doctor['email'];

        header("Location: doctor.php");
        exit();
    }
}

$_SESSION['login_error'] = "Invalid email or password.";
header("Location: login.php");
exit();
