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

        <!-- HEADER  -->
        <section class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Appointments</h4>
                <p class="text-muted mb-0">Manage day-wise patient appointments</p>
            </div>

            <!-- Date Selector -->
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm">
                    <option selected>Today</option>
                    <option>Yesterday</option>
                    <option>Tomorrow</option>
                </select>

                <input type="date" class="form-control form-control-sm">
            </div>
        </section>

        <!--  FILTERS  -->
        <section class="mb-3">
            <div class="dcard p-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select class="form-select form-select-sm">
                            <option selected>All Types</option>
                            <option>New</option>
                            <option>Follow-up</option>
                            <option>Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm">
                            <option selected>All Status</option>
                            <option>Waiting</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                            <option>No-show</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <!--  APPOINTMENT LIST  -->
        <section>
            <div class="dcard">

                <div class="card-header">
                    Appointment List
                </div>

                <div class="card-body p-0">
                    <table class="table mb-0 appointment-table">
                        <thead>
                            <tr>
                                <th>Token</th>
                                <th>Patient</th>
                                <th>Type</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- Normal Appointment -->
                            <tr>
                                <td>#21</td>
                                <td>
                                    Rahul Patel <br>
                                    <small class="text-muted">32 / Male</small>
                                </td>
                                <td><span class="badge type-new">New</span></td>
                                <td>10:30 AM</td>
                                <td><span class="badge status-waiting">Waiting</span></td>
                                <td class="action-cell">
                                    <!-- View Patient -->
                                    <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewPatientModal">
                                        <i class="bi bi-person"></i>
                                    </button>

                                    <!-- Go to Queue -->
                                    <a href="./doctor-dashboard.php#live-queue"
                                        class="btn btn-sm btn-light"
                                        title="Go to Live Queue">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </a>

                                    <!-- Add Notes -->
                                    <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#notesModal">
                                        <i class="bi bi-journal-text"></i>
                                    </button>
                                </td>

                            </tr>

                            <!-- Follow-up -->
                            <tr>
                                <td>#22</td>
                                <td>
                                    Anita Shah <br>
                                    <small class="text-muted">45 / Female</small>
                                </td>
                                <td><span class="badge type-follow">Follow-up</span></td>
                                <td>10:45 AM</td>
                                <td><span class="badge status-completed">Completed</span></td>
                                <td class="action-cell">
                                    <!-- View Patient -->
                                    <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewPatientModal">
                                        <i class="bi bi-person"></i>
                                    </button>

                                    <!-- Go to Queue -->
                                    <a href="./doctor-dashboard.php#live-queue"
                                        class="btn btn-sm btn-light"
                                        title="Go to Live Queue">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </a>

                                    <!-- Add Notes -->
                                    <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#notesModal">
                                        <i class="bi bi-journal-text"></i>
                                    </button>
                                </td>

                            </tr>

                            <!-- Emergency -->
                            <tr class="emergency-row">
                                <td>#23</td>
                                <td>
                                    Mohit Kumar <br>
                                    <small class="text-muted">29 / Male</small>
                                </td>
                                <td>
                                    <span class="badge type-emergency">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                                    </span>
                                </td>
                                <td>Immediate</td>
                                <td><span class="badge status-waiting">Waiting</span></td>
                                <td class="action-cell">
                                    <!-- View Patient -->
                                    <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewPatientModal">
                                        <i class="bi bi-person"></i>
                                    </button>

                                    <!-- Go to Queue -->
                                    <a href="./doctor-dashboard.php#live-queue"
                                        class="btn btn-sm btn-light"
                                        title="Go to Live Queue">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </a>

                                    <!-- Add Notes -->
                                    <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#notesModal">
                                        <i class="bi bi-journal-text"></i>
                                    </button>
                                </td>


                        </tbody>
                    </table>
                </div>

            </div>
        </section>

    </main>

    <!--  VIEW PATIENT MODAL  -->
    <div class="modal fade" id="viewPatientModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content dcard">

                <div class="modal-header">
                    <h5 class="modal-title">Patient Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Name:</strong><br> Rahul Patel
                        </div>
                        <div class="col-md-4">
                            <strong>Age:</strong><br> 32
                        </div>
                        <div class="col-md-4">
                            <strong>Gender:</strong><br> Male
                        </div>
                    </div>

                    <hr>

                    <h6>Visit History</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            12 Jan 2026 – Fever (Completed)
                        </li>
                        <li class="list-group-item">
                            03 Dec 2025 – Follow-up (Completed)
                        </li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!--  ADD NOTES MODAL  -->
    <div class="modal fade" id="notesModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dcard">

                <div class="modal-header">
                    <h5 class="modal-title">Consultation Notes</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" rows="4"
                        placeholder="Enter symptoms, diagnosis, or advice..."></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-brand">
                        Save Notes
                    </button>
                </div>

            </div>
        </div>
    </div>

</body>

</html>