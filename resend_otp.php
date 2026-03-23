<?php
session_start();
include_once "db.php";
include_once "mailer.php";

if (!isset($_SESSION['verify_email'])) {
    header("Location: ./login.php");
    exit();
}

$email = mysqli_real_escape_string($con, $_SESSION['verify_email']);
$name  = $_SESSION['verify_name'] ?? 'User';

// Generate new OTP
$otp = rand(100000, 999999);

// Update token in DB
mysqli_query($con, "UPDATE patients SET token = '$otp' WHERE email = '$email' AND is_active = 0");

// Send new OTP email
$body = "
<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
    <div style='background: linear-gradient(135deg, #FF5A5F, #ff7a7f); padding: 30px; text-align: center; border-radius: 12px 12px 0 0;'>
        <h1 style='color: white; margin: 0; font-size: 28px;'>MediQueue</h1>
        <p style='color: rgba(255,255,255,0.85); margin: 5px 0 0;'>by CliniQ</p>
    </div>
    <div style='background: #ffffff; padding: 35px 30px; border: 1px solid #f0f0f0;'>
        <h2 style='color: #1a1a2e;'>New Verification Code</h2>
        <p style='color: #555; line-height: 1.7;'>Here is your new OTP for email verification:</p>
        <div style='text-align: center; margin: 30px 0;'>
            <div style='display: inline-block; background: #fff5f5; border: 2px dashed #FF5A5F; border-radius: 12px; padding: 20px 40px;'>
                <p style='color: #888; font-size: 13px; margin: 0 0 8px;'>Your Verification Code</p>
                <h1 style='color: #FF5A5F; font-size: 42px; letter-spacing: 10px; margin: 0; font-family: monospace;'>$otp</h1>
            </div>
        </div>
        <p style='color: #888; font-size: 13px; text-align: center;'>This code expires in <strong>10 minutes</strong>.</p>
    </div>
    <div style='background: #f8f8f8; padding: 15px; text-align: center; border-radius: 0 0 12px 12px;'>
        <p style='color: #aaa; font-size: 12px; margin: 0;'>2025 MediQueue by CliniQ. All rights reserved.</p>
    </div>
</div>
";

sendEmail($email, "New Verification Code - MediQueue", $body);

$_SESSION['otp_error'] = "A new OTP has been sent to your email.";
header("Location: ./verify_otp.php");
exit();
