<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Login | MediQueue</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/reception.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <main class="explore-hero">
        <section class="features-header text-center">
            <h2>Receptionist <span>Login</span></h2>
            <div class="section-divider"></div>
            <p>Secure access to your clinic dashboard</p>
        </section>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="feature-card">
                        <h5 class="text-center mb-4 fw-bold">Sign In</h5>

                        <!-- FIX: action corrected, login button is now a proper submit button -->
                        <form action="./reception_dashboard.php" method="post" id="loginForm">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="hospital@email.com"
                                    data-validation="required|email">
                                <small id="email_error" class="text-danger"></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Enter password"
                                    data-validation="required|strongPassword">
                                <small id="password_error" class="text-danger"></small>
                            </div>

                            <button type="submit" class="btn btn-brand w-100 mb-3 py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>

                            <p class="text-center mb-0 text-muted" style="font-size:0.9rem;">
                                Don't have an account?
                                <a href="./hospital_registration.php" class="text-brand fw-semibold">Register here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>
</body>

</html>