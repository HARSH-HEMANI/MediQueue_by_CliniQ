<?php
session_start();
include "../db.php";

// Ensure doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = (int)$_SESSION['doctor_id'];
$action = $_POST['action'] ?? '';

// ── ADD RECEPTIONIST ──
if ($action === 'add') {
    $full_name  = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email      = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone      = mysqli_real_escape_string($con, trim($_POST['phone']));
    $gender     = mysqli_real_escape_string($con, $_POST['gender']);
    $clinic_id  = (int)$_POST['clinic_id'];
    $address    = mysqli_real_escape_string($con, trim($_POST['address']));
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($clinic_id)) {
        $_SESSION['rec_error'] = "All fields are required.";
        header("Location: manage-receptionist.php");
        exit();
    }

    if ($password !== $confirm) {
        $_SESSION['rec_error'] = "Passwords do not match.";
        header("Location: manage-receptionist.php");
        exit();
    }

    // Check duplicate email
    $check = mysqli_query($con, "SELECT receptionist_id FROM receptionists WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['rec_error'] = "A receptionist with this email already exists.";
        header("Location: manage-receptionist.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO receptionists (doctor_id, clinic_id, full_name, email, password, phone, gender, address, is_active)
              VALUES ($doctor_id, $clinic_id, '$full_name', '$email', '$hashed_password', '$phone', '$gender', '$address', 1)";

    if (mysqli_query($con, $query)) {
        $_SESSION['rec_success'] = "Receptionist added successfully.";
    } else {
        $_SESSION['rec_error'] = "Failed to add receptionist: " . mysqli_error($con);
    }

    header("Location: manage-receptionist.php");
    exit();
}

// ── EDIT RECEPTIONIST ──
if ($action === 'edit') {
    $rec_id     = (int)$_POST['receptionist_id'];
    $full_name  = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email      = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone      = mysqli_real_escape_string($con, trim($_POST['phone']));
    $gender     = mysqli_real_escape_string($con, $_POST['gender']);
    $clinic_id  = (int)$_POST['clinic_id'];
    $address    = mysqli_real_escape_string($con, trim($_POST['address']));

    if (empty($full_name) || empty($email) || empty($phone) || empty($clinic_id)) {
        $_SESSION['rec_error'] = "All fields are required.";
        header("Location: manage-receptionist.php");
        exit();
    }

    // Check duplicate email (exclude self)
    $check = mysqli_query($con, "SELECT receptionist_id FROM receptionists WHERE email = '$email' AND receptionist_id != $rec_id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['rec_error'] = "Another receptionist with this email already exists.";
        header("Location: manage-receptionist.php");
        exit();
    }

    $query = "UPDATE receptionists 
              SET full_name = '$full_name', email = '$email', phone = '$phone', 
                  gender = '$gender', clinic_id = $clinic_id, address = '$address'
              WHERE receptionist_id = $rec_id AND doctor_id = $doctor_id";

    if (mysqli_query($con, $query)) {
        // Handle password reset if provided
        $new_pass = $_POST['new_password'] ?? '';
        $confirm_new = $_POST['confirm_new_password'] ?? '';

        if (!empty($new_pass)) {
            if ($new_pass !== $confirm_new) {
                $_SESSION['rec_error'] = "Profile updated but passwords do not match.";
                header("Location: manage-receptionist.php");
                exit();
            }
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            mysqli_query($con, "UPDATE receptionists SET password = '$hashed' WHERE receptionist_id = $rec_id AND doctor_id = $doctor_id");
        }

        $_SESSION['rec_success'] = "Receptionist updated successfully.";
    } else {
        $_SESSION['rec_error'] = "Failed to update receptionist.";
    }

    header("Location: manage-receptionist.php");
    exit();
}

// ── TOGGLE ACTIVE/INACTIVE ──
if ($action === 'toggle') {
    $rec_id = (int)$_POST['receptionist_id'];

    $query = "UPDATE receptionists SET is_active = NOT is_active 
              WHERE receptionist_id = $rec_id AND doctor_id = $doctor_id";
    mysqli_query($con, $query);

    $_SESSION['rec_success'] = "Receptionist status updated.";
    header("Location: manage-receptionist.php");
    exit();
}

// ── DELETE RECEPTIONIST ──
if ($action === 'delete') {
    $rec_id = (int)$_POST['receptionist_id'];

    $query = "DELETE FROM receptionists WHERE receptionist_id = $rec_id AND doctor_id = $doctor_id";

    if (mysqli_query($con, $query)) {
        $_SESSION['rec_success'] = "Receptionist deleted successfully.";
    } else {
        $_SESSION['rec_error'] = "Failed to delete receptionist.";
    }

    header("Location: manage-receptionist.php");
    exit();
}

// Fallback
header("Location: manage-receptionist.php");
exit();
