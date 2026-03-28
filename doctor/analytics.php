<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$filter_date = $_GET['date'] ?? date('Y-m-d');

// Total Patients
$totalQ = mysqli_query($con, "SELECT COUNT(*) as total FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$filter_date'");
$totalPatients = mysqli_fetch_assoc($totalQ)['total'] ?? 0;

// Completed Patients
$compQ = mysqli_query($con, "SELECT COUNT(*) as count FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date' AND t.status = 'Completed'");
$completed = mysqli_fetch_assoc($compQ)['count'] ?? 0;

// Pending / Skipped
$pendQ = mysqli_query($con, "SELECT COUNT(*) as count FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date' AND t.status IN ('Waiting', 'Skipped', 'In Progress')");
$pending = mysqli_fetch_assoc($pendQ)['count'] ?? 0;

// Avg Consultation Time
$avgTimeQ = mysqli_query($con, "SELECT AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as avg_min 
                                FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
                                WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date' AND t.status = 'Completed'");
$avgData = mysqli_fetch_assoc($avgTimeQ);
$avgTime = $avgData['avg_min'] ? round($avgData['avg_min']) : 0;

// Appointment Type Breakdown
$typeQ = mysqli_query($con, "SELECT appointment_type, COUNT(*) as count FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$filter_date' GROUP BY appointment_type");
$typeData = ['New' => 0, 'Follow Up' => 0, 'Emergency' => 0];
while ($row = mysqli_fetch_assoc($typeQ)) {
    if ($row['appointment_type'] === 'Follow-up' || $row['appointment_type'] === 'Follow Up') {
        $typeData['Follow Up'] = $row['count'];
    } else if (isset($typeData[$row['appointment_type']])) {
        $typeData[$row['appointment_type']] = $row['count'];
    }
}

// Speed indicator (Fast < 8, Avg 8-15, Slow > 15)
$speedData = ['fast' => 0, 'average' => 0, 'slow' => 0];
$timesQ = mysqli_query($con, "SELECT TIMESTAMPDIFF(MINUTE, called_at, completed_at) as duration 
                              FROM tokens t JOIN appointments a ON t.appointment_id = a.appointment_id 
                              WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date' AND t.status = 'Completed'");
while ($row = mysqli_fetch_assoc($timesQ)) {
    $dur = (int)$row['duration'];
    if ($dur >= 0 && $dur < 8) $speedData['fast']++;
    elseif ($dur >= 8 && $dur <= 15) $speedData['average']++;
    elseif ($dur > 15) $speedData['slow']++;
}

// Peak hours 
$peakQ = mysqli_query($con, "SELECT HOUR(appointment_time) as h, COUNT(*) as c FROM appointments WHERE doctor_id = $doctor_id AND appointment_date = '$filter_date' GROUP BY HOUR(appointment_time) ORDER BY h");
$peakChartData = array_fill(9, 9, 0); // 9 AM to 5 PM
while ($r = mysqli_fetch_assoc($peakQ)) {
    $h = (int)$r['h'];
    if ($h >= 9 && $h <= 17) {
        $peakChartData[$h] = (int)$r['c'];
    }
}
$hoursValues = array_values($peakChartData);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FIX: corrected page title -->
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
            <!-- FIX: wrapped session output in htmlspecialchars to prevent XSS -->
            <h2>Welcome, <span>Dr. <?php echo htmlspecialchars($doc['full_name']); ?></span></h2>
        </section>

        <!-- HEADER -->
        <section class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Consultation Time Analytics</h4>
                <p class="text-muted mb-0">Analyze consultation efficiency and workload</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" onclick="exportAnalytics()">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>
            </div>
            <!-- Date Filter -->
            <form class="d-flex gap-2" method="GET" action="analytics.php">
                <input type="date" name="date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($filter_date); ?>" onchange="this.form.submit();">
            </form>
        </section>

        <!-- KEY METRICS -->
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

        <!-- CHARTS -->
        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Peak Consultation Hours</div>
                    <div class="card-body">
                        <canvas id="peakHoursChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Appointment Type Breakdown</div>
                    <div class="card-body">
                        <canvas id="appointmentTypeChart"></canvas>
                    </div>
                </div>
            </div>

        </section>

        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Daily Patient Status</div>
                    <div class="card-body">
                        <canvas id="dailyStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Consultation Speed Distribution</div>
                    <div class="card-body">
                        <canvas id="speedChart"></canvas>
                    </div>
                </div>
            </div>

        </section>

        <!-- DAILY SUMMARY -->
        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Daily Consultation Summary</div>
                    <div class="card-body">
                        <p><strong>Total Consultation Time:</strong> 6 hrs 12 mins</p>
                        <p><strong>Peak Hour:</strong> 10:00 – 11:00 AM</p>
                        <p class="mb-0"><strong>Slowest Hour:</strong> 2:00 – 3:00 PM</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Appointment Type Breakdown</div>
                    <div class="card-body">
                        <div class="analytics-row">
                            <span>New Patients</span>
                            <span><?php echo $typeData['New']; ?></span>
                        </div>
                        <div class="analytics-row">
                            <span>Follow-ups</span>
                            <span><?php echo $typeData['Follow Up']; ?></span>
                        </div>
                        <div class="analytics-row emergency-visit">
                            <span>Emergency Visits</span>
                            <span><?php echo $typeData['Emergency']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- CONSULTATION SPEED -->
        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Consultation Speed Indicator</div>
                    <div class="card-body">
                        <div class="speed-indicator fast">
                            <span>Fast (&lt; 8 min)</span>
                            <span><?php echo $speedData['fast']; ?></span>
                        </div>
                        <div class="speed-indicator average">
                            <span>Average (8–15 min)</span>
                            <span><?php echo $speedData['average']; ?></span>
                        </div>
                        <div class="speed-indicator slow">
                            <span>Slow (&gt; 15 min)</span>
                            <span><?php echo $speedData['slow']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">Weekly Snapshot (Last 7 Days)</div>
                    <div class="card-body">
                        <p><strong>Avg Consultation Time:</strong> 13 min</p>
                        <p><strong>Total Patients:</strong> 214</p>
                        <p class="mb-0"><strong>Working Days:</strong> 6</p>
                    </div>
                </div>
            </div>

        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <!-- FIX: Bootstrap JS and Chart.js moved to bottom of body -->
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
        Chart.defaults.color = "#374151";

        /**
         * After a chart finishes rendering, snapshot its canvas onto a
         * white background and inject a hidden <img> beside it.
         * The @media print CSS hides the canvas and shows the img instead.
         */
        function snapshotChart(chart) {
            var canvas = chart.canvas;

            // Draw white background first, then the chart on top
            var offscreen = document.createElement('canvas');
            offscreen.width = canvas.width;
            offscreen.height = canvas.height;
            var ctx = offscreen.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, offscreen.width, offscreen.height);
            ctx.drawImage(canvas, 0, 0);

            // Find or create the companion <img>
            var img = canvas.nextElementSibling;
            if (!img || !img.classList.contains('chart-print-img')) {
                img = document.createElement('img');
                img.className = 'chart-print-img';
                img.style.display = 'none'; // hidden on screen, visible only in print
                canvas.parentNode.insertBefore(img, canvas.nextSibling);
            }
            img.src = offscreen.toDataURL('image/png');
        }

        // Helper: build chart config with onComplete snapshot callback
        function makeOptions(extra) {
            return Object.assign({
                animation: {
                    onComplete: function() {
                        snapshotChart(this);
                    }
                }
            }, extra);
        }

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
            },
            options: makeOptions({
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            })
        });

        new Chart(document.getElementById('appointmentTypeChart'), {
            type: 'doughnut',
            data: {
                labels: ['New', 'Follow-up', 'Emergency'],
                datasets: [{
                    data: [<?php echo $typeData['New']; ?>, <?php echo $typeData['Follow Up']; ?>, <?php echo $typeData['Emergency']; ?>],
                    backgroundColor: ['#3b82f6', '#22c55e', '#dc3545']
                }]
            },
            options: makeOptions({
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            })
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
            },
            options: makeOptions({
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            })
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
            options: makeOptions({
                plugins: {
                    legend: {
                        display: false
                    }
                },
                indexAxis: 'y',
                scales: {
                    x: {
                        ticks: {
                            precision: 0
                        }
                    }
                }
            })
        });

        function exportAnalytics() {
            window.print();
        }
    </script>

</body>

</html>