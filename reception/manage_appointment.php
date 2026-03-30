<?php
require_once "reception-init.php";
$content_page = 'Manage Appointments | Reception | MediQueue';
ob_start();

$clinic_id = $_SESSION['clinic_id'];
$today = date('Y-m-d');

// --- 1. HANDLE FILTERS ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$filter_date = isset($_GET['date']) ? mysqli_real_escape_string($con, $_GET['date']) : $today;
$filter_doctor = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;

// --- 2. STAT CARD COUNTERS ---
$stats_query = mysqli_query($con, "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as waiting,
    SUM(CASE WHEN status = 'Confirmed' THEN 1 ELSE 0 END) as progress,
    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed
    FROM appointments WHERE appointment_date = '$filter_date' AND clinic_id = $clinic_id");
$stats = mysqli_fetch_assoc($stats_query);

// --- 3. CURRENTLY CONSULTING SPOTLIGHT ---
// Fetches the first 'Confirmed' patient for today
$current_query = mysqli_query($con, "SELECT a.*, p.full_name, p.gender, p.date_of_birth, d.full_name as doctor_name 
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.patient_id 
    JOIN doctors d ON a.doctor_id = d.doctor_id 
    WHERE a.status = 'Confirmed' AND a.appointment_date = '$today' AND a.clinic_id = $clinic_id 
    ORDER BY a.appointment_time ASC LIMIT 1");
$current = mysqli_fetch_assoc($current_query);

// Helper function for Age
function getAge($dob) {
    if(!$dob) return "—";
    return date_diff(date_create($dob), date_create('today'))->y;
}
?>

<div class="reception-dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Control patient flow</small>
            <h1 class="dashboard-title mt-1">Manage <span>Appointments</span></h1>
            <p class="dashboard-subtitle">Update status, manage walk-ins and patient flow</p>
        </div>
        <a href="register-patient.php" class="btn btn-brand rounded-pill">
            <i class="bi bi-person-plus me-1"></i>Walk-In Patient
        </a>
    </div>

    <div class="stats-row mb-4">
        <div class="rstat-card"><h6>Total Today</h6><h2><?= $stats['total'] ?? 0 ?></h2></div>
        <div class="rstat-card"><h6>Pending</h6><h2><?= $stats['waiting'] ?? 0 ?></h2></div>
        <div class="rstat-card"><h6>Confirmed</h6><h2><?= $stats['progress'] ?? 0 ?></h2></div>
        <div class="rstat-card"><h6>Completed</h6><h2><?= $stats['completed'] ?? 0 ?></h2></div>
    </div>

    <?php if ($current): ?>
    <div class="dcard current-token-card p-4 mb-4" style="border-left: 5px solid #28a745;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex gap-2 mb-2">
                    <span class="badge bg-success">Currently Consulting</span>
                    <span class="badge-soft-primary"><?= $current['appointment_type'] ?></span>
                </div>
                <div class="current-token-number mt-1">Token <span>#<?= $current['appointment_id'] ?></span></div>
                <div class="r-patient-name mt-1">
                    <?= htmlspecialchars($current['full_name']) ?>
                    <span class="text-muted fw-normal" style="font-size:0.9rem;">(<?= getAge($current['date_of_birth']) ?> / <?= $current['gender'] ?>)</span>
                </div>
                <small class="text-muted d-block mt-1"><i class="bi bi-person-badge me-1"></i>Dr. <?= htmlspecialchars($current['doctor_name']) ?></small>
            </div>
            <div class="d-flex flex-column gap-2">
                <form action="appointment_status_action.php" method="POST">
                    <input type="hidden" name="appointment_id" value="<?= $current['appointment_id'] ?>">
                    <button name="status" value="Completed" class="btn btn-brand rounded-pill w-100 mb-2"><i class="bi bi-check-circle me-1"></i>Complete Visit</button>
                    <button name="status" value="Pending" class="btn btn-outline-warning rounded-pill w-100"><i class="bi bi-pause-circle me-1"></i>Put on Hold</button>
                </form>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="alert alert-light border text-center p-4 mb-4">No patient is currently in consultation.</div>
    <?php endif; ?>

    <div class="rcard mb-4">
        <div class="rcard-body">
            <form method="GET" action="manage_patient.php" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">Search</label>
                    <input type="text" name="search" class="form-control rounded-pill" placeholder="Name or phone..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">Date</label>
                    <input type="date" name="date" class="form-control rounded-3" value="<?= $filter_date ?>">
                </div>
                <!-- <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">Doctor</label>
                    <select name="doctor_id" class="form-select rounded-3">
                        <option value="0">All Doctors</option>
                        <?php 
                        $docs = mysqli_query($con, "SELECT doctor_id, full_name FROM doctors WHERE clinic_id = $clinic_id");
                        while($d = mysqli_fetch_assoc($docs)): ?>
                            <option value="<?= $d['doctor_id'] ?>" <?= $filter_doctor == $d['doctor_id'] ? 'selected' : '' ?>>Dr. <?= $d['full_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div> -->
                <div class="col-md-5 text-end">
                    <button type="submit" class="btn btn-brand rounded-pill w-50 mb-2">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="rcard">
        <div class="rcard-body">
            <h5 class="mb-3">Appointment List</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="r-thead">
                            <th>Token</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $list_query = "SELECT a.*, p.full_name, p.phone, d.full_name as doctor_name 
                                       FROM appointments a 
                                       JOIN patients p ON a.patient_id = p.patient_id 
                                       JOIN doctors d ON a.doctor_id = d.doctor_id 
                                       WHERE a.clinic_id = $clinic_id AND a.appointment_date = '$filter_date'";

                        if (!empty($search)) $list_query .= " AND (p.full_name LIKE '%$search%' OR p.phone LIKE '%$search%')";
                        if ($filter_doctor) $list_query .= " AND a.doctor_id = $filter_doctor";

                        $list_query .= " ORDER BY a.appointment_time ASC";
                        $appointments = mysqli_query($con, $list_query);

                        if (mysqli_num_rows($appointments) > 0):
                            while ($row = mysqli_fetch_assoc($appointments)):
                        ?>
                                <tr>
                                    <td><strong>#<?= $row['appointment_id'] ?></strong></td>
                                    <td>
                                        <div class="fw-semibold text-brand"><?= htmlspecialchars($row['full_name']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($row['phone']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                                    <td class="text-muted"><?= date('h:i A', strtotime($row['appointment_time'])) ?></td>
                                    <td><span class="badge-soft-primary"><?= $row['appointment_type'] ?></span></td>
                                    <td>
                                        <form action="appointment_status_action.php" method="POST">
                                            <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                                            <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                                                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                                <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                                <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><input class="form-control form-control-sm rounded-pill" placeholder="Add note..."></td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">No appointments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>