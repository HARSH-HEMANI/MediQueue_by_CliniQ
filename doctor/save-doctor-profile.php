<?php
session_start();
include_once "../db.php";

// Ensure doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = (int) $_SESSION['doctor_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Doctor profile fields
    $full_name      = mysqli_real_escape_string($con, trim($_POST['name'] ?? ''));
    $specialization = mysqli_real_escape_string($con, trim($_POST['specialization'] ?? ''));
    $qualification  = mysqli_real_escape_string($con, trim($_POST['qualification'] ?? ''));
    $experience     = (int) ($_POST['experience'] ?? 0);
    $email          = mysqli_real_escape_string($con, trim($_POST['email_phone'] ?? ''));
    $phone          = mysqli_real_escape_string($con, trim($_POST['contact'] ?? ''));

    // Clinic fields
    $clinic_name = mysqli_real_escape_string($con, trim($_POST['cname'] ?? ''));
    $clinic_addr = mysqli_real_escape_string($con, trim($_POST['caddress'] ?? ''));
    $clinic_fee  = (float) ($_POST['cfee'] ?? 0);

    // Schedule & Appointment preference fields
    $start_time          = mysqli_real_escape_string($con, $_POST['start_time'] ?? '');
    $end_time            = mysqli_real_escape_string($con, $_POST['end_time'] ?? '');
    $break_time          = (int) ($_POST['break_time'] ?? 0);
    $max_patients_online = (int) ($_POST['max_patients_online'] ?? 0);
    $max_patients_offline = (int) ($_POST['max_patients_offline'] ?? 0);
    $allow_walkins       = isset($_POST['allow_walkins']) ? 1 : 0;
    $allow_emergency     = isset($_POST['allow_emergency']) ? 1 : 0;

    // Validation
    if (empty($full_name) || empty($email)) {
        $_SESSION['profile_error'] = "Name and email are required.";
        header("Location: doctor-profile.php");
        exit();
    }

    // Check duplicate email (exclude self)
    $check = mysqli_query($con, "SELECT doctor_id FROM doctors WHERE email = '$email' AND doctor_id != $doctor_id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['profile_error'] = "Another doctor with this email already exists.";
        header("Location: doctor-profile.php");
        exit();
    }

    // Update doctor record (including preferences)
    $doc_query = "UPDATE doctors SET
        full_name = '$full_name',
        specialization = '$specialization',
        qualification = '$qualification',
        experience_years = $experience,
        email = '$email',
        phone = '$phone',
        consultation_fee = $clinic_fee,
        start_time = " . (!empty($start_time) ? "'$start_time'" : "NULL") . ",
        end_time = " . (!empty($end_time) ? "'$end_time'" : "NULL") . ",
        break_time = $break_time,
        max_patients_online = $max_patients_online,
        max_patients_offline = $max_patients_offline,
        allow_walkins = $allow_walkins,
        allow_emergency = $allow_emergency
        WHERE doctor_id = $doctor_id";

    $doc_ok = mysqli_query($con, $doc_query);

    // Update clinic record
    $clinic_id = 0;
    $clinic_row = mysqli_query($con, "SELECT clinic_id FROM doctors WHERE doctor_id = $doctor_id");
    if ($row = mysqli_fetch_assoc($clinic_row)) {
        $clinic_id = (int) $row['clinic_id'];
    }

    if ($clinic_id > 0) {
        $clinic_query = "UPDATE clinics SET
            clinic_name = '$clinic_name',
            address = '$clinic_addr',
            phone = '$phone'
            WHERE clinic_id = $clinic_id";
        mysqli_query($con, $clinic_query);
    }

    // Handle password change
    $old_pass = $_POST['oldPassword'] ?? '';
    $new_pass = $_POST['newPassword'] ?? '';
    $confirm  = $_POST['confirmPassword'] ?? '';

    if (!empty($new_pass)) {
        // Verify old password
        $pw_row = mysqli_query($con, "SELECT password FROM doctors WHERE doctor_id = $doctor_id");
        $pw_data = mysqli_fetch_assoc($pw_row);

        if (empty($old_pass) || !password_verify($old_pass, $pw_data['password'])) {
            $_SESSION['profile_error'] = "Profile updated but old password is incorrect.";
            header("Location: doctor-profile.php");
            exit();
        }

        if ($new_pass !== $confirm) {
            $_SESSION['profile_error'] = "Profile updated but new passwords do not match.";
            header("Location: doctor-profile.php");
            exit();
        }

        $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        mysqli_query($con, "UPDATE doctors SET password = '$hashed' WHERE doctor_id = $doctor_id");
    }

    // Update session name
    $_SESSION['doctor_name']  = stripslashes($full_name);
    $_SESSION['doctor_email'] = stripslashes($email);

    if ($doc_ok) {
        $_SESSION['profile_success'] = "Profile updated successfully.";
    } else {
        $_SESSION['profile_error'] = "Failed to update profile: " . mysqli_error($con);
    }

    header("Location: doctor-profile.php");
    exit();
}
