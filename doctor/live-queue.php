<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$queue_date = $_GET['date'] ?? date('Y-m-d');

/* ================================
   ⚡ OPTIMIZED STATS QUERY (1 QUERY INSTEAD OF 3)
================================ */
$statsQ = mysqli_query($con, "
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN t.status = 'Completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN t.status IN ('Waiting','Skipped') THEN 1 ELSE 0 END) as pending
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$queue_date'
");

$stats = mysqli_fetch_assoc($statsQ);

$totalTokens = (int)($stats['total'] ?? 0);
$completed   = (int)($stats['completed'] ?? 0);
$pending     = (int)($stats['pending'] ?? 0);

/* ================================
   🧠 CURRENT TOKEN (NO CHANGE, JUST SAFE)
================================ */
$currentQ = mysqli_query($con, "
SELECT t.token_id, t.token_no, p.full_name, p.date_of_birth, a.appointment_type 
FROM tokens t 
JOIN appointments a ON t.appointment_id = a.appointment_id 
JOIN patients p ON a.patient_id = p.patient_id 
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$queue_date' 
AND t.status = 'In Progress' 
ORDER BY t.called_at DESC LIMIT 1
");

$currentTokenData = mysqli_fetch_assoc($currentQ);

$currentToken = null;
$currentName  = null;
$currentAge   = null;
$currentType  = null;
$currentTokenId = null;
$statusBadge  = "bg-secondary";
$statusText   = "Idle";

if ($currentTokenData) {
    $currentToken = $currentTokenData['token_no'];
    $currentTokenId = $currentTokenData['token_id'];
    $currentName  = $currentTokenData['full_name'];
    $currentType  = $currentTokenData['appointment_type'];

    if ($currentTokenData['date_of_birth']) {
        $dob = new DateTime($currentTokenData['date_of_birth']);
        $currentAge = (new DateTime())->diff($dob)->y;
    }

    if ($currentType === 'Emergency') {
        $statusBadge = "bg-danger";
        $statusText  = "Emergency Active";
    } else {
        $statusBadge = "bg-success";
        $statusText  = "In Progress";
    }
}

/* ================================
   ⚡ AVG CONSULTATION TIME (NEW)
================================ */
$avgQ = mysqli_query($con, "
SELECT AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as avg_time
FROM tokens t
JOIN appointments a ON t.appointment_id = a.appointment_id
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$queue_date'
AND t.status = 'Completed'
");

$avgTime = round(mysqli_fetch_assoc($avgQ)['avg_time'] ?? 5);

/* ================================
   🚨 OVERLOAD PREDICTION (NEW)
================================ */
$estimatedTimeLeft = $pending * max($avgTime, 1);
$isOverloaded = $estimatedTimeLeft > 120;

/* ================================
   🏥 CAPACITY CALCULATION (NEW)
================================ */
$workingHours = 8;
$totalCapacity = ($workingHours * 60) / max($avgTime, 1);
$utilization = ($totalTokens / max($totalCapacity, 1)) * 100;

/* ================================
   📋 QUEUE (IMPROVED ORDER)
================================ */
$queueQ = mysqli_query($con, "
SELECT t.token_no, p.full_name, a.appointment_type, t.status 
FROM tokens t 
JOIN appointments a ON t.appointment_id = a.appointment_id 
JOIN patients p ON a.patient_id = p.patient_id 
WHERE a.doctor_id = $doctor_id 
AND a.appointment_date = '$queue_date' 
AND t.status IN ('Waiting', 'Skipped') 
ORDER BY 
    (a.appointment_type = 'Emergency') DESC, 
    t.queue_position ASC,
    t.token_no ASC
");

/* ================================
   🎯 OPTIONAL: ESTIMATED WAIT PER PATIENT (ADVANCED)
================================ */
$position = 0;
$estimatedWaitMap = [];

while ($row = mysqli_fetch_assoc($queueQ)) {
    $position++;
    $estimatedWaitMap[$row['token_no']] = $position * $avgTime;
}

// Reset pointer (important)
mysqli_data_seek($queueQ, 0);
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
                <p class="text-muted mb-0">Real-time patient queue · <?php echo date('d M Y', strtotime($queue_date)); ?></p>
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
            <div class="dcard text-center p-4 current-token-card shadow-sm">
                <div class="mb-2">
                    <span class="badge <?php echo htmlspecialchars($statusBadge); ?>"><?php echo htmlspecialchars($statusText); ?></span>
                    <?php if ($currentType): ?>
                        <span class="badge ms-1 <?php echo ($currentType === 'Emergency') ? 'bg-danger' : 'bg-info text-dark'; ?>">
                            <?php echo htmlspecialchars($currentType); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="current-token-number" style="font-size: 3rem; font-weight: 800; color: var(--brand-color);">
                    <?php echo $currentToken !== null ? 'Token #' . htmlspecialchars($currentToken) : 'No Active Patient'; ?>
                </div>

                <div class="patient-name mt-2 h4">
                    <?php
                    if ($currentName) {
                        $ageStr = $currentAge !== null ? $currentAge . ' yrs' : '—';
                        echo htmlspecialchars($currentName) . ' (' . htmlspecialchars($ageStr) . ')';
                    } else {
                        echo '<span class="text-muted">Waiting to start session</span>';
                    }
                    ?>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="queue-action.php?action=next" class="btn btn-brand">
                        <i class="bi bi-arrow-right-circle"></i> Call Next
                    </a>
                    <?php if ($currentTokenId): ?>
                        <a href="queue-action.php?action=complete&token_id=<?php echo $currentTokenId; ?>" class="btn btn-outline-success">
                            <i class="bi bi-check-circle"></i> Complete
                        </a>
                        <a href="queue-action.php?action=skip&token_id=<?php echo $currentTokenId; ?>" class="btn btn-outline-warning">
                            <i class="bi bi-pause-circle"></i> Hold
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="dstat-card text-center p-3 border rounded bg-white">
                        <h6 class="text-muted">Total Patients</h6>
                        <h2 class="fw-bold"><?php echo (int) $totalTokens; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card text-center p-3 border rounded bg-white" style="border-bottom: 4px solid #22c55e !important;">
                        <h6 class="text-muted">Completed</h6>
                        <h2 class="text-success fw-bold"><?php echo (int) $completed; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dstat-card text-center p-3 border rounded bg-white" style="border-bottom: 4px solid #ef4444 !important;">
                        <h6 class="text-muted">Waiting</h6>
                        <h2 class="text-danger fw-bold"><?php echo (int) $pending; ?></h2>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4">
            <div class="col-lg-8">
                <div class="dcard shadow-sm">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">Upcoming Patients</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Token</th>
                                        <th>Patient</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="queue-body">
                                    <?php if (mysqli_num_rows($queueQ) > 0): ?>
                                        <?php while ($q = mysqli_fetch_assoc($queueQ)): ?>
                                            <tr class="<?php echo $q['appointment_type'] === 'Emergency' ? 'table-danger' : ''; ?>">
                                                <td class="fw-bold">
                                                    #<?php echo $q['token_no']; ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        ~<?php echo $estimatedWaitMap[$q['token_no']] ?? 0; ?> min
                                                    </small>
                                                </td>
                                                <td><?php echo htmlspecialchars($q['full_name']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo ($q['appointment_type'] === 'Emergency') ? 'bg-danger' : 'bg-info text-dark'; ?>">
                                                        <?php echo htmlspecialchars($q['appointment_type']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($q['status']); ?></span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No pending patients in queue.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dcard mb-3 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">Queue Actions</div>
                    <div class="card-body d-grid gap-2">
                        <a href="appointment.php" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-event"></i> All Appointments
                        </a>
                    </div>
                </div>

                <div class="dcard shadow-sm border-primary">
                    <div class="card-header bg-primary text-white fw-bold py-3">Patient Details</div>
                    <div class="card-body">
                        <?php if ($currentName): ?>
                            <div class="mb-2"><strong>Name:</strong> <?php echo htmlspecialchars($currentName); ?></div>
                            <div class="mb-2"><strong>Age:</strong> <?php echo $currentAge !== null ? (int) $currentAge : '—'; ?></div>
                            <div><strong>Visit Reason:</strong> <span class="text-muted">Standard Consultation</span></div>
                        <?php else: ?>
                            <p class="text-muted mb-0 italic">Call a patient to view details here.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

    <script>
        setInterval(() => {
            fetch('live-queue-data.php')
                .then(res => res.json())
                .then(data => {

                    // CURRENT TOKEN UPDATE
                    if (data.current) {
                        document.querySelector('.current-token-number').innerText =
                            "Token #" + data.current.token_no;

                        document.querySelector('.patient-name').innerText =
                            data.current.full_name;
                    }

                    // TABLE UPDATE
                    let tbody = document.getElementById("queue-body");
                    tbody.innerHTML = "";

                    data.queue.forEach((p, i) => {
                        tbody.innerHTML += `
                            <tr class="${p.appointment_type === 'Emergency' ? 'table-danger' : ''}">
    
                                <td class="fw-bold">
                                    #${p.token_no}
                                    <br>
                                    <small class="text-muted">~${p.wait_time ?? 0} min</small>
                                </td>

                                <td>${p.full_name}</td>

                                    <td>
                                        <span class="badge ${
                                            p.appointment_type === 'Emergency' 
                                                ? 'bg-danger' 
                                                : 'bg-info text-dark'
                                                }">
                                            ${p.appointment_type}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">Waiting</span>
                                    </td>

                            </tr>
                            `;
                    });

                })
                .catch(err => console.error("Fetch error:", err));
        }, 3000);
    </script>

</body>

</html>