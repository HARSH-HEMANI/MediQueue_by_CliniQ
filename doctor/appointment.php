<?php
include "doctor-auth.php";
include "../db.php";
require_once __DIR__ . '/doctor-helpers.inc.php';

$doctor_id = (int) $_SESSION['doctor_id'];

$filter_date = $_GET['filter_date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $filter_date)) {
    $filter_date = date('Y-m-d');
}
$esc_date = mysqli_real_escape_string($con, $filter_date);

$type_filter = $_GET['type'] ?? 'all';
$status_filter = $_GET['status'] ?? 'all';

$type_sql = '';
if ($type_filter === 'New') {
    $type_sql = " AND a.appointment_type = 'New'";
} elseif ($type_filter === 'Follow-up') {
    $type_sql = " AND a.appointment_type = 'Follow Up'";
} elseif ($type_filter === 'Emergency') {
    $type_sql = " AND a.appointment_type = 'Emergency'";
}

$qstr = "SELECT a.*, t.token_id, t.token_no, t.status AS token_status, t.called_at,
    p.patient_id, p.full_name, p.gender, p.date_of_birth, p.phone
FROM appointments a
LEFT JOIN tokens t ON t.appointment_id = a.appointment_id
INNER JOIN patients p ON a.patient_id = p.patient_id
WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$esc_date' $type_sql
ORDER BY COALESCE(t.queue_position, 9999), a.appointment_time ASC";

$res = mysqli_query($con, $qstr);
$rows = [];
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) {
        $rows[] = $r;
    }
}

function appt_display_status(array $r): string
{
    if (!empty($r['token_status'])) {
        return $r['token_status'];
    }
    $st = $r['status'] ?? '';
    if ($st === 'Pending' || $st === 'Confirmed') {
        return 'Waiting';
    }
    return $st !== '' ? $st : '—';
}

function appt_status_badge_class(array $r): string
{
    $s = appt_display_status($r);
    if ($s === 'Completed') {
        return 'status-completed';
    }
    if ($s === 'In Progress') {
        return 'bg-success';
    }
    if ($s === 'Cancelled') {
        return 'bg-secondary';
    }
    return 'status-waiting';
}

$filtered = $rows;
if ($status_filter !== 'all') {
    $filtered = array_values(array_filter($rows, function ($r) use ($status_filter) {
        $s = appt_display_status($r);
        if ($status_filter === 'Waiting') {
            return $s === 'Waiting' || (($r['status'] ?? '') === 'Pending' && empty($r['token_status']));
        }
        if ($status_filter === 'Completed') {
            return $s === 'Completed' || ($r['status'] ?? '') === 'Completed';
        }
        if ($status_filter === 'Cancelled') {
            return ($r['status'] ?? '') === 'Cancelled';
        }
        if ($status_filter === 'No-show') {
            return ($r['status'] ?? '') === 'Cancelled' && $s !== 'Completed';
        }
        return true;
    }));
}

$notes_prefetch = [];
if (count($filtered)) {
    $ids = array_unique(array_map(function ($r) {
        return (int) $r['appointment_id'];
    }, $filtered));
    $idlist = implode(',', $ids);
    if ($idlist !== '') {
        $nq = mysqli_query($con, "SELECT * FROM consultation_notes WHERE appointment_id IN ($idlist)");
        if ($nq) {
            while ($n = mysqli_fetch_assoc($nq)) {
                $notes_prefetch[(int) $n['appointment_id']] = $n;
            }
        }
    }
}

