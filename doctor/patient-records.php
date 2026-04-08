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

// Validation Logic
$isValid = false;
foreach ($patientsList as $p) {
    if ($p['patient_id'] == $patientId) {
        $isValid = true;
        break;
    }
}
if (!$isValid && $firstPatientId > 0) $patientId = $firstPatientId;

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

    foreach ($patientsList as $p) {
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
    <title>MediQueue | Patient Records</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">

    <style>
        @media print {

            body * {
                visibility: hidden;
            }

            #printSection,
            #printSection * {
                visibility: visible;
            }

            #printSection {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                background: #fff;
                padding: 20px;
            }

            /* Hide unwanted UI */
            .doctor-sidebar,
            .card-header,
            button {
                display: none !important;
            }

        }

        @media print {
            .visit-timeline>div {
                page-break-inside: avoid;
            }
        }
    </style>

</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <section class="features-header my-1">
            <h2>Patient <span>Records</span></h2>
            <p class="text-muted text-decoration-underline mx-0 mb-3">Patients who have booked with you</p>
        </section>

        <?php if (empty($patientsList)): ?>
            <div class="alert alert-info">No patient records yet. Appointments will appear here after patients book with you.</div>
        <?php else: ?>

            <section class="row g-4">
                <div class="col-lg-7">
                    <div class="dcard">
                        <div class="card-header">Patient List</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
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
                                        <?php foreach ($patientsList as $pData): ?>
                                            <tr class="<?php echo ($pData['patient_id'] == $patientId) ? 'table-active' : ''; ?>"
                                                onclick="loadPatient(<?php echo $pData['patient_id']; ?>)" style="cursor:pointer;">
                                                <td>
                                                    <span class="text-primary fw-bold">#P<?php echo str_pad($pData['patient_id'], 4, '0', STR_PAD_LEFT); ?></span>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($pData['full_name']); ?></strong><br>
                                                    <small class="text-muted">
                                                        <?php echo $pData['age']; ?> yrs / <?php echo htmlspecialchars($pData['gender'] ?? 'N/A'); ?>
                                                    </small>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($pData['last_visit'])); ?></td>
                                                <td><span class="badge bg-light text-dark border"><?php echo (int)$pData['total_visits']; ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <?php if ($current): ?>
                        <div class="dcard mb-3">
                            <div class="card-header">Patient Profile</div>
                            <div class="card-body" id="patientProfile">
                                <p class="mb-2"><strong>Name:</strong> <?php echo htmlspecialchars($current['full_name']); ?></p>
                                <p class="mb-2"><strong>Age / Gender:</strong> <?php echo $current['age']; ?> / <?php echo htmlspecialchars($current['gender'] ?? 'N/A'); ?></p>
                                <p class="mb-2"><strong>Phone:</strong> <?php echo htmlspecialchars($current['phone']); ?></p>
                                <hr>
                                <p class="mb-1 text-primary"><strong>Medical Summary</strong></p>
                                <p class="text-muted small mb-0">Refer to visit history for specific consultation notes and prescribed treatments.</p>
                            </div>
                        </div>

                        <div class="dcard mb-3">
                            <div class="card-header">Visit Summary</div>
                            <div class="card-body">
                                <p class="mb-2"><strong>Total Visits:</strong> <?php echo (int)$current['total_visits']; ?></p>
                                <p class="mb-0"><strong>Last Visit:</strong> <?php echo date('d M Y', strtotime($current['last_visit'])); ?></p>
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
                            <p class="text-muted mb-0">Select a patient from the list to view full records.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="mt-4" id="printSection">
                <div class="dcard">
                    <div class="card-header">Visit History</div>
                    <div class="card-body" id="patientHistory">
                        <h3 class="text-center mb-3">Patient History</h3>

                        <p><strong>Name:</strong> <?php echo htmlspecialchars($current['full_name']); ?></p>
                        <p><strong>Age / Gender:</strong> <?php echo $current['age']; ?> / <?php echo htmlspecialchars($current['gender']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($current['phone']); ?></p>

                        <hr>
                        <?php if (!empty($historyList)): ?>
                            <div class="visit-timeline">
                                <?php foreach ($historyList as $visit): ?>
                                    <div class="mb-4 pb-3 border-bottom <?php echo ($visit['appointment_type'] === 'Emergency') ? 'emergency-visit' : ''; ?>">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold visit-date"><?php echo date('d M Y', strtotime($visit['appointment_date'])); ?></span>
                                            <?php if ($visit['appointment_type'] === 'Emergency'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">
                                                    <?php echo htmlspecialchars($visit['appointment_type']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($visit['diagnosis']): ?>
                                            <h6 class="mb-1 text-primary">Diagnosis: <?php echo htmlspecialchars($visit['diagnosis']); ?></h6>
                                        <?php endif; ?>

                                        <?php if ($visit['note_text']): ?>
                                            <p class="mb-0 text-muted small">Notes: <?php echo nl2br(htmlspecialchars($visit['note_text'])); ?></p>
                                        <?php endif; ?>

                                        <?php if (!$visit['diagnosis'] && !$visit['note_text']): ?>
                                            <p class="mb-0 text-muted fst-italic small">No consultation notes recorded for this visit.</p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center py-3 mb-0">No past completed visits recorded for this patient.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        <?php endif; ?>
    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

    <script>
        function loadPatient(patientId) {
            fetch("get-patient.php?patient=" + patientId)
                .then(res => res.json())
                .then(data => {

                    /* -------- UPDATE PROFILE -------- */
                    document.getElementById("patientProfile").innerHTML = `
                <p><strong>Name:</strong> ${data.profile.name}</p>
                <p><strong>Age / Gender:</strong> ${data.profile.age} / ${data.profile.gender}</p>
                <p><strong>Phone:</strong> ${data.profile.phone}</p>
            `;

                    /* -------- UPDATE HISTORY -------- */
                    let html = "";

                    if (data.history.length > 0) {
                        data.history.forEach(v => {
                            html += `
                        <div class="mb-3 border-bottom pb-2">
                            <strong>${v.appointment_date}</strong>
                            <span class="badge bg-info">${v.appointment_type}</span>
                            ${v.diagnosis ? `<p>Diagnosis: ${v.diagnosis}</p>` : ""}
                            ${v.note_text ? `<p>Notes: ${v.note_text}</p>` : ""}
                        </div>
                    `;
                        });
                    } else {
                        html = `<p class="text-muted">No history found</p>`;
                    }

                    document.getElementById("patientHistory").innerHTML = html;

                });
            document.querySelectorAll("tbody tr").forEach(tr => tr.classList.remove("table-active"));
            event.currentTarget.classList.add("table-active");
        }
    </script>

</body>

</html>