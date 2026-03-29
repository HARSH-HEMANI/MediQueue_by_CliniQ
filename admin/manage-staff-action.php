<?php
// Secure the page and connect to the DB
require_once "admin-init.php";

$action = $_POST['action'] ?? '';

// ── ADD STAFF (RECEPTIONIST) ──
if ($action === 'add') {
    $full_name = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email     = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone     = mysqli_real_escape_string($con, trim($_POST['phone']));
    $address   = mysqli_real_escape_string($con, trim($_POST['address'] ?? ''));
    $clinic_id = (int)$_POST['clinic_id'];
    $password  = $_POST['password'];

    if (empty($full_name) || empty($email) || empty($password) || empty($clinic_id)) {
        $_SESSION['admin_error'] = "Name, email, clinic, and password are required.";
        header("Location: staff.php");
        exit();
    }

    // Check duplicate email
    $check = mysqli_query($con, "SELECT receptionist_id FROM receptionists WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['admin_error'] = "A staff member with this email already exists.";
        header("Location: staff.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO receptionists (clinic_id, full_name, email, phone, address, password, is_active) 
              VALUES ($clinic_id, '$full_name', '$email', '$phone', '$address', '$hashed_password', 1)";

    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Staff added successfully.";
    } else {
        $_SESSION['admin_error'] = "Failed to add staff: " . mysqli_error($con);
    }

    header("Location: staff.php");
    exit();
}

// ── EDIT STAFF ──
if ($action === 'edit') {
    $rec_id    = (int)$_POST['receptionist_id'];
    $full_name = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email     = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone     = mysqli_real_escape_string($con, trim($_POST['phone']));
    $address   = mysqli_real_escape_string($con, trim($_POST['address'] ?? ''));
    $clinic_id = (int)$_POST['clinic_id'];

    if (empty($full_name) || empty($email) || empty($clinic_id)) {
        $_SESSION['admin_error'] = "Name, email, and clinic are required.";
        header("Location: staff.php");
        exit();
    }

    $check = mysqli_query($con, "SELECT receptionist_id FROM receptionists WHERE email = '$email' AND receptionist_id != $rec_id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['admin_error'] = "Another staff member is using this email.";
        header("Location: staff.php");
        exit();
    }

    $query = "UPDATE receptionists 
              SET full_name = '$full_name', email = '$email', phone = '$phone', address = '$address', clinic_id = $clinic_id 
              WHERE receptionist_id = $rec_id";

    if (mysqli_query($con, $query)) {
        // Handle optional password update
        $new_pass = $_POST['new_password'] ?? '';
        if (!empty($new_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            mysqli_query($con, "UPDATE receptionists SET password = '$hashed' WHERE receptionist_id = $rec_id");
        }
        $_SESSION['admin_success'] = "Staff updated successfully.";
    } else {
        $_SESSION['admin_error'] = "Failed to update staff.";
    }

    header("Location: staff.php");
    exit();
}

// ── TOGGLE STATUS ──
if ($action === 'toggle') {
    $rec_id = (int)$_POST['receptionist_id'];
    $query = "UPDATE receptionists SET is_active = NOT is_active WHERE receptionist_id = $rec_id";
    mysqli_query($con, $query);
    $_SESSION['admin_success'] = "Staff status updated.";
    header("Location: staff.php");
    exit();
}

// ── DELETE STAFF ──
if ($action === 'delete') {
    $rec_id = (int)$_POST['receptionist_id'];
    $query = "DELETE FROM receptionists WHERE receptionist_id = $rec_id";

    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Staff deleted successfully.";
    } else {
        $_SESSION['admin_error'] = "Failed to delete staff.";
    }
    header("Location: staff.php");
    exit();
}

header("Location: staff.php");
exit();
