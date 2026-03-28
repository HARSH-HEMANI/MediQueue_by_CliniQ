<?php
session_start();
include "./db.php";

// Check access (only after OTP verification)
if (!isset($_SESSION['reset_email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['reset_email'];
$error = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $new     = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    if (empty($new) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif ($new !== $confirm) {
        $error = "Passwords do not match!";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {

        $hashed = password_hash($new, PASSWORD_BCRYPT);

        $update = mysqli_query($con, "UPDATE patients SET password='$hashed', otp=NULL WHERE email='$email'");

        if ($update) {
            // Clear session
            unset($_SESSION['reset_email']);
            unset($_SESSION['verify_email']);

            // Set success message for login page
            $_SESSION['login_success'] = "Password changed successfully!";

            // Redirect to login
            header("Location: login.php");
            exit();
        } else {
            $error = "Failed to update password. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | MediQueue</title>

    <!-- SAME CSS AS LOGIN -->
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css?v=vibrant">
    <link rel="stylesheet" href="./css/doctor.css?v=vibrant">
</head>

<body>

    <main class="explore-hero">

        <!-- HEADER -->
        <section class="features-header text-center">
            <h2>Reset <span>Password</span></h2>
            <div class="section-divider"></div>
            <p>Create a new secure password for your account</p>
        </section>

        <!-- CARD -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">

                    <div class="feature-card">
                        <h5 class="text-center mb-4">Set New Password</h5>

                        <!-- ERROR MESSAGE -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" id="resetPasswordForm">

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password"
                                    name="new_password"
                                    id="new_password"
                                    class="form-control"
                                    placeholder="Enter new password"
                                    data-validation="required|strongPassword"
                                    required>
                                <small id="new_password_error"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password"
                                    name="confirm_password"
                                    id="confirm_password"
                                    class="form-control"
                                    placeholder="Confirm password"
                                    data-validation="required|confirmPassword"
                                    data-match="new_password"
                                    required>
                                <small id="confirm_password_error"></small>
                            </div>

                            <button type="submit" class="hero-btn w-100 mb-3">
                                Update Password
                            </button>

                            <p class="text-center mb-0">
                                Remember your password?
                                <a href="login.php" class="text-brand fw-semibold">Login here</a>
                            </p>

                        </form>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <!-- JS -->
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/validation.js"></script>

</body>

</html>