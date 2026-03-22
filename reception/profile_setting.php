<?php
$content_page = 'Profile Settings | Reception | MediQueue';
ob_start();
?>

<div class="reception-dashboard">

    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Account management</small>
        <h1 class="dashboard-title mt-1">Profile <span>Settings</span></h1>
        <p class="dashboard-subtitle">Manage receptionist profile, account, and preferences</p>
    </div>

    <form method="post" action="save-reception-profile.php" enctype="multipart/form-data" id="profileForm">

        <!-- Profile & Hospital Info -->
        <div class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="rcard h-100">
                    <div class="rcard-body">
                        <h5 class="mb-3">Receptionist Profile</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="full_name" id="rName" class="form-control"
                                value="Ajay Patel"
                                data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="rName_error" class="text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="rEmail" class="form-control"
                                placeholder="Enter email" data-validation="required|email">
                            <small id="rEmail_error" class="text-danger"></small>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="contact" id="contact" class="form-control"
                                    placeholder="Phone number" data-validation="required|number">
                                <small id="contact_error" class="text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender</label>
                                <select name="gender" class="form-select" data-validation="required">
                                    <option selected>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                                <small id="gender_error" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" rows="2"
                                data-validation="required">Rajkot, Gujarat</textarea>
                            <small id="address_error" class="text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="rcard h-100">
                    <div class="rcard-body">
                        <h5 class="mb-3">Hospital Information</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Hospital Name</label>
                            <input type="text" name="hospitalName" id="hospitalName" class="form-control"
                                data-validation="required|min|max" data-min="3" data-max="100">
                            <small id="hospitalName_error" class="text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Department</label>
                            <input type="text" name="depart" class="form-control"
                                value="Reception" data-validation="required">
                            <small id="depart_error" class="text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Work Shift</label>
                            <select class="form-select">
                                <option>Morning</option>
                                <option selected>Day</option>
                                <option>Night</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Employee ID</label>
                            <input type="text" class="form-control" value="REC1024" readonly>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Account & Status -->
        <div class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="rcard h-100">
                    <div class="rcard-body">
                        <h5 class="mb-3">Account Settings</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="recepName" id="recepName" class="form-control"
                                data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="recepName_error" class="text-danger"></small>
                        </div>

                        <label class="form-label fw-semibold">Change Password</label>
                        <input type="password" name="newPassword" id="newPassword"
                            class="form-control mb-2" placeholder="New Password"
                            data-validation="required|strongPassword">
                        <small id="newPassword_error" class="text-danger d-block mb-2"></small>

                        <input type="password" name="confirmPassword" id="confirmPassword"
                            class="form-control" placeholder="Confirm New Password"
                            data-validation="required|confirmPassword">
                        <small id="confirmPassword_error" class="text-danger"></small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="rcard h-100">
                    <div class="rcard-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="mb-1">Profile Status</h5>
                                <small class="text-muted">Enable or disable your account</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked>
                            </div>
                        </div>

                        <h5 class="mb-3">Notification Preferences</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="notifReg" checked>
                            <label class="form-check-label" for="notifReg">New Patient Registration</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="notifAppt" checked>
                            <label class="form-check-label" for="notifAppt">Appointment Updates</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="notifEmerg">
                            <label class="form-check-label" for="notifEmerg">Emergency Alerts</label>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Activity Log -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="rcard">
                    <div class="rcard-body">
                        <h5 class="mb-3">Activity Log</h5>
                        <p class="mb-2" style="font-size:0.9rem;"><strong>Last Login:</strong> 16 Feb 2026, 09:15 AM</p>
                        <p class="mb-0" style="font-size:0.9rem;"><strong>Last Profile Update:</strong> <span class="text-muted">—</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-brand rounded-pill px-5 py-2">
                <i class="bi bi-save me-2"></i>Save Changes
            </button>
        </div>

    </form>

</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>