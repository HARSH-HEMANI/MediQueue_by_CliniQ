<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Registration | MediQueue</title>

    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/reception.css?v=vibrant">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">

</head>

<body>
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
                                    <input type="text" class="form-control" placeholder="Enter Hospital Name" name="hospitalName" id="hospitalName" data-validation="required|min|max" data-min="3" data-max="50">
                                    <small id="hospitalName_error"></small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control"
                                        data-validation="required|email" placeholder="Enter email">
                                    <small id="email_error"></small>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
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

                                    <div class="mb-3">
                                        <label class="form-label">Hospital Type</label>
                                        <select name="hospitalType" class="form-select" data-validation="required">
                                            <option value="">Select Type</option>
                                            <option value="">Goverment</option>
                                            <option>Private</option>
                                            <option>clinic</option>
                                        </select>
                                        <small id="hospitalType_error"></small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Licence Number</label>
                                        <input type="text" name="licenceNo" class="form-control" placeholder="Enter Licence Number" data-validation="required">
                                        <small id="licenceNo_error"></small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" placeholder="Rajkot" name="city" data-validation="required">
                                        <small id="city_error"></small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="3" placeholder="Enter address" data-validation="required"></textarea>
                                        <small id="address_error"></small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" id="password" data-validation="required|strongPassword" placeholder="******">
                                            <small id="password_error"></small>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Confirm Password</label>
                                            <input name="confirm_password"
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
                                        <a href="hospital_login.php" class="text-brand fw-semibold">
                                            Login here
                                        </a>
                                    </p>
                                </div>
                            </div>
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



