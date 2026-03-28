<?php
session_start();
include_once "db.php";

if (isset($_POST['submit'])) {

    if (!isset($_SESSION['verify_email'])) {
        header("Location: ./login.php");
        exit();
    }

    $email = mysqli_real_escape_string($con, $_SESSION['verify_email']);

    // Combine OTP
    $otp = trim($_POST['otp1']) . trim($_POST['otp2']) . trim($_POST['otp3']) .
        trim($_POST['otp4']) . trim($_POST['otp5']) . trim($_POST['otp6']);

    if (empty($otp) || strlen($otp) < 6) {
        $_SESSION['otp_error'] = "Please enter the complete 6-digit OTP.";
        header("Location: ./verify_otp.php");
        exit();
    }

    // Check OTP
    $query = mysqli_query($con, "SELECT otp, otp_expiry FROM patients WHERE email='$email'");
    $data = mysqli_fetch_assoc($query);

    $current_time = date("Y-m-d H:i:s");

    if ($data) {

        if ($current_time > $data['otp_expiry']) {
            $_SESSION['otp_error'] = "OTP expired! Please request a new one.";
            header("Location: verify_otp.php");
            exit();
        }

        if ($otp == $data['otp']) {

            $_SESSION['reset_email'] = $email;

            header("Location: new-password.php");
            exit();
        }
    }

    $_SESSION['otp_error'] = "Invalid OTP!";
    header("Location: verify_otp.php");
}
