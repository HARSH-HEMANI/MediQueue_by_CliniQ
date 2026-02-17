<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Animated Login & Register</title>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="container" id="container">

 
<div class="row justify-content-center fade-in-up">
    <div class="col-md-8 col-lg-8">
        <div class="card border-0 shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: #667eea;">
                        Create Account
                    </h2>
                    <p class="text-muted">Join us today and get started</p>
                </div>

                <form action="" method="get" id="regform" onsubmit="validateForm()">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <label for="firstName" class="form-label fw-semibold">First Name</label>
                            <input type="text" class="form-control  " id="firstName" name="firstName" placeholder="John" data-validation="required min" data-min="2">
                            <span id="firstName_error" class="text-danger"> </span>
                        </div>


                        <div class="col-lg-6 mb-4">
                            <label for="lastName" class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control  " id="lastName" name="lastName" placeholder="Doe" data-validation="required min" data-min="2">
                            <span id="lastName_error" class="text-danger"> </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="text" class="form-control  " id="email" name="email" placeholder="john.doe@example.com">
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control  " id="phone" name="phone" placeholder="Enter your phone number"
                        placeholder="Doe" data-validation="required min max" data-min="10" data-max="10">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control  " id="password" name="password" placeholder="Create a strong password">
                        <div class="form-text">Password must be at least 8 characters long</div>
                    </div>

                    <div class="mb-4">
                        <label for="confirmPassword" class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" class="form-control  " id="confirmPassword" name="confirmPassword" placeholder="Re-enter your password">
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <label for="gender" class="form-label fw-semibold">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label for="" class="form-label fw-semibold">Profile Picture</label>
                            <input type="file" name="" id="" class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms">
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none" style="color: #667eea;">Terms & Conditions</a> and <a href="#" class="text-decoration-none" style="color: #667eea;">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient w-100 btn-lg mb-3">Create Account</button>

                    <div class="text-center">
                        <p class="text-muted mb-0">Already have an account? <a href="login.php" class="text-decoration-none fw-semibold" style="color: #667eea;">Login</a></p>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted mb-3">Or register with</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-outline-secondary flex-fill">
                            <i class="fab fa-google"></i>
                        </button>
                        <button class="btn btn-outline-secondary flex-fill">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button class="btn btn-outline-secondary flex-fill">
                            <i class="fab fa-github"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
