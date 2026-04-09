<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$doctor_name = $_SESSION['doctor_name'] ?? "Doctor";

/* ================================
   📅 DATE HANDLING
================================ */
$filter_date = $_GET['date'] ?? date('Y-m-d');

$checkQ = mysqli_query($con, "
SELECT COUNT(*) as count 
FROM appointments 
WHERE doctor_id = $doctor_id 
AND appointment_date = '$filter_date'
");

$hasData = mysqli_fetch_assoc($checkQ)['count'] ?? 0;

if (!$hasData) {
    $latestQ = mysqli_query($con, "
    SELECT MAX(appointment_date) as latest_date 
    FROM appointments 
    WHERE doctor_id = $doctor_id
    ");
    $latestDate = mysqli_fetch_assoc($latestQ)['latest_date'];
    if ($latestDate) $filter_date = $latestDate;
}

/* ================================
   📅 WEEK WINDOW
================================ */
$ref_date = $filter_date;
$week_start = date('Y-m-d', strtotime('-6 days', strtotime($filter_date)));

/* ================================
   📊 BASIC STATS
================================ */
$totalPatients = 0;
$completed = 0;
$pending = 0;

$res = mysqli_query($con, "
SELECT COUNT(*) as total 
FROM appointments 
WHERE doctor_id = $doctor_id 
AND appointment_date = '$filter_date'
");
if ($res) $totalPatients = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($con, "
SELECT COUNT(*) as count 
FROM tokens t 
JOIN appointments a ON t.appointment_id = a.appointment_id 
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$filter_date' 
AND t.status = 'Completed'
");
if ($res) $completed = mysqli_fetch_assoc($res)['count'] ?? 0;

$res = mysqli_query($con, "
SELECT COUNT(*) as count 
FROM tokens t 
JOIN appointments a ON t.appointment_id = a.appointment_id 
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$filter_date' 
AND t.status IN ('Waiting','Skipped','In Progress')
");
if ($res) $pending = mysqli_fetch_assoc($res)['count'] ?? 0;

/* ================================
   ⏱ CONSULT TIME
================================ */
$avgTime = 0;
$total_consult_mins = 0;

$res = mysqli_query($con, "
SELECT 
AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as avg_min,
SUM(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as total_min
FROM tokens t 
JOIN appointments a ON t.appointment_id = a.appointment_id 
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$filter_date'
AND t.status = 'Completed'
");

if ($res && mysqli_num_rows($res)) {
    $row = mysqli_fetch_assoc($res);
    $avgTime = round($row['avg_min'] ?? 0);
    $total_consult_mins = (int)($row['total_min'] ?? 0);
}

/* ================================
   🧠 FORMAT FUNCTION
================================ */
function fmt_dur($mins)
{
    return ($mins && $mins > 0) ? round($mins) . " min" : "--";
}

/* ================================
   📊 PEAK HOURS
================================ */
$hoursValues = array_fill(0, 9, 0);

$res = mysqli_query($con, "
SELECT HOUR(appointment_time) as h, COUNT(*) as c 
FROM appointments 
WHERE doctor_id = $doctor_id 
AND appointment_date = '$filter_date' 
GROUP BY h
");

if ($res) {
    while ($r = mysqli_fetch_assoc($res)) {
        $h = (int)$r['h'];
        if ($h >= 9 && $h <= 17) {
            $hoursValues[$h - 9] = (int)$r['c'];
        }
    }
}

/* ================================
   🧠 PEAK LABELS (FIXED)
================================ */
$peak_hour_label = "--";
$slow_hour_label = "--";

if (array_sum($hoursValues) > 0) {
    $maxIndex = array_search(max($hoursValues), $hoursValues);
    $minIndex = array_search(min($hoursValues), $hoursValues);

    if ($maxIndex !== false) {
        $peak_hour_label = date("g A", strtotime((9 + $maxIndex) . ":00"));
    }
    if ($minIndex !== false) {
        $slow_hour_label = date("g A", strtotime((9 + $minIndex) . ":00"));
    }
}

/* ================================
   📊 TYPE DATA
================================ */
$typeData = ['New' => 0, 'Follow Up' => 0, 'Emergency' => 0];

$res = mysqli_query($con, "
SELECT appointment_type, COUNT(*) as count 
FROM appointments 
WHERE doctor_id = $doctor_id 
AND appointment_date = '$filter_date' 
GROUP BY appointment_type
");

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $type = $row['appointment_type'];
        if ($type === 'Follow-up') $type = 'Follow Up';
        if (isset($typeData[$type])) {
            $typeData[$type] = (int)$row['count'];
        }
    }
}

/* ================================
   📊 SPEED DATA
================================ */
$speedData = ['fast' => 0, 'average' => 0, 'slow' => 0];

$res = mysqli_query($con, "
SELECT TIMESTAMPDIFF(MINUTE, called_at, completed_at) as duration 
FROM tokens t 
JOIN appointments a ON t.appointment_id = a.appointment_id 
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date BETWEEN '$week_start' AND '$ref_date' 
AND t.status = 'Completed'
");

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $dur = (int)$row['duration'];

        if ($dur < 8) $speedData['fast']++;
        elseif ($dur <= 15) $speedData['average']++;
        else $speedData['slow']++;
    }
}

/* ================================
   ⏱ DELAY
================================ */
$avgDelay = 0;
$maxDelay = 0;

$res = mysqli_query($con, "
SELECT 
AVG(
    TIMESTAMPDIFF(
        MINUTE, 
        CONCAT(a.appointment_date, ' ', a.appointment_time), 
        t.called_at
    )
) as avg_delay,

MAX(
    TIMESTAMPDIFF(
        MINUTE, 
        CONCAT(a.appointment_date, ' ', a.appointment_time), 
        t.called_at
    )
) as max_delay

FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id

WHERE a.doctor_id = $doctor_id
AND a.appointment_date = '$filter_date'
AND t.called_at IS NOT NULL
");

if ($res && mysqli_num_rows($res)) {
    $row = mysqli_fetch_assoc($res);
    $avgDelay = round($row['avg_delay'] ?? 0);
    $maxDelay = round($row['max_delay'] ?? 0);
}

/* ================================
   📊 WEEKLY SNAPSHOT (FIXED)
================================ */
$week_total_appts = 0;
$week_days = 0;
$week_avg = 0;
$res = mysqli_query($con, "
SELECT 
COUNT(DISTINCT a.appointment_id) as total,
COUNT(DISTINCT a.appointment_date) as days,

AVG(
    TIMESTAMPDIFF(
        MINUTE,
        t.called_at,
        t.completed_at
    )
) as avg_time

FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id

WHERE a.doctor_id = $doctor_id
AND a.appointment_date BETWEEN '$week_start' AND '$ref_date'
AND t.status = 'Completed'
");

if ($res && mysqli_num_rows($res)) {
    $row = mysqli_fetch_assoc($res);
    $week_total_appts = (int)($row['total'] ?? 0);
    $week_days = (int)($row['days'] ?? 0);
    $week_avg = round($row['avg_time'] ?? 0);
}
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
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="printReport()">
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

            <div class="dcard">
                <div class="card-header">Delay Impact</div>
                <div class="card-body">
                    <p><strong>Average Delay:</strong> <?php echo $avgDelay; ?> min</p>
                    <p><strong>Max Delay:</strong> <?php echo $maxDelay; ?> min</p>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Daily Consultation Summary</div>
                    <div class="card-body">
                        <p><strong>Estimated total time:</strong> <?php echo fmt_dur($total_consult_mins); ?></p>
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

    <script>
        function convertChartsToImages() {
            document.querySelectorAll("canvas").forEach(canvas => {
                try {
                    const img = document.createElement("img");
                    img.src = canvas.toDataURL("image/png");
                    img.style.width = "100%";
                    img.style.height = "300px";

                    canvas.parentNode.replaceChild(img, canvas);
                } catch (e) {
                    console.log("Chart conversion failed:", e);
                }
            });
        }
    </script>

    <script>
        function printReport() {
            console.log("Print clicked"); // debug

            setTimeout(() => {
                convertChartsToImages(); // convert charts
                window.print(); // print
            }, 800);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Initializations
        new Chart(document.getElementById('peakHoursChart'), {
            type: 'line',
            data: {
                labels: ['9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
                datasets: [{
                    label: 'Patients',
                    data: <?php echo json_encode($hoursValues ?? [0, 0, 0, 0, 0, 0, 0, 0, 0]); ?>,
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