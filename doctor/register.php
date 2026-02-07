<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration | MediQueue</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/doctor.css">
</head>

<body>
    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="features auth-page">

        <div class="container">
            <section class="features-header text-center my-5">
                <h2>Doctor <span>Registration</span></h2>
                <div class="section-divider"></div>
                <p>
                    Create your MediQueue doctor account
                </p>
            </section>
            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8">

                    <div class="feature-card">
                        <h5 class="text-center mb-4">Register</h5>

                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" placeholder="Dr. John Doe">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" placeholder="doctor@email.com">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" placeholder="+91 XXXXXXXX">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Specialization</label>
                                    <input type="text" class="form-control" placeholder="Cardiologist">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Clinic / Hospital Name</label>
                                <input type="text" class="form-control" placeholder="Clinic Name">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" placeholder="Create password">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm password">
                                </div>
                            </div>

                            <button type="submit" class="hero-btn w-100 mb-3">
                                Register
                            </button>

                            <p class="text-center mb-0">
                                Already have an account?
                                <a href="doctor-login.php" class="text-brand fw-semibold">
                                    Login here
                                </a>
                            </p>
                        </form>
                    </div>

                </div>

            </div>

        </div>

    </main>

<?php include './doctor-footer.php';?>
</body>

</html>