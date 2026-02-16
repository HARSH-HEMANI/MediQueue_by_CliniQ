<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Registration | MediQueue</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style_mq.css">
    <link rel="stylesheet" href="../css/reception_mq.css">
</head>

<body>
    <?php include '../includes/reception_sidebar.php'; ?>

    <main class="features auth-page">

        <div class="container">
            <section class="features-header text-center my-5">
                <h2>Hospital <span>Registration</span></h2>
                <div class="section-divider"></div>
                <p>
                    Create your Hospital's MediQueue account
                </p>
            </section>
            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8">

                    <div class="feature-card">
                        <h5 class="text-center mb-4">Register</h5>

                        <form>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Hospital Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Hospital Name ">
                                </div>
                            
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email" >
                        </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" placeholder="+91 XXXXXXXX">
                                </div>

                            <div class="mb-3">
                                <label class="form-label">Hospital Type</label>
                                <select name="country" class="form-select">
                                    <option value="">Select Type</option>
                                    <option>Goverment</option>
                                    <option>Private</option>
                                    <option>clinic</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Licence Number</label>
                                <input type="text" name="fullname" class="form-control" placeholder="Enter Licence Number" >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" placeholder="Rajkot">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Enter address"></textarea>
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
                                <a href="hospital_login.php" class="text-brand fw-semibold">
                                    Login here
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