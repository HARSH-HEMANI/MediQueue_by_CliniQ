<?php
// session_start();

// if (!isset($_SESSION['doctor_id'])) {
//     header("Location: ../login.php");
//     exit();
// }
?>
<?php include "doctor-auth.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Doctor Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/doctor.css">
</head>

<body>
    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">
        <section class="features-header my-1">
            <h2>Welcome, <span>Dr. <?php echo $_SESSION['doctor_name']; ?></span></h2>
        </section>
        <section class="mb-4">
            <h4 class="mb-1">Profile Settings</h4>
            <p class="text-muted mb-0">Manage doctor profile, clinic, and preferences</p>
        </section>

        <!--  profile and clinic  -->
        <form method="post" action="save-profile.php" enctype="multipart/form-data">

            <section class="row g-4 mb-4">

                <!-- Doctor Profile -->
                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Doctor Profile Information</div>
                        <div class="card-body">

                            <!-- Profile Photo -->
                            <div class="mb-3 text-center">
                                <img id="profilePreview"
                                    src="../img/default-avatar.png"
                                    class="rounded-circle mb-2"
                                    width="90">
                                <input type="file"
                                    name="profile_photo"
                                    class="form-control form-control-sm"
                                    accept="image/*"
                                    onchange="previewPhoto(event)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" id="doctorName" name="doctor_name"
                                    data-save class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" id="specialization" name="specialization"
                                    data-save class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Qualification</label>
                                    <input type="text" id="qualification" name="qualification"
                                        data-save class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Years of Experience</label>
                                    <input type="number" id="experience" name="experience"
                                        data-save class="form-control" min="0">
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
                                <input type="text" id="clinicName" name="clinic_name"
                                    data-save class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Clinic Address</label>
                                <textarea id="clinicAddress" name="clinic_address"
                                    data-save class="form-control" rows="2"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" id="clinicContact" name="clinic_contact"
                                        data-save class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Consultation Fee (₹)</label>
                                    <input type="number" id="consultationFee" name="consultation_fee"
                                        data-save class="form-control" min="0">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

            <!--  acc setting  -->
            <section class="row g-4 mb-4">

                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Account Settings</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username"
                                    class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email / Phone</label>
                                <input type="text" id="emailPhone" name="email_phone"
                                    data-save class="form-control">
                            </div>

                            <hr>

                            <h6>Change Password</h6>

                            <input type="password" id="oldPassword"
                                class="form-control mb-2"
                                placeholder="Old Password">

                            <input type="password" id="newPassword"
                                class="form-control mb-2"
                                placeholder="New Password">

                            <input type="password" id="confirmPassword"
                                class="form-control mb-3"
                                placeholder="Confirm New Password">

                            <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="changePassword()">
                                Change Password
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Profile Status -->
                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Profile Status</div>
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span id="profileStatusText">Active</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="profileStatus"
                                    data-save
                                    checked
                                    onchange="toggleStatus(this)">
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <!--  preference  -->
            <section class="row g-4 mb-4">

                <!-- Schedule -->
                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Schedule Preferences</div>
                        <div class="card-body row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" id="startTime" name="start_time"
                                    data-save class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" id="endTime" name="end_time"
                                    data-save class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Break (mins)</label>
                                <input type="number" id="breakTime" name="break_time"
                                    data-save class="form-control" min="0">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Appointment Preferences -->
                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Appointment Preferences</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Max Patients / Day</label>
                                <input type="number" id="maxPatients" name="max_patients"
                                    data-save class="form-control" min="1">
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="allowWalkins"
                                    name="allow_walkins"
                                    data-save>
                                <label class="form-check-label">Allow Walk-ins</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="allowEmergency"
                                    name="allow_emergency"
                                    data-save>
                                <label class="form-check-label">Allow Emergency Requests</label>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

            <!--  notification and activity  -->
            <section class="row g-4 mb-4">

                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Notification Preferences</div>
                        <div class="card-body">

                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="notifyAppointments"
                                    data-save>
                                <label class="form-check-label">New Appointments</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="notifyEmergency"
                                    data-save>
                                <label class="form-check-label">Emergency Requests</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="notifyCancel"
                                    data-save>
                                <label class="form-check-label">Appointment Cancellations</label>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="col-lg-6">
                    <div class="dcard">
                        <div class="card-header">Activity Log</div>
                        <div class="card-body">
                            <p><strong>Last Login:</strong> 08 Feb 2026, 09:12 AM</p>
                            <p><strong>Last Profile Update:</strong>
                                <span id="lastUpdate">—</span>
                            </p>
                        </div>
                    </div>
                </div>

            </section>

            <!--  SAVE  -->
            <section class="mb-5 text-end">
                <button type="button"
                    class="btn btn-brand px-4"
                    onclick="saveProfile()">
                    <i class="bi bi-save"></i> Save Changes
                </button>
            </section>

        </form>

    </main>



    <?php include './doctor-footer.php'; ?>
</body>

</html>