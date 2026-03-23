<?php
session_start();
include_once "db.php";

if (isset($_POST['submit'])) {

    // If no email in session, redirect
    if (!isset($_SESSION['verify_email'])) {
        header("Location: ./login.php");
        exit();
    }

    $email = mysqli_real_escape_string($con, $_SESSION['verify_email']);

    // Combine 6 OTP inputs into one code
    $otp = trim($_POST['otp1']) . trim($_POST['otp2']) . trim($_POST['otp3']) .
        trim($_POST['otp4']) . trim($_POST['otp5']) . trim($_POST['otp6']);

    if (empty($otp) || strlen($otp) < 6) {
        $_SESSION['otp_error'] = "Please enter the complete 6-digit OTP.";
        header("Location: ./verify_otp.php");
        exit();
    }

    // Check OTP matches token in DB for this email
    $query  = "SELECT patient_id, full_name FROM patients WHERE email = '$email' AND token = '$otp' AND is_active = 0";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Activate account and clear token
        mysqli_query($con, "UPDATE patients SET is_active = 1, token = NULL WHERE email = '$email'");

        // Clear session verify data
        unset($_SESSION['verify_email']);
        unset($_SESSION['verify_name']);

        // Show success on login page
        $_SESSION['success'] = "Email verified successfully! You can now login.";
        header("Location: ./login.php");
        exit();
    } else {
        $_SESSION['otp_error'] = "Invalid OTP. Please check your email and try again.";
        header("Location: ./verify_otp.php");
        exit();
    }
}
