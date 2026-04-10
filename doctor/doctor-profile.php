<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int) $_SESSION['doctor_id'];

// Fetch doctor + clinic data
$query = "SELECT d.*, c.clinic_name, c.address AS clinic_address, c.phone AS clinic_phone
        FROM doctors d
        LEFT JOIN clinics c ON d.clinic_id = c.clinic_id
        WHERE d.doctor_id = $doctor_id";
$result = mysqli_query($con, $query);
$doc = mysqli_fetch_assoc($result);

if (!$doc) {
    echo "Doctor not found.";
    exit();
}

// Session messages
$success_msg = $_SESSION['profile_success'] ?? '';
$error_msg   = $_SESSION['profile_error'] ?? '';
unset($_SESSION['profile_success'], $_SESSION['profile_error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Profile Settings</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <section class="features-header my-1">
            <h2>Welcome, <span>Dr. <?php echo htmlspecialchars($doc['full_name']); ?></span></h2>
        </section>

        <section class="mb-4">
            <h4 class="mb-1">Profile Settings</h4>
            <p class="text-muted mb-0">Manage doctor profile, clinic, and preferences</p>
        </section>

        <?php if ($success_msg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <?php echo htmlspecialchars($success_msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?php echo htmlspecialchars($error_msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="save-doctor-profile.php" id="profileForm">

            <!-- Doctor Profile & Clinic Info -->
            <section class="row g-4 mb-4">

                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Doctor Profile Information</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?php echo htmlspecialchars($doc['full_name']); ?>"
                                    data-validation="required|min|max" data-min="3" data-max="50">
                                <small id="name_error"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control"
                                    value="<?php echo htmlspecialchars($doc['specialization'] ?? ''); ?>"
                                    data-validation="required">
                                <small id="specialization_error"></small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Qualification</label>
                                    <input type="text" name="qualification" class="form-control"
                                        value="<?php echo htmlspecialchars($doc['qualification'] ?? ''); ?>"
                                        data-validation="required">
                                    <small id="qualification_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Years of Experience</label>
                                    <input type="number" name="experience" class="form-control"
                                        value="<?php echo (int)($doc['experience_years'] ?? 0); ?>"
                                        data-validation="required">
                                    <small id="experience_error"></small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Clinic Info -->
                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Clinic Information</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Clinic Name</label>
                                <input type="text" name="cname" class="form-control"
                                    value="<?php echo htmlspecialchars($doc['clinic_name'] ?? ''); ?>"
                                    data-validation="required|min|max" data-min="3" data-max="50">
                                <small id="cname_error"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Clinic Address</label>
                                <textarea name="caddress" class="form-control" rows="2"
                                    data-validation="required"><?php echo htmlspecialchars($doc['clinic_address'] ?? ''); ?></textarea>
                                <small id="caddress_error"></small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact" class="form-control"
                                        value="<?php echo htmlspecialchars($doc['phone'] ?? ''); ?>"
                                        data-validation="required|number">
                                    <small id="contact_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Consultation Fee (₹)</label>
                                    <input type="number" name="cfee" class="form-control" min="0"
                                        value="<?php echo (float)($doc['consultation_fee'] ?? 0); ?>"
                                        data-validation="required">
                                    <small id="cfee_error"></small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

            <!-- Account Settings -->
            <section class="row g-4 mb-4">

                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Account Settings</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Doctor ID</label>
                                <input type="text" class="form-control" readonly
                                    value="#DOC-<?php echo str_pad($doc['doctor_id'], 4, '0', STR_PAD_LEFT); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email_phone" class="form-control"
                                    value="<?php echo htmlspecialchars($doc['email']); ?>"
                                    data-validation="required|email">
                                <small id="email_phone_error"></small>
                            </div>

                            <hr>
                            <h6>Change Password</h6>
                            <p class="text-muted mb-2" style="font-size:0.82rem;">Leave blank if you don't want to change your password</p>

                            <input type="password" name="oldPassword" class="form-control mb-2"
                                placeholder="Old Password">
                            <small id="oldPassword_error"></small>

                            <input type="password" name="newPassword" id="newPassword" class="form-control mb-2"
                                placeholder="New Password"
                                data-validation="strongPassword">
                            <small id="newPassword_error"></small>

                            <input type="password" name="confirmPassword" class="form-control mb-3"
                                placeholder="Confirm New Password"
                                data-validation="confirmPassword" data-match="newPassword">
                            <small id="confirmPassword_error"></small>

                        </div>
                    </div>
                </div>

                <!-- Schedule & Appointment Preferences -->

                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Schedule Preferences</div>
                        <div class="card-body row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" class="form-control"
                                    value="<?php echo htmlspecialchars($doc['start_time'] ?? ''); ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control"
                                    value="<?php echo htmlspecialchars($doc['end_time'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Break (mins)</label>
                                <input type="number" name="break_time" class="form-control" min="0"
                                    value="<?php echo (int)($doc['break_time'] ?? 0); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="dcard mt-4">
                        <div class="card-header">Appointment Preferences</div>
                        <div class="card-body">

                            <p class="text-muted mb-3" style="font-size:0.82rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                Offline slots are for walk-in patients managed by receptionists. Online slots are for patients who book via MediQueue.
                            </p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Max Offline Patients / Day</label>
                                    <input type="number" name="max_patients_offline" class="form-control" min="0"
                                        value="<?php echo (int)($doc['max_patients_offline'] ?? 0); ?>">
                                    <small class="text-muted">Walk-in / Receptionist bookings</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Max Online Patients / Day</label>
                                    <input type="number" name="max_patients_online" class="form-control" min="0"
                                        value="<?php echo (int)($doc['max_patients_online'] ?? 0); ?>">
                                    <small class="text-muted">Patient self-booking via app</small>
                                </div>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                    id="allowWalkins" name="allow_walkins" value="1"
                                    <?php echo ($doc['allow_walkins'] ?? 0) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="allowWalkins">Allow Walk-ins</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    id="allowEmergency" name="allow_emergency" value="1"
                                    <?php echo ($doc['allow_emergency'] ?? 0) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="allowEmergency">Allow Emergency Requests</label>
                            </div>

                        </div>
                    </div>

                </div>
            </section>

            <section class="row g-4 mb-4">

                <!-- Profile Status -->
                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Profile Status</div>
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <span id="statusText">
                                <?php echo $doc['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>

                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="statusToggle"
                                    <?php echo $doc['is_active'] ? 'checked' : ''; ?>>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <!-- Save -->
            <section class="mb-5 text-center">
                <button type="submit" class="btn btn-brand px-4">
                    <i class="bi bi-save"></i> Save Changes
                </button>
            </section>

        </form>

    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>
    <script>
        $(document).ready(function() {
            $('#statusToggle').change(function() {
                let status = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: 'toggle-status.php',
                    method: 'POST',
                    data: {
                        status: status
                    },
                    success: function(response) {
                        if (status == 1) {
                            $('#statusText').text('Active');
                        } else {
                            $('#statusText').text('Inactive');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>