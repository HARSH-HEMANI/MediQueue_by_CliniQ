<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Dashboard</title>
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.css">
    <script src="./css/bootstrap/js/bootstrap.bundle.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php include './sidebar/reception-sidebar.php'; ?>
    <div class="container-fluid my-5 pt-4">

        <!-- Page Title -->
        <div class="mb-4">
            <br>
            <h3>Receptionist Dashboard</h3>
            <p class="text-muted">Welcome, Receptionist</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Total Visitors</h6>
                        <h3 class="text-primary">120</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Today Appointments</h6>
                        <h3 class="text-success">18</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Pending Requests</h6>
                        <h3 class="text-warning">5</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Completed</h6>
                        <h3 class="text-info">97</h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Quick Actions
                    </div>
                    <div class="card-body d-flex gap-2 flex-wrap">
                        <a href="add_visitor.php" class="btn btn-success">Add Visitor</a>
                        <a href="appointments.php" class="btn btn-info text-white">View Appointments</a>
                        <a href="users.php" class="btn btn-secondary">View Users</a>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visitor List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        Recent Visitors
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Purpose</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Rahul Sharma</td>
                                    <td>9876543210</td>
                                    <td>Appointment</td>
                                    <td>2026-01-30</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Anita Patel</td>
                                    <td>9123456780</td>
                                    <td>Inquiry</td>
                                    <td>2026-01-30</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>