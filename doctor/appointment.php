<?php 
include "doctor-auth.php"; 
include "../db.php";

$doctor_id = (int)$_SESSION['doctor_id'];
$filter_date = $_GET['date'] ?? date('Y-m-d');
$filter_type = $_GET['type'] ?? '';
$filter_status = $_GET['status'] ?? '';

// Build Query
$queryStr = "SELECT a.appointment_id, p.patient_id, p.full_name, p.date_of_birth, p.gender, 
                    a.appointment_type, a.appointment_time, t.token_no, t.status as token_status, t.token_id 
             FROM appointments a 
             JOIN patients p ON a.patient_id = p.patient_id 
             LEFT JOIN tokens t ON a.appointment_id = t.appointment_id 
             WHERE a.doctor_id = $doctor_id AND a.appointment_date = '$filter_date'";

if ($filter_type) {
    if ($filter_type === 'Follow Up') {
        $queryStr .= " AND a.appointment_type = 'Follow Up'";
    } else {
        $queryStr .= " AND a.appointment_type = '$filter_type'";
    }
}
if ($filter_status) {
    if ($filter_status === 'Pending') {
        $queryStr .= " AND a.status = 'Pending' AND t.token_id IS NULL";
    } else {
        $queryStr .= " AND t.status = '$filter_status'";
    }
}
$queryStr .= " ORDER BY a.appointment_time ASC, t.queue_position ASC";

