<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Doctor Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body>

    <?php if (isset($_SESSION['login_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> <?php echo htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) || isset($_SESSION['login_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> <?php echo isset($_SESSION['login_success']) ? htmlspecialchars($_SESSION['login_success']) : 'Registration successful. Please log in.'; unset($_SESSION['login_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <main class="explore-hero">
        <section class="features-header text-center">
            <h2>Doctor <span>Login</span></h2>
            <div class="section-divider"></div>
            <p>Secure access to your clinic dashboard</p>
        </section>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="feature-card">
                        <h5 class="text-center mb-4">Sign In</h5>

                        <form action="./doctor-login-action.php" method="post" id="doctorLoginForm">

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    data-validation="required|email"
                                    placeholder="doctor@email.com">
                                <small id="email_error"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    data-validation="required|strongPassword"
                                    placeholder="******">
                                <small id="password_error"></small>
                            </div>

                            <button type="submit" class="hero-btn w-100 mb-3" name="doc_login">
                                Login
                            </button>

                            <p class="text-center mb-0">
                                Don't have an account?
                                <a href="./register.php" class="text-brand fw-semibold">Register here</a>
                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FIX: Bootstrap JS and other scripts moved to bottom of body -->
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>

</body>

</html>