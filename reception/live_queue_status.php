<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception | Live Queue</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style_mq.css">
    <link rel="stylesheet" href="../css/reception_mq.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>

<?php include '../includes/recep_sidebar.php';?>

<div class="reception-dashboard">


    <!-- PAGE HEADER -->
    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">

        <div>
        <h1 class="fw-bold text-black">Live <span>Queue</span> </h1>
            <p class="dashboard-subtitle">
                Track and manage patient queue in real time
            </p>
        </div>

        <div class="d-flex gap-2">

            <button class="btn btn-outline-danger">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>

            <button class="btn btn-brand">
                <i class="bi bi-pause-circle"></i> Pause Queue
            </button>

        </div>

    </div>

    <!-- SUMMARY CARDS -->
    <div class="stats-row mb-4">

        <div class="rstat-card">
            <h6>Total Tokens</h6>
            <h2>25</h2>
        </div>

        <div class="rstat-card">
            <h6>Waiting</h6>
            <h2>12</h2>
        </div>

        <div class="rstat-card">
            <h6>In Progress</h6>
            <h2>2</h2>
        </div>

        <div class="rstat-card">
            <h6>Completed</h6>
            <h2>11</h2>
        </div>

    </div>

    <!-- CURRENT TOKEN CARD -->
    <section class="mb-4">

        <div class="dcard current-token-card p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <span class="badge bg-success">
                        Now Consulting
                    </span>

                    <div class="current-token-number text-muted mt-2">
                        Token <span>#21</span> 
                    </div>

                    <div class="patient-name">
                        Rahul Patel (32 / Male)
                    </div>

                    <small class="text-muted">
                        Doctor: Dr. Mehta • Room 2
                    </small>

                </div>


                <!-- ACTION BUTTONS -->
                <div class="d-flex flex-column gap-2 mt-3">

                    <button class="btn btn-brand">
                        <i class="bi bi-arrow-right-circle"></i>
                        Call Next
                    </button>

                    <button class="btn btn-outline-success">
                        <i class="bi bi-check-circle"></i>
                        Complete
                    </button>

                    <button class="btn btn-outline-warning">
                        <i class="bi bi-skip-forward-circle"></i>
                        Skip
                    </button>

                </div>

            </div>

        </div>

    </section>

    <!-- QUEUE TABLE -->
    <div class="card dashboard-card shadow-lg">

        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Token</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td><strong>#13</strong></td>
                        <td>
                            Rahul Sharma
                            <div class="text-muted small">
                                9876543210
                            </div>
                        </td>
                        <td>Dr. Mehta</td>
                        <td>10:30 AM</td>
                        <td>
                            <span class="badge bg-primary">
                                Follow-up
                            </span>
                        </td>
                        <td><span class="badge bg-warning text-dark">
                                Waiting
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-brand">
                                Call
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>#14</strong></td>
                        <td>
                            Anita Patel
                            <div class="text-muted small">
                                9123456780
                            </div>
                        </td>
                        <td>Dr. Shah</td>
                        <td>11:00 AM</td>
                        <td>
                            <span class="badge bg-danger">
                                Emergency
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                Waiting
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-brand">
                                Call
                            </button>
                        </td>

                    </tr>


                </tbody>

            </table>

        </div>

    </div>



    <!-- QUEUE CONTROL -->
    <div class="mt-4 d-flex gap-3">

        <button class="btn btn-brand">

            <i class="bi bi-arrow-right"></i>
            Call Next Patient

        </button>

        <button class="btn btn-outline-danger">
            <i class="bi bi-trash"></i>
            Clear Completed
        </button>

    </div>

</div>

    <?php include '../reception/recep_footer.php'; ?>

</body>
</html>
