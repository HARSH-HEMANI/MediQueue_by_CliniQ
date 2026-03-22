<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Registration | MediQueue</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/reception.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <main class="features auth-page">
        <div class="container">
            <section class="features-header text-center my-5">
                <h2>Hospital <span>Registration</span></h2>
                <div class="section-divider"></div>
                <p>Create your hospital's MediQueue account</p>
            </section>

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <div class="feature-card">
                        <h5 class="text-center mb-4 fw-bold">Register</h5>

                        <!-- FIX: removed broken nested row-inside-row structure -->
                        <form id="registrationForm" method="post" action="reception-register-action.php">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hospital Name</label>
                                <input type="text" class="form-control" name="hospitalName" id="hospitalName"
                                    placeholder="Enter hospital name"
                                    data-validation="required|min|max" data-min="3" data-max="100">
                                <small id="hospitalName_error" class="text-danger"></small>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="hospital@email.com"
                                        data-validation="required|email">
                                    <small id="email_error" class="text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                        placeholder="+91 XXXXXXXXXX"
                                        data-validation="required|number|min|max" data-min="10" data-max="10">
                                    <small id="phone_error" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Hospital Type</label>
                                    <select name="hospitalType" id="hospitalType" class="form-select" data-validation="required">
                                        <option value="">Select Type</option>
                                        <option value="government">Government</option>
                                        <option value="private">Private</option>
                                        <option value="clinic">Clinic</option>
                                    </select>
                                    <small id="hospitalType_error" class="text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Licence Number</label>
                                    <input type="text" class="form-control" name="licenceNo" id="licenceNo"
                                        placeholder="Enter licence number"
                                        data-validation="required">
                                    <small id="licenceNo_error" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">City</label>
                                    <input type="text" class="form-control" name="city" id="city"
                                        placeholder="e.g. Rajkot"
                                        data-validation="required">
                                    <small id="city_error" class="text-danger"></small>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Address</label>
                                    <textarea name="address" id="address" class="form-control" rows="1"
                                        placeholder="Enter full address"
                                        data-validation="required"></textarea>
                                    <small id="address_error" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Password</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Create password"
                                        data-validation="required|strongPassword">
                                    <small id="password_error" class="text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                        placeholder="Confirm password"
                                        data-validation="required|confirmPassword" data-match="password">
                                    <small id="confirm_password_error" class="text-danger"></small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-brand w-100 mb-3 py-2">
                                <i class="bi bi-hospital me-2"></i>Register Hospital
                            </button>

                            <p class="text-center mb-0 text-muted" style="font-size:0.9rem;">
                                Already have an account?
                                <a href="hospital_login.php" class="text-brand fw-semibold">Login here</a>
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