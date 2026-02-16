<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Hosptal login</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style_mq.css">
    <link rel="stylesheet" href="../css/reception_mq.css">
</head>

<body>

    <main class="features auth-page">
        <section class="features-header text-center">
            <h2>Hospital <span>Login</span></h2>
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
                                <input type="email" class="form-control" name="email" placeholder="hospital@email.com">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="******">
                            </div>

                            <button type="submit" class="hero-btn w-100 mb-3">
                                Login
                            </button>

                            <p class="text-center mb-0">
                                Don't have an account?
                                <a href="hospital-register.php" class="text-brand fw-semibold">
                                    Register here
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>