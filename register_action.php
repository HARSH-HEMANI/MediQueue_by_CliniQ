<?php
session_start();
include_once "db.php";

if (isset($_POST['submit'])) {

    $full_name  = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $email      = mysqli_real_escape_string($con, trim($_POST['email']));
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];
    $phone      = mysqli_real_escape_string($con, trim($_POST['phone']));
    $gender     = mysqli_real_escape_string($con, $_POST['gender']);
    $dob        = mysqli_real_escape_string($con, $_POST['dob']);
    $address    = mysqli_real_escape_string($con, trim($_POST['address']));

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password) || empty($phone) || empty($gender) || empty($dob)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ./login.php#register");
        exit();
    }

    if ($password !== $confirm) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ./login.php#register");
        exit();
    }

    // Check if email already exists
    $check = mysqli_query($con, "SELECT patient_id FROM patients WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Email already registered. Please login.";
        header("Location: ./login.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert into patients table
    $query = "INSERT INTO patients (full_name, email, password, phone, gender, date_of_birth, address, is_active)
            VALUES ('$full_name', '$email', '$hashed_password', '$phone', '$gender', '$dob', '$address', 1)";

    if (mysqli_query($con, $query)) {
        // Auto login after registration
        $patient_id = mysqli_insert_id($con);
        $_SESSION['patient_id']   = $patient_id;
        $_SESSION['patient_name'] = $full_name;
        $_SESSION['patient_email'] = $email;
        $_SESSION['role']         = 'patient';

        header("Location: ./patient/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: ./login.php#register");
        exit();
    }
}
