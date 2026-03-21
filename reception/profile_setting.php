<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Receptionist Profile Settings</title>

    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/reception.css?v=vibrant">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/reception-sidebar.php'; ?>

    <main class="reception-dashboard">


        <!-- PAGE HEADER -->
        <section class="dashboard-header mb-4">

            <h1 class="fw-bold text-black">Profile <span>Settings</span> </h1>


            <p class="dashboard-subtitle">
                Manage receptionist profile, account, and preferences
            </p>

        </section>



        <form method="post" action="save-reception-profile.php" enctype="multipart/form-data">


            <!-- PROFILE AND WORK INFO -->
            <section class="row g-4 mb-4">


                <!-- PROFILE INFO -->
                <div class="col-lg-6">

                    <div class="rcard">

                        <div class="rcard-body">

                            <h5 class="mb-3">
                                Receptionist Profile
                            </h5>

                            <div class="mb-3">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input type="text"
                                    name="full_name"
                                    class="form-control"
                                    value="Ajay Patel"
                                    type="text" id="rName" name="came" data-validation="required|min|max"
                                    data-min="3" data-max="50" data-save class="form-control">
                                <small id="rname_error"></small>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input id="rAddress" name="rAddress"
                                    data-save class="form-control" rows="2" data-validation="required"></textarea>
                                <small id="rAddress_error"></small>
                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Phone
                                    </label>

                                    <input type="text" id="contact" name="contact"
                                        data-save class="form-control" data-validation="required|number">
                                    <small id="contact_error"></small>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Gender
                                    </label>

                                    <select name="gender"
                                        class="form-select" data-validation="required">

                                        <option selected>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>

                                    </select>
                                    <small id="gender_error"></small>
                                </div>

                            </div>



                            <div class="mb-3">

                                <label class="form-label">
                                    Address
                                </label>

                                <textarea name="address"
                                    class="form-control"
                                    rows="2" data-validation="required">Rajkot, Gujarat</textarea>
                                <small id="address_error"></small>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- HOSPITAL INFO -->
                <div class="col-lg-6">

                    <div class="rcard">

                        <div class="rcard-body">

                            <h5 class="mb-3">
                                Hospital Information
                            </h5>



                            <div class="mb-3">

                                <label class="form-label">
                                    Hospital
                                </label>

                                <input type="text" id="hospitalName" name="hospitalName"
                                    data-save class="form-control" data-validation="required|min|max" data-min="3" data-max="50">
                                <small id="hospitalName_error"></small>

                            </div>



                            <div class="mb-3">

                                <label class="form-label">
                                    Department
                                </label>

                                <input type="text"
                                    class="form-control"
                                    value="Reception" name="depart" data-validation="required">
                                <small id="depart_error"></small>
                            </div>



                            <div class="mb-3">

                                <label class="form-label">
                                    Work Shift
                                </label>

                                <select class="form-select">

                                    <option>Morning</option>
                                    <option selected>Day</option>
                                    <option>Night</option>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Employee ID
                                </label>

                                <input type="text"
                                    class="form-control"
                                    value="REC1024"
                                    readonly>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ACCOUNT SETTINGS -->
            <section class="row g-4 mb-4">
                <div class="col-lg-6">

                    <div class="rcard">

                        <div class="rcard-body">

                            <h5 class="mb-3">
                                Account Settings
                            </h5>

                            <div class="mb-3">

                                <label class="form-label">
                                    Username
                                </label>

                                <input type="text" id="recepName" name="recepName"
                                    data-save class="form-control" data-validation="required|min|max" data-min="3" data-max="50">
                                <small id="recepName_error"></small>
                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Change Password
                                </label>

                                <input type="password" id="newPassword"
                                    class="form-control mb-2" name="newPassword"
                                    placeholder="New Password" data-validation="required|strongPassword">
                                <small id="newPassword_error"></small>

                                <input type="password" id="confirmPassword"
                                    class="form-control mb-3" name="confirmPassword"
                                    placeholder="Confirm New Password" data-validation="required|confirmPassword">
                                <small id="confirmPassword_error"></small>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- STATUS -->
                <div class="col-lg-6">

                    <div class="rcard">

                        <div class="rcard-body d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="mb-1">
                                    Profile Status
                                </h5>

                                <small class="text-muted">
                                    Enable or disable receptionist account
                                </small>

                            </div>

                            <div class="form-check form-switch">

                                <input class="form-check-input"
                                    type="checkbox"
                                    checked>
                            </div>

                        </div>

                    </div>

                </div>


            </section>

            <!-- NOTIFICATIONS -->
            <section class="row g-4 mb-4">

                <div class="col-lg-6">

                    <div class="rcard">

                        <div class="rcard-body">

                            <h5 class="mb-3">
                                Notification Preferences
                            </h5>

                            <div class="form-check mb-2">

                                <input class="form-check-input"
                                    type="checkbox"
                                    checked>

                                <label class="form-check-label">
                                    New Patient Registration
                                </label>

                            </div>

                            <div class="form-check mb-2">

                                <input class="form-check-input"
                                    type="checkbox"
                                    checked>

                                <label class="form-check-label">
                                    Appointment Updates
                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input"
                                    type="checkbox">

                                <label class="form-check-label">
                                    Emergency Alerts
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ACTIVITY -->
                <div class="col-lg-6">

                    <div class="rcard">

                        <div class="rcard-body">

                            <h5 class="mb-3">
                                Activity Log
                            </h5>

                            <p>
                                <strong>Last Login:</strong>
                                16 Feb 2026, 09:15 AM
                            </p>

                            <p>
                                <strong>Last Update:</strong>
                                —
                            </p>

                        </div>

                    </div>

                </div>

            </section>

            <!-- SAVE BUTTON -->
            <section class="text-end mb-5">

                <button type="submit"
                    class="btn btn-brand px-4">

                    <i class="bi bi-save"></i>
                    Save Changes

                </button>

            </section>

        </form>

    </main>

    <?php include './reception_footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>
</body>

</html>



