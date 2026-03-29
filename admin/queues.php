<?php
require_once "admin-init.php";

$content_page = 'live-queue';
ob_start();

// ==========================================
// 1. FETCH GLOBAL STATS FOR TODAY
// ==========================================
$stats_query = "SELECT 
    SUM(CASE WHEN status IN ('Pending', 'Confirmed') THEN 1 ELSE 0 END) as total_waiting,
    SUM(CASE WHEN appointment_type = 'Emergency' AND status IN ('Pending', 'Confirmed') THEN 1 ELSE 0 END) as emergency_today
FROM appointments 
WHERE appointment_date = CURDATE()";

$stats_result = mysqli_query($con, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

$total_waiting = $stats['total_waiting'] ?? 0;
$emergency_today = $stats['emergency_today'] ?? 0;

// ==========================================
// 2. FETCH QUEUE DATA BY CLINIC & DOCTOR
// ==========================================
$queue_query = "SELECT 
    c.clinic_name,
    d.full_name as doctor_name,
    COUNT(CASE WHEN a.status IN ('Pending', 'Confirmed') THEN 1 END) as waiting_count,
    COUNT(CASE WHEN a.appointment_type = 'Emergency' AND a.status IN ('Pending', 'Confirmed') THEN 1 END) as emergency_count,
    MIN(CASE WHEN a.status IN ('Pending', 'Confirmed') THEN a.appointment_time END) as next_appt_time
FROM appointments a
JOIN clinics c ON a.clinic_id = c.clinic_id
JOIN doctors d ON a.doctor_id = d.doctor_id
WHERE a.appointment_date = CURDATE()
GROUP BY a.clinic_id, a.doctor_id
ORDER BY waiting_count DESC";

$queue_result = mysqli_query($con, $queue_query);

$queue_data = [];
$clinics_under_load = 0;

if ($queue_result) {
    while ($row = mysqli_fetch_assoc($queue_result)) {
        // Calculate dynamic status and load (e.g., more than 5 waiting = busy/load)
        if ($row['waiting_count'] > 5) {
            $clinics_under_load++;
            $row['status_badge'] = '<span class="badge bg-warning text-dark">Busy</span>';
        } elseif ($row['waiting_count'] > 0) {
            $row['status_badge'] = '<span class="badge bg-success">Smooth</span>';
        } else {
            $row['status_badge'] = '<span class="badge bg-secondary">Idle</span>';
        }

        $queue_data[] = $row;
    }
}
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <div class="features-header text-center mb-4">
            <h2>Live <span>Queue Monitoring</span></h2>
            <div class="section-divider"></div>
            <p>Real-time overview of clinic queues for today (<?= date('d M Y') ?>)</p>
        </div>

        <div class="row mb-4">

            <div class="col-md-4">
                <div class="feature-acard text-center py-4">
                    <h6 class="text-muted">Total Waiting Patients</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_waiting ?></h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-acard text-center py-4">
                    <h6 class="text-muted">Clinics Under Load</h6>
                    <h2 class="mb-0 fw-bold <?= ($clinics_under_load > 0) ? 'text-warning' : 'text-success' ?>">
                        <?= $clinics_under_load ?>
                    </h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-acard text-center py-4">
                    <h6 class="text-muted">Emergency Cases Today</h6>
                    <h2 class="mb-0 fw-bold <?= ($emergency_today > 0) ? 'text-danger' : 'text-success' ?>">
                        <?= $emergency_today ?>
                    </h2>
                </div>
            </div>

        </div>

        <div class="feature-acard">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Clinic</th>
                            <th>Doctor</th>
                            <th>Next Appt Time</th>
                            <th>Waiting</th>
                            <th>Est. Wait Time</th>
                            <th>Emergency</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($queue_data)): ?>
                            <?php foreach ($queue_data as $q): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($q['clinic_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($q['doctor_name']) ?></td>
                                    <td>
                                        <?php if ($q['next_appt_time']): ?>
                                            <span class="text-primary fw-bold"><?= date('h:i A', strtotime($q['next_appt_time'])) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fs-6 fw-bold <?= ($q['waiting_count'] > 0) ? 'text-dark' : 'text-muted' ?>">
                                            <?= $q['waiting_count'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        // Assume an average of 15 minutes per waiting patient
                                        $est_wait = $q['waiting_count'] * 15;
                                        if ($est_wait > 0) {
                                            echo "~" . $est_wait . " min";
                                        } else {
                                            echo "—";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($q['emergency_count'] > 0): ?>
                                            <span class="badge bg-danger"><?= $q['emergency_count'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $q['status_badge'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No active queues or appointments scheduled for today.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>