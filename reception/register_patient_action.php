<?php
// Start session for alerts
session_start();
// Absolute path to parent db.php
require_once __DIR__ . "/../db.php";

$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- ADD (CREATE) ---
    if ($action === 'add') {
        $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
        $dob = mysqli_real_escape_string($con, $_POST['birthdate']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $pass = password_hash($phone, PASSWORD_DEFAULT); // Default pass is phone

        $check = mysqli_query($con, "SELECT patient_id FROM patients WHERE phone='$phone'");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['error'] = "Mobile number is already registered.";
        } else {
            $sql = "INSERT INTO patients (full_name, email, phone, date_of_birth, gender, address, password, is_active) 
                    VALUES ('$full_name', '$email', '$phone', '$dob', '$gender', '$address', '$pass', 1)";
            if (mysqli_query($con, $sql)) $_SESSION['success'] = "Registration successful.";
            else $_SESSION['error'] = "Critical database error.";
        }
    }

    // --- EDIT (UPDATE) ---
    elseif ($action === 'edit') {
        $pid = (int)$_POST['patient_id'];
        $name = mysqli_real_escape_string($con, $_POST['full_name']);
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
        $addr = mysqli_real_escape_string($con, $_POST['address']);

        $sql = "UPDATE patients SET full_name='$name', phone='$phone', address='$addr' WHERE patient_id=$pid";
        if (mysqli_query($con, $sql)) $_SESSION['success'] = "Patient records updated.";
        else $_SESSION['error'] = "Update failed.";
    }

    // --- DELETE ---
    elseif ($action === 'delete') {
        $pid = (int)$_POST['patient_id'];
        // Attempt deletion (FK constraints might block if appointments exist)
        $sql = "DELETE FROM patients WHERE patient_id=$pid";
        if (mysqli_query($con, $sql)) {
            $_SESSION['success'] = "Record removed from system.";
        } else {
            $_SESSION['error'] = "Unable to delete: Patient has existing appointment history.";
        }
    }
}

// Always redirect back to the management page
header("Location: register-patient.php");
exit();
