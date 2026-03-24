<?php
session_start();
include_once "../db.php";

$email    = mysqli_real_escape_string($con, trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Email and password are required.";
    header("Location: hospital_login.php?error=1");
    exit();
}

// Fetch receptionist by email
$query  = "SELECT * FROM receptionists WHERE email = '$email'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    // Check if account is active
    if ($row['is_active'] == 0) {
        $_SESSION['login_error'] = "Your account has been deactivated. Please contact your doctor.";
        header("Location: hospital_login.php?error=1");
        exit();
    }

    // Verify password
    if (password_verify($password, $row['password'])) {
        session_regenerate_id(true);

        $_SESSION['receptionist_id']    = $row['receptionist_id'];
        $_SESSION['receptionist_name']  = $row['full_name'];
        $_SESSION['receptionist_email'] = $row['email'];
        $_SESSION['doctor_id']          = $row['doctor_id'];
        $_SESSION['clinic_id']          = $row['clinic_id'];

        header("Location: reception_dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Incorrect password.";
        header("Location: hospital_login.php?error=1");
        exit();
    }
} else {
    $_SESSION['login_error'] = "No account found with this email.";
    header("Location: hospital_login.php?error=1");
    exit();
}
