<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$today = date('Y-m-d');

// ✅ Total Patients
$totalQ = mysqli_query($con, "
    SELECT COUNT(*) as total 
    FROM appointments 
    WHERE doctor_id = $doctor_id 
    AND appointment_date = '$today'
");
$totalPatients = mysqli_fetch_assoc($totalQ)['total'] ?? 0;


// ✅ Emergency Count
$emergencyQ = mysqli_query($con, "
    SELECT COUNT(*) as count 
    FROM appointments 
    WHERE doctor_id = $doctor_id 
    AND appointment_date = '$today' 
    AND appointment_type = 'Emergency'
");
$emergencyCount = mysqli_fetch_assoc($emergencyQ)['count'] ?? 0;


// ✅ Avg Consultation Time
$avgTimeQ = mysqli_query($con, "
    SELECT AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as avg_min
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    WHERE a.doctor_id = $doctor_id 
    AND a.appointment_date = '$today'
    AND t.status = 'Completed'
");
$avgData = mysqli_fetch_assoc($avgTimeQ);
$avgTime = $avgData['avg_min'] ? round($avgData['avg_min']) : 0;


// ✅ CURRENT TOKEN (FIXED — ONLY ONE ACTIVE)
$currentQ = mysqli_query($con, "
    SELECT t.token_id, t.token_no, p.full_name, a.visit_reason, a.appointment_type
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    JOIN patients p ON a.patient_id = p.patient_id
    WHERE a.doctor_id = $doctor_id
    AND a.appointment_date = '$today'
    AND t.status = 'In Progress'
    ORDER BY t.called_at DESC
    LIMIT 1
");

$currentTokenData = mysqli_fetch_assoc($currentQ);

$currentToken = null;
$currentPatient = null;
$currentTokenId = null;
$statusText = "Waiting to start";
$badgeClass = "bg-secondary";
$badgeText = "Idle";

if ($currentTokenData) {
    $currentToken   = $currentTokenData['token_no'];
    $currentPatient = $currentTokenData['full_name'];
    $currentTokenId = $currentTokenData['token_id'];

    if ($currentTokenData['appointment_type'] === 'Emergency') {
        $statusText = "Emergency Case - " . htmlspecialchars($currentTokenData['visit_reason']);
        $badgeClass = "bg-danger";
        $badgeText  = "Emergency Active";
    } else {
        $statusText = "In Consultation";
        $badgeClass = "bg-success";
        $badgeText  = "Live";
    }
}


// ✅ UPCOMING PATIENTS (STRICT ORDER)
$upcomingQ = mysqli_query($con, "
    SELECT t.token_no, p.full_name, a.appointment_type
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    JOIN patients p ON a.patient_id = p.patient_id
    WHERE a.doctor_id = $doctor_id
    AND a.appointment_date = '$today'
    AND t.status = 'Waiting'
    ORDER BY t.token_no ASC
    LIMIT 5
");


// ✅ EMERGENCY ALERTS
$emerAlertQ = mysqli_query($con, "
    SELECT t.token_no, a.visit_reason
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    WHERE a.doctor_id = $doctor_id
    AND a.appointment_date = '$today'
    AND a.appointment_type = 'Emergency'
    AND t.status = 'Waiting'
    ORDER BY t.token_no ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MediQueue | Doctor Dashboard</title>

    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/doctor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <!-- HEADER -->
        <section class="features-header">
            <h2>Welcome, <span><?php echo htmlspecialchars($_SESSION['doctor_name']); ?></span></h2>
        </section>

        <!-- STATS -->
        <section class="mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Total Patients</h6>
                        <h2><?php echo $totalPatients; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card highlight">
                        <h6>Current Token</h6>
                        <h2><?php echo $currentToken ? "#$currentToken" : "--"; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card">
                        <h6>Avg Time</h6>
                        <h2><?php echo $avgTime ?: "--"; ?> min</h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dstat-card danger">
                        <h6>Emergency</h6>
                        <h2><?php echo $emergencyCount; ?></h2>
                    </div>
                </div>

            </div>
        </section>


        <!-- MAIN -->
        <section class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-7">
                <div class="dcard text-center">

                    <div class="card-header d-flex justify-content-between">
                        <span>Live Queue Control</span>
                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                    </div>

                    <div class="card-body">

                        <div class="live-token mb-2">
                            <?php echo $currentToken ? "Token #$currentToken" : "No Active Token"; ?>
                        </div>

                        <div class="patient-name mb-3">
                            <?php echo $currentPatient ? "Patient: " . htmlspecialchars($currentPatient) : $statusText; ?>
                        </div>

                        <div class="d-flex justify-content-center gap-3">

                            <a href="queue-action.php?action=next" class="btn btn-brand">
                                Next Patient
                            </a>

                            <?php if ($currentTokenId): ?>
                                <a href="queue-action.php?action=complete&token_id=<?php echo $currentTokenId; ?>" class="btn btn-success">
                                    Complete
                                </a>

                                <a href="queue-action.php?action=skip&token_id=<?php echo $currentTokenId; ?>" class="btn btn-warning">
                                    Hold
                                </a>
                            <?php endif; ?>

                            <a href="reset_queue.php"
                                onclick="return confirm('Reset today’s queue?')"
                                class="btn btn-dark">
                                Reset Queue
                            </a>

                        </div>

                    </div>
                </div>
            </div>


            <!-- RIGHT -->
            <div class="col-lg-5">
                <div class="dcard emergency-card">

                    <div class="card-header bg-danger text-white">
                        Emergency Alerts
                    </div>

                    <div class="card-body">

                        <?php if (mysqli_num_rows($emerAlertQ) > 0): ?>
                            <?php while ($em = mysqli_fetch_assoc($emerAlertQ)): ?>
                                <div class="mb-2">
                                    Token #<?php echo $em['token_no']; ?> -
                                    <?php echo htmlspecialchars($em['visit_reason']); ?>

                                    <a href="queue-action.php?action=call_emergency&token_no=<?php echo $em['token_no']; ?>" class="btn btn-sm btn-light">
                                        Call
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted">No emergency cases</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </section>


        <!-- UPCOMING -->
        <section class="mt-4">
            <div class="dcard">
                <div class="card-header">Upcoming Patients</div>

                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Token</th>
                            <th>Patient</th>
                            <th>Type</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (mysqli_num_rows($upcomingQ) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($upcomingQ)): ?>
                                <tr>
                                    <td>#<?php echo $row['token_no']; ?></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['appointment_type']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No patients</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </section>

    </main>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
        setInterval(() => {
            location.reload();
        }, 5000);
    </script>
</body>

</html>