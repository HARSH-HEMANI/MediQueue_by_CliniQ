<?php
include "doctor-auth.php";
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$patientId = isset($_GET['patient']) ? (int)$_GET['patient'] : 0;

// Fetch all unique patients for this doctor
$patientsListQ = mysqli_query($con, "
    SELECT p.patient_id, p.full_name, p.gender, p.date_of_birth, p.phone,
           COUNT(a.appointment_id) as total_visits,
           MAX(a.appointment_date) as last_visit
    FROM patients p
    JOIN appointments a ON p.patient_id = a.patient_id
    WHERE a.doctor_id = $doctor_id
    GROUP BY p.patient_id
    ORDER BY last_visit DESC
");

$patientsList = [];
$firstPatientId = 0;
while ($row = mysqli_fetch_assoc($patientsListQ)) {
    if ($firstPatientId === 0) $firstPatientId = $row['patient_id'];
    
    $age = "N/A";
    if ($row['date_of_birth']) {
        $dob = new DateTime($row['date_of_birth']);
        $now = new DateTime();
        $age = $now->diff($dob)->y;
    }
    $row['age'] = $age;
    $patientsList[] = $row;
}

$isValid = false;
foreach ($patientsList as $p) {
    if ($p['patient_id'] == $patientId) { $isValid = true; break; }
}
if (!$isValid) $patientId = $firstPatientId;

// Fetch current patient details
$current = null;
$historyList = [];
if ($patientId > 0) {
    $currQ = mysqli_query($con, "SELECT * FROM patients WHERE patient_id = $patientId");
    $current = mysqli_fetch_assoc($currQ);
    
    if ($current['date_of_birth']) {
        $current['age'] = (new DateTime())->diff(new DateTime($current['date_of_birth']))->y;
    } else {
        $current['age'] = "N/A";
    }

    foreach($patientsList as $p) {
        if ($p['patient_id'] == $patientId) {
            $current['total_visits'] = $p['total_visits'];
            $current['last_visit'] = $p['last_visit'];
            break;
        }
    }

    // Fetch visit history with consultation notes
    $histQ = mysqli_query($con, "
        SELECT a.appointment_date, a.appointment_type, t.token_no, cn.note_text, cn.diagnosis 
        FROM appointments a 
        LEFT JOIN tokens t ON a.appointment_id = t.appointment_id 
        LEFT JOIN consultation_notes cn ON a.appointment_id = cn.appointment_id 
        WHERE a.patient_id = $patientId AND a.doctor_id = $doctor_id AND a.status IN ('Completed', 'Confirmed', 'Pending')
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    while ($h = mysqli_fetch_assoc($histQ)) {
        $historyList[] = $h;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FIX: corrected page title -->
    <title>MediQueue | Patient Records</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <!-- HEADER -->
        <section class="features-header my-1">
            <h2>Patient <span>Records</span></h2>
            <p class="text-muted text-decoration-underline mx-0 mb-3">Search and review registered patients</p>
        </section>

        <!-- PATIENT TABLE -->
        <section class="row g-4">

            <div class="col-lg-7">
                <div class="dcard">
                    <div class="card-header">Patient List</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Last Visit</th>
                                    <th>Total Visits</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($patientsList)): ?>
                                    <?php foreach ($patientsList as $pData): ?>
                                        <tr class="<?php echo ($pData['patient_id'] == $patientId) ? 'table-active' : ''; ?>">
                                            <td>
                                                <a href="?patient=<?php echo $pData['patient_id']; ?>">
                                                    #P<?php echo str_pad($pData['patient_id'], 4, '0', STR_PAD_LEFT); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($pData['full_name']); ?><br>
                                                <small class="text-muted">
                                                    <?php echo $pData['age']; ?> yrs / <?php echo htmlspecialchars($pData['gender'] ?? 'N/A'); ?>
                                                </small>
                                            </td>
                                            <td><?php echo date('d M Y', strtotime($pData['last_visit'])); ?></td>
                                            <td><?php echo (int)$pData['total_visits']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center text-muted">No patient records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PROFILE PANEL -->
            <div class="col-lg-5">

                <?php if ($current): ?>
                <div class="dcard mb-3">
                    <div class="card-header">Patient Profile</div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($current['full_name']); ?></p>
                        <p><strong>Age / Gender:</strong> <?php echo $current['age']; ?> / <?php echo htmlspecialchars($current['gender'] ?? 'N/A'); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($current['phone']); ?></p>
                        <hr>
                        <p class="mb-1"><strong>Medical Summary</strong></p>
                        <p class="text-muted mb-0">No generalized medical notes available. Refer to visit history for specific consultation notes.</p>
                    </div>
                </div>

                <div class="dcard mb-3">
                    <div class="card-header">Visit Summary</div>
                    <div class="card-body">
                        <p><strong>Total Visits:</strong> <?php echo (int)$current['total_visits']; ?></p>
                        <p><strong>Last Visit:</strong> <?php echo date('d M Y', strtotime($current['last_visit'])); ?></p>
                    </div>
                </div>

                <div class="dcard">
                    <div class="card-body">
                        <button class="btn btn-outline-secondary w-100" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print Patient History
                        </button>
                    </div>
                </div>
                <?php else: ?>
                    <div class="dcard p-4 text-center">
                        <p class="text-muted mb-0">Select a patient to view details.</p>
                    </div>
                <?php endif; ?>

            </div>

        </section>

        <!-- VISIT HISTORY -->
        <section class="mt-4">
            <div class="dcard">
                <div class="card-header">Visit History</div>
                <div class="card-body">
                    <?php if (!empty($historyList)): ?>
                    <ul class="visit-timeline">
                        <?php foreach ($historyList as $visit): ?>
                            <li class="<?php echo ($visit['appointment_type'] === 'Emergency') ? 'emergency-visit' : ''; ?>">

                                <span class="visit-date"><?php echo date('d M Y', strtotime($visit['appointment_date'])); ?></span>

                                <?php if ($visit['appointment_type'] === 'Emergency'): ?>
                                    <span class="badge type-emergency">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                                    </span>
                                <?php else: ?>
                                    <span class="badge type-<?php echo strtolower(str_replace(' ', '-', $visit['appointment_type'])); ?>">
                                        <?php echo htmlspecialchars($visit['appointment_type']); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if($visit['diagnosis']): ?>
                                    <p class="mt-1 mb-1 fw-bold"><?php echo htmlspecialchars($visit['diagnosis']); ?></p>
                                <?php endif; ?>
                                <?php if($visit['note_text']): ?>
                                    <p class="mb-0 text-muted">Notes: <?php echo htmlspecialchars($visit['note_text']); ?></p>
                                <?php endif; ?>
                                <?php if(!$visit['diagnosis'] && !$visit['note_text']): ?>
                                    <p class="mt-1 mb-0 text-muted fst-italic">No consultation notes recorded.</p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">No past visits recorded.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>

    <?php include './doctor-footer.php'; ?>

    <!-- FIX: Bootstrap JS moved to bottom of body -->
    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

</body>

</html>