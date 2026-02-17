<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception | Manage Appointment</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/reception.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>

<?php include '../sidebar/reception-sidebar.php';?>

<!-- MAIN CONTAINER -->
<div class="reception-dashboard" style="padding-top:120px; padding-left:20px; padding-right:20px;">

    <!-- PAGE HEADER WITH ACTION -->
    <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
        <h1 class="fw-bold text-black">Manage <span>Appointments</span> </h1>
            <p class="dashboard-subtitle mb-0">
                Control patient flow, update status, and manage walk-ins
            </p>
        </div>

        <div class="d-flex gap-2 mt-2">
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#walkinModal">
                <i class="bi bi-person-plus"></i> Walk-In
            </button>
        </div>

    </div>

    <!-- SUMMARY STRIP -->
    <div class="stats-row mb-4">

        <div class="rstat-card">
            <h6>Total Today</h6>
            <h2>18</h2>
        </div>

        <div class="rstat-card">
            <h6>Waiting</h6>
            <h2>10</h2>
        </div>

        <div class="rstat-card">
            <h6>In Progress</h6>
            <h2>1</h2>
        </div>

        <div class="rstat-card">
            <h6>Completed</h6>
            <h2>7</h2>
        </div>

    </div>

    <!-- CURRENT TOKEN SECTION -->
    <section class="mb-4">

        <div class="dcard current-token-card p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>
                    <span class="badge bg-success">Currently Consulting</span>
                    <span class="badge type-follow ms-2">Follow-up</span>

                    <div class="current-token-number text-muted mt-2">
                        Token <span>#12</span>
                    </div>

                    <div class="patient-name">
                        Rahul Patel (32 / Male)
                    </div>

                    <small class="text-muted">
                        Doctor: Dr. Mehta 
                    </small>
                </div>

                <div class="d-flex flex-column gap-2 mt-3 mt-md-0">

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

    </section>


    <!-- FILTER BAR -->
    <div class="rcard mb-4">

        <div class="rcard-body">

            <div class="row g-3 align-items-center">

                <div class="col-md-4">
                    <input type="text" class="form-control"
                           placeholder="Search by patient name or phone">
                </div>

                <div class="col-md-3">
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Doctors</option>
                        <option>Dr. Mehta</option>
                        <option>Dr. Shah</option>
                    </select>
                </div>

                <div class="col-md-2 text-end">
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel"></i>Filter
                    </button>

                </div>

            </div>

        </div>

    </div>

<!-- FILTER MODAL -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-funnel text-brand"></i>
                    Filter Appointments
                </h5>

                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>


            <div class="modal-body">

                <div class="row g-3">

                    <!-- Doctor -->
                    <div class="col-md-6">
                        <label class="form-label">Doctor</label>
                        <select class="form-select">
                            <option value="">All Doctors</option>
                            <option>Dr. Mehta</option>
                            <option>Dr. Shah</option>
                            <option>Dr. Patel</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option value="">All Status</option>
                            <option>Waiting</option>
                            <option>Consulting</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                </div>

            </div>


            <div class="modal-footer">

                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Reset
                </button>

                <button class="btn btn-brand">
                    Apply Filter
                </button>

            </div>

        </div>
    </div>
</div>

<!-- WALK-IN MODAL -->
<div class="modal fade" id="walkinModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-person-plus text-brand"></i>
                    Register Walk-In Patient
                </h5>

                <button class="btn-close" data-bs-dismiss="modal"></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Patient Name</label>
                    <input type="text" class="form-control" placeholder="Enter full name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" placeholder="Enter phone number">
                </div>

                <div class="mb-3">
                    <label class="form-label">Doctor</label>
                    <select class="form-select">
                        <option>Select Doctor</option>
                        <option>Dr. Mehta</option>
                        <option>Dr. Shah</option>
                    </select>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control">
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Case Type</label>
                    <select class="form-select">
                        <option>Regular</option>
                        <option>Emergency</option>
                        <option>Follow-up</option>
                    </select>
                </div>

            </div>


            <div class="modal-footer">

                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-brand">
                    Register Patient
                </button>

            </div>

        </div>

    </div>

</div>



    <!-- APPOINTMENT TABLE -->
    <div class="card dashboard-card shadow-lg">

        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Token</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>

                        <td><strong>#13</strong></td>

                        <td>
                            <a data-bs-toggle="modal" data-bs-target="#patient">
                                Rahul Sharma
                            </a>
                            <div class="text-muted small">
                                9876543210
                            </div>
                        </td>

                        <td>Dr. Shah</td>

                        <td>10:30 AM</td>

                        <td>
                            <span class="badge bg-primary">
                                Follow-up
                            </span>
                        </td>

                        <td>
                            <select class="form-select form-select-sm">
                                <option>Waiting</option>
                                <option>Consulting</option>
                                <option>Completed</option>
                                <option>Cancelled</option>
                            </select>
                        </td>

                        <td>
                            <input class="form-control form-control-sm"
                                   placeholder="Add note">
                        </td>

                    </tr>


                    <tr>

                        <td><strong>#14</strong></td>

                        <td>
                            Anita Patel
                            <div class="text-muted small">
                                9123456780
                            </div>
                        </td>

                        <td>Dr. Mehta</td>

                        <td>11:00 AM</td>

                        <td>
                            <span class="badge bg-danger">
                                Emergency
                            </span>
                        </td>

                        <td>
                            <select class="form-select form-select-sm">
                                <option>Waiting</option>
                                <option selected>Consulting</option>
                                <option>Completed</option>
                                <option>Cancelled</option>
                            </select>
                        </td>

                        <td>
                            <input class="form-control form-control-sm"
                                   placeholder="Add note">
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>


</div>

    <?php include '../reception/reception_footer.php'; ?>


</body>
</html>

