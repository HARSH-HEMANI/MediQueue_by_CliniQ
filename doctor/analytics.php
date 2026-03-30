<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$doctor_name = $_SESSION['doctor_name'] ?? "Doctor"; // Using session name directly
$filter_date = $_GET['date'] ?? date('Y-m-d');

// --- Define Time Window Variables (7-day window) ---
$ref_date = $filter_date;
$week_start = date('Y-m-d', strtotime('-6 days', strtotime($filter_date)));

// --- Total Patients (Filtered Day) ---
$totalQ = mysqli_query($con, "SELECT COUNT(*) as total FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$filter_date'");
$totalPatients = mysqli_fetch_assoc($totalQ)['total'] ?? 0;

// --- Completed Patients (Filtered Day) ---
$compQ = mysqli_query($con, "SELECT COUNT(*) as count FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date' AND t.status = 'Completed'");
$completed = mysqli_fetch_assoc($compQ)['count'] ?? 0;

// --- Pending / Skipped (Filtered Day) ---
$pendQ = mysqli_query($con, "SELECT COUNT(*) as count FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date' AND t.status IN ('Waiting', 'Skipped', 'In Progress')");
$pending = mysqli_fetch_assoc($pendQ)['count'] ?? 0;

// --- Avg Consultation Time (Filtered Day) ---
$avgTimeQ = mysqli_query($con, "SELECT AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as avg_min 
                                FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
                                WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date' AND t.status = 'Completed'");
$avgData = mysqli_fetch_assoc($avgTimeQ);
$avgTime = $avgData['avg_min'] ? round($avgData['avg_min']) : 0;

// --- Appointment Type Breakdown (Filtered Day) ---
$typeQ = mysqli_query($con, "SELECT appointment_type, COUNT(*) as count FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$filter_date' GROUP BY appointment_type");
$typeData = ['New' => 0, 'Follow Up' => 0, 'Emergency' => 0];
while ($row = mysqli_fetch_assoc($typeQ)) {
    $type = $row['appointment_type'];
    if ($type === 'Follow-up') $type = 'Follow Up';
    if (isset($typeData[$type])) $typeData[$type] = $row['count'];
}

// --- Speed indicator (7-day Window) ---
$speedData = ['fast' => 0, 'average' => 0, 'slow' => 0];
$timesQ = mysqli_query($con, "SELECT TIMESTAMPDIFF(MINUTE, called_at, completed_at) as duration 
                            FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
                            WHERE a.doctor_id = $doctor_id AND a.appointment_date BETWEEN '$week_start' AND '$ref_date' AND t.status = 'Completed'");
while ($row = mysqli_fetch_assoc($timesQ)) {
    $dur = (int)$row['duration'];
    if ($dur >= 0 && $dur < 8) $speedData['fast']++;
    elseif ($dur >= 8 && $dur <= 15) $speedData['average']++;
    elseif ($dur > 15) $speedData['slow']++;
}

// --- Peak hours (Filtered Day) ---
$peakQ = mysqli_query($con, "SELECT HOUR(appointment_time) as h, COUNT(*) as c FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$filter_date' GROUP BY HOUR(appointment_time) ORDER BY h");
$peakChartData = array_fill(9, 9, 0); // 9 AM to 5 PM
while ($r = mysqli_fetch_assoc($peakQ)) {
    $h = (int)$r['h'];
    if ($h >= 9 && $h <= 17) $peakChartData[$h] = (int)$r['c'];
}
$hoursValues = array_values($peakChartData);

// --- Weekly Snapshot Logic ---
$weekStatsQ = mysqli_query($con, "SELECT 
    COUNT(*) as total_appts, 
    COUNT(DISTINCT appointment_date) as active_days,
    AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as week_avg_time
    FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
    WHERE a.doctor_id = $doctor_id AND a.appointment_date BETWEEN '$week_start' AND '$ref_date' AND t.status = 'Completed'");
$weekStats = mysqli_fetch_assoc($weekStatsQ);

$week_total_appts = $weekStats['total_appts'] ?? 0;
$week_days = $weekStats['active_days'] ?? 0;
$week_avg = $weekStats['week_avg_time'] ? round($weekStats['week_avg_time']) : 0;

// --- Helper for Duration Formatting ---
$total_consult_mins = $completed * $avgTime;
$fmt_dur = function ($mins) {
    $h = floor($mins / 60);
    $m = $mins % 60;
    return ($h > 0 ? "{$h}h " : "") . "{$m}m";
};

// --- Busiest/Quietest labels (Placeholder logic) ---
$peak_hour_label = "11:00 AM - 12:00 PM";
$slow_hour_label = "04:00 PM - 05:00 PM";
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
            <h2>Welcome, <span>Dr. <?php echo htmlspecialchars($doctor_name); ?></span></h2>
        </section>

        <section class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">Consultation Time Analytics</h4>
                <p class="text-muted mb-0">Based on completed consultations (<?php echo date('d M', strtotime($week_start)); ?> → <?php echo date('d M', strtotime($ref_date)); ?> for charts)</p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="window.print()">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>
                <form class="d-flex gap-2" method="GET" action="analytics.php">
                    <input type="date" name="date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($filter_date); ?>" onchange="this.form.submit();">
                </form>
            </div>
        </section>

        <section class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="dstat-card highlight">
                        <h6>Avg Consultation Time</h6>
                        <h2><?php echo $avgTime ?: "--"; ?> min</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Total Patients</h6>
                        <h2><?php echo $totalPatients; ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Completed</h6>
                        <h2><?php echo $completed; ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dstat-card danger">
                        <h6>Pending / Skipped</h6>
                        <h2><?php echo $pending; ?></h2>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Peak Consultation Hours (Day)</div>
                    <div class="card-body"><canvas id="peakHoursChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Appointment Type Breakdown (Day)</div>
                    <div class="card-body"><canvas id="appointmentTypeChart"></canvas></div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Daily Patient Status</div>
                    <div class="card-body"><canvas id="dailyStatusChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Consultation Speed (7-day window)</div>
                    <div class="card-body"><canvas id="speedChart"></canvas></div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Daily Consultation Summary</div>
                    <div class="card-body">
                        <p><strong>Estimated total time:</strong> <?php echo $fmt_dur($total_consult_mins); ?></p>
                        <p><strong>Busiest slot:</strong> <?php echo $peak_hour_label; ?></p>
                        <p class="mb-0"><strong>Quietest slot:</strong> <?php echo $slow_hour_label; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Weekly Snapshot</div>
                    <div class="card-body">
                        <p><strong>Avg consultation time:</strong> <?php echo $week_avg; ?> min</p>
                        <p><strong>Total appointments:</strong> <?php echo $week_total_appts; ?></p>
                        <p class="mb-0"><strong>Active Days:</strong> <?php echo $week_days; ?></p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Initializations
        new Chart(document.getElementById('peakHoursChart'), {
            type: 'line',
            data: {
                labels: ['9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
                datasets: [{
                    label: 'Patients',
                    data: <?php echo json_encode($hoursValues); ?>,
                    borderColor: '#FF5A5F',
                    backgroundColor: 'rgba(255,90,95,0.15)',
                    tension: 0.3,
                    fill: true
                }]
            }
        });

        new Chart(document.getElementById('appointmentTypeChart'), {
            type: 'doughnut',
            data: {
                labels: ['New', 'Follow-up', 'Emergency'],
                datasets: [{
                    data: [<?php echo $typeData['New']; ?>, <?php echo $typeData['Follow Up']; ?>, <?php echo $typeData['Emergency']; ?>],
                    backgroundColor: ['#3b82f6', '#22c55e', '#dc3545']
                }]
            }
        });

        new Chart(document.getElementById('dailyStatusChart'), {
            type: 'bar',
            data: {
                labels: ['<?php echo date("d M", strtotime($filter_date)); ?>'],
                datasets: [{
                        label: 'Completed',
                        data: [<?php echo $completed; ?>],
                        backgroundColor: '#22c55e'
                    },
                    {
                        label: 'Pending',
                        data: [<?php echo $pending; ?>],
                        backgroundColor: '#fbbf24'
                    }
                ]
            }
        });

        new Chart(document.getElementById('speedChart'), {
            type: 'bar',
            data: {
                labels: ['Fast', 'Average', 'Slow'],
                datasets: [{
                    data: [<?php echo $speedData['fast']; ?>, <?php echo $speedData['average']; ?>, <?php echo $speedData['slow']; ?>],
                    backgroundColor: ['#16a34a', '#f59e0b', '#dc3545']
                }]
            },
            options: {
                indexAxis: 'y'
            }
        });
    </script>
</body>

</html>