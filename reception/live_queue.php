<?php
require_once "reception-init.php";

// ✅ IMPORTANT: Fix timezone
date_default_timezone_set('Asia/Kolkata');

$content_page = 'Live Queue | Reception';
ob_start();

/* ========================
   SET TODAY DATE
======================== */
$today = date('Y-m-d');

/* ========================
   FETCH TODAY'S QUEUE ONLY
======================== */
$query = mysqli_query($con, "
    SELECT 
        t.token_id,
        t.token_no,
        t.queue_position,
        t.status,
        t.called_at,
        t.completed_at,

        p.full_name AS patient_name,
        d.full_name AS doctor_name,

        a.appointment_time,
        a.appointment_date

    FROM tokens t
    INNER JOIN appointments a ON t.appointment_id = a.appointment_id
    INNER JOIN patients p ON a.patient_id = p.patient_id
    INNER JOIN doctors d ON a.doctor_id = d.doctor_id

    WHERE DATE(a.appointment_date) = '$today'

    ORDER BY t.token_no ASC
");

/* ========================
   HANDLE QUERY FAILURE
======================== */
if (!$query) {
    die("Query Error: " . mysqli_error($con));
}

/* ========================
   PROCESS QUEUE
======================== */
$queue = [];
$current = null;

$waiting = 0;
$in_progress = 0;
$completed = 0;

while ($row = mysqli_fetch_assoc($query)) {

    $queue[] = $row;

    switch ($row['status']) {

        case 'Waiting':
            $waiting++;
            break;

        case 'In Progress':
        case 'Consulting': // support both
            $in_progress++;

            // First active patient becomes current
            if (!$current) {
                $current = $row;
            }
            break;

        case 'Completed':
            $completed++;
            break;
    }
}

/* ========================
   TOTAL COUNT
======================== */
$total = count($queue);

/* ========================
   FALLBACK LOGIC
   (if no one is in progress)
======================== */
if (!$current && $total > 0) {
    foreach ($queue as $row) {
        if ($row['status'] === 'Waiting') {
            $current = $row;
            break;
        }
    }
}

/* ========================
   OPTIONAL: DEBUG (REMOVE LATER)
======================== */
// echo "<pre>";
// print_r($queue);
// exit;

?>
<div class="reception-dashboard">

    <!-- ===== PAGE HEADER ===== -->
    <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h1 class="dashboard-title"><span>Live</span> Queue Monitor</h1>
            <p class="dashboard-subtitle">
                <i class="bi bi-calendar3 me-1"></i>
                <?= date('l, d F Y') ?> &nbsp;·&nbsp;
                <i class="bi bi-clock me-1"></i>
                <span id="live-clock"></span>
            </p>
        </div>
        <div class="lq-refresh-badge">
            <span class="lq-pulse-dot"></span>
            Auto-refreshing live
        </div>
    </div>

    <!-- ===== STATS ROW ===== -->
    <div class="stats-row mb-4">
        <div class="rstat-card">
            <h6><i class="bi bi-people me-1"></i>Total</h6>
            <h2><?= $total ?></h2>
        </div>
        <div class="rstat-card">
            <h6><i class="bi bi-hourglass-split me-1"></i>Waiting</h6>
            <h2><?= $waiting ?></h2>
        </div>
        <div class="rstat-card lq-stat-amber">
            <h6><i class="bi bi-activity me-1"></i>In Progress</h6>
            <h2><?= $in_progress ?></h2>
        </div>
        <div class="rstat-card lq-stat-green">
            <h6><i class="bi bi-check2-circle me-1"></i>Completed</h6>
            <h2><?= $completed ?></h2>
        </div>
    </div>

    <!-- ===== MAIN CONTENT GRID ===== -->
    <div class="row g-4">

        <!-- LEFT COLUMN: Live Monitor -->
        <div class="col-xl-4 col-lg-5">
            <div class="rcard current-token-card h-100">
                <div class="rcard-body d-flex flex-column gap-3">

                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="bi bi-broadcast me-2 text-brand"></i>Live Monitor
                        </h5>
                        <span class="lq-live-pill">
                            <span class="lq-pulse-dot lq-pulse-dot--sm"></span> LIVE
                        </span>
                    </div>

                    <?php if ($current): ?>

                        <!-- Now Serving -->
                        <div class="lq-now-serving-label">NOW SERVING</div>

                        <!-- Big Token -->
                        <div class="text-center py-2">
                            <div class="lq-token-badge"><?= str_pad($current['token_no'], 3, '0', STR_PAD_LEFT) ?></div>
                        </div>

                        <!-- Patient Details -->
                        <div class="lq-patient-card">
                            <div class="lq-patient-avatar"><?= strtoupper(substr($current['patient_name'], 0, 1)) ?></div>
                            <div>
                                <div class="r-patient-name"><?= htmlspecialchars($current['patient_name']) ?></div>
                                <div style="font-size:.82rem; color:#6b7280; margin-top:2px;">
                                    <i class="bi bi-person-badge me-1 text-brand"></i><?= htmlspecialchars($current['doctor_name']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Row -->
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge-soft-warning">
                                <i class="bi bi-clock me-1"></i><?= date('h:i A', strtotime($current['appointment_time'])) ?>
                            </span>
                            <span class="badge-soft-success">
                                <i class="bi bi-activity me-1"></i><?= $current['status'] ?>
                            </span>
                        </div>

                        <!-- Progress -->
                        <?php if ($total > 0): ?>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between mb-1 lq-progress-label">
                                    <span>Queue Progress</span>
                                    <span><?= $completed ?> / <?= $total ?> completed</span>
                                </div>
                                <div class="progress lq-progress">
                                    <div class="progress-bar lq-progress-bar"
                                        style="width:<?= round(($completed / $total) * 100) ?>%">
                                    </div>
                                </div>
                                <div class="lq-progress-pct"><?= round(($completed / $total) * 100) ?>%</div>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="lq-empty-state flex-grow-1">
                            <div class="lq-empty-icon"><i class="bi bi-person-slash"></i></div>
                            <p class="lq-empty-title">No Active Patient</p>
                            <p class="lq-empty-sub">Queue is idle for today</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Queue Table -->
        <div class="col-xl-8 col-lg-7">
            <div class="rcard h-100">
                <div class="rcard-body d-flex flex-column gap-3">

                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ol me-2 text-brand"></i>Queue List
                        </h5>
                        <span class="badge-soft-primary"><?= $total ?> Patient<?= $total != 1 ? 's' : '' ?></span>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="r-thead">
                                <tr>
                                    <th>Token</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($queue as $row): ?>
                                    <tr class="<?= $row['status'] === 'In Progress' ? 'lq-row-active' : '' ?>">
                                        <td>
                                            <span class="lq-token-chip">#<?= str_pad($row['token_no'], 3, '0', STR_PAD_LEFT) ?></span>
                                        </td>
                                        <td>
                                            <div class="lq-patient-cell">
                                                <div class="lq-avatar-sm"><?= strtoupper(substr($row['patient_name'], 0, 1)) ?></div>
                                                <span style="font-weight:600; font-size:.9rem; color:#111827;">
                                                    <?= htmlspecialchars($row['patient_name']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td style="font-size:.85rem; color:#374151;">
                                            <i class="bi bi-person-badge me-1 text-brand" style="font-size:.8rem;"></i>
                                            <?= htmlspecialchars($row['doctor_name']) ?>
                                        </td>
                                        <td style="font-size:.83rem; color:#6b7280; white-space:nowrap;">
                                            <i class="bi bi-clock me-1"></i><?= date('h:i A', strtotime($row['appointment_time'])) ?>
                                        </td>
                                        <td>
                                            <?php
                                            [$badge, $icon] = match ($row['status']) {
                                                'In Progress' => ['badge-soft-warning', 'bi-activity'],
                                                'Completed'   => ['badge-soft-success', 'bi-check2-circle'],
                                                default       => ['badge-soft-primary', 'bi-hourglass-split'],
                                            };
                                            ?>
                                            <span class="<?= $badge ?>">
                                                <i class="bi <?= $icon ?> me-1"></i><?= $row['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($queue)): ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="lq-empty-state" style="padding:40px 0;">
                                                <div class="lq-empty-icon"><i class="bi bi-calendar-x"></i></div>
                                                <p class="lq-empty-title">No Queue for Today</p>
                                                <p class="lq-empty-sub">Appointments will appear here once registered</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div><!-- /row -->

</div><!-- /reception-dashboard -->

<script>
    // Live clock
    function updateClock() {
        const el = document.getElementById('live-clock');
        if (el) el.textContent = new Date().toLocaleTimeString('en-IN', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Auto-refresh page every 30 seconds
    setTimeout(() => location.reload(), 30000);
</script>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>