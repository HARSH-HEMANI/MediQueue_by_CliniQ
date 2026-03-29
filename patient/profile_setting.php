<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}
$patient_id = $_SESSION['patient_id'];

// Fetch patient info
$query = "SELECT * FROM patients WHERE patient_id = $patient_id";
$res = mysqli_query($con, $query);
if ($res && mysqli_num_rows($res) > 0) {
    $patient = mysqli_fetch_assoc($res);
} else {
    $patient = [];
}

$fname = htmlspecialchars($patient['full_name'] ?? 'John Doe');
$email = htmlspecialchars($patient['email'] ?? '');
$phone = htmlspecialchars($patient['phone'] ?? '');
$dob = htmlspecialchars($patient['date_of_birth'] ?? '');
$gender = htmlspecialchars($patient['gender'] ?? 'Male');
$blood_group = htmlspecialchars($patient['blood_group'] ?? 'A+');
$allergies = htmlspecialchars($patient['allergies'] ?? 'None');
$existing_conditions = htmlspecialchars($patient['existing_conditions'] ?? 'None');
$em_name = htmlspecialchars($patient['emergency_contact_name'] ?? '');
$em_relation = htmlspecialchars($patient['emergency_contact_relation'] ?? '');
$em_phone = htmlspecialchars($patient['emergency_contact_phone'] ?? '');

$display_id = "#P-" . str_pad($patient_id, 4, '0', STR_PAD_LEFT);

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
                        <!-- <img src="https://i.pravatar.cc/150?u=<?php echo $patient_id; ?>" id="profileImage" alt="Profile Photo"> -->
                        <label class="upload-overlay" for="imageUpload" title="Change photo">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" id="imageUpload" class="d-none" accept="image/*">
                    </div>
                    <p class="fw-bold fs-6 mb-1"><?php echo $fname; ?></p>
                    <p class="text-muted mb-3" style="font-size:0.82rem;">Patient ID: <?php echo $display_id; ?></p>
                    <div class="p-3 rounded-3 bg-brand-soft">
                        <p class="text-muted mb-1" style="font-size:0.78rem;"><strong>Blood Group</strong></p>
                        <p class="fw-bold text-brand fs-4 mb-0" id="displayBloodGroup"><?php echo $blood_group; ?></p>
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
                            <input type="text" class="form-control" value="<?php echo $fname; ?>"
                                name="fname" id="fname" data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="fname_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" value="<?php echo $email; ?>"
                                name="email" id="email" data-validation="required|email">
                            <small id="email_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" value="<?php echo $phone; ?>"
                                name="phone" id="phone" data-validation="required|min" data-min="10">
                            <small id="phone_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" class="form-control" value="<?php echo $dob; ?>"
                                name="dob" id="dob" data-validation="required">
                            <small id="dob_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender</label>
                            <select class="form-select" name="gender" id="gender" data-validation="required">
                                <option <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                            <small id="gender_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Blood Group</label>
                            <select class="form-select" name="blood_group" id="blood_group" data-validation="required">
                                <?php
                                $bgs = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                                foreach($bgs as $bg) {
                                    $sel = ($blood_group == $bg) ? 'selected' : '';
                                    echo "<option $sel>$bg</option>";
                                }
                                ?>
                            </select>
                            <small id="blood_group_error" class="text-danger"></small>
                        </div>
                    </div>

                    <p class="profile-section-title">Medical Information</p>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Allergies</label>
                            <textarea class="form-control" rows="2" name="allergies" id="allergies" data-validation="required"><?php echo $allergies; ?></textarea>
                            <small id="allergies_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Existing Conditions</label>
                            <textarea class="form-control" rows="2" name="existing_conditions" id="existing_conditions" data-validation="required"><?php echo $existing_conditions; ?></textarea>
                            <small id="existing_conditions_error" class="text-danger"></small>
                        </div>
                    </div>

                    <p class="profile-section-title">Emergency Contact</p>
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control" value="<?php echo $em_name; ?>"
                                name="emergency_contact_name" id="emergency_contact_name">                           
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Relation</label>
                            <input type="text" class="form-control" value="<?php echo $em_relation; ?>"
                                name="emergency_contact_relation" id="emergency_contact_relation">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" value="<?php echo $em_phone; ?>"
                                name="emergency_contact_phone" id="emergency_contact_phone">
                        </div>
                    </div>

                    <p class="profile-section-title">Change Password <small class="text-muted fw-normal fs-7">(Leave blank to keep current)</small></p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter new password">
                            <small id="password_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                placeholder="Confirm password">
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
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: rgba(34, 197, 94, 0.1);">
                    <i class="bi bi-check-lg text-success" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Profile Updated!</h5>
                <p class="text-muted mb-4">Your changes have been saved successfully.</p>
                <button class="btn btn-brand px-4 py-2" data-bs-dismiss="modal">Close</button>
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

        // Basic password confirmation check
        const pwd = document.getElementById('password').value;
        const cpwd = document.getElementById('confirm_password').value;
        if (pwd !== '' && pwd !== cpwd) {
            document.getElementById('confirm_password_error').innerText = "Passwords do not match";
            return;
        } else {
            document.getElementById('confirm_password_error').innerText = "";
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

        const formData = new FormData(this);

        fetch("profile_setting_action.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                new bootstrap.Modal(document.getElementById("successModal")).show();
                // Update UI elements that changed
                const fName = formData.get('fname');
                document.querySelector('.profile-avatar-wrap + p').innerText = fName;
                document.getElementById('displayBloodGroup').innerText = formData.get('blood_group');
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while updating the profile.");
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Save Changes';
        });
    });
</script>

<?php $content = ob_get_clean();
include './patient-layout.php'; ?>