<?php
require_once "admin-init.php";

$content_page = 'clinic-overview';
ob_start();

// Fetch all clinics and count how many doctors are assigned to each
$query = "SELECT c.*, 
          (SELECT COUNT(*) FROM doctors WHERE clinic_id = c.clinic_id) as doctor_count 
          FROM clinics c ORDER BY c.clinic_id DESC";
$clinics_result = mysqli_query($con, $query);

// Fetch all doctors to populate the "Assign Doctors" modals
$doctors_query = mysqli_query($con, "SELECT doctor_id, full_name, specialization, clinic_id FROM doctors");
$all_doctors = [];
while ($d = mysqli_fetch_assoc($doctors_query)) {
    $all_doctors[] = $d;
}
?>

<style>
    /* Custom Highlight Styles for Doctor Selection */
    .doctor-label {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border: 1px solid #dee2e6 !important;
        /* Standard Bootstrap border */
        border-left: 4px solid transparent !important;
        /* Placeholder for highlight bar */
        background-color: #ffffff;
    }

    .doctor-label:hover {
        border-color: #b0d4ff !important;
        background-color: #f8f9fa;
    }

    /* When the hidden checkbox is checked, style the label immediately following it */
    .doctor-checkbox:checked+.doctor-label {
        border-color: #0d6efd !important;
        /* Bootstrap Primary Blue */
        background-color: #f0f7ff !important;
        /* Light blue tint */
        border-left: 4px solid #0d6efd !important;
        /* Thick left highlight */
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.1);
        /* Subtle glow */
    }
