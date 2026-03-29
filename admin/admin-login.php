<?php
session_start();

// If the admin is already logged in, redirect them straight to the dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php"); // Adjust to your actual admin dashboard filename
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Admin Login</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body>

    <main class="features auth-page">
        <section class="features-header text-center">
            <h2>Welcome <span>Admin</span></h2>
            <div class="section-divider"></div>
            <p>
                Secure access to your admin dashboard
            </p>
        </section>
        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-5 col-md-7">

                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <?php echo $_SESSION['login_error'];
                            unset($_SESSION['login_error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="feature-card">
                        <h5 class="text-center mb-4">Sign In</h5>

                        <form action="admin-login-action.php" method="post" id="adminLoginForm">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    data-validation="required|email"
                                    placeholder="Enter Admin Email" required>
                                <small id="email_error" class="text-danger"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" data-validation="required" placeholder="******" required>
                                <small id="password_error" class="text-danger"></small>
                            </div>

                            <button type="submit" class="hero-btn w-100 mb-3">
                                Login
                            </button>

                            <p class="text-center mb-0">
                                <a href="./forgot_password.php" class="text-brand fw-semibold">
                                    Forgot Password?
                                </a>
                            </p>
                        </form>
                    </div>

                </div>

            </div>

        </div>

    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>

</body>

</html>