$appointmentsQ = mysqli_query($con, $queryStr);
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
            <form class="d-flex gap-2" method="GET" action="appointment.php">
                <?php if($filter_type): ?><input type="hidden" name="type" value="<?php echo htmlspecialchars($filter_type); ?>"><?php endif; ?>
                <?php if($filter_status): ?><input type="hidden" name="status" value="<?php echo htmlspecialchars($filter_status); ?>"><?php endif; ?>
                <select class="form-select form-select-sm" onchange="this.form.date.value=this.value; this.form.submit();">
                    <option value="<?php echo date('Y-m-d'); ?>" <?php echo $filter_date === date('Y-m-d') ? 'selected' : ''; ?>>Today</option>
                    <option value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>" <?php echo $filter_date === date('Y-m-d', strtotime('-1 day')) ? 'selected' : ''; ?>>Yesterday</option>
                    <option value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" <?php echo $filter_date === date('Y-m-d', strtotime('+1 day')) ? 'selected' : ''; ?>>Tomorrow</option>
                </select>
                <input type="date" name="date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($filter_date); ?>" onchange="this.form.submit();">
            </form>
        </section>

        <section class="mb-3">
            <div class="dcard p-3">
                <form class="row g-2" method="GET" action="appointment.php">
                    <input type="hidden" name="date" value="<?php echo htmlspecialchars($filter_date); ?>">
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" name="type" onchange="this.form.submit();">
                            <option value="">All Types</option>
                            <option value="New" <?php echo $filter_type === 'New' ? 'selected' : ''; ?>>New</option>
                            <option value="Follow Up" <?php echo $filter_type === 'Follow Up' ? 'selected' : ''; ?>>Follow-up</option>
                            <option value="Emergency" <?php echo $filter_type === 'Emergency' ? 'selected' : ''; ?>>Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" name="status" onchange="this.form.submit();">
                            <option value="">All Status</option>
                            <option value="Waiting" <?php echo $filter_status === 'Waiting' ? 'selected' : ''; ?>>Waiting</option>
                            <option value="In Progress" <?php echo $filter_status === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo $filter_status === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Skipped" <?php echo $filter_status === 'Skipped' ? 'selected' : ''; ?>>Skipped/Hold</option>
                            <option value="Pending" <?php echo $filter_status === 'Pending' ? 'selected' : ''; ?>>Pending (No Token)</option>
                        </select>
                    </div>
                </form>
            </div>
        </section>

        <section>
            <div class="dcard">
                <div class="card-header">Appointment List</div>
                <div class="card-body p-0">
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

                            <!-- Dynamic Rows -->
                            <?php if(mysqli_num_rows($appointmentsQ) > 0): ?>
                                <?php while($appt = mysqli_fetch_assoc($appointmentsQ)): 
                                    $age = "N/A";
                                    if ($appt['date_of_birth']) {
                                        $dob = new DateTime($appt['date_of_birth']);
                                        $now = new DateTime();
                                        $age = $now->diff($dob)->y;
                                    }
                                    $isEmergency = ($appt['appointment_type'] === 'Emergency');
                                ?>
                                <tr class="<?php echo $isEmergency ? 'emergency-row' : ''; ?>">
                                    <td><?php echo $appt['token_no'] ? "#" . $appt['token_no'] : "--"; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($appt['full_name']); ?><br>
                                        <small class="text-muted"><?php echo $age; ?> yrs / <?php echo htmlspecialchars($appt['gender']); ?></small>
                                    </td>
                                    <td>
                                        <?php if($isEmergency): ?>
                                            <span class="badge type-emergency">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                                            </span>
                                        <?php else: ?>
                                            <span class="badge type-<?php echo strtolower(str_replace(' ', '-', $appt['appointment_type'])); ?>">
                                                <?php echo htmlspecialchars($appt['appointment_type']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $appt['appointment_time'] ? date('h:i A', strtotime($appt['appointment_time'])) : 'Immediate'; ?></td>
                                    <td>
                                        <?php 
                                            $badgeClass = 'secondary';
                                            $statusTxt = $appt['token_status'] ?? 'Pending';
                                            if ($statusTxt === 'Waiting') $badgeClass = 'warning';
                                            if ($statusTxt === 'In Progress') $badgeClass = 'primary';
                                            if ($statusTxt === 'Completed') $badgeClass = 'success';
                                            if ($statusTxt === 'Pending') $badgeClass = 'secondary';
                                        ?>
                                        <span class="badge status-<?php echo strtolower(str_replace(' ', '-', $statusTxt)); ?> bg-<?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($statusTxt); ?>
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                        <a href="patient-records.php?patient=<?php echo $appt['patient_id']; ?>" class="btn btn-sm btn-light" title="View Patient Details">
                                            <i class="bi bi-person"></i>
                                        </a>
                                        <?php if(in_array($appt['token_status'], ['Waiting', 'In Progress', 'Skipped'])): ?>
                                        <a href="./live-queue.php" class="btn btn-sm btn-light" title="Go to Live Queue">
                                            <i class="bi bi-arrow-right-circle"></i>
                                        </a>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-light" title="Consultation Notes"
                                            onclick="openNotesModal(<?php echo $appt['appointment_id']; ?>, '<?php echo addslashes(htmlspecialchars($appt['full_name'])); ?>')">
                                            <i class="bi bi-journal-text"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No appointments found for the selected criteria.</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>


    <!-- ADD NOTES MODAL -->
    <div class="modal fade" id="notesModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dcard">
                <form action="save-consultation-notes.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Consultation Notes - <span id="notesPatientName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="appointment_id" id="notesApptId">
                        
                        <div class="mb-3">
                            <label class="form-label">Diagnosis</label>
                            <input type="text" name="diagnosis" class="form-control" placeholder="Primary diagnosis...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Detailed Notes</label>
                            <textarea class="form-control" name="note_text" rows="3"
                                placeholder="Enter symptoms, observations..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Medicines Prescribed</label>
                            <textarea class="form-control" name="medicines" rows="2"
                                placeholder="Paracetamol 500mg - 1x3..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" name="follow_up_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand">Save Notes</button>
                    </div>
                </form>
            </div>
        </div>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
    function openNotesModal(apptId, patientName) {
        document.getElementById('notesApptId').value = apptId;
        document.getElementById('notesPatientName').innerText = patientName;
        // Optional: clear the previous form inputs
        document.querySelector('input[name="diagnosis"]').value = '';
        document.querySelector('textarea[name="note_text"]').value = '';
        document.querySelector('textarea[name="medicines"]').value = '';
        document.querySelector('input[name="follow_up_date"]').value = '';
        
        var myModal = new bootstrap.Modal(document.getElementById('notesModal'));
        myModal.show();
    }
    </script>

</body>

</html>
