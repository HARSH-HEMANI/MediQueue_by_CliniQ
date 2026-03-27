<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_SESSION['patient_id'];

    $fname = mysqli_real_escape_string($con, trim($_POST['fname']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone']));
    $dob = mysqli_real_escape_string($con, trim($_POST['dob']));
    $gender = mysqli_real_escape_string($con, trim($_POST['gender']));
    $blood_group = mysqli_real_escape_string($con, trim($_POST['blood_group']));
    $allergies = mysqli_real_escape_string($con, trim($_POST['allergies']));
    $existing_conditions = mysqli_real_escape_string($con, trim($_POST['existing_conditions']));
    $em_name = mysqli_real_escape_string($con, trim($_POST['emergency_contact_name']));
    $em_relation = mysqli_real_escape_string($con, trim($_POST['emergency_contact_relation']));
    $em_phone = mysqli_real_escape_string($con, trim($_POST['emergency_contact_phone']));

    $password_query = "";
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $password_query = ", password = '$password'";
    }

    $query = "UPDATE patients SET 
            full_name = '$fname',
            email = '$email',
            phone = '$phone',
            date_of_birth = '$dob',
            gender = '$gender',
            blood_group = '$blood_group',
            allergies = '$allergies',
            existing_conditions = '$existing_conditions',
            emergency_contact_name = '$em_name',
            emergency_contact_relation = '$em_relation',
            emergency_contact_phone = '$em_phone'
            $password_query
            WHERE patient_id = $patient_id";

    if (mysqli_query($con, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
