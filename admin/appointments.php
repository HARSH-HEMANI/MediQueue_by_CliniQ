<?php
require_once "admin-init.php";

$content_page = 'appointment';
ob_start();

// ==========================================
// FETCH DROPDOWN DATA FOR FORMS & FILTERS
// ==========================================
$clinics_result = mysqli_query($con, "SELECT clinic_id, clinic_name FROM clinics WHERE is_active = 1");
$doctors_result = mysqli_query($con, "SELECT doctor_id, full_name FROM doctors");
$patients_result = mysqli_query($con, "SELECT patient_id, full_name FROM patients WHERE is_active = 1");

$clinics = [];
while ($c = mysqli_fetch_assoc($clinics_result)) $clinics[] = $c;
$doctors = [];
while ($d = mysqli_fetch_assoc($doctors_result)) $doctors[] = $d;
$patients = [];
while ($p = mysqli_fetch_assoc($patients_result)) $patients[] = $p;

// ==========================================
// SEARCH & FILTER LOGIC
// ==========================================
$query = "SELECT a.*, p.full_name as patient_name, d.full_name as doctor_name, c.clinic_name 
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.patient_id 
          JOIN doctors d ON a.doctor_id = d.doctor_id 
          JOIN clinics c ON a.clinic_id = c.clinic_id 
          WHERE 1=1";

$conditions = [];

if (!empty($_GET['date'])) {
    $date = mysqli_real_escape_string($con, $_GET['date']);
    $conditions[] = "a.appointment_date = '$date'";
}

if (!empty($_GET['clinic_id'])) {
    $clinic_id = (int)$_GET['clinic_id'];
    $conditions[] = "a.clinic_id = $clinic_id";
}

if (!empty($_GET['doctor_id'])) {
    $doctor_id = (int)$_GET['doctor_id'];
    $conditions[] = "a.doctor_id = $doctor_id";
}

if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($con, $_GET['status']);
    $conditions[] = "a.status = '$status'";
}

if (count($conditions) > 0) {
    $query .= " AND " . implode(' AND ', $conditions);
}

