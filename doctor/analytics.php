<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int) $_SESSION['doctor_id'];

$ref_date = $_GET['ref_date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $ref_date)) {
    $ref_date = date('Y-m-d');
}
$d = mysqli_real_escape_string($con, $ref_date);
$week_start = date('Y-m-d', strtotime($ref_date . ' -6 days'));
$ws = mysqli_real_escape_string($con, $week_start);

$avg_day = null;
$total_patients_day = 0;
$completed_day = 0;
$pending_skipped_day = 0;

$q1 = mysqli_query($con, "SELECT AVG(TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at)) AS avg_m,
    COUNT(*) AS completed_n
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$d'
AND t.status = 'Completed' AND t.called_at IS NOT NULL AND t.completed_at IS NOT NULL");
if ($q1 && ($r1 = mysqli_fetch_assoc($q1))) {
    if ($r1['avg_m'] !== null) {
        $avg_day = max(0, (int) round((float) $r1['avg_m']));
    }
    $completed_day = (int) ($r1['completed_n'] ?? 0);
}

$q1b = mysqli_query($con, "SELECT COUNT(*) AS c FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$d'");
if ($q1b && ($rb = mysqli_fetch_assoc($q1b))) {
    $total_patients_day = (int) $rb['c'];
}

$q1c = mysqli_query($con, "SELECT 
    COALESCE(SUM(CASE WHEN t.status IN ('Waiting','In Progress','Skipped') THEN 1 ELSE 0 END),0) AS ps
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$d'");
if ($q1c && ($rc = mysqli_fetch_assoc($q1c))) {
    $pending_skipped_day = (int) $rc['ps'];
}

$peak_labels = ['9–10', '10–11', '11–12', '12–1', '1–2', '2–3'];
$peak_data = [0, 0, 0, 0, 0, 0];
$qh = mysqli_query($con, "SELECT HOUR(t.called_at) AS hr, COUNT(*) AS c
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date BETWEEN '$ws' AND '$d'
AND t.status = 'Completed' AND t.called_at IS NOT NULL
AND HOUR(t.called_at) BETWEEN 9 AND 14
GROUP BY HOUR(t.called_at)");
if ($qh) {
    while ($h = mysqli_fetch_assoc($qh)) {
        $hr = (int) $h['hr'];
        $idx = $hr - 9;
        if ($idx >= 0 && $idx <= 5) {
            $peak_data[$idx] = (int) $h['c'];
        }
    }
}

$type_new = $type_fu = $type_em = 0;
$qt = mysqli_query($con, "SELECT appointment_type, COUNT(*) AS c FROM appointments
WHERE doctor_id = $doctor_id AND appointment_date BETWEEN '$ws' AND '$d'
GROUP BY appointment_type");
if ($qt) {
    while ($t = mysqli_fetch_assoc($qt)) {
        if ($t['appointment_type'] === 'New') {
            $type_new = (int) $t['c'];
        } elseif ($t['appointment_type'] === 'Follow Up') {
            $type_fu = (int) $t['c'];
        } elseif ($t['appointment_type'] === 'Emergency') {
            $type_em = (int) $t['c'];
        }
    }
}

$done_stack = $pend_stack = 0;
$qd = mysqli_query($con, "SELECT 
COALESCE(SUM(CASE WHEN t.status = 'Completed' THEN 1 ELSE 0 END),0) AS done,
COALESCE(SUM(CASE WHEN t.status IN ('Waiting','In Progress','Skipped') THEN 1 ELSE 0 END),0) AS pend
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$d'");
if ($qd && ($rd = mysqli_fetch_assoc($qd))) {
    $done_stack = (int) $rd['done'];
    $pend_stack = (int) $rd['pend'];
}

$fast = $avg = $slow = 0;
$qs = mysqli_query($con, "SELECT 
COALESCE(SUM(CASE WHEN TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at) < 8 THEN 1 ELSE 0 END),0) AS f,
COALESCE(SUM(CASE WHEN TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at) >= 8 AND TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at) <= 15 THEN 1 ELSE 0 END),0) AS a,
COALESCE(SUM(CASE WHEN TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at) > 15 THEN 1 ELSE 0 END),0) AS s
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date BETWEEN '$ws' AND '$d'
AND t.status = 'Completed' AND t.called_at IS NOT NULL AND t.completed_at IS NOT NULL");
if ($qs && ($rs = mysqli_fetch_assoc($qs))) {
    $fast = (int) $rs['f'];
    $avg = (int) $rs['a'];
    $slow = (int) $rs['s'];
}

$total_consult_mins = 0;
$qtot = mysqli_query($con, "SELECT COALESCE(SUM(TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at)),0) AS sm
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$d'
AND t.status = 'Completed' AND t.called_at IS NOT NULL AND t.completed_at IS NOT NULL");
if ($qtot && ($rt = mysqli_fetch_assoc($qtot))) {
    $total_consult_mins = (int) $rt['sm'];
}

$peak_hour_label = '—';
$slow_hour_label = '—';
$peak_max = max($peak_data);
if ($peak_max > 0) {
    $peak_hour_label = $peak_labels[array_search($peak_max, $peak_data, true)];
    $nz = array_filter($peak_data, function ($v) {
        return $v > 0;
    });
    if (count($nz)) {
        $slow_val = min($nz);
        $slow_hour_label = $peak_labels[array_search($slow_val, $peak_data, true)];
    }
}

$week_avg = null;
$week_total_appts = 0;
$week_days = 0;
$qw = mysqli_query($con, "SELECT AVG(TIMESTAMPDIFF(MINUTE, t.called_at, t.completed_at)) AS avg_m
FROM tokens t
INNER JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date BETWEEN '$ws' AND '$d'
AND t.status = 'Completed' AND t.called_at IS NOT NULL AND t.completed_at IS NOT NULL");
if ($qw && ($rw = mysqli_fetch_assoc($qw)) && $rw['avg_m'] !== null) {
    $week_avg = max(0, (int) round((float) $rw['avg_m']));
}

$qw2 = mysqli_query($con, "SELECT COUNT(*) AS c FROM appointments
WHERE doctor_id = $doctor_id AND appointment_date BETWEEN '$ws' AND '$d'");
if ($qw2 && ($rw2 = mysqli_fetch_assoc($qw2))) {
    $week_total_appts = (int) $rw2['c'];
}

$qw3 = mysqli_query($con, "SELECT COUNT(DISTINCT appointment_date) AS c FROM appointments
WHERE doctor_id = $doctor_id AND appointment_date BETWEEN '$ws' AND '$d'");
if ($qw3 && ($rw3 = mysqli_fetch_assoc($qw3))) {
    $week_days = (int) $rw3['c'];
}

$fmt_dur = function ($mins) {
    if ($mins <= 0) {
        return '0 min';
    }
    $h = intdiv($mins, 60);
    $m = $mins % 60;
    if ($h > 0) {
        return $h . ' hr' . ($m ? ' ' . $m . ' min' : '');
    }
    return $m . ' min';
};

$avg_day_disp = $avg_day !== null ? $avg_day . ' min' : '—';
$chart_peak = json_encode($peak_data);
$chart_type = json_encode([$type_new, $type_fu, $type_em]);
$chart_daily = json_encode([$done_stack, $pend_stack]);
$chart_speed = json_encode([$fast, $avg, $slow]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Analytics</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <section class="features-header my-1">
            <h2>Welcome, <span>Dr. <?php echo htmlspecialchars($_SESSION['doctor_name']); ?></span></h2>
        </section>

        <section class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">Consultation Time Analytics</h4>
                <p class="text-muted mb-0">Based on completed consultations (<?php echo htmlspecialchars($week_start); ?> → <?php echo htmlspecialchars($ref_date); ?> for charts)</p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="exportAnalytics()">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>
                <form method="get" class="d-flex gap-2 align-items-center">
                    <label class="small text-muted mb-0">Reference day</label>
                    <input type="date" name="ref_date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($ref_date); ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Apply</button>
                </form>
            </div>
        </section>

        <section class="mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="dstat-card highlight">
                        <h6>Avg Consultation Time</h6>
                        <h2><?php echo htmlspecialchars($avg_day_disp); ?></h2>
                        <small class="text-muted">Selected day</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Total Appointments</h6>
                        <h2><?php echo (int) $total_patients_day; ?></h2>
                        <small class="text-muted">Selected day</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Completed</h6>
                        <h2><?php echo (int) $completed_day; ?></h2>
                        <small class="text-muted">Tokens completed</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card danger">
                        <h6>Queue (wait / active / hold)</h6>
                        <h2><?php echo (int) $pending_skipped_day; ?></h2>
                        <small class="text-muted">Selected day</small>
                    </div>
                </div>

            </div>
        </section>

        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Peak Consultation Hours (7-day window)</div>
                    <div class="card-body">
                        <canvas id="peakHoursChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Appointment Type Breakdown (7-day window)</div>
                    <div class="card-body">
                        <canvas id="appointmentTypeChart"></canvas>
                    </div>
                </div>
            </div>

        </section>

        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Daily Patient Status (selected day)</div>
                    <div class="card-body">
                        <canvas id="dailyStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Consultation Speed Distribution (7-day window)</div>
                    <div class="card-body">
                        <canvas id="speedChart"></canvas>
                    </div>
                </div>
            </div>

        </section>

        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Daily Consultation Summary</div>
                    <div class="card-body">
                        <p><strong>Total consultation time:</strong> <?php echo htmlspecialchars($fmt_dur($total_consult_mins)); ?></p>
                        <p><strong>Busiest slot (7 days):</strong> <?php echo htmlspecialchars($peak_hour_label); ?></p>
                        <p class="mb-0"><strong>Quietest slot (7 days):</strong> <?php echo htmlspecialchars($slow_hour_label); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Appointment Type Breakdown (7-day window)</div>
                    <div class="card-body">
                        <div class="analytics-row">
                            <span>New Patients</span>
                            <span><?php echo (int) $type_new; ?></span>
                        </div>
                        <div class="analytics-row">
                            <span>Follow-ups</span>
                            <span><?php echo (int) $type_fu; ?></span>
                        </div>
                        <div class="analytics-row emergency-visit">
                            <span>Emergency Visits</span>
                            <span><?php echo (int) $type_em; ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Consultation Speed Indicator (7-day window)</div>
                    <div class="card-body">
                        <div class="speed-indicator fast">
                            <span>Fast (&lt; 8 min)</span>
                            <span><?php echo (int) $fast; ?></span>
                        </div>
                        <div class="speed-indicator average">
                            <span>Average (8–15 min)</span>
                            <span><?php echo (int) $avg; ?></span>
                        </div>
                        <div class="speed-indicator slow">
                            <span>Slow (&gt; 15 min)</span>
                            <span><?php echo (int) $slow; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Weekly Snapshot (last 7 days ending selected day)</div>
                    <div class="card-body">
                        <p><strong>Avg consultation time:</strong> <?php echo $week_avg !== null ? (int) $week_avg . ' min' : '—'; ?></p>
                        <p><strong>Total appointments:</strong> <?php echo (int) $week_total_appts; ?></p>
                        <p class="mb-0"><strong>Days with bookings:</strong> <?php echo (int) $week_days; ?></p>
                    </div>
                </div>
            </div>

        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
        Chart.defaults.color = "#374151";

        function snapshotChart(chart) {
            var canvas = chart.canvas;
            var offscreen = document.createElement('canvas');
            offscreen.width  = canvas.width;
            offscreen.height = canvas.height;
            var ctx = offscreen.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, offscreen.width, offscreen.height);
            ctx.drawImage(canvas, 0, 0);
            var img = canvas.nextElementSibling;
            if (!img || !img.classList.contains('chart-print-img')) {
                img = document.createElement('img');
                img.className = 'chart-print-img';
                img.style.display = 'none';
                canvas.parentNode.insertBefore(img, canvas.nextSibling);
            }
            img.src = offscreen.toDataURL('image/png');
        }

        function makeOptions(extra) {
            return Object.assign({
                animation: {
                    onComplete: function() { snapshotChart(this); }
                }
            }, extra);
        }

        var peakData = <?php echo $chart_peak; ?>;
        var typeData = <?php echo $chart_type; ?>;
        var dailyData = <?php echo $chart_daily; ?>;
        var speedData = <?php echo $chart_speed; ?>;

        new Chart(document.getElementById('peakHoursChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($peak_labels); ?>,
                datasets: [{
                    label: 'Patients',
                    data: peakData,
                    borderColor: '#FF5A5F',
                    backgroundColor: 'rgba(255,90,95,0.15)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: makeOptions({
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            })
        });

        new Chart(document.getElementById('appointmentTypeChart'), {
            type: 'doughnut',
            data: {
                labels: ['New', 'Follow-up', 'Emergency'],
                datasets: [{
                    data: typeData,
                    backgroundColor: ['#3b82f6', '#22c55e', '#dc3545']
                }]
            },
            options: makeOptions({
                plugins: { legend: { position: 'bottom' } }
            })
        });

        new Chart(document.getElementById('dailyStatusChart'), {
            type: 'bar',
            data: {
                labels: ['Selected day'],
                datasets: [
                    { label: 'Completed', data: [dailyData[0]], backgroundColor: '#22c55e' },
                    { label: 'In queue', data: [dailyData[1]], backgroundColor: '#fbbf24' }
                ]
            },
            options: makeOptions({
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            })
        });

        new Chart(document.getElementById('speedChart'), {
            type: 'bar',
            data: {
                labels: ['Fast', 'Average', 'Slow'],
                datasets: [{
                    data: speedData,
                    backgroundColor: ['#16a34a', '#f59e0b', '#dc3545']
                }]
            },
            options: makeOptions({
                plugins: { legend: { display: false } },
                indexAxis: 'y'
            })
        });

        function exportAnalytics() {
            window.print();
        }
    </script>

</body>

</html>
