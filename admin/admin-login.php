<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Admin Login</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/doctor.css">
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

                    <div class="feature-card">
                        <h5 class="text-center mb-4">Sign In</h5>

                        <form action="./doctor-login-action.php" method="post" id="adminLoginForm">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    data-validation="required|email"
                                    placeholder="Enter Admin Email">
                                <small id="email_error"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" data-validation="required|strongPassword" placeholder="******">
                                <small id="password_error"></small>
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