$qs_base = 'filter_date=' . urlencode($filter_date) . '&type=' . urlencode($type_filter) . '&status=' . urlencode($status_filter);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Appointments</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <section class="features-header my-1">
            <h2>Welcome, <span>Dr. <?php echo htmlspecialchars($_SESSION['doctor_name']); ?></span></h2>
        </section>

        <section class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1">Appointments</h4>
                <p class="text-muted mb-0">Day-wise schedule and queue status</p>
            </div>
            <form method="get" class="d-flex gap-2 align-items-center flex-wrap">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type_filter); ?>">
                <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
                <label class="small text-muted mb-0">Date</label>
                <input type="date" name="filter_date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($filter_date); ?>">
                <button type="submit" class="btn btn-outline-secondary btn-sm">Apply</button>
            </form>
        </section>

        <section class="mb-3">
            <div class="dcard p-3">
                <form method="get" class="row g-2 align-items-end">
                    <input type="hidden" name="filter_date" value="<?php echo htmlspecialchars($filter_date); ?>">
                    <div class="col-md-3">
                        <label class="form-label small mb-0">Visit type</label>
                        <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all" <?php echo $type_filter === 'all' ? 'selected' : ''; ?>>All Types</option>
                            <option value="New" <?php echo $type_filter === 'New' ? 'selected' : ''; ?>>New</option>
                            <option value="Follow-up" <?php echo $type_filter === 'Follow-up' ? 'selected' : ''; ?>>Follow-up</option>
                            <option value="Emergency" <?php echo $type_filter === 'Emergency' ? 'selected' : ''; ?>>Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-0">Status</label>
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="Waiting" <?php echo $status_filter === 'Waiting' ? 'selected' : ''; ?>>Waiting</option>
                            <option value="Completed" <?php echo $status_filter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo $status_filter === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </form>
            </div>
        </section>

        <section>
            <div class="dcard">
                <div class="card-header">Appointment List</div>
                <div class="card-body p-0">
                    <?php if (count($filtered) === 0): ?>
                        <p class="text-muted p-4 mb-0">No appointments for this date and filters.</p>
                    <?php else: ?>
                        <table class="table mb-0 appointment-table">
                            <thead>
                                <tr>
                                    <th>Token</th>
                                    <th>Patient</th>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filtered as $ap): ?>
                                    <?php
                                    $pid = (int) $ap['patient_id'];
                                    $aid = (int) $ap['appointment_id'];
                                    $age = doctor_patient_age($ap['date_of_birth'] ?? '');
                                    $tok = isset($ap['token_no']) ? '#' . (int) $ap['token_no'] : '—';
                                    $timeDisp = !empty($ap['appointment_time']) ? date('g:i A', strtotime($ap['appointment_time'])) : '—';
                                    $typeLabel = doctor_appt_type_label($ap['appointment_type'] ?? '');
                                    $emRow = ($ap['appointment_type'] ?? '') === 'Emergency' ? 'emergency-row' : '';
                                    $typeBadge = 'type-new';
                                    if (($ap['appointment_type'] ?? '') === 'Follow Up') {
                                        $typeBadge = 'type-follow';
                                    }
                                    if (($ap['appointment_type'] ?? '') === 'Emergency') {
                                        $typeBadge = 'type-emergency';
                                    }
                                    ?>
                                    <tr class="<?php echo $emRow; ?>">
                                        <td><?php echo htmlspecialchars($tok); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($ap['full_name']); ?><br>
                                            <small class="text-muted">
                                                <?php echo $age !== null ? (int) $age : '—'; ?> / <?php echo htmlspecialchars($ap['gender'] ?? '—'); ?>
                                            </small>
                                        </td>
                                        <td><span class="badge <?php echo htmlspecialchars($typeBadge); ?>"><?php echo htmlspecialchars($typeLabel); ?></span></td>
                                        <td><?php echo htmlspecialchars($timeDisp); ?></td>
                                        <td><span class="badge <?php echo htmlspecialchars(appt_status_badge_class($ap)); ?>"><?php echo htmlspecialchars(appt_display_status($ap)); ?></span></td>
                                        <td class="action-cell">
                                            <a href="patient-records.php?patient=<?php echo $pid; ?>" class="btn btn-sm btn-light" title="Patient record">
                                                <i class="bi bi-person"></i>
                                            </a>
                                            <a href="live-queue.php?date=<?php echo urlencode($filter_date); ?>" class="btn btn-sm btn-light" title="Live Queue">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </a>
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="modal" data-bs-target="#notesModal<?php echo $aid; ?>" title="Notes">
                                                <i class="bi bi-journal-text"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>

    <?php foreach ($filtered as $ap): ?>
        <?php
        $aid = (int) $ap['appointment_id'];
        $np = $notes_prefetch[$aid] ?? [];
        ?>
        <div class="modal fade" id="notesModal<?php echo $aid; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content dcard">
                    <div class="modal-header">
                        <h5 class="modal-title">Consultation Notes · <?php echo htmlspecialchars($ap['full_name']); ?></h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <form method="post" action="save-consultation-notes.php">
                        <input type="hidden" name="appointment_id" value="<?php echo $aid; ?>">
                        <input type="hidden" name="redirect" value="appointment.php?<?php echo htmlspecialchars($qs_base); ?>">
                        <div class="modal-body">
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Diagnosis</label>
                                    <input type="text" name="diagnosis" class="form-control" value="<?php echo htmlspecialchars($np['diagnosis'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Follow-up date</label>
                                    <input type="date" name="follow_up_date" class="form-control" value="<?php echo htmlspecialchars($np['follow_up_date'] ?? ''); ?>">
                                </div>
                            </div>
                            <label class="form-label">Clinical notes</label>
                            <textarea name="note_text" class="form-control mb-2" rows="3" placeholder="Symptoms, advice..."><?php echo htmlspecialchars($np['note_text'] ?? ''); ?></textarea>
                            <label class="form-label">Medicines</label>
                            <textarea name="medicines" class="form-control" rows="2" placeholder="Prescription / medicines"><?php echo htmlspecialchars($np['medicines'] ?? ''); ?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                            <button class="btn btn-brand" type="submit">Save Notes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>

</body>

</html>
