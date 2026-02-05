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

        <!-- ================= HEADER ================= -->
        <section class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Live Queue</h4>
                <p class="text-muted mb-0">Real-time patient queue control</p>
            </div>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </section>

        <!-- ================= CURRENT TOKEN ================= -->
        <section class="mb-4">
            <div class="dcard text-center p-4 current-token-card">

                <div class="mb-2">
                    <span class="badge bg-success">In Progress</span>
                    <span class="badge type-follow ms-1">Follow-up</span>
                </div>

                <div class="current-token-number">
                    Token #21
                </div>

                <div class="patient-name mt-2">
                    Rahul Patel (32 / Male)
                </div>

                <!-- Queue Controls -->
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
        </section>

        <!-- ================= QUEUE SUMMARY ================= -->
        <section class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="dstat-card">
                        <h6>Total Tokens</h6>
                        <h2>48</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card highlight">
                        <h6>Completed</h6>
                        <h2>21</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card danger">
                        <h6>Pending</h6>
                        <h2>27</h2>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= UPCOMING QUEUE ================= -->
        <section class="row g-4">

            <!-- Upcoming Tokens -->
            <div class="col-lg-8">
                <div class="dcard">

                    <div class="card-header">
                        Upcoming Tokens
                    </div>

                    <div class="card-body p-0">
                        <table class="table mb-0 queue-table">
                            <thead>
                                <tr>
                                    <th>Token</th>
                                    <th>Patient</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>#22</td>
                                    <td>Anita Shah</td>
                                    <td><span class="badge type-new">New</span></td>
                                    <td><span class="badge status-waiting">Waiting</span></td>
                                </tr>

                                <tr>
                                    <td>#23</td>
                                    <td>Mohit Kumar</td>
                                    <td><span class="badge type-follow">Follow-up</span></td>
                                    <td><span class="badge status-waiting">Waiting</span></td>
                                </tr>

                                <tr class="priority-row">
                                    <td>#E1</td>
                                    <td>
                                        Suresh Mehta <br>
                                        <small class="text-muted">Emergency</small>
                                    </td>
                                    <td>
                                        <span class="badge type-emergency">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Priority
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge status-waiting">Waiting</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-lg-4">

                <!-- Emergency Actions -->
                <div class="dcard mb-3">
                    <div class="card-header emergency-header">
                        Emergency Actions
                    </div>
                    <div class="card-body d-grid gap-2">
                        <button class="btn btn-outline-danger">
                            <i class="bi bi-plus-circle"></i> Insert Emergency Token
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="bi bi-telephone-forward"></i> Call Priority Patient
                        </button>
                    </div>
                </div>

                <!-- Token Info -->
                <div class="dcard">
                    <div class="card-header">
                        Selected Token Info
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> Rahul Patel</p>
                        <p class="mb-1"><strong>Age:</strong> 32</p>
                        <p class="mb-0"><strong>Visit Type:</strong> Follow-up</p>
                    </div>
                </div>

            </div>

        </section>

    </main>
    <?php include './doctor-footer.php'; ?>

</body>

</html>