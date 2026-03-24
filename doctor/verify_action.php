<?php
session_start();
include_once "../db.php";

if (isset($_POST['submit'])) {

    if (!isset($_SESSION['doc_verify_email'])) {
        header("Location: ./login.php");
        exit();
    }

    $email = mysqli_real_escape_string($con, $_SESSION['doc_verify_email']);

    // Combine 6 OTP inputs
    $otp = trim($_POST['otp1']) . trim($_POST['otp2']) . trim($_POST['otp3']) .
        trim($_POST['otp4']) . trim($_POST['otp5']) . trim($_POST['otp6']);

    if (empty($otp) || strlen($otp) < 6) {
        $_SESSION['doc_otp_error'] = "Please enter the complete 6-digit OTP.";
        header("Location: ./verify_otp.php");
        exit();
    }

    // Check OTP matches token in DB
    $query  = "SELECT doctor_id, full_name FROM doctors WHERE email = '$email' AND token = '$otp' AND is_active = 0";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        // Activate account and clear token
        mysqli_query($con, "UPDATE doctors SET is_active = 1, token = NULL WHERE email = '$email'");

        // Clear session verify data
        unset($_SESSION['doc_verify_email']);
        unset($_SESSION['doc_verify_name']);

        // Redirect to login with success
        header("Location: ./login.php?success=1");
        exit();
    } else {
        $_SESSION['doc_otp_error'] = "Invalid OTP. Please check your email and try again.";
        header("Location: ./verify_otp.php");
        exit();
    }
}
