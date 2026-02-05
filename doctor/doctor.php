<?php
// session_start();

// if (!isset($_SESSION['doctor_id'])) {
//     header("Location: ../login.php");
//     exit();
// }
?>


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

        <!--  TODAY OVERVIEW  -->
        <section class="mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Total Patients</h6>
                        <h2>48</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card highlight">
                        <h6>Current Token</h6>
                        <h2>#21</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Avg Consultation</h6>
                        <h2>12 min</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card danger">
                        <h6>Emergency Cases</h6>
                        <h2>2</h2>
                    </div>
                </div>

            </div>
        </section>

        <!--  LIVE QUEUE & EMERGENCY  -->
        <section class="row g-4">

            <!-- Live Queue Control -->
            <div class="col-lg-7">
                <div class="dcard">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Live Queue Control</span>
                        <span class="badge bg-success">Live</span>
                    </div>

                    <div class="card-body text-center">
                        <div class="live-token">Token #21</div>
                        <div class="patient-name">Patient: Rahul Patel</div>

                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button class="btn btn-brand">
                                <i class="bi bi-arrow-right-circle"></i> Call Next
                            </button>
                            <button class="btn btn-outline-success">
                                <i class="bi bi-check-circle"></i> Complete
                            </button>
                            <button class="btn btn-outline-warning">
                                <i class="bi bi-pause-circle"></i> Hold
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Emergency Alerts -->
            <div class="col-lg-5">
                <div class="dcard emergency-card">

                    <div class="card-header emergency-header">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Emergency Alerts
                    </div>

                    <div class="card-body">

                        <div class="emergency-item">
                            <span>Token #35 – Chest Pain</span>
                            <button class="btn btn-sm btn-light">View</button>
                        </div>

                        <div class="emergency-item">
                            <span>Token #41 – Accident Case</span>
                            <button class="btn btn-sm btn-light">View</button>
                        </div>

                    </div>
                </div>
            </div>

        </section>

        <!--  UPCOMING PATIENTS & RIGHT PANEL  -->
        <section class="row g-4 mt-1">

            <!-- Upcoming Patients -->
            <div class="col-lg-7">
                <div class="dcard">

                    <div class="card-header">
                        Upcoming Patients
                    </div>

                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Token</th>
                                    <th>Patient</th>
                                    <th>Visit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#22</td>
                                    <td>Anita Shah</td>
                                    <td>Follow-up</td>
                                    <td><span class="badge bg-warning">Waiting</span></td>
                                </tr>
                                <tr>
                                    <td>#23</td>
                                    <td>Mohit Kumar</td>
                                    <td>New</td>
                                    <td><span class="badge bg-warning">Waiting</span></td>
                                </tr>
                                <tr>
                                    <td>#24</td>
                                    <td>Neha Joshi</td>
                                    <td>New</td>
                                    <td><span class="badge bg-warning">Waiting</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-lg-5">

                <!-- Clinic Status -->
                <div class="dcard mb-3">
                    <div class="card-body clinic-status">
                        <div>
                            <h6 class="mb-1">Clinic Status</h6>
                            <span class="badge bg-success">Open</span>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">
                            Disable Booking
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dcard quick-actions">
                    <div class="card-header">
                        Quick Actions
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">View All Appointments</a>
                        <a href="#" class="btn btn-outline-primary">Patient Records</a>
                        <a href="#" class="btn btn-outline-primary">Schedule Settings</a>
                    </div>
                </div>

            </div>

        </section>

    </main>
    <?php include './doctor-footer.php'; ?>
</body>

</html>