<?php
session_start();
include_once "../db.php";

if (!isset($_SESSION['receptionist_id'])) {
    header("Location: hospital_login.php");
    exit();
}

$rec_id = (int)$_SESSION['receptionist_id'];

$full_name = mysqli_real_escape_string($con, trim($_POST['full_name'] ?? ''));
$email     = mysqli_real_escape_string($con, trim($_POST['email'] ?? ''));
$phone     = mysqli_real_escape_string($con, trim($_POST['contact'] ?? ''));
$gender    = mysqli_real_escape_string($con, $_POST['gender'] ?? '');
$address   = mysqli_real_escape_string($con, trim($_POST['address'] ?? ''));

// Validation
if (empty($full_name) || empty($email) || empty($phone)) {
    $_SESSION['profile_error'] = "Name, email, and phone are required.";
    header("Location: profile_setting.php");
    exit();
}

// Check duplicate email (exclude self)
$check = mysqli_query($con, "SELECT receptionist_id FROM receptionists WHERE email = '$email' AND receptionist_id != $rec_id");
if (mysqli_num_rows($check) > 0) {
    $_SESSION['profile_error'] = "This email is already used by another account.";
    header("Location: profile_setting.php");
    exit();
}

// Update profile
$query = "UPDATE receptionists 
          SET full_name = '$full_name', email = '$email', phone = '$phone', 
              gender = '$gender', address = '$address'
          WHERE receptionist_id = $rec_id";

if (mysqli_query($con, $query)) {
    // Update session name
    $_SESSION['receptionist_name'] = $full_name;
    $_SESSION['receptionist_email'] = $email;

    // Handle password change if provided
    $new_pass = $_POST['newPassword'] ?? '';
    $confirm_pass = $_POST['confirmPassword'] ?? '';

    if (!empty($new_pass)) {
        if ($new_pass !== $confirm_pass) {
            $_SESSION['profile_error'] = "Profile updated but passwords do not match.";
            header("Location: profile_setting.php");
            exit();
        }
        $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        mysqli_query($con, "UPDATE receptionists SET password = '$hashed' WHERE receptionist_id = $rec_id");
    }

    $_SESSION['profile_success'] = "Profile updated successfully.";
} else {
    $_SESSION['profile_error'] = "Failed to update profile.";
}

header("Location: profile_setting.php");
exit();
