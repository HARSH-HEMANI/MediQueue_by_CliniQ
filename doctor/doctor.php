<?php
include "doctor-auth.php";

$state = $_GET['state'] ?? 'idle';

$currentToken = null;
$currentPatient = null;
$statusText = "Waiting to start";
$badgeClass = "bg-secondary";
$badgeText = "Idle";

$totalPatients = 4;
$emergencyCount = 1;

if ($state == "next") {
    $currentToken = 21;
    $currentPatient = "Rahul Patel";
    $statusText = "In Consultation";
    $badgeClass = "bg-success";
    $badgeText = "Live";
}

if ($state == "hold") {
    $statusText = "Patient on Hold";
    $badgeClass = "bg-warning";
    $badgeText = "On Hold";
}

if ($state == "complete") {
    $statusText = "Patient Completed";
    $badgeClass = "bg-secondary";
    $badgeText = "Idle";
    $totalPatients = 3;
}

if($state == "emergency"){
    $currentToken = 23;
    $currentPatient = "Mohit Kumar";
    $statusText = "Emergency Case - Chest Pain";
    $badgeClass = "bg-danger";
    $badgeText = "Emergency Active";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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

        <!-- Header -->
        <section class="mb-4">
            <h2>Welcome, <span>Dr. <?php echo $_SESSION['doctor_name']; ?></span></h2>
        </section>

        <!-- Overview Cards -->
        <section class="mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Total Patients</h6>
                        <h2><?php echo $totalPatients; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card highlight">
                        <h6>Current Token</h6>
                        <h2>
                            <?php
                            if ($currentToken) {
                                echo "#" . $currentToken;
                            } else {
                                echo "--";
                            }
                            ?>
                        </h2>
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
                        <h2><?php echo $emergencyCount; ?></h2>
                    </div>
                </div>

            </div>
        </section>

        <!-- Live Queue -->
        <section class="row g-4">

            <div class="col-lg-7">
                <div class="dcard">

                    <div class="card-header d-flex justify-content-between">
                        <span>Live Queue Control</span>
                        <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo $badgeText; ?>
                        </span>
                    </div>

                    <div class="card-body text-center">

                        <div class="live-token mb-2">
                            <?php
                            if ($currentToken) {
                                echo "Token #" . $currentToken;
                            } else {
                                echo "No Active Token";
                            }
                            ?>
                        </div>

                        <div class="patient-name mb-3">
                            <?php
                            if ($currentPatient) {
                                echo "Patient: " . $currentPatient;
                            } else {
                                echo $statusText;
                            }
                            ?>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="?state=next" class="btn btn-brand">
                                <i class="bi bi-arrow-right-circle"></i> Call Next
                            </a>

                            <a href="?state=complete" class="btn btn-outline-success">
                                <i class="bi bi-check-circle"></i> Complete
                            </a>

                            <a href="?state=hold" class="btn btn-outline-warning">
                                <i class="bi bi-pause-circle"></i> Hold
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Emergency Panel -->
            <div class="col-lg-5">
                <div class="dcard emergency-card">

                    <div class="card-header emergency-header">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Emergency Alerts
                    </div>

                    <div class="card-body">

                        <?php if ($emergencyCount > 0): ?>
                            <div class="emergency-item">
                                <span>Token #23 - Chest Pain</span>
                                <a href="?state=emergency" class="btn btn-sm btn-light">
                                    View
                                </a>

                            </div>
                        <?php else: ?>
                            <p class="text-muted">No emergency cases.</p>
                        <?php endif; ?>

                    </div>

                </div>
            </div>

        </section>

        <!-- Upcoming Patients -->
        <section class="row g-4 mt-4">

            <div class="col-lg-12">
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
                                    <td><span class="badge bg-danger">Emergency</span></td>
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

        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

</body>

</html>