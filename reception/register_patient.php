<?php
$content_page = 'Register Patient | Reception | MediQueue';
ob_start();
?>

<div class="reception-dashboard">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Walk-in registration</small>
        <h1 class="dashboard-title mt-1">Register <span>Patient</span></h1>
        <p class="dashboard-subtitle">Enter patient details to create a new record</p>
    </div>

    <div class="rcard">
        <div class="rcard-body">
            <form action="register_patient_action.php" method="POST" id="registerForm">

                <p class="profile-section-title" style="margin-top:0;">Personal Information</p>
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                            placeholder="Enter full name"
                            data-validation="required|min|max" data-min="3" data-max="50">
                        <small id="full_name_error" class="text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control"
                            placeholder="Enter email"
                            data-validation="required|email">
                        <small id="email_error" class="text-danger"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="birthdate" class="form-control"
                            data-validation="required">
                        <small id="birthdate_error" class="text-danger"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control"
                            placeholder="Enter phone number"
                            data-validation="required|number|min|max" data-min="10" data-max="10">
                        <small id="phone_error" class="text-danger"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Gender</label>
                        <select name="gender" class="form-select" data-validation="required">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <small id="gender_error" class="text-danger"></small>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address" class="form-control" rows="2"
                            placeholder="Enter full address"
                            data-validation="required"></textarea>
                        <small id="address_error" class="text-danger"></small>
                    </div>
                </div>

                <p class="profile-section-title">Account Setup</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Create password"
                            data-validation="required|strongPassword">
                        <small id="password_error" class="text-danger"></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control"
                            placeholder="Confirm password"
                            data-validation="required|confirmPassword" data-match="password">
                        <small id="confirm_password_error" class="text-danger"></small>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-brand rounded-pill px-4">
                        <i class="bi bi-person-plus me-1"></i>Register Patient
                    </button>
                    <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">Reset</button>
                </div>

            </form>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>