// Order by date and time
$query .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$appointments_result = mysqli_query($con, $query);
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <?php if (isset($_SESSION['admin_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['admin_success'];
                unset($_SESSION['admin_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['admin_error'];
                unset($_SESSION['admin_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="features-header text-start mb-0">
                <h2>Appointment <span>Monitoring</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
                <p class="mb-0">System-wide appointment overview</p>
            </div>

            <button class="btn btn-brand w-30 mb-2 py-2" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                + New Appointment
            </button>
        </div>

        <div class="feature-acard mb-4">
            <form class="row g-3 align-items-center" method="GET" action="appointments.php">

                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <select class="form-select" name="clinic_id">
                        <option value="">All Clinics</option>
                        <?php foreach ($clinics as $c): ?>
                            <option value="<?= $c['clinic_id'] ?>" <?= (isset($_GET['clinic_id']) && $_GET['clinic_id'] == $c['clinic_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['clinic_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-select" name="doctor_id">
                        <option value="">All Doctors</option>
                        <?php foreach ($doctors as $d): ?>
                            <option value="<?= $d['doctor_id'] ?>" <?= (isset($_GET['doctor_id']) && $_GET['doctor_id'] == $d['doctor_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="Pending" <?= (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="Confirmed" <?= (isset($_GET['status']) && $_GET['status'] == 'Confirmed') ? 'selected' : '' ?>>Confirmed</option>
                        <option value="Completed" <?= (isset($_GET['status']) && $_GET['status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
                        <option value="Cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-brand w-30 mb-2 py-2 w-100 py-2">Filter</button>
                </div>
            </form>
            <?php if (!empty($_GET)): ?>
                <div class="text-end mt-2">
                    <a href="appointments.php" class="text-muted text-decoration-none small">Clear Filters</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="feature-acard">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor & Clinic</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($appointments_result && mysqli_num_rows($appointments_result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($appointments_result)): ?>
                                <tr>
                                    <td><strong>APT-<?= htmlspecialchars($row['appointment_id']) ?></strong></td>
                                    <td>
                                        <?= htmlspecialchars($row['patient_name']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['doctor_name']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($row['clinic_name']) ?></small>
                                    </td>
                                    <td>
                                        <?= date('d M Y', strtotime($row['appointment_date'])) ?><br>
                                        <small class="text-muted"><?= date('h:i A', strtotime($row['appointment_time'])) ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $type_class = 'bg-light text-dark border';
                                        if ($row['appointment_type'] == 'Emergency') $type_class = 'bg-danger';
                                        if ($row['appointment_type'] == 'Follow Up') $type_class = 'bg-info text-dark';
                                        ?>
                                        <span class="badge <?= $type_class ?>"><?= htmlspecialchars($row['appointment_type'] ?? 'N/A') ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $badge_class = 'bg-secondary';
                                        if ($row['status'] == 'Pending') $badge_class = 'bg-warning text-dark';
                                        if ($row['status'] == 'Confirmed') $badge_class = 'bg-primary';
                                        if ($row['status'] == 'Completed') $badge_class = 'bg-success';
                                        if ($row['status'] == 'Cancelled') $badge_class = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($row['status']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">

                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editApptModal_<?= $row['appointment_id'] ?>">
                                                Edit
                                            </button>

                                            <?php if ($row['status'] !== 'Cancelled' && $row['status'] !== 'Completed'): ?>
                                                <form action="manage-appointment-action.php" method="POST" class="m-0" onsubmit="return confirm('Cancel this appointment?');">
                                                    <input type="hidden" name="action" value="update_status">
                                                    <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                                                    <input type="hidden" name="status" value="Cancelled">
                                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                                        Cancel
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <form action="manage-appointment-action.php" method="POST" class="m-0" onsubmit="return confirm('Delete this appointment completely?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<div class="modal fade" id="addAppointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="manage-appointment-action.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="modal-header">
                    <h5>Schedule New Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Select Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php foreach ($patients as $p): ?>
                                    <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Select Doctor <span class="text-danger">*</span></label>
                            <select name="doctor_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php foreach ($doctors as $d): ?>
                                    <option value="<?= $d['doctor_id'] ?>"><?= htmlspecialchars($d['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Clinic Location <span class="text-danger">*</span></label>
                            <select name="clinic_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php foreach ($clinics as $c): ?>
                                    <option value="<?= $c['clinic_id'] ?>"><?= htmlspecialchars($c['clinic_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Appointment Type <span class="text-danger">*</span></label>
                            <select name="appointment_type" class="form-select" required>
                                <option value="New">New</option>
                                <option value="Follow Up">Follow Up</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time <span class="text-danger">*</span></label>
                            <input type="time" name="appointment_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Reason for Visit</label>
                            <textarea name="visit_reason" class="form-control" rows="2" placeholder="Briefly describe the symptoms or reason..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-brand w-30 mb-2 py-2">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if ($appointments_result && mysqli_num_rows($appointments_result) > 0) {
    mysqli_data_seek($appointments_result, 0);
    while ($row = mysqli_fetch_assoc($appointments_result)):
        $modal_id = $row['appointment_id'];
?>
        <div class="modal fade" id="editApptModal_<?= $modal_id ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="manage-appointment-action.php" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="appointment_id" value="<?= $modal_id ?>">
                        <div class="modal-header">
                            <h5>Edit Appointment (APT-<?= $modal_id ?>)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Doctor <span class="text-danger">*</span></label>
                                    <select name="doctor_id" class="form-select" required>
                                        <?php foreach ($doctors as $d): ?>
                                            <option value="<?= $d['doctor_id'] ?>" <?= ($row['doctor_id'] == $d['doctor_id']) ? 'selected' : '' ?>><?= htmlspecialchars($d['full_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Clinic Location <span class="text-danger">*</span></label>
                                    <select name="clinic_id" class="form-select" required>
                                        <?php foreach ($clinics as $c): ?>
                                            <option value="<?= $c['clinic_id'] ?>" <?= ($row['clinic_id'] == $c['clinic_id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['clinic_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" name="appointment_date" class="form-control" value="<?= $row['appointment_date'] ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Time <span class="text-danger">*</span></label>
                                    <input type="time" name="appointment_time" class="form-control" value="<?= $row['appointment_time'] ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Type <span class="text-danger">*</span></label>
                                    <select name="appointment_type" class="form-select" required>
                                        <option value="New" <?= ($row['appointment_type'] == 'New') ? 'selected' : '' ?>>New</option>
                                        <option value="Follow Up" <?= ($row['appointment_type'] == 'Follow Up') ? 'selected' : '' ?>>Follow Up</option>
                                        <option value="Emergency" <?= ($row['appointment_type'] == 'Emergency') ? 'selected' : '' ?>>Emergency</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="Confirmed" <?= ($row['status'] == 'Confirmed') ? 'selected' : '' ?>>Confirmed</option>
                                        <option value="Completed" <?= ($row['status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
                                        <option value="Cancelled" <?= ($row['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Reason for Visit</label>
                                    <textarea name="visit_reason" class="form-control" rows="2"><?= htmlspecialchars($row['visit_reason'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-brand w-30 mb-2 py-2">Update Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    endwhile;
}
?>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>