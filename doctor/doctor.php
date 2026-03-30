<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$today = date('Y-m-d');

// Total Patients Today
$totalQ = mysqli_query($con, "SELECT COUNT(*) as total FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$today'");
$totalPatients = mysqli_fetch_assoc($totalQ)['total'] ?? 0;

// Emergency Cases Today
$emergencyQ = mysqli_query($con, "SELECT COUNT(*) as count FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$today' AND appointment_type = 'Emergency'");
$emergencyCount = mysqli_fetch_assoc($emergencyQ)['count'] ?? 0;

// Avg consultation time today
$avgTimeQ = mysqli_query($con, "SELECT AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as avg_min 
                                FROM tokens t 
                                JOIN appointments a ON t.appointment_id = a.appointment_id 
                                WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'Completed'");
$avgData = mysqli_fetch_assoc($avgTimeQ);
$avgTime = $avgData['avg_min'] ? round($avgData['avg_min']) : 0;

// Current Token in Progress
$currentQ = mysqli_query($con, "SELECT t.token_id, t.token_no, p.full_name, a.visit_reason, a.appointment_type 
                                FROM tokens t 
                                JOIN appointments a ON t.appointment_id = a.appointment_id 
                                JOIN patients p ON a.patient_id = p.patient_id 
                                WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'In Progress' 
                                ORDER BY t.called_at DESC LIMIT 1");

$currentTokenData = mysqli_fetch_assoc($currentQ);

$currentToken = null;
$currentPatient = null;
$statusText     = "Waiting to start";
$badgeClass     = "bg-secondary";
$badgeText      = "Idle";
$currentTokenId = null;

if ($currentTokenData) {
    $currentToken   = $currentTokenData['token_no'];
    $currentPatient = $currentTokenData['full_name'];
    $currentTokenId = $currentTokenData['token_id'];
    if ($currentTokenData['appointment_type'] === 'Emergency') {
        $statusText = "Emergency Case - " . htmlspecialchars($currentTokenData['visit_reason'] ?? 'Immediate Attention');
        $badgeClass = "bg-danger";
        $badgeText  = "Emergency Active";
    } else {
        $statusText = "In Consultation";
        $badgeClass = "bg-success";
        $badgeText  = "Live";
    }
}

// Upcoming Patients
$upcomingQ = mysqli_query($con, "SELECT t.token_no, p.full_name, a.appointment_type, t.status 
                                 FROM tokens t 
                                 JOIN appointments a ON t.appointment_id = a.appointment_id 
                                 JOIN patients p ON a.patient_id = p.patient_id 
                                 WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' AND t.status = 'Waiting' 
                                 ORDER BY t.queue_position ASC LIMIT 5");

// Emergency Alerts
$emerAlertQ = mysqli_query($con, "SELECT t.token_no, a.visit_reason 
                                  FROM tokens t 
                                  JOIN appointments a ON t.appointment_id = a.appointment_id 
                                  WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$today' 
                                  AND a.appointment_type = 'Emergency' AND t.status IN ('Waiting') 
                                  ORDER BY t.queue_position ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Doctor Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <section class="features-header my-1">
            <h2>Welcome, <span><?php echo htmlspecialchars($_SESSION['doctor_name'] ?? 'Doctor'); ?></span></h2>
        </section>

        <section class="mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Total Patients (today)</h6>
                        <h2><?php echo (int) $totalPatients; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card highlight">
                        <h6>Current Token</h6>
                        <h2><?php echo $currentToken !== null ? '#' . (int) $currentToken : '--'; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Avg Consultation</h6>
                        <h2><?php echo $avgTime ?: "--"; ?> min</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card danger">
                        <h6>Emergency Waiting</h6>
                        <h2><?php echo (int) $emergencyCount; ?></h2>
                    </div>
                </div>

            </div>
        </section>

        <section class="row g-4">

            <div class="col-lg-7">
                <div class="dcard">
                    <div class="card-header d-flex justify-content-between">
                        <span>Live Queue Control</span>
                        <span class="badge <?php echo htmlspecialchars($badgeClass); ?>"><?php echo htmlspecialchars($badgeText); ?></span>
                    </div>
                    <div class="card-body text-center">

                        <div class="live-token mb-2">
                            <?php echo $currentToken !== null ? 'Token #' . (int) $currentToken : 'No Active Token'; ?>
                        </div>

                        <div class="patient-name mb-3">
                            <?php
                            if ($currentPatient) {
                                echo 'Patient: ' . htmlspecialchars($currentPatient);
                            } else {
                                echo htmlspecialchars($statusText);
                            }
                            ?>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
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
                </div>
            </div>

            <div class="col-lg-5">
                <div class="dcard emergency-card">
                    <div class="card-header emergency-header">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Emergency Alerts
                    </div>
                    <div class="card-body">
                        <?php if (mysqli_num_rows($emerAlertQ) > 0): ?>
                            <?php while($em = mysqli_fetch_assoc($emerAlertQ)): ?>
                            <div class="emergency-item">
                                <span>Token #<?php echo $em['token_no']; ?> - <?php echo htmlspecialchars($em['visit_reason'] ?: 'Emergency'); ?></span>
                                <a href="queue-action.php?action=call_emergency&token_no=<?php echo $em['token_no']; ?>" class="btn btn-sm btn-light">Call</a>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">No emergency cases waiting.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </section>

        <section class="row g-4 mt-4">
            <div class="col-lg-12">
                <div class="dcard">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Upcoming Patients</span>
                        <a href="live-queue.php" class="btn btn-sm btn-outline-secondary">Open Live Queue</a>
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
                                <?php if (mysqli_num_rows($upcomingQ) > 0): ?>
                                    <?php while($up = mysqli_fetch_assoc($upcomingQ)): ?>
                                        <tr>
                                            <td>#<?php echo $up['token_no']; ?></td>
                                            <td><?php echo htmlspecialchars($up['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($up['appointment_type']); ?></td>
                                            <td>
                                                <?php if ($up['appointment_type'] === 'Emergency'): ?>
                                                    <span class="badge bg-danger">Emergency</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Waiting</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-3 text-muted">No upcoming patients.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

</body>

</html>
