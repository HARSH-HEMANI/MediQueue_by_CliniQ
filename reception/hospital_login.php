<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Receptionist login</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">

    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/reception.css?v=vibrant">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>

    <main class="features auth-page">
        <section class="features-header text-center">
            <h2>Receptionist <span>Login</span></h2>
            <div class="section-divider"></div>
            <p>
                Secure access to your dashboard
            </p>
        </section>
        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-5 col-md-7">

                    <div class="feature-card">
                        <h5 class="text-center mb-4">Sign In</h5>

                        <form action="./doctor-login-action.php" method="post">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="hospital@email.com" data-validation="required|email">
                                <small id="email_error"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="******" data-validation="required|strongPassword">
                                <small id="password_error"></small>
                            </div>

                            <a href="./reception_dashboard.php" type="submit" class="hero-btn w-100 mb-3" value="none">
                                Login
                            </a>

                            <p class="text-center mb-0">
                                Don't have an account?
                                <a href="./hospital_registration.php" class="text-brand fw-semibold">
                                    Register here
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



