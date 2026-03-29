<?php
session_start();
include_once "../db.php"; // Adjust this path if your db.php is located elsewhere

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Email and password are required.";
        header("Location: admin-login.php");
        exit();
    }

    $safe_email = mysqli_real_escape_string($con, $email);

    // Fetch the single admin record by email
    $query  = "SELECT admin_id, full_name, email, password FROM admins WHERE email = '$safe_email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        // Verify the hashed password
        if (password_verify($password, $admin['password'])) {

            // Security measure: generate a new session ID on successful login
            session_regenerate_id(true);

            // Set the Admin session variables
            $_SESSION['admin_id']    = $admin['admin_id'];
            $_SESSION['admin_name']  = $admin['full_name'];
            $_SESSION['admin_email'] = $admin['email'];

            // Redirect to the main admin dashboard (change 'index.php' if your dashboard has a different name)
            header("Location: admin.php");
            exit();
        }
    }

    // If no match or wrong password
    $_SESSION['login_error'] = "Invalid admin email or password.";
    header("Location: admin-login.php");
    exit();
} else {
    // If someone tries to access this file directly without submitting the form
    header("Location: admin-login.php");
    exit();
}
