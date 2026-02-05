<?php
// session_start();

// if (!isset($_SESSION['doctor_id'])) {
//     header("Location: ../login.php");
//     exit();
// }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Doctor Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/doctor.css">
</head>

<body>
    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <!-- ================= HEADER ================= -->
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
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm">
                    <option selected>Today</option>
                    <option>Yesterday</option>
                </select>
                <input type="date" class="form-control form-control-sm">
            </div>
        </section>

        <!-- ================= KEY METRICS ================= -->
        <section class="mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="dstat-card highlight">
                        <h6>Avg Consultation Time</h6>
                        <h2>12 min</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Total Patients</h6>
                        <h2>38</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Completed</h6>
                        <h2>31</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card danger">
                        <h6>Pending / Skipped</h6>
                        <h2>7</h2>
                    </div>
                </div>

            </div>
        </section>

        <!-- ================= CHARTS ================= -->
        <section class="row g-4 mb-4">

            <!-- Peak Hours -->
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Peak Consultation Hours
                    </div>
                    <div class="card-body">
                        <canvas id="peakHoursChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Appointment Type Breakdown -->
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Appointment Type Breakdown
                    </div>
                    <div class="card-body">
                        <canvas id="appointmentTypeChart"></canvas>
                    </div>
                </div>
            </div>

        </section>

        <section class="row g-4 mb-4">

            <!-- Daily Patient Status -->
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Daily Patient Status
                    </div>
                    <div class="card-body">
                        <canvas id="dailyStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Consultation Speed -->
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Consultation Speed Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="speedChart"></canvas>
                    </div>
                </div>
            </div>

        </section>

        <!-- ================= DAILY SUMMARY ================= -->
        <section class="row g-4 mb-4">

            <!-- Total Time -->
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Daily Consultation Summary
                    </div>
                    <div class="card-body">
                        <p><strong>Total Consultation Time:</strong> 6 hrs 12 mins</p>
                        <p><strong>Peak Hour:</strong> 10:00 – 11:00 AM</p>
                        <p class="mb-0"><strong>Slowest Hour:</strong> 2:00 – 3:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Appointment Type Breakdown -->
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Appointment Type Breakdown
                    </div>
                    <div class="card-body">

                        <div class="analytics-row">
                            <span>New Patients</span>
                            <span>14</span>
                        </div>

                        <div class="analytics-row">
                            <span>Follow-ups</span>
                            <span>18</span>
                        </div>

                        <div class="analytics-row emergency-visit">
                            <span>Emergency Visits</span>
                            <span>6</span>
                        </div>

                    </div>
                </div>
            </div>

        </section>

        <!-- ================= CONSULTATION SPEED ================= -->
        <section class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Consultation Speed Indicator
                    </div>
                    <div class="card-body">

                        <div class="speed-indicator fast">
                            <span>Fast (&lt; 8 min)</span>
                            <span>10</span>
                        </div>

                        <div class="speed-indicator average">
                            <span>Average (8–15 min)</span>
                            <span>17</span>
                        </div>

                        <div class="speed-indicator slow">
                            <span>Slow (&gt; 15 min)</span>
                            <span>11</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Weekly Snapshot -->
            <div class="col-lg-6">
                <div class="dcard">
                    <div class="card-header">
                        Weekly Snapshot (Last 7 Days)
                    </div>
                    <div class="card-body">
                        <p><strong>Avg Consultation Time:</strong> 13 min</p>
                        <p><strong>Total Patients:</strong> 214</p>
                        <p class="mb-0"><strong>Working Days:</strong> 6</p>
                    </div>
                </div>
            </div>

        </section>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        /* Global style (no animation drama) */
        Chart.defaults.animation = false;
        Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
        Chart.defaults.color = "#374151";

        /* ================= PEAK HOURS ================= */
        new Chart(document.getElementById('peakHoursChart'), {
            type: 'line',
            data: {
                labels: ['9–10', '10–11', '11–12', '12–1', '1–2', '2–3'],
                datasets: [{
                    label: 'Patients',
                    data: [4, 9, 7, 5, 3, 2],
                    borderColor: '#FF5A5F',
                    backgroundColor: 'rgba(255,90,95,0.15)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        /* ================= APPOINTMENT TYPE ================= */
        new Chart(document.getElementById('appointmentTypeChart'), {
            type: 'doughnut',
            data: {
                labels: ['New', 'Follow-up', 'Emergency'],
                datasets: [{
                    data: [14, 18, 6],
                    backgroundColor: ['#3b82f6', '#22c55e', '#dc3545']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        /* ================= DAILY STATUS ================= */
        new Chart(document.getElementById('dailyStatusChart'), {
            type: 'bar',
            data: {
                labels: ['Today'],
                datasets: [{
                        label: 'Completed',
                        data: [31],
                        backgroundColor: '#22c55e'
                    },
                    {
                        label: 'Pending',
                        data: [7],
                        backgroundColor: '#fbbf24'
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });

        /* ================= CONSULTATION SPEED ================= */
        new Chart(document.getElementById('speedChart'), {
            type: 'bar',
            data: {
                labels: ['Fast', 'Average', 'Slow'],
                datasets: [{
                    data: [10, 17, 11],
                    backgroundColor: ['#16a34a', '#f59e0b', '#dc3545']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                indexAxis: 'y'
            }
        });

        function exportAnalytics() {
            window.print();
        }
    </script>


</body>

</html>