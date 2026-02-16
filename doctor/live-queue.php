<?php
include "doctor-auth.php";

$state = $_GET['state'] ?? 'idle';

$currentToken = null;
$currentName  = null;
$currentAge   = null;
$currentType  = null;

$totalTokens = 48;
$completed   = 21;
$pending     = 27;

$statusBadge = "bg-secondary";
$statusText  = "Idle";

/* ================= NORMAL NEXT ================= */
if ($state == "next") {
    $currentToken = "22";
    $currentName  = "Anita Shah";
    $currentAge   = 29;
    $currentType  = "New";

    $statusBadge = "bg-success";
    $statusText  = "In Progress";
}

/* ================= COMPLETE ================= */
if ($state == "complete") {
    $completed = 22;
    $pending   = 26;

    $statusBadge = "bg-secondary";
    $statusText  = "Patient Completed";
}

/* ================= HOLD ================= */
if ($state == "hold") {
    $statusBadge = "bg-warning";
    $statusText  = "On Hold";
}

/* ================= EMERGENCY ================= */
if ($state == "emergency") {
    $currentToken = "E1";
    $currentName  = "Suresh Mehta";
    $currentAge   = 45;
    $currentType  = "Emergency";

    $statusBadge = "bg-danger";
    $statusText  = "Emergency Active";
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

        <!-- HEADER -->
        <section class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Live Queue</h4>
                <p class="text-muted mb-0">Real-time patient queue control</p>
            </div>
            <a href="doctor-dashboard.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </a>
        </section>

        <!-- CURRENT TOKEN -->
        <section class="mb-4">
            <div class="dcard text-center p-4 current-token-card">

                <div class="mb-2">
                    <span class="badge <?php echo $statusBadge; ?>">
                        <?php echo $statusText; ?>
                    </span>

                    <?php if ($currentType): ?>
                        <span class="badge ms-1 
                        <?php echo ($currentType == 'Emergency') ? 'type-emergency' : 'type-follow'; ?>">
                            <?php echo $currentType; ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="current-token-number">
                    <?php
                    if ($currentToken) {
                        echo "Token #" . $currentToken;
                    } else {
                        echo "No Active Token";
                    }
                    ?>
                </div>

                <div class="patient-name mt-2">
                    <?php
                    if ($currentName) {
                        echo $currentName . " (" . $currentAge . " yrs)";
                    } else {
                        echo "Waiting to start";
                    }
                    ?>
                </div>

                <!-- CONTROLS -->
                <div class="d-flex justify-content-center gap-3 mt-4">
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
        </section>

        <!-- SUMMARY -->
        <section class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="dstat-card">
                        <h6>Total Tokens</h6>
                        <h2><?php echo $totalTokens; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card highlight">
                        <h6>Completed</h6>
                        <h2><?php echo $completed; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card danger">
                        <h6>Pending</h6>
                        <h2><?php echo $pending; ?></h2>
                    </div>
                </div>
            </div>
        </section>

        <!-- QUEUE TABLE -->
        <section class="row g-4">

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
                                        <a href="?state=emergency" class="badge bg-danger text-decoration-none">
                                            View
                                        </a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="col-lg-4">

                <div class="dcard mb-3">
                    <div class="card-header emergency-header">
                        Emergency Actions
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="?state=emergency" class="btn btn-outline-danger">
                            <i class="bi bi-plus-circle"></i> Insert Emergency Token
                        </a>

                        <a href="?state=emergency" class="btn btn-outline-danger">
                            <i class="bi bi-telephone-forward"></i> Call Priority Patient
                        </a>
                    </div>
                </div>

                <div class="dcard">
                    <div class="card-header">
                        Selected Token Info
                    </div>
                    <div class="card-body">
                        <?php if ($currentName): ?>
                            <p class="mb-1"><strong>Name:</strong> <?php echo $currentName; ?></p>
                            <p class="mb-1"><strong>Age:</strong> <?php echo $currentAge; ?></p>
                            <p class="mb-0"><strong>Visit Type:</strong> <?php echo $currentType; ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-0">No patient selected</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

</body>

</html>