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
        <!--  HEADER  -->
        <section class="mb-4">
            <h4 class="mb-1">Patient Records</h4>
            <p class="text-muted mb-0">Search and review registered patients</p>
        </section>

        <!--  SEARCH & FILTER  -->
        <section class="mb-3">
            <div class="dcard p-3">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control form-control-sm"
                            placeholder="Search by name, phone or Patient ID">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm">
                            <option selected>All Patients</option>
                            <option>Emergency History</option>
                            <option>Frequent Visitors</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!--  PATIENT LIST  -->
        <section class="row g-4">

            <!-- Patient Table -->
            <div class="col-lg-7">
                <div class="dcard">

                    <div class="card-header">
                        Patient List
                    </div>

                    <div class="card-body p-0">
                        <table class="table mb-0 patient-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Last Visit</th>
                                    <th>Total Visits</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>P1021</td>
                                    <td>
                                        Rahul Patel <br>
                                        <small class="text-muted">32 / Male</small>
                                    </td>
                                    <td>12 Jan 2026</td>
                                    <td>5</td>
                                </tr>

                                <tr>
                                    <td>P1044</td>
                                    <td>
                                        Anita Shah <br>
                                        <small class="text-muted">45 / Female</small>
                                    </td>
                                    <td>05 Jan 2026</td>
                                    <td>8</td>
                                </tr>

                                <tr class="emergency-row">
                                    <td>P1102</td>
                                    <td>
                                        Mohit Kumar <br>
                                        <small class="text-muted">29 / Male</small>
                                    </td>
                                    <td>10 Jan 2026</td>
                                    <td>3</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!--  PATIENT PROFILE  -->
            <div class="col-lg-5">

                <!-- Profile -->
                <div class="dcard mb-3">
                    <div class="card-header">
                        Patient Profile
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> Rahul Patel</p>
                        <p><strong>Age / Gender:</strong> 32 / Male</p>
                        <p><strong>Phone:</strong> 98XXXXXX21</p>
                        <p><strong>Registered On:</strong> 15 Aug 2025</p>

                        <hr>

                        <p class="mb-1"><strong>Medical Notes (Summary)</strong></p>
                        <p class="text-muted mb-0">
                            Recurrent fever complaints, advised blood test and follow-up.
                        </p>
                    </div>
                </div>

                <!-- Visit Summary -->
                <div class="dcard mb-3">
                    <div class="card-header">
                        Visit Summary
                    </div>
                    <div class="card-body">
                        <p><strong>Total Visits:</strong> 5</p>
                        <p><strong>Last Visit:</strong> 12 Jan 2026</p>
                        <p class="mb-0"><strong>Avg Visit Frequency:</strong> Once in 2 months</p>
                    </div>
                </div>

                <!-- Export -->
                <div class="dcard">
                    <div class="card-body">
                        <button class="btn btn-outline-secondary w-100">
                            <i class="bi bi-printer"></i> Print Patient History
                        </button>
                    </div>
                </div>

            </div>

        </section>

        <!--  VISIT HISTORY  -->
        <section class="mt-4">
            <div class="dcard">

                <div class="card-header">
                    Visit History
                </div>

                <div class="card-body">

                    <ul class="visit-timeline">

                        <li>
                            <span class="visit-date">12 Jan 2026</span>
                            <span class="badge type-follow">Follow-up</span>
                            <span class="badge status-completed">Completed</span>
                            <p class="mt-1 mb-0">
                                Token #21 · Appointment ID A2031
                            </p>
                            <small class="text-muted">
                                Notes: Fever reduced, advised rest
                            </small>
                        </li>

                        <li class="emergency-visit">
                            <span class="visit-date">03 Dec 2025</span>
                            <span class="badge type-emergency">
                                <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                            </span>
                            <span class="badge status-completed">Completed</span>
                            <p class="mt-1 mb-0">
                                Token #E3 · Emergency Visit
                            </p>
                            <small class="text-muted">
                                Notes: Acute chest pain, referred to hospital
                            </small>
                        </li>

                    </ul>

                </div>
            </div>
        </section>

    </main>
    <?php include './doctor-footer.php'; ?>

</body>

</html>