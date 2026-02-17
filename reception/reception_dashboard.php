<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receptionist Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/reception.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

<?php include '../sidebar/reception-sidebar.php'; ?>

<!-- PAGE CONTENT -->
<div class="container" style="padding-top:140px;">

    <!-- PAGE TITLE -->
    <div class="mb-4">
        <h1 class="fw-bold text-black">Receptionist <span>Dashboard</span> </h1>
        <p class="text-muted mb-0">Overview of today’s activities</p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-4 mb-5">

        <div class="col-md-6 col-lg-3">
            <div class="rcard card-bg summary-card h-100">
                <div class="rcard-body">
                    <h6 class="text-muted">Today’s Appointments</h6>
                    <h3 class="fw-bold text-brand mb-0">12</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="rcard rcard-bg summary-card h-100">
                <div class="rcard-body">
                    <h6 class="text-muted mb-2">Patients in Queue</h6>
                    <h2 class="fw-bold text-brand mb-0">5</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="rcard rcard-bg summary-card h-100">
                <div class="rcard-body">
                    <h6 class="text-muted mb-2">Doctors Available</h6>
                    <h2 class="fw-bold text-brand mb-0">3</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="rcard rcard-bg summary-card h-100">
                <div class="rcard-body">
                    <h6 class="text-muted mb-2">Total Patients</h6>
                    <h2 class="fw-bold text-brand mb-0">48</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- TODAY'S APPOINTMENTS -->
    <div class="rcard rcard-bg mb-5">
        <div class="rcard-body">
            <h5 class="fw-bold mb-3">Today’s Appointments</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted">
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10:00 AM</td>
                            <td>John Doe</td>
                            <td>Dr. Smith</td>
                            <td><span class="badge bg-warning text-dark">Waiting</span></td>
                        </tr>
                        <tr>
                            <td>10:30 AM</td>
                            <td>Sarah Khan</td>
                            <td>Dr. Ali</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>11:00 AM</td>
                            <td>Michael Lee</td>
                            <td>Dr. Smith</td>
                            <td><span class="badge bg-primary">In Consultation</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- LIVE QUEUE -->
    <div class="rcard rcard-bg mb-5">
        <div class="rcard-body">
            <h5 class="fw-bold mb-3">Live Queue</h5>

            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Current</span>
                    <strong>John Doe</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Next</span>
                    <strong>Michael Lee</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Upcoming</span>
                    <strong>Sarah Khan</strong>
                </li>
            </ul>

        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="rcard rcard-bg summary-card mb-5">
        <div class="rcard-body">
            <h5 class="fw-bold mb-3">Quick Actions</h5>

            <div class="d-flex flex-wrap gap-3">
                <a href="../reception/register_patient.php" class="btn btn-outline-danger">
                    Register Patient
                </a>
                <a href="../reception/manage_appointments.php" class="btn btn-outline-danger">
                    Manage Appointment
                </a>
                <a href="../reception/live_queue.php" class="btn btn-outline-danger">
                    View Queue
                </a>
            </div>
        </div>
    </div>

</div>


<!-- Bootstrap JS -->
<script src="../bootstrap/js/bootstrap.bundle.js"></script>

<?php include '../reception/reception_footer.php'; ?>
</body>
</html>
