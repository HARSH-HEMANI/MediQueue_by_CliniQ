<?php
require_once "reception-init.php";

$content_page = 'Register Patient | Reception';
ob_start();

// Ensure clinic_id is present
$clinic_id = $_SESSION['clinic_id'] ?? null;
if (!$clinic_id) {
    header("Location: logout.php");
    exit();
}

/**
 * 1. FETCH DOCTORS WITH FALLBACK LOGIC
 * If the strict query (Clinic + Active) returns 0, we broaden the search 
 * to ensure the dropdown is never empty if data exists.
 */
$safe_clinic = mysqli_real_escape_string($con, $clinic_id);

// Attempt 1: Strict
$doctors_query = mysqli_query($con, "SELECT doctor_id, full_name FROM doctors WHERE clinic_id = '$safe_clinic' AND is_active = 1");

// Attempt 2: Fallback (Ignore is_active)
if (mysqli_num_rows($doctors_query) == 0) {
    $doctors_query = mysqli_query($con, "SELECT doctor_id, full_name FROM doctors WHERE clinic_id = '$safe_clinic'");
}

// Attempt 3: Emergency Fallback (All doctors in DB)
if (mysqli_num_rows($doctors_query) == 0) {
    $doctors_query = mysqli_query($con, "SELECT doctor_id, full_name FROM doctors");
}

// 2. Search Logic for Table
$search = $_GET['search'] ?? '';
$searchTerm = mysqli_real_escape_string($con, $search);

$query = "SELECT p.*, d.full_name as doctor_name, a.appointment_date 
          FROM patients p
          LEFT JOIN appointments a ON p.patient_id = a.patient_id
          LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
          WHERE 1=1";

if (!empty($searchTerm)) {
    $query .= " AND (p.full_name LIKE '%$searchTerm%' OR p.phone LIKE '%$searchTerm%')";
}

$query .= " GROUP BY p.patient_id ORDER BY p.patient_id DESC LIMIT 20";
$patients_result = mysqli_query($con, $query);

$patients_data = [];
while ($row = mysqli_fetch_assoc($patients_result)) {
    $patients_data[] = $row;
}
?>

<div class="reception-dashboard">
    <div class="mb-4">
        <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Walk-in registration</small>
        <h1 class="dashboard-title mt-1">Patient <span>Management</span></h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['success'];
                                                                        unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= $_SESSION['error'];
                                                                    unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="rcard mb-4 shadow-sm border-0">
        <div class="rcard-body p-4">
            <form action="register_patient_action.php" method="POST">
                <input type="hidden" name="action" value="add">

                <p class="profile-section-title fw-bold text-dark mb-4" style="margin-top:0;">1. Personal Details</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><label class="form-label fw-semibold small">Full Name</label><input type="text" name="full_name" class="form-control" placeholder="Enter name" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold small">Email</label><input type="email" name="email" class="form-control" placeholder="Optional"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold small">Phone</label><input type="text" name="phone" class="form-control" placeholder="10-digit" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold small">Date of Birth</label><input type="date" name="birthdate" class="form-control" required></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label fw-semibold small">Address</label><input type="text" name="address" class="form-control" placeholder="Residential address"></div>
                </div>

                <p class="profile-section-title fw-bold text-brand mb-4">2. Schedule Appointment</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><label class="form-label fw-semibold small">Appointment Date</label><input type="date" name="appointment_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Assign Doctor</label>
                        <select name="doctor_id" class="form-select" required>
                            <?php if (mysqli_num_rows($doctors_query) > 0): ?>
                                <option value="">Choose Doctor...</option>
                                <?php mysqli_data_seek($doctors_query, 0);
                                while ($doc = mysqli_fetch_assoc($doctors_query)): ?>
                                    <option value="<?= $doc['doctor_id'] ?>"><?= htmlspecialchars($doc['full_name']) ?></option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">No Doctors Found in DB</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Visit Type</label>
                        <select name="appointment_type" class="form-select" required>
                            <option value="New">New Consultation</option>
                            <option value="Follow Up">Follow-up</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-brand rounded-pill px-5 shadow-sm"><i class="bi bi-person-plus me-2"></i>Register & Book</button>
            </form>
        </div>
    </div>

    <div class="rcard shadow-sm border-0">
        <div class="rcard-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <p class="profile-section-title m-0 fw-bold">Recent Registrations</p>
                <form class="d-flex gap-2" method="GET">
                    <input type="text" name="search" class="form-control form-control-sm rounded-pill px-3" style="width:250px;" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-sm btn-brand rounded-pill px-3">Search</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Details</th>
                            <th>Phone</th>
                            <th>Assigned</th>
                            <th>Appt. Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients_data as $p): ?>
                            <tr>
                                <td class="text-muted small">#P<?= str_pad($p['patient_id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($p['full_name']) ?></div><small class="text-muted"><?= $p['gender'] ?></small>
                                </td>
                                <td><?= htmlspecialchars($p['phone']) ?></td>
                                <td><?= $p['doctor_name'] ? "<span class='badge-soft-primary'>{$p['doctor_name']}</span>" : "—" ?></td>
                                <td class="small"><?= $p['appointment_date'] ? date('d M Y', strtotime($p['appointment_date'])) : '—' ?></td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['patient_id'] ?>"><i class="bi bi-pencil"></i></button>
                                        <form action="register_patient_action.php" method="POST" onsubmit="return confirm('Delete record?')">
                                            <input type="hidden" name="action" value="delete"><input type="hidden" name="patient_id" value="<?= $p['patient_id'] ?>"><button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach ($patients_data as $p): ?>
    <div class="modal fade" id="editModal<?= $p['patient_id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="register_patient_action.php" method="POST" class="modal-content border-0 shadow-lg">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="patient_id" value="<?= $p['patient_id'] ?>">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold">Update Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold small">Full Name</label><input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($p['full_name']) ?>" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold small">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($p['email']) ?>"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold small">Phone</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($p['phone']) ?>" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold small">DOB</label><input type="date" name="birthdate" class="form-control" value="<?= $p['date_of_birth'] ?>" required></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="Male" <?= $p['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $p['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= $p['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-12"><label class="form-label fw-semibold small">Address</label><textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($p['address']) ?></textarea></div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0"><button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-brand rounded-pill px-4 shadow-sm">Save Changes</button></div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?php $content = ob_get_clean();
include './reception-layout.php'; ?>