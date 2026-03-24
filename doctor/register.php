<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration | MediQueue</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-without-sidebar">

    <main class="features auth-page">
        <div class="container">

            <section class="features-header text-center mb-3">
                <h2>Doctor <span>Registration</span></h2>
                <div class="section-divider"></div>
                <p>Create your MediQueue doctor account</p>
            </section>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="feature-card">

                        <?php if (isset($_SESSION['reg_error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> <?php echo htmlspecialchars($_SESSION['reg_error']); unset($_SESSION['reg_error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <h5 class="text-center mb-4">Register</h5>

                        <form id="doctorRegisterForm" method="post" action="doctor-register-action.php">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text"
                                        name="name"
                                        class="form-control"
                                        data-validation="required|min|max"
                                        data-min="3"
                                        data-max="50"
                                        placeholder="Dr. John Doe">
                                    <small id="name_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control"
                                        data-validation="required|email"
                                        placeholder="doctor@email.com">
                                    <small id="email_error"></small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text"
                                        name="phone"
                                        class="form-control"
                                        data-validation="required|number|min|max"
                                        data-min="10"
                                        data-max="10"
                                        placeholder="+91 XXXXXXXX">
                                    <small id="phone_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Specialization</label>
                                    <input type="text"
                                        name="specialization"
                                        class="form-control"
                                        data-validation="required|min|max"
                                        data-min="3"
                                        data-max="50"
                                        placeholder="Cardiologist">
                                    <small id="specialization_error"></small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Qualification</label>
                                    <input type="text"
                                        name="qualification"
                                        class="form-control"
                                        data-validation="required|min|max"
                                        data-min="2"
                                        data-max="50"
                                        placeholder="MBBS, MD">
                                    <small id="qualification_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Years of Experience</label>
                                    <input type="number"
                                        name="experience_years"
                                        class="form-control"
                                        data-validation="required"
                                        min="0"
                                        placeholder="e.g. 5">
                                    <small id="experience_years_error"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Clinic / Hospital Name</label>
                                <input type="text"
                                    name="clinic"
                                    class="form-control"
                                    data-validation="required|min|max"
                                    data-min="3"
                                    data-max="100"
                                    placeholder="Clinic Name">
                                <small id="clinic_error"></small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Password</label>
                                    <input type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        data-validation="required|strongPassword"
                                        placeholder="Create password">
                                    <small id="password_error"></small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password"
                                        name="confirm_password"
                                        class="form-control"
                                        data-validation="required|confirmPassword"
                                        data-match="password"
                                        placeholder="Confirm password">
                                    <small id="confirm_password_error"></small>
                                </div>
                            </div>

                            <button type="submit" class="hero-btn w-100 mb-3">
                                Register
                            </button>

                            <p class="text-center mb-0">
                                Already have an account?
                                <!-- FIX: was pointing to "doctor-login.php" which doesn't exist -->
                                <a href="login.php" class="text-brand fw-semibold">Login here</a>
                            </p>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include './doctor-footer.php'; ?>

    <!-- FIX: Bootstrap JS and scripts moved to bottom of body -->
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>

</body>

</html>