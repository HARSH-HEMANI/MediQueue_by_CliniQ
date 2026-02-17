```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Receptionist Profile Settings</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/reception.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>

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
                                   value="Ajay Patel">
                                   
                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="ajay@email.com">

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Phone
                                </label>

                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       value="9876543210">

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Gender
                                </label>

                                <select name="gender"
                                        class="form-select">

                                    <option selected>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>

                                </select>

                            </div>

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea name="address"
                                      class="form-control"
                                      rows="2">Rajkot, Gujarat</textarea>

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

                            <input type="text"
                                   class="form-control"
                                   value="MediQueue Hospital">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Department
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="Reception">

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

                            <input type="text"
                                   class="form-control"
                                   value="ajay_recep"
                                   readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Change Password
                            </label>

                            <input type="password"
                                   class="form-control mb-2"
                                   placeholder="New Password">

                            <input type="password"
                                   class="form-control"
                                   placeholder="Confirm Password">

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

    <?php include '../reception/reception_footer.php'; ?>

</body>
</html>
