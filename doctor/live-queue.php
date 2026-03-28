<?php
include "doctor-auth.php";
include "../db.php";
include __DIR__ . "/queue-context.inc.php";

$totalTokens = $queue_stats['total_tokens'];
$completed = $queue_stats['completed'];
$pending = $queue_stats['waiting'];

$statusBadge = 'bg-secondary';
$statusText = 'Idle';
$currentToken = null;
$currentName = null;
$currentAge = null;
$currentType = null;

if ($current_token) {
    $currentToken = (string) (int) $current_token['token_no'];
    $currentName = $current_token['full_name'];
    $currentAge = doctor_patient_age($current_token['date_of_birth'] ?? '');
    $currentType = doctor_appt_type_label($current_token['appointment_type'] ?? '');
    $statusBadge = 'bg-success';
    $statusText = 'In Progress';
    if (($current_token['appointment_type'] ?? '') === 'Emergency') {
        $statusBadge = 'bg-danger';
        $statusText = 'Emergency Active';
    }
} elseif ($queue_stats['waiting'] > 0) {
    $statusText = 'Ready — ' . $queue_stats['waiting'] . ' waiting';
    $statusBadge = 'bg-warning text-dark';
}

function doctor_live_type_badge($appointment_type)
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

                <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                    <a href="queue-action.php?action=next&amp;redirect=<?php echo urlencode('live-queue.php?date=' . $queue_date); ?>&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-brand">
                        <i class="bi bi-arrow-right-circle"></i> Call Next
                    </a>
                    <?php if (!empty($current_token['token_id'])): ?>
                        <a href="queue-action.php?action=complete&amp;token_id=<?php echo (int) $current_token['token_id']; ?>&amp;redirect=<?php echo urlencode('live-queue.php?date=' . $queue_date); ?>&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-outline-success">
                            <i class="bi bi-check-circle"></i> Complete
                        </a>
                        <a href="queue-action.php?action=skip&amp;token_id=<?php echo (int) $current_token['token_id']; ?>&amp;redirect=<?php echo urlencode('live-queue.php?date=' . $queue_date); ?>&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-outline-warning">
                            <i class="bi bi-pause-circle"></i> Hold
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-success" disabled>Complete</button>
                        <button type="button" class="btn btn-outline-warning" disabled>Hold</button>
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
                        <?php if (count($waiting_rows) === 0): ?>
                            <p class="text-muted p-3 mb-0">No tokens waiting.</p>
                        <?php else: ?>
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
                                    <?php foreach ($waiting_rows as $row): ?>
                                        <?php
                                        $isEm = ($row['appointment_type'] ?? '') === 'Emergency';
                                        $trClass = $isEm ? 'priority-row' : '';
                                        ?>
                                        <tr class="<?php echo $trClass; ?>">
                                            <td>#<?php echo (int) $row['token_no']; ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($row['full_name']); ?><br>
                                                <small class="text-muted">
                                                    <?php
                                                    $a = doctor_patient_age($row['date_of_birth'] ?? '');
                                                    echo $a !== null ? (int) $a . ' yrs' : '—';
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo htmlspecialchars(doctor_live_type_badge($row['appointment_type'] ?? '')); ?>">
                                                    <?php echo htmlspecialchars(doctor_appt_type_label($row['appointment_type'] ?? '')); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($isEm): ?>
                                                    <a href="queue-action.php?action=call_emergency&amp;token_no=<?php echo (int) $row['token_no']; ?>&amp;redirect=<?php echo urlencode('live-queue.php?date=' . $queue_date); ?>&amp;date=<?php echo urlencode($queue_date); ?>" class="badge bg-danger text-decoration-none">Call now</a>
                                                <?php else: ?>
                                                    <span class="badge status-waiting">Waiting</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="dcard mb-3">
                    <div class="card-header emergency-header">Emergency Actions</div>
                    <div class="card-body d-grid gap-2">
                        <?php if (count($emergency_waiting_rows) > 0): ?>
                            <?php foreach ($emergency_waiting_rows as $er): ?>
                                <a href="queue-action.php?action=call_emergency&amp;token_no=<?php echo (int) $er['token_no']; ?>&amp;redirect=<?php echo urlencode('live-queue.php?date=' . $queue_date); ?>&amp;date=<?php echo urlencode($queue_date); ?>" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-telephone-forward"></i> Call #<?php echo (int) $er['token_no']; ?> — <?php echo htmlspecialchars($er['full_name']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small mb-0">No emergency tokens in queue.</p>
                        <?php endif; ?>
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
