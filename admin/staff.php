<?php
require_once "admin-init.php";

$content_page = 'staff-management';
ob_start();

// Fetch all receptionists with their clinic names
$query = "SELECT r.*, c.clinic_name 
          FROM receptionists r 
          LEFT JOIN clinics c ON r.clinic_id = c.clinic_id 
          ORDER BY r.receptionist_id DESC";
$staff_result = mysqli_query($con, $query);

// Fetch clinics for the dropdown menus
$clinics_query = mysqli_query($con, "SELECT clinic_id, clinic_name FROM clinics");
$clinics = [];
while ($clinic_row = mysqli_fetch_assoc($clinics_query)) {
    $clinics[] = $clinic_row;
}
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
                <h2>Staff <span>Management</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
                <p class="mb-0">Manage receptionists</p>
            </div>

            <button class="btn btn-brand w-30 mb-3 py-2" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                + Add Staff
            </button>
        </div>

        <div class="feature-acard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Clinic</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($staff_result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($staff_result)): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($row['full_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['email']) ?></small>
                                </td>
                                <td>Receptionist</td>
                                <td><?= htmlspecialchars($row['clinic_name'] ?? 'Not Assigned') ?></td>
                                <td>
                                    <?php if ($row['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">

                                    <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#editStaffModal_<?= $row['receptionist_id'] ?>">
                                        Edit
                                    </button>

                                    <form action="manage-staff-action.php" method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="receptionist_id" value="<?= $row['receptionist_id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $row['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                            <?= $row['is_active'] ? 'Deactivate' : 'Activate' ?>
                                        </button>
                                    </form>

                                    <form action="manage-staff-action.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="receptionist_id" value="<?= $row['receptionist_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No staff found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
if (mysqli_num_rows($staff_result) > 0) {
    mysqli_data_seek($staff_result, 0);
    while ($row = mysqli_fetch_assoc($staff_result)):
?>
        <div class="modal fade" id="editStaffModal_<?= $row['receptionist_id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="manage-staff-action.php" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="receptionist_id" value="<?= $row['receptionist_id'] ?>">

                        <div class="modal-header">
                            <h5>Edit Staff Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-start">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($row['full_name']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Assign Clinic <span class="text-danger">*</span></label>
                                    <select name="clinic_id" class="form-select" required>
                                        <option value="">Select a Clinic</option>
                                        <?php foreach ($clinics as $clinic): ?>
                                            <option value="<?= $clinic['clinic_id'] ?>" <?= ($row['clinic_id'] == $clinic['clinic_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($clinic['clinic_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($row['address'] ?? '') ?></textarea>
                            </div>

                            <hr>
                            <div class="mb-3">
                                <label>Reset Password (Leave blank to keep current)</label>
                                <input type="password" name="new_password" class="form-control" placeholder="New Password">
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
<?php
    endwhile;
}
?>

<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="manage-staff-action.php" method="POST" id="addStaffForm">
                <input type="hidden" name="action" value="add">

                <div class="modal-header">
                    <h5>Add New Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="Staff Name" data-validation="required">
                            <small id="full_name_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="staff@clinic.com" data-validation="required|email">
                            <small id="email_error" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number">
                            <small id="phone_error" class="text-danger"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Assign Clinic <span class="text-danger">*</span></label>
                            <select name="clinic_id" class="form-select" data-validation="required">
                                <option value="">Select a Clinic</option>
                                <?php foreach ($clinics as $clinic): ?>
                                    <option value="<?= $clinic['clinic_id'] ?>">
                                        <?= htmlspecialchars($clinic['clinic_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small id="clinic_id_error" class="text-danger"></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" id="add_address" class="form-control" rows="2" placeholder="Full Address"></textarea>
                        <small id="address_error" class="text-danger"></small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Role</label>
                            <input type="text" class="form-control" value="Receptionist" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Login Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" data-validation="required|strongPassword">
                            <small id="password_error" class="text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand w-30 mb-2 py-2">Add Staff</button>
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