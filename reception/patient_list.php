```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception | Patient Directory</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/reception.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>

<?php include '../sidebar/reception-sidebar.php'; ?>


<div class="reception-dashboard">

    <!-- HEADER -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="dashboard-title">
                Patient <span>Directory</span>
            </h2>

            <p class="dashboard-subtitle">
                All patients with appointments scheduled for today
            </p>

        </div>

        <div class="d-flex gap-2">
            <input type="text"
                   class="form-control"
                   placeholder="Search patient..."
                   style="width:250px;">

        <a href="register_patient.php" class="btn btn-brand">
            <i class="bi bi-person-plus"></i>
            Register Patient
        </a>


        </div>

    </div>

    <!-- DIRECTORY CARD -->
    <div class="card dashboard-card shadow-lg">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age / Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Doctor</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Rahul Patel</td>
                        <td>32 / Male</td>
                        <td>9876543210</td>
                        <td>rahul@email.com</td>
                        <td>Dr. Mehta</td>
                        <td>10:30 AM</td>
                        <td><span class="badge bg-primary">Follow-up</span></td>
                        <td><span class="badge bg-warning text-dark">Waiting</span></td>
                    </tr>

                    <tr>
                        <td>Neha Sharma</td>
                        <td>28 / Female</td>
                        <td>9123456780</td>
                        <td>neha@email.com</td>
                        <td>Dr. Shah</td>
                        <td>11:00 AM</td>
                        <td><span class="badge bg-danger">Emergency</span></td>
                        <td><span class="badge bg-success">Completed</span></td>
                    </tr>
                    
                </tbody>

</table>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="mt-3 text-muted">

        Showing patients scheduled for today

    </div>

</div>

    <?php include '../reception/reception_footer.php'; ?>

</body>
</html>
