<?php
$content_page = 'Profile Settings | MediQueue';
ob_start();
?>

<div class="container-fluid patient-page px-4 py-4">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Manage your information</small>
        <h3 class="fw-bold mb-0 mt-1">Profile Settings</h3>
    </div>

    <form id="profileForm">
        <div class="row g-4">

            <!-- Avatar column -->
            <div class="col-lg-3">
                <div class="p-card text-center">
                    <div class="profile-avatar-wrap mb-3">
                        <img src="https://i.pravatar.cc/150" id="profileImage" alt="Profile Photo">
                        <label class="upload-overlay" for="imageUpload" title="Change photo">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" id="imageUpload" class="d-none" accept="image/*">
                    </div>
                    <p class="fw-bold fs-6 mb-1">John Doe</p>
                    <p class="text-muted mb-3" style="font-size:0.82rem;">Patient ID: #P-1021</p>
                    <div class="p-3 rounded-3 bg-brand-soft">
                        <p class="text-muted mb-1" style="font-size:0.78rem;"><strong>Blood Group</strong></p>
                        <p class="fw-bold text-brand fs-4 mb-0">A+</p>
                    </div>
                </div>
            </div>

            <!-- Form column -->
            <div class="col-lg-9">
                <div class="p-card">

                    <p class="profile-section-title">Personal Information</p>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" value="John Doe"
                                name="fname" id="fname" data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="fname_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" value="john@example.com"
                                name="email" id="email" data-validation="required|email">
                            <small id="email_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" value="+91 9876543210"
                                name="phone" id="phone" data-validation="required|min" data-min="10">
                            <small id="phone_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" class="form-control" value="1998-05-20"
                                name="dob" id="dob" data-validation="required">
                            <small id="dob_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender</label>
                            <select class="form-select" name="gender" id="gender" data-validation="required">
                                <option selected>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            <small id="gender_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Blood Group</label>
                            <select class="form-select" name="blood_group" id="blood_group" data-validation="required">
                                <option selected>A+</option>
                                <option>A-</option>
                                <option>B+</option>
                                <option>B-</option>
                                <option>O+</option>
                                <option>O-</option>
                                <option>AB+</option>
                                <option>AB-</option>
                            </select>
                            <small id="blood_group_error" class="text-danger"></small>
                        </div>
                    </div>

                    <p class="profile-section-title">Medical Information</p>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Allergies</label>
                            <textarea class="form-control" rows="2" name="allergies" id="allergies" data-validation="required">None</textarea>
                            <small id="allergies_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Existing Conditions</label>
                            <textarea class="form-control" rows="2" name="existing_conditions" id="existing_conditions" data-validation="required">Hypertension</textarea>
                            <small id="existing_conditions_error" class="text-danger"></small>
                        </div>
                    </div>

                    <p class="profile-section-title">Emergency Contact</p>
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control" value="Jane Doe"
                                name="emergency_contact_name" id="emergency_contact_name"
                                data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="emergency_contact_name_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Relation</label>
                            <input type="text" class="form-control" value="Sister"
                                name="emergency_contact_relation" id="emergency_contact_relation"
                                data-validation="required|min|max" data-min="2" data-max="50">
                            <small id="emergency_contact_relation_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" value="+91 9123456789"
                                name="emergency_contact_phone" id="emergency_contact_phone"
                                data-validation="required|min" data-min="10">
                            <small id="emergency_contact_phone_error" class="text-danger"></small>
                        </div>
                    </div>

                    <p class="profile-section-title">Change Password</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                data-validation="required|strongPassword" placeholder="Enter new password">
                            <small id="password_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                data-validation="required|confirmPassword" data-match="password" placeholder="Confirm password">
                            <small id="confirm_password_error" class="text-danger"></small>
                        </div>
                    </div>

                    <button type="submit" class="btn-confirm">
                        <i class="bi bi-check2-circle me-2"></i>Save Changes
                    </button>

                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="successModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body p-5">
                <div style="font-size:3rem;margin-bottom:16px;">✅</div>
                <h5 class="fw-bold mb-2">Profile Updated!</h5>
                <p class="text-muted">Your changes have been saved successfully.</p>
                <button class="btn btn-brand mt-2" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("imageUpload").addEventListener("change", function(e) {
        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById("profileImage").src = reader.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    });
    document.getElementById("profileForm").addEventListener("submit", function(e) {
        e.preventDefault();
        new bootstrap.Modal(document.getElementById("successModal")).show();
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>