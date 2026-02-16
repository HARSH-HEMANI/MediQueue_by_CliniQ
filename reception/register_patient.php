<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception | Register Patient</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style_mq.css">
    <link rel="stylesheet" href="../css/reception_mq.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>

    <?php include '../includes/recep_sidebar.php'; ?>

    <div class="reception-dashboard">

        <!-- PAGE HEADER -->
        <div class="dashboard-header mb-4">

        <h1 class="fw-bold text-black">Register <span>Walk-in Patients</span> </h1>


            <p class="dashboard-subtitle">
                Enter patient details to create a new patient record
            </p>

        </div>

        <!-- REGISTRATION FORM CARD -->
        <div class="rcard">

            <div class="rcard-body">

                <form action="register_patient_action.php" method="POST">


                    <div class="row g-3">

                        <!-- FULL NAME -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input type="text"
                                name="full_name"
                                class="form-control"
                                placeholder="Enter full name">

                        </div>

                        <!-- EMAIL -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter email">

                        </div>

                        <!-- BIRTHDATE -->
                        <div class="col-md-4">

                            <label class="form-label">
                                Birthdate
                            </label>

                            <input type="date"
                                name="birthdate"
                                class="form-control">

                        </div>

                        <!-- PHONE -->
                        <div class="col-md-4">

                            <label class="form-label">
                                Phone Number
                            </label>

                            <input type="text"
                                name="phone"
                                class="form-control"
                                placeholder="Enter phone number">


                        </div>

                        <!-- GENDER -->
                        <div class="col-md-4">

                            <label class="form-label">
                                Gender
                            </label>

                            <select name="gender"
                                class="form-select">

                                <option value="">
                                    Select Gender
                                </option>

                                <option value="Male">
                                    Male
                                </option>

                                <option value="Female">
                                    Female
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>

                        <!-- ADDRESS -->
                        <div class="col-md-12">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea name="address"
                                class="form-control"
                                rows="3"
                                placeholder="Enter full address">
                            </textarea>

                        </div>

                        <!-- PASSWORD -->
                        <div class="col-md-6">

                            <label class="form-label">
                                Password
                            </label>

                            <input type="password"
                                name="password"
                                class="form-control"
                                placeholder="Create password">

                        </div>

                    </div>

                    <!-- BUTTON SECTION -->
                    <div class="mt-4 d-flex gap-3">

                        <button type="submit"
                            class="btn btn-brand">

                            <i class="bi bi-person-plus"></i>
                            Register Patient

                        </button>

                        <button type="reset"
                            class="btn btn-outline-danger">

                            Reset

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <?php include '../reception/recep_footer.php'; ?>

</body>

</html>
