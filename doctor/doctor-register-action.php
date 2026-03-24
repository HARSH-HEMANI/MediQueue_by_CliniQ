<?php
session_start();
include_once "../db.php";
include_once "../mailer.php";

// Ensure doctors table has token column
$col_check = mysqli_query($con, "SHOW COLUMNS FROM doctors LIKE 'token'");
if (mysqli_num_rows($col_check) == 0) {
    mysqli_query($con, "ALTER TABLE doctors ADD COLUMN token VARCHAR(10) DEFAULT NULL");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name      = mysqli_real_escape_string($con, trim($_POST['name']));
    $email          = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone          = mysqli_real_escape_string($con, trim($_POST['phone']));
    $specialization = mysqli_real_escape_string($con, trim($_POST['specialization']));
    $qualification  = mysqli_real_escape_string($con, trim($_POST['qualification']));
    $experience     = (int) $_POST['experience_years'];
    $clinic_name    = mysqli_real_escape_string($con, trim($_POST['clinic']));
    $password       = $_POST['password'];
    $confirm        = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($specialization) || empty($qualification) || empty($clinic_name) || empty($password)) {
        $_SESSION['reg_error'] = "All fields are required.";
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirm) {
        $_SESSION['reg_error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Check duplicate email
    $check = mysqli_query($con, "SELECT doctor_id FROM doctors WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['reg_error'] = "Email already registered. Please login.";
        header("Location: login.php");
        exit();
    }

    // Create clinic first
    $clinic_query = "INSERT INTO clinics (clinic_name) VALUES ('$clinic_name')";
    if (!mysqli_query($con, $clinic_query)) {
        $_SESSION['reg_error'] = "Failed to create clinic: " . mysqli_error($con);
        header("Location: register.php");
        exit();
    }
    $clinic_id = mysqli_insert_id($con);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);

    // Insert doctor as inactive, store OTP in token column
    $query = "INSERT INTO doctors (clinic_id, full_name, specialization, qualification, experience_years, email, password, phone, is_active, token)
              VALUES ($clinic_id, '$full_name', '$specialization', '$qualification', $experience, '$email', '$hashed_password', '$phone', 0, '$otp')";

    if (mysqli_query($con, $query)) {

        // Send OTP email
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: linear-gradient(135deg, #FF5A5F, #ff7a7f); padding: 30px; text-align: center; border-radius: 12px 12px 0 0;'>
                <h1 style='color: white; margin: 0; font-size: 28px;'>MediQueue</h1>
                <p style='color: rgba(255,255,255,0.85); margin: 5px 0 0;'>by CliniQ</p>
            </div>
            <div style='background: #ffffff; padding: 35px 30px; border: 1px solid #f0f0f0;'>
                <h2 style='color: #1a1a2e;'>Welcome, Dr. $full_name! 👋</h2>
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
        $_SESSION['doc_verify_email'] = $email;
        $_SESSION['doc_verify_name']  = $full_name;

        if ($mail_result === true) {
            $_SESSION['reg_success'] = "OTP sent to $email. Please verify to complete registration.";
        } else {
            $_SESSION['reg_success'] = "Registered! OTP could not be sent — please contact support.";
        }

        header("Location: verify_otp.php");
        exit();
    } else {
        // If doctor insert failed, clean up the clinic we just created
        mysqli_query($con, "DELETE FROM clinics WHERE clinic_id = $clinic_id");
        $_SESSION['reg_error'] = "Registration failed: " . mysqli_error($con);
        header("Location: register.php");
        exit();
    }
}
