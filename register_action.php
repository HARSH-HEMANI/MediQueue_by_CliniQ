<?php
session_start();
include_once "db.php";
include_once "mailer.php";

if (isset($_POST['submit'])) {

    $full_name = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email     = mysqli_real_escape_string($con, trim($_POST['email']));
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];
    $phone     = mysqli_real_escape_string($con, trim($_POST['phone']));
    $gender    = mysqli_real_escape_string($con, $_POST['gender']);
    $dob       = mysqli_real_escape_string($con, $_POST['dob']);
    $address   = mysqli_real_escape_string($con, trim($_POST['address']));
    $pincode       = mysqli_real_escape_string($con, trim($_POST['pincode']));

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password) || empty($phone) || empty($gender) || empty($dob) || empty($pincode) || empty($address)) {
        $_SESSION['reg_error'] = "All fields are required.";
        header("Location: ./login.php#register");
        exit();
    }

    if ($password !== $confirm) {
        $_SESSION['reg_error'] = "Passwords do not match.";
        header("Location: ./login.php#register");
        exit();
    }

    // Check if email already exists
    $check = mysqli_query($con, "SELECT patient_id FROM patients WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['reg_error'] = "Email already registered. Please login.";
        header("Location: ./login.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);

    // Insert patient as inactive (is_active = 0), store OTP in token column
    $query = "INSERT INTO patients (full_name, email, password, phone, gender, date_of_birth, address, pincode, is_active, token)
            VALUES ('$full_name', '$email', '$hashed_password', '$phone', '$gender', '$dob', '$address', '$pincode', 0, '$otp')";

    if (mysqli_query($con, $query)) {

        // Send OTP email
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: linear-gradient(135deg, #FF5A5F, #ff7a7f); padding: 30px; text-align: center; border-radius: 12px 12px 0 0;'>
                <h1 style='color: white; margin: 0; font-size: 28px;'>MediQueue</h1>
                <p style='color: rgba(255,255,255,0.85); margin: 5px 0 0;'>by CliniQ</p>
            </div>
            <div style='background: #ffffff; padding: 35px 30px; border: 1px solid #f0f0f0;'>
                <h2 style='color: #1a1a2e;'>Welcome, $full_name! 👋</h2>
                <p style='color: #555; line-height: 1.7;'>Thank you for registering with MediQueue. Use the OTP below to verify your email address.</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <div style='display: inline-block; background: #fff5f5; border: 2px dashed #FF5A5F; border-radius: 12px; padding: 20px 40px;'>
                        <p style='color: #888; font-size: 13px; margin: 0 0 8px;'>Your Verification Code</p>
                        <h1 style='color: #FF5A5F; font-size: 42px; letter-spacing: 10px; margin: 0; font-family: monospace;'>$otp</h1>
                    </div>
                </div>
                <p style='color: #888; font-size: 13px; text-align: center;'>This code expires in <strong>10 minutes</strong>. Do not share it with anyone.</p>
                <p style='color: #888; font-size: 13px;'>If you did not create this account, you can safely ignore this email.</p>
            </div>
            <div style='background: #f8f8f8; padding: 15px; text-align: center; border-radius: 0 0 12px 12px;'>
                <p style='color: #aaa; font-size: 12px; margin: 0;'>2025 MediQueue by CliniQ. All rights reserved.</p>
            </div>
        </div>
        ";

        $mail_result = sendEmail($email, "Your Verification Code - MediQueue", $body);

        // Store email in session for OTP verify page
        $_SESSION['verify_email'] = $email;
        $_SESSION['verify_name']  = $full_name;

        if ($mail_result === true) {
            $_SESSION['success'] = "OTP sent to $email. Please verify to complete registration.";
        } else {
            $_SESSION['success'] = "Registered! OTP could not be sent — please contact support.";
        }

        header("Location: ./verify_otp.php");
        exit();
    } else {
        $_SESSION['reg_error'] = "Registration failed. Please try again.";
        header("Location: ./login.php#register");
        exit();
    }
}