</style>

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
                <h2>Clinic <span>Management</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
                <p class="mb-0">Manage clinics, schedules, and doctor assignments</p>
            </div>

            <button class="btn btn-brand w-30 mb-3 py-2" data-bs-toggle="modal" data-bs-target="#addClinicModal">
                + Add Clinic
            </button>
        </div>

        <div class="feature-acard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Clinic Name</th>
                        <th>Contact Info</th>
                        <th>Address</th>
                        <th>Working Days</th>
                        <th>Hours</th>
                        <th>Doctors</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($clinics_result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($clinics_result)): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['clinic_name']) ?></strong></td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-telephone"></i> <?= htmlspecialchars($row['phone'] ?? 'N/A') ?><br>
                                        <i class="bi bi-envelope"></i> <?= htmlspecialchars($row['email'] ?? 'N/A') ?>
                                    </small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['address'] ?? 'N/A') ?>
                                    <?= !empty($row['pincode']) ? " - " . htmlspecialchars($row['pincode']) : '' ?>
                                </td>
                                <td><?= htmlspecialchars($row['working_days'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $open = !empty($row['start_time']) ? date('H:i', strtotime($row['start_time'])) : '--:--';
                                    $close = !empty($row['end_time']) ? date('H:i', strtotime($row['end_time'])) : '--:--';
                                    echo "$open - $close";
                                    ?>
                                </td>
                                <td><span class="badge bg-primary"><?= $row['doctor_count'] ?></span></td>
                                <td>
                                    <?php if (isset($row['is_active']) && $row['is_active'] == 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">

                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">

                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editClinicModal_<?= $row['clinic_id'] ?>">
                                            Edit
                                        </button>

                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#assignDoctorModal_<?= $row['clinic_id'] ?>">
                                            Assign
                                        </button>

                                        <form action="manage-clinic-action.php" method="POST" class="m-0">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="clinic_id" value="<?= $row['clinic_id'] ?>">
                                            <button type="submit" class="btn btn-sm <?= (isset($row['is_active']) && $row['is_active']) ? 'btn-outline-warning' : 'btn-outline-success' ?>" style="min-width: 85px;">
                                                <?= (isset($row['is_active']) && $row['is_active']) ? 'Deactivate' : 'Activate' ?>
                                            </button>
                                        </form>

                                        <form action="manage-clinic-action.php" method="POST" class="m-0" onsubmit="return confirm('Delete this clinic?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="clinic_id" value="<?= $row['clinic_id'] ?>">
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
                            <td colspan="8" class="text-center text-muted py-4">No clinics found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php
if (mysqli_num_rows($clinics_result) > 0) {
    mysqli_data_seek($clinics_result, 0);
    while ($row = mysqli_fetch_assoc($clinics_result)):
        $modal_id = $row['clinic_id'];
?>
        <div class="modal fade" id="editClinicModal_<?= $modal_id ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="manage-clinic-action.php" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="clinic_id" value="<?= $modal_id ?>">

                        <div class="modal-header">
                            <h5>Edit Clinic Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-start">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Clinic Name <span class="text-danger">*</span></label>
                                    <input type="text" name="clinic_name" class="form-control" value="<?= htmlspecialchars($row['clinic_name']) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Contact Number</label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label>Clinic Address</label>
                                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($row['address'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Pincode</label>
                                    <input type="text" name="pincode" class="form-control" value="<?= htmlspecialchars($row['pincode'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Working Days</label>
                                    <select name="working_days" class="form-select">
                                        <option value="Mon - Fri" <?= ($row['working_days'] == 'Mon - Fri') ? 'selected' : '' ?>>Mon - Fri</option>
                                        <option value="Mon - Sat" <?= ($row['working_days'] == 'Mon - Sat') ? 'selected' : '' ?>>Mon - Sat</option>
                                        <option value="All Days" <?= ($row['working_days'] == 'All Days') ? 'selected' : '' ?>>All Days</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Start Time</label>
                                    <input type="time" name="start_time" class="form-control" value="<?= $row['start_time'] ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>End Time</label>
                                    <input type="time" name="end_time" class="form-control" value="<?= $row['end_time'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-brand w-30 mb-2 py-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="assignDoctorModal_<?= $modal_id ?>" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form action="manage-clinic-action.php" method="POST">
                        <input type="hidden" name="action" value="assign_doctors">
                        <input type="hidden" name="clinic_id" value="<?= $modal_id ?>">

                        <div class="modal-header">
                            <h5>Assign Doctors to <?= htmlspecialchars($row['clinic_name']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-start">
                            <?php if (empty($all_doctors)): ?>
                                <p class="text-muted">No doctors found in the system.</p>
                            <?php else: ?>
                                <div style="max-height: 300px; overflow-y: auto; padding-right: 10px;">
                                    <?php foreach ($all_doctors as $doc): ?>
                                        <?php $is_assigned = ($doc['clinic_id'] == $modal_id); ?>

                                        <div class="doctor-option mb-2">
                                            <input class="d-none doctor-checkbox" type="checkbox"
                                                name="doctor_ids[]"
                                                value="<?= $doc['doctor_id'] ?>"
                                                id="doc_<?= $modal_id ?>_<?= $doc['doctor_id'] ?>"
                                                <?= $is_assigned ? 'checked' : '' ?>>
                                            <label class="w-100 p-3 rounded doctor-label m-0 d-block text-start" for="doc_<?= $modal_id ?>_<?= $doc['doctor_id'] ?>">
                                                <strong style="color: #333; font-size: 16px;"><?= htmlspecialchars($doc['full_name']) ?></strong>
                                                <small class="text-muted" style="font-size: 14.5px; margin-left: 4px;">(<?= htmlspecialchars($doc['specialization']) ?>)</small>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-brand w-30 mb-2 py-2">Save Assignment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    endwhile;
}
?>

<div class="modal fade" id="addClinicModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="manage-clinic-action.php" method="POST" id="addClinicForm">
                <input type="hidden" name="action" value="add">

                <div class="modal-header">
                    <h5>Add New Clinic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Clinic Name <span class="text-danger">*</span></label>
                            <input type="text" name="clinic_name" id="add_clinic_name" class="form-control" data-validation="required">
                            <small id="add_clinic_name_error" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Contact Number</label>
                            <input type="text" name="phone" id="add_phone" class="form-control">
                            <small id="add_phone_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" id="add_email" class="form-control">
                            <small id="add_email_error" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label>Clinic Address</label>
                            <input type="text" name="address" id="add_address" class="form-control">
                            <small id="add_address_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Pincode</label>
                            <input type="text" name="pincode" id="add_pincode" class="form-control">
                            <small id="add_pincode_error" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Working Days</label>
                            <select name="working_days" class="form-select">
                                <option value="Mon - Fri">Mon - Fri</option>
                                <option value="Mon - Sat" selected>Mon - Sat</option>
                                <option value="All Days">All Days</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="09:00">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control" value="18:00">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand w-30 mb-2 py-2">Add Clinic</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/validation.js"></script>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>