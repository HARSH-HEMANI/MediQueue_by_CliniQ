<?php
include "doctor-auth.php";
include "../db.php";
require_once __DIR__ . '/doctor-helpers.inc.php';

$doctor_id = (int) $_SESSION['doctor_id'];

$listq = mysqli_query($con, "SELECT DISTINCT p.patient_id, p.full_name, p.gender, p.date_of_birth, p.phone, p.address,
    (SELECT MAX(a2.appointment_date) FROM appointments a2 WHERE a2.patient_id = p.patient_id AND a2.doctor_id = $doctor_id) AS last_visit,
    (SELECT COUNT(*) FROM appointments a3 WHERE a3.patient_id = p.patient_id AND a3.doctor_id = $doctor_id) AS visit_count
FROM patients p
INNER JOIN appointments a ON a.patient_id = p.patient_id AND a.doctor_id = $doctor_id
ORDER BY last_visit DESC");

$patient_order = [];
if ($listq) {
    while ($row = mysqli_fetch_assoc($listq)) {
        $patient_order[] = $row;
    }
}

$patient_id = isset($_GET['patient']) ? (int) $_GET['patient'] : 0;
if ($patient_id <= 0 && count($patient_order)) {
    $patient_id = (int) $patient_order[0]['patient_id'];
}

$current = null;
foreach ($patient_order as $p) {
    if ((int) $p['patient_id'] === $patient_id) {
        $current = $p;
        break;
    }
}

$history = [];
if ($patient_id > 0) {
    $hq = mysqli_query($con, "SELECT a.*, t.token_no, t.status AS token_status,
        cn.diagnosis, cn.note_text, cn.medicines, cn.follow_up_date
        FROM appointments a
        LEFT JOIN tokens t ON t.appointment_id = a.appointment_id
        LEFT JOIN consultation_notes cn ON cn.appointment_id = a.appointment_id
        WHERE a.patient_id = $patient_id AND a.doctor_id = $doctor_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC");
    if ($hq) {
        while ($h = mysqli_fetch_assoc($hq)) {
            $history[] = $h;
        }
    }
}

$last_notes = '';
foreach ($history as $h) {
    if (!empty($h['note_text'])) {
        $last_notes = $h['note_text'];
        break;
    }
}
if ($last_notes === '' && count($history)) {
    $last_notes = $history[0]['visit_reason'] ?? '';
}

$fmt_date = function ($d) {
    if (empty($d)) {
        return '—';
    }
    $ts = strtotime($d);
    return $ts ? date('d M Y', $ts) : '—';
};

$freq_label = '—';
if ($current && (int) ($current['visit_count'] ?? 0) > 1 && !empty($current['last_visit'])) {
    $freq_label = 'Multiple visits with you';
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
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <section class="features-header my-1">
            <h2>Patient <span>Records</span></h2>
            <p class="text-muted text-decoration-underline mx-0 mb-3">Patients who have booked with you</p>
        </section>

        <?php if (count($patient_order) === 0): ?>
            <div class="alert alert-info">No patient records yet. Appointments will appear here after patients book with you.</div>
        <?php else: ?>

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
                                        <th>Visits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($patient_order as $p): ?>
                                        <?php $pid = (int) $p['patient_id']; ?>
                                        <tr class="<?php echo $pid === $patient_id ? 'table-active' : ''; ?>">
                                            <td>
                                                <a href="?patient=<?php echo $pid; ?>">
                                                    <?php echo $pid; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($p['full_name']); ?><br>
                                                <small class="text-muted">
                                                    <?php
                                                    $a = doctor_patient_age($p['date_of_birth'] ?? '');
                                                    echo $a !== null ? (int) $a : '—';
                                                    ?> / <?php echo htmlspecialchars($p['gender'] ?? '—'); ?>
                                                </small>
                                            </td>
                                            <td><?php echo htmlspecialchars($fmt_date($p['last_visit'] ?? '')); ?></td>
                                            <td><?php echo (int) ($p['visit_count'] ?? 0); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">

                    <?php if ($current): ?>
                        <div class="dcard mb-3">
                            <div class="card-header">Patient Profile</div>
                            <div class="card-body">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($current['full_name']); ?></p>
                                <p><strong>Age / Gender:</strong>
                                    <?php
                                    $a = doctor_patient_age($current['date_of_birth'] ?? '');
                                    echo $a !== null ? (int) $a : '—';
                                    ?> / <?php echo htmlspecialchars($current['gender'] ?? '—'); ?>
                                </p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($current['phone'] ?? '—'); ?></p>
                                <p><strong>Date of birth:</strong> <?php echo htmlspecialchars($fmt_date($current['date_of_birth'] ?? '')); ?></p>
                                <hr>
                                <p class="mb-1"><strong>Recent notes</strong></p>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($last_notes !== '' ? $last_notes : 'No notes yet.'); ?></p>
                            </div>
                        </div>

                        <div class="dcard mb-3">
                            <div class="card-header">Visit Summary</div>
                            <div class="card-body">
                                <p><strong>Total visits (with you):</strong> <?php echo (int) ($current['visit_count'] ?? 0); ?></p>
                                <p><strong>Last visit:</strong> <?php echo htmlspecialchars($fmt_date($current['last_visit'] ?? '')); ?></p>
                                <p class="mb-0"><strong>Visit pattern:</strong> <?php echo htmlspecialchars($freq_label); ?></p>
                            </div>
                        </div>

                        <div class="dcard">
                            <div class="card-body">
                                <button class="btn btn-outline-secondary w-100" type="button" onclick="window.print()">
                                    <i class="bi bi-printer"></i> Print Patient History
                                </button>
                            </div>
                        </div>
                    <?php elseif ($patient_id > 0): ?>
                        <div class="alert alert-warning mb-0">This patient is not in your records or has no appointments with you.</div>
                    <?php endif; ?>

                </div>

            </section>

            <?php if ($current && count($history)): ?>
                <section class="mt-4">
                    <div class="dcard">
                        <div class="card-header">Visit History</div>
                        <div class="card-body">
                            <ul class="visit-timeline">
                                <?php foreach ($history as $visit): ?>
                                    <?php
                                    $vtype = doctor_appt_type_label($visit['appointment_type'] ?? '');
                                    $isEm = ($visit['appointment_type'] ?? '') === 'Emergency';
                                    $dt = $fmt_date($visit['appointment_date'] ?? '');
                                    $tok = isset($visit['token_no']) ? (string) (int) $visit['token_no'] : '—';
                                    $note = trim($visit['note_text'] ?? '');
                                    if ($note === '' && !empty($visit['diagnosis'])) {
                                        $note = $visit['diagnosis'];
                                    }
                                    if ($note === '') {
                                        $note = $visit['visit_reason'] ?? '—';
                                    }
                                    ?>
                                    <li class="<?php echo $isEm ? 'emergency-visit' : ''; ?>">

                                        <span class="visit-date"><?php echo htmlspecialchars($dt); ?></span>

                                        <?php if ($isEm): ?>
                                            <span class="badge type-emergency">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                                            </span>
                                        <?php else: ?>
                                            <span class="badge type-follow"><?php echo htmlspecialchars($vtype); ?></span>
                                        <?php endif; ?>

                                        <span class="badge status-completed"><?php echo htmlspecialchars($visit['token_status'] ?? $visit['status'] ?? '—'); ?></span>

                                        <p class="mt-1 mb-0">Token #<?php echo htmlspecialchars($tok); ?></p>
                                        <small class="text-muted">Notes: <?php echo htmlspecialchars($note); ?></small>

                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

        <?php endif; ?>

    </main>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

</body>

</html>
