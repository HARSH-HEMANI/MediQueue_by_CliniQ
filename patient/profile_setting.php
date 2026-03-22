<?php
$content_page = 'Profile Settings | MediQueue';
ob_start();
?>

<div class="container-fluid px-4 py-4">

    <div class="page-header mb-4">
        <small>Manage your personal information</small>
        <h3>Profile Settings</h3>
    </div>

    <div class="card-glass">

        <div class="row g-4">

            <!-- LEFT SIDE -->
            <div class="col-lg-4 text-center">

                <img src="https://i.pravatar.cc/150"
                    class="rounded-circle mb-3 shadow-sm"
                    width="150"
                    id="profileImage">

                <div class="mb-3">
                    <input type="file"
                        class="form-control"
                        id="imageUpload">
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-8">

                <form id="profileForm">

                    <!-- Personal Info -->
                    <h6 class="section-title">Personal Information</h6>

                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="John Doe" name="fname" id="fname" data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="fname_error"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="john@example.com" name="email" id="email" data-validation="required|email">
                            <small id="email_error"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" value="+91 9876543210" name="phone" id="phone" data-validation="required|min" data-min="10">
                            <small id="phone_error"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" value="1998-05-20" name="dob" id="dob" data-validation="required">
                            <small id="dob_error"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" id="gender" data-validation="required">
                                <option selected>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            <small id="gender_error"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <select class="form-select" name="blood_group" id="blood_group" data-validation="required">
                                <option selected>A+</option>
                                <option>B+</option>
                                <option>O+</option>
                                <option>AB+</option>
                            </select>
                            <small id="blood_group_error"></small>
                        </div>

                    </div>

                    <!-- Medical Info -->
                    <h6 class="section-title">Medical Information</h6>

                    <div class="mb-3">
                        <label class="form-label">Allergies</label>
                        <textarea class="form-control" rows="2" name="allergies" id="allergies" data-validation="required">None</textarea>
                        <small id="allergies_error"></small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Existing Conditions</label>
                        <textarea class="form-control" rows="2" name="existing_conditions" id="existing_conditions" data-validation="required">Hypertension</textarea>
                        <small id="existing_conditions_error"></small>
                    </div>

                    <!-- Emergency Contact -->
                    <h6 class="section-title">Emergency Contact</h6>

                    <div class="row g-3 mb-4">

                        <div class="col-md-4">
                            <label class="form-label" >Name</label>
                            <input type="text" class="form-control" value="Jane Doe" name="emergency_contact_name" id="emergency_contact_name" data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="emergency_contact_name_error"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Relation</label>
                            <input type="text" class="form-control" value="Sister" name="emergency_contact_relation" id="emergency_contact_relation" data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="emergency_contact_relation_error"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" value="+91 9123456789" name="emergency_contact_phone" id="emergency_contact_phone" data-validation="required|min" data-min="10">
                            <small id="emergency_contact_phone_error"></small>
                        </div>

                    </div>

                    <!-- Password -->
                    <h6 class="section-title">Change Password</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="password" id="password" data-validation="required|strongPassword">
                            <small id="password_error"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" data-validation="required|confirmPassword" data-match="password">
                            <small id="confirm_password_error"></small>
                        </div>
                    </div>

                    <button type="submit" class="btn-confirm">
                        <i class="bi bi-check2-circle me-2"></i>
                        Save Changes
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body text-center p-4">
                <h5>Profile Updated Successfully 🎉</h5>
                <button class="btn btn-brand mt-3"
                    data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Image Preview
    document.getElementById("imageUpload")
        .addEventListener("change", function(e) {

            const reader = new FileReader();

            reader.onload = function() {
                document.getElementById("profileImage").src = reader.result;
            };

            reader.readAsDataURL(e.target.files[0]);
        });

    // Save Profile (UI Demo)
    document.getElementById("profileForm")
        .addEventListener("submit", function(e) {

            e.preventDefault();

            new bootstrap.Modal(
                document.getElementById("successModal")
            ).show();
        });
</script>

<?php
$content = ob_get_clean();
include './patient-layout.php';
?>