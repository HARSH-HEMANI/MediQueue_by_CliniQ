<?php
session_start();
include_once "db.php";

if (isset($_POST['submit'])) {

    $email    = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: ./login.php");
        exit();
    }

    // Fetch patient by email
    $query  = "SELECT * FROM patients WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Check if email is verified
        if ($row['is_active'] == 0) {
            $_SESSION['error'] = "Please verify your email before logging in. Check your inbox.";
            header("Location: ./login.php");
            exit();
        }

        if (password_verify($password, $row['password'])) {
            $_SESSION['patient_id']    = $row['patient_id'];
            $_SESSION['patient_name']  = $row['full_name'];
            $_SESSION['patient_email'] = $row['email'];
            $_SESSION['role']          = 'patient';

            header("Location: ./patient/dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header("Location: ./login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with this email.";
        header("Location: ./login.php");
        exit();
    }
}
