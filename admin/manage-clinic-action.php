<?php
// Secure the page and connect to the DB
require_once "admin-init.php";

$action = $_POST['action'] ?? '';

// ── ADD CLINIC ──
if ($action === 'add') {
    $clinic_name  = mysqli_real_escape_string($con, trim($_POST['clinic_name']));
    $phone        = mysqli_real_escape_string($con, trim($_POST['phone'] ?? ''));
    $email        = mysqli_real_escape_string($con, trim($_POST['email'] ?? ''));
    $address      = mysqli_real_escape_string($con, trim($_POST['address'] ?? ''));
    $pincode      = mysqli_real_escape_string($con, trim($_POST['pincode'] ?? ''));
    $working_days = mysqli_real_escape_string($con, trim($_POST['working_days'] ?? 'Mon - Sat'));

    // Handle empty times correctly for the database
    $start_time   = !empty($_POST['start_time']) ? "'" . mysqli_real_escape_string($con, $_POST['start_time']) . "'" : "NULL";
    $end_time     = !empty($_POST['end_time']) ? "'" . mysqli_real_escape_string($con, $_POST['end_time']) . "'" : "NULL";

    if (empty($clinic_name)) {
        $_SESSION['admin_error'] = "Clinic name is required.";
        header("Location: clinic.php");
        exit();
    }

    $query = "INSERT INTO clinics (clinic_name, phone, email, address, pincode, working_days, start_time, end_time, is_active) 
              VALUES ('$clinic_name', '$phone', '$email', '$address', '$pincode', '$working_days', $start_time, $end_time, 1)";

    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Clinic added successfully.";
    } else {
        $_SESSION['admin_error'] = "Failed to add clinic: " . mysqli_error($con);
    }
    header("Location: clinic.php");
    exit();
}

// ── EDIT CLINIC ──
if ($action === 'edit') {
    $clinic_id    = (int)$_POST['clinic_id'];
    $clinic_name  = mysqli_real_escape_string($con, trim($_POST['clinic_name']));
    $phone        = mysqli_real_escape_string($con, trim($_POST['phone'] ?? ''));
    $email        = mysqli_real_escape_string($con, trim($_POST['email'] ?? ''));
    $address      = mysqli_real_escape_string($con, trim($_POST['address'] ?? ''));
    $pincode      = mysqli_real_escape_string($con, trim($_POST['pincode'] ?? ''));
    $working_days = mysqli_real_escape_string($con, trim($_POST['working_days'] ?? 'Mon - Sat'));

    $start_time   = !empty($_POST['start_time']) ? "'" . mysqli_real_escape_string($con, $_POST['start_time']) . "'" : "NULL";
    $end_time     = !empty($_POST['end_time']) ? "'" . mysqli_real_escape_string($con, $_POST['end_time']) . "'" : "NULL";

    if (empty($clinic_name)) {
        $_SESSION['admin_error'] = "Clinic name is required.";
        header("Location: clinic.php");
        exit();
    }

    $query = "UPDATE clinics SET 
              clinic_name = '$clinic_name', phone = '$phone', email = '$email', address = '$address', pincode = '$pincode',
              working_days = '$working_days', start_time = $start_time, end_time = $end_time 
              WHERE clinic_id = $clinic_id";

    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Clinic updated successfully.";
    } else {
        $_SESSION['admin_error'] = "Failed to update clinic.";
    }
    header("Location: clinic.php");
    exit();
}

// ── ASSIGN DOCTORS ──
if ($action === 'assign_doctors') {
    $clinic_id = (int)$_POST['clinic_id'];
    $assigned_doctors = $_POST['doctor_ids'] ?? [];

    mysqli_query($con, "UPDATE doctors SET clinic_id = NULL WHERE clinic_id = $clinic_id");

    if (!empty($assigned_doctors)) {
        $clean_ids = array_map('intval', $assigned_doctors);
        $id_string = implode(',', $clean_ids);
        mysqli_query($con, "UPDATE doctors SET clinic_id = $clinic_id WHERE doctor_id IN ($id_string)");
    }

    $_SESSION['admin_success'] = "Doctor assignments updated successfully.";
    header("Location: clinic.php");
    exit();
}

// ── TOGGLE STATUS ──
if ($action === 'toggle') {
    $clinic_id = (int)$_POST['clinic_id'];
    mysqli_query($con, "UPDATE clinics SET is_active = NOT is_active WHERE clinic_id = $clinic_id");
    $_SESSION['admin_success'] = "Clinic status updated.";
    header("Location: clinic.php");
    exit();
}

// ── DELETE CLINIC ──
if ($action === 'delete') {
    $clinic_id = (int)$_POST['clinic_id'];
    $query = "DELETE FROM clinics WHERE clinic_id = $clinic_id";

    if (mysqli_query($con, $query)) {
        $_SESSION['admin_success'] = "Clinic deleted successfully.";
    } else {
        $_SESSION['admin_error'] = "Cannot delete clinic. It may be assigned to existing doctors.";
    }
    header("Location: clinic.php");
    exit();
}

header("Location: clinic.php");
exit();
