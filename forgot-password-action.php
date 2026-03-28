<?php
session_start();
include "./db.php";
include "mailer.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get email
    $email = trim($_POST['email']);

    // ✅ Validate email
    if (empty($email)) {
        $_SESSION['login_error'] = "Email is required!";
        header("Location: forgot_password.php");
        exit();
    }

    // Escape email
    $email = mysqli_real_escape_string($con, $email);

    // ✅ Check if user exists
    $check = mysqli_query($con, "SELECT * FROM patients WHERE email = '$email'");

    if (mysqli_num_rows($check) == 0) {
        $_SESSION['login_error'] = "Email not registered!";
        header("Location: forgot_password.php");
        exit();
    }

    // ✅ Generate OTP
    $otp = rand(100000, 999999);

    // ✅ Set expiry (60 seconds)
    $expiry = date("Y-m-d H:i:s", strtotime("+60 seconds"));

    // ✅ Update DB
    $update = mysqli_query($con, "UPDATE patients 
        SET otp = '$otp', otp_expiry = '$expiry' 
        WHERE email = '$email'");

    if (!$update) {
        $_SESSION['login_error'] = "Something went wrong. Please try again!";
        header("Location: forgot_password.php");
        exit();
    }

    // ✅ Store email in session
    $_SESSION['verify_email'] = $email;

    // ✅ Email content (styled)
    $subject = "Password Reset OTP";

    $body = "
    <div style='font-family:Segoe UI, sans-serif; background:#f4f6fb; padding:40px;'>

        <div style='max-width:500px; margin:auto; background:#ffffff; padding:30px; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.08); text-align:center;'>

            <h2 style='color:#1a1a2e;'>Reset Your Password</h2>

            <p style='color:#555; font-size:14px; margin-bottom:25px;'>
                Use the OTP below to reset your password.
            </p>

            <div style='font-size:30px; font-weight:bold; letter-spacing:5px; color:#FF5A5F; margin:20px 0;'>
                $otp
            </div>

            <p style='color:#777; font-size:13px;'>
                This OTP is valid for <b>60 seconds</b>.<br>
                Do not share it with anyone.
            </p>

            <hr style='margin:25px 0;'>

            <p style='font-size:12px; color:#aaa;'>
                MediQueue by <span style='color:#FF5A5F;'>CliniQ</span>
            </p>

        </div>

    </div>
    ";

    //  Send email
    $result = sendEmail($email, $subject, $body);

    if ($result !== true) {
        $_SESSION['login_error'] = $result;
        header("Location: forgot_password.php");
        exit();
    }

    //  Redirect to OTP page
    header("Location: verify_otp.php");
    exit();
}
