<?php
include "doctor-auth.php";
$q = function_exists('doctor_appt_type_label') ? null : null;

include "../db.php";
include __DIR__ . "/queue-context.inc.php";

$totalPatients = $queue_stats['total_tokens'];
$emergencyCount = $queue_stats['emergency_pending'];

$currentToken = null;
$currentPatient = null;
$statusText = 'Waiting to start';
$badgeClass = 'bg-secondary';
$badgeText = 'Idle';

if ($current_token) {
    $currentToken = (int) $current_token['token_no'];
    $currentPatient = $current_token['full_name'];
    $statusText = 'In Consultation';
    $badgeClass = 'bg-success';
    $badgeText = 'Live';
    if (($current_token['appointment_type'] ?? '') === 'Emergency') {
        $statusText = 'Emergency Case' . (!empty($current_token['visit_reason']) ? ' — ' . $current_token['visit_reason'] : '');
        $badgeClass = 'bg-danger';
        $badgeText = 'Emergency Active';
    }
} elseif ($queue_stats['waiting'] > 0) {
    $statusText = $queue_stats['waiting'] . ' patient(s) waiting';
    $badgeClass = 'bg-warning text-dark';
    $badgeText = 'Queue';
}

$avgLabel = $avg_consult_min !== null ? $avg_consult_min . ' min' : '—';
$upcoming = array_slice($waiting_rows, 0, 10);

function doctor_queue_type_badge_class($appointment_type)
{
    if ($appointment_type === 'Emergency') {
        return 'type-emergency';
    }
    if ($appointment_type === 'Follow Up') {
        return 'type-follow';
    }
    return 'type-new';
}
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
            <h2>Welcome, <span>Dr. <?php echo htmlspecialchars($_SESSION['doctor_name'] ?? 'Doctor'); ?></span></h2>
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
                        <h2><?php echo htmlspecialchars($avgLabel); ?></h2>
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

                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="queue-action.php?action=next&amp;redirect=doctor.php&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-brand">
                                <i class="bi bi-arrow-right-circle"></i> Call Next
                            </a>
                            <?php if (!empty($current_token['token_id'])): ?>
                                <a href="queue-action.php?action=complete&amp;token_id=<?php echo (int) $current_token['token_id']; ?>&amp;redirect=doctor.php&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-outline-success">
                                    <i class="bi bi-check-circle"></i> Complete
                                </a>
                                <a href="queue-action.php?action=skip&amp;token_id=<?php echo (int) $current_token['token_id']; ?>&amp;redirect=doctor.php&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-outline-warning">
                                    <i class="bi bi-pause-circle"></i> Hold
                                </a>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-success" disabled title="No active consultation">Complete</button>
                                <button type="button" class="btn btn-outline-warning" disabled title="No active consultation">Hold</button>
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
                        <?php if (count($emergency_waiting_rows) > 0): ?>
                            <?php foreach ($emergency_waiting_rows as $er): ?>
                                <div class="emergency-item d-flex justify-content-between align-items-center gap-2 mb-2">
                                    <span>Token #<?php echo (int) $er['token_no']; ?> — <?php echo htmlspecialchars($er['full_name']); ?></span>
                                    <a href="queue-action.php?action=call_emergency&amp;token_no=<?php echo (int) $er['token_no']; ?>&amp;redirect=doctor.php&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-sm btn-light">Call</a>
                                </div>
                            <?php endforeach; ?>
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
                        <?php if (count($upcoming) === 0): ?>
                            <p class="text-muted p-3 mb-0">No patients waiting in queue for <?php echo htmlspecialchars($queue_date); ?>.</p>
                        <?php else: ?>
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
                                    <?php foreach ($upcoming as $row): ?>
                                        <tr>
                                            <td>#<?php echo (int) $row['token_no']; ?></td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><span class="badge <?php echo htmlspecialchars(doctor_queue_type_badge_class($row['appointment_type'] ?? '')); ?>"><?php echo htmlspecialchars(doctor_appt_type_label($row['appointment_type'] ?? '')); ?></span></td>
                                            <td><span class="badge bg-warning text-dark">Waiting</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
