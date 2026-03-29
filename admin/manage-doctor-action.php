<?php
session_start();
include "../db.php"; // Adjust path if db.php is in the root

// Ensure Admin is logged in (Adjust session variable name to match your admin login logic)
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$action = $_POST['action'] ?? '';

// ── ADD DOCTOR ──
if ($action === 'add') {
    $full_name      = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email          = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone          = mysqli_real_escape_string($con, trim($_POST['phone']));
    $specialization = mysqli_real_escape_string($con, trim($_POST['specialization']));
    $qualification  = mysqli_real_escape_string($con, trim($_POST['qualification']));
    $experience     = (int)$_POST['experience_years'];
    $clinic_name    = mysqli_real_escape_string($con, trim($_POST['clinic_name']));
    $password       = $_POST['password'];
    $confirm        = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($specialization) || empty($clinic_name) || empty($password)) {
        $_SESSION['admin_error'] = "All mandatory fields are required.";
        header("Location: doctor.php");
        exit();
    }

    if ($password !== $confirm) {
        $_SESSION['admin_error'] = "Passwords do not match.";
        header("Location: doctor.php");
        exit();
    }

    // Check duplicate email
    $check = mysqli_query($con, "SELECT doctor_id FROM doctors WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['admin_error'] = "A doctor with this email already exists.";
        header("Location: doctor.php");
        exit();
    }

    // 1. Create a clinic for the doctor first
    $clinic_query = "INSERT INTO clinics (clinic_name) VALUES ('$clinic_name')";
    if (!mysqli_query($con, $clinic_query)) {
        $_SESSION['admin_error'] = "Failed to create clinic: " . mysqli_error($con);
        header("Location: doctor.php");
        exit();
    }
    $clinic_id = mysqli_insert_id($con);

    // 2. Hash password and insert doctor (Setting is_active to 1 since Admin is adding them)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO doctors (clinic_id, full_name, specialization, qualification, experience_years, email, password, phone, is_active)
              VALUES ($clinic_id, '$full_name', '$specialization', '$qualification', $experience, '$email', '$hashed_password', '$phone', 1)";

    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Doctor and Clinic added successfully.";
    } else {
        // Rollback clinic if doctor fails
        mysqli_query($con, "DELETE FROM clinics WHERE clinic_id = $clinic_id");
        $_SESSION['admin_error'] = "Failed to add doctor: " . mysqli_error($con);
    }

    header("Location: doctor.php");
    exit();
}

// ── EDIT DOCTOR ──
if ($action === 'edit') {
    $doctor_id      = (int)$_POST['doctor_id'];
    $full_name      = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email          = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone          = mysqli_real_escape_string($con, trim($_POST['phone']));
    $specialization = mysqli_real_escape_string($con, trim($_POST['specialization']));
    $qualification  = mysqli_real_escape_string($con, trim($_POST['qualification']));
    $experience     = (int)$_POST['experience_years'];

    if (empty($full_name) || empty($email) || empty($phone)) {
        $_SESSION['admin_error'] = "Name, email, and phone are required.";
        header("Location: doctor.php");
        exit();
    }

    // Check duplicate email (exclude the doctor being edited)
    $check = mysqli_query($con, "SELECT doctor_id FROM doctors WHERE email = '$email' AND doctor_id != $doctor_id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['admin_error'] = "Another doctor with this email already exists.";
        header("Location: doctor.php");
        exit();
    }

    $query = "UPDATE doctors 
              SET full_name = '$full_name', email = '$email', phone = '$phone', 
                  specialization = '$specialization', qualification = '$qualification', experience_years = $experience
              WHERE doctor_id = $doctor_id";

    if (mysqli_query($con, $query)) {
        // Handle optional password reset by Admin
        $new_pass = $_POST['new_password'] ?? '';

        if (!empty($new_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            mysqli_query($con, "UPDATE doctors SET password = '$hashed' WHERE doctor_id = $doctor_id");
        }

        $_SESSION['admin_success'] = "Doctor profile updated successfully.";
    } else {
        $_SESSION['admin_error'] = "Failed to update doctor: " . mysqli_error($con);
    }

    header("Location: doctor.php");
    exit();
}

// ── TOGGLE ACTIVE/INACTIVE STATUS ──
if ($action === 'toggle') {
    $doctor_id = (int)$_POST['doctor_id'];

    $query = "UPDATE doctors SET is_active = NOT is_active WHERE doctor_id = $doctor_id";
    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Doctor status updated.";
    } else {
        $_SESSION['admin_error'] = "Failed to update status.";
    }

    header("Location: doctor.php");
    exit();
}

// ── DELETE DOCTOR ──
if ($action === 'delete') {
    $doctor_id = (int)$_POST['doctor_id'];

    // Optional: You might want to get the clinic_id first to delete the clinic as well, 
    // depending on your database constraints (ON DELETE CASCADE).
    $query = "DELETE FROM doctors WHERE doctor_id = $doctor_id";

    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Doctor deleted successfully.";
    } else {
        $_SESSION['admin_error'] = "Failed to delete doctor. Check for linked appointments.";
    }

    header("Location: doctor.php");
    exit();
}

// Fallback redirect
header("Location: doctor.php");
exit();
