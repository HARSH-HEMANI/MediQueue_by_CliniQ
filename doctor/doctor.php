<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MediQueue | Doctor Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/doctor.css">
</head>

<body class="dashboard-body">
<?php include '../sidebar/doctor-sidebar.php';?>

<main class="container-fluid" style="padding-top: 110px;">

    <!--------- Page Header ------------>
    <div class="d-flex justify-content-between align-items-center mb-4 px-4">
        <h4 class="fw-semibold mb-0">Doctor Dashboard</h4>
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-bell fs-5"></i>
            <span class="fw-semibold">Dr. Sharma</span>
        </div>
    </div>

    <!----------- Stats ----------->
    <div class="row g-3 px-4">
        <div class="col-md-3">
            <div class="stat-card">
                <small>Today's Patients</small>
                <h3>24</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <small>Current Token</small>
                <h3>T-07</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <small>Avg Consultation</small>
                <h3>8 min</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <small>Emergency Requests</small>
                <h3>1</h3>
            </div>
        </div>
    </div>

    <!--------- Live Queue ------------->
    <div class="row g-4 px-4 mt-4">
        <div class="col-md-7">
            <div class="live-card text-center">
                <h6 class="text-muted">NOW SERVING</h6>
                <h1 class="token">T-07</h1>
                <p>Patient: <strong>Ramesh Patel</strong></p>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button class="btn btn-brand">Call Next</button>
                    <button class="btn btn-success">Complete</button>
                    <button class="btn btn-outline-secondary">Hold</button>
                </div>
            </div>
        </div>

        <!------ Upcoming Patients --------->
        <div class="col-md-5">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    Upcoming Patients
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Token</th>
                                <th>Patient</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>T-08</td>
                                <td>Anita Shah</td>
                                <td>Follow-up</td>
                            </tr>
                            <tr>
                                <td>T-09</td>
                                <td>Rahul Mehta</td>
                                <td>New</td>
                            </tr>
                            <tr>
                                <td>T-10</td>
                                <td>Sneha Joshi</td>
                                <td>New</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</main>

</body>
</html>
