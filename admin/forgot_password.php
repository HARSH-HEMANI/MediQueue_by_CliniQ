<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Forgot Password</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/doctor.css">
</head>

<body>

    <main class="features auth-page">
        <section class="features-header text-center">

        </section>
        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-5 col-md-7">

                    <div class="feature-acard">
                        <h5 class="text-center mb-4">Forgot Password</h5>

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

                            <button type="button" class="hero-btn w-100 mb-3">
                                Send Reset Link
                            </button>
                            <p class="text-center mb-0">
                                <a href="./admin-login.php" class="text-brand fw-semibold">
                                    back to Login?
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