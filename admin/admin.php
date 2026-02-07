<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

    <?php include "admin-header.php"; ?>
    <?php include "admin-sidebar.php"; ?>

    <main class="features" style="margin-top:90px;">
        <div class="container">

            <div class="features-header text-center">
                <h2>System <span>Overview</span></h2>
                <div class="section-divider"></div>
                <p>Bird’s-eye view of MediQueue system health</p>
            </div>

            <div class="row">

                <div class="col-md-3 mb-4">
                    <div class="feature-card text-center">
                        <h6>Total Doctors</h6>
                        <h3>12</h3>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="feature-card text-center">
                        <h6>Total Patients</h6>
                        <h3>1,240</h3>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="feature-card text-center">
                        <h6>Today’s Appointments</h6>
                        <h3>86</h3>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="feature-card text-center">
                        <h6>Active Clinics</h6>
                        <h3>5</h3>
                    </div>
                </div>

            </div>

        </div>
    </main>



</body>

</html>