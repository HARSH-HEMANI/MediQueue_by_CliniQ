<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$today = date('Y-m-d');

// Total Tokens
$totalQ = mysqli_query($con, "SELECT COUNT(*) as total FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today'");
$totalTokens = mysqli_fetch_assoc($totalQ)['total'] ?? 0;

// Completed
$compQ = mysqli_query($con, "SELECT COUNT(*) as count FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'Completed'");
$completed = mysqli_fetch_assoc($compQ)['count'] ?? 0;

// Pending
$pendQ = mysqli_query($con, "SELECT COUNT(*) as count FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status IN ('Waiting', 'Skipped')");
$pending = mysqli_fetch_assoc($pendQ)['count'] ?? 0;

// Current Token
$currentQ = mysqli_query($con, "SELECT t.token_id, t.token_no, p.full_name, p.date_of_birth, a.appointment_type 
                                FROM tokens t 
                                JOIN appointments a ON t.appointment_id = a.appointment_id 
                                JOIN patients p ON a.patient_id = p.patient_id 
                                WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'In Progress' 
                                ORDER BY t.called_at DESC LIMIT 1");
$currentTokenData = mysqli_fetch_assoc($currentQ);

$statusBadge = 'bg-secondary';
$statusText = 'Idle';
$currentToken = null;
$currentName  = null;
$currentAge   = null;
$currentType  = null;
$currentTokenId = null;
$statusBadge  = "bg-secondary";
$statusText   = "Idle";

if ($currentTokenData) {
    $currentToken = $currentTokenData['token_no'];
    $currentTokenId = $currentTokenData['token_id'];
    $currentName  = $currentTokenData['full_name'];
    $currentType  = $currentTokenData['appointment_type'];
    
    if ($currentTokenData['date_of_birth']) {
        $dob = new DateTime($currentTokenData['date_of_birth']);
        $now = new DateTime();
        $currentAge = $now->diff($dob)->y;
    }
    
    if ($currentType === 'Emergency') {
        $statusBadge = "bg-danger";
        $statusText  = "Emergency Active";
    } else {
        $statusBadge = "bg-success";
        $statusText  = "In Progress";
    }
}

// Queue Table
$queueQ = mysqli_query($con, "SELECT t.token_no, p.full_name, a.appointment_type, t.status 
                              FROM tokens t 
                              JOIN appointments a ON t.appointment_id = a.appointment_id 
                              JOIN patients p ON a.patient_id = p.patient_id 
                              WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status IN ('Waiting', 'Skipped') 
                              ORDER BY (a.appointment_type = 'Emergency') DESC, t.queue_position ASC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Live Queue</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <section class="mb-4 d-flex justify-content-between align-items-center features-header my-1 flex-wrap gap-2">
            <div>
                <h2>Live <span>Queue</span></h2>
                <p class="text-muted mb-0">Real-time patient queue · <?php echo htmlspecialchars($queue_date); ?></p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <form method="get" action="live-queue.php" class="d-flex gap-2 align-items-center">
                    <input type="date" name="date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($queue_date); ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Go</button>
                </form>
                <a href="live-queue.php?date=<?php echo urlencode($queue_date); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
            </div>
        </section>

        <section class="mb-4">
            <div class="dcard text-center p-4 current-token-card">

                <div class="mb-2">
                    <span class="badge <?php echo htmlspecialchars($statusBadge); ?>"><?php echo htmlspecialchars($statusText); ?></span>
                    <?php if ($currentType): ?>
                        <span class="badge ms-1 <?php echo (($current_token['appointment_type'] ?? '') === 'Emergency') ? 'type-emergency' : 'type-follow'; ?>">
                            <?php echo htmlspecialchars($currentType); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="current-token-number">
                    <?php echo $currentToken !== null ? 'Token #' . htmlspecialchars($currentToken) : 'No Active Token'; ?>
                </div>

                <div class="patient-name mt-2">
                    <?php
                    if ($currentName) {
                        $ageStr = $currentAge !== null ? $currentAge . ' yrs' : '—';
                        echo htmlspecialchars($currentName) . ' (' . htmlspecialchars($ageStr) . ')';
                    } else {
                        echo 'Waiting to start';
                    }
                    ?>
                </div>

                <!-- CONTROLS -->
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="queue-action.php?action=next" class="btn btn-brand">
                        <i class="bi bi-arrow-right-circle"></i> Call Next
                    </a>
                    <?php if ($currentTokenId): ?>
                    <a href="queue-action.php?action=complete&token_id=<?php echo $currentTokenId; ?>" class="btn btn-outline-success">
                        <i class="bi bi-check-circle"></i> Complete
                    </a>
                    <a href="queue-action.php?action=skip&token_id=<?php echo $currentTokenId; ?>" class="btn btn-outline-warning">
                        <i class="bi bi-pause-circle"></i> Hold
                    </a>
                    <?php endif; ?>
                </div>

            </div>
        </section>

        <section class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="dstat-card">
                        <h6>Total Tokens</h6>
                        <h2><?php echo (int) $totalTokens; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card highlight">
                        <h6>Completed</h6>
                        <h2><?php echo (int) $completed; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card danger">
                        <h6>Waiting</h6>
                        <h2><?php echo (int) $pending; ?></h2>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4">

            <div class="col-lg-8">
                <div class="dcard">
                    <div class="card-header">Upcoming Tokens</div>
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
                                <?php if (mysqli_num_rows($queueQ) > 0): ?>
                                    <?php while($q = mysqli_fetch_assoc($queueQ)): ?>
                                    <tr class="<?php echo $q['appointment_type'] === 'Emergency' ? 'priority-row' : ''; ?>">
                                        <td>#<?php echo $q['token_no']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($q['full_name']); ?><br>
                                            <?php if ($q['appointment_type'] === 'Emergency'): ?>
                                                <small class="text-muted">Emergency</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($q['appointment_type'] === 'Emergency'): ?>
                                                <span class="badge type-emergency">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Priority
                                                </span>
                                            <?php else: ?>
                                                <span class="badge type-<?php echo strtolower(str_replace(' ', '-', $q['appointment_type'])); ?>">
                                                    <?php echo htmlspecialchars($q['appointment_type']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($q['appointment_type'] === 'Emergency'): ?>
                                                <a href="queue-action.php?action=call_emergency&token_no=<?php echo $q['token_no']; ?>" class="badge bg-danger text-decoration-none">Call Now</a>
                                            <?php else: ?>
                                                <span class="badge status-waiting"><?php echo htmlspecialchars($q['status']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-3 text-muted">No upcoming tokens.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="dcard mb-3">
                    <div class="card-header emergency-header">Emergency Actions</div>
                    <div class="card-body d-grid gap-2">
                        <a href="appointment.php" class="btn btn-outline-danger">
                            <i class="bi bi-calendar-event"></i> Manage Appointments
                        </a>
                    </div>
                </div>

                <div class="dcard">
                    <div class="card-header">Selected Token Info</div>
                    <div class="card-body">
                        <?php if ($currentName): ?>
                            <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($currentName); ?></p>
                            <p class="mb-1"><strong>Age:</strong> <?php echo $currentAge !== null ? (int) $currentAge : '—'; ?></p>
                            <p class="mb-0"><strong>Visit Type:</strong> <?php echo htmlspecialchars($currentType ?? ''); ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-0">No patient selected</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

</body>

</html>
