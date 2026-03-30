<?php
require_once "reception-init.php";

$content_page = 'Register Patient | Reception';
ob_start();

// Search Logic
$search = $_GET['search'] ?? '';
$searchTerm = mysqli_real_escape_string($con, $search);
$query = "SELECT * FROM patients WHERE full_name LIKE '%$searchTerm%' OR phone LIKE '%$searchTerm%' ORDER BY patient_id DESC";
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

    <div class="rcard mb-4">
        <div class="rcard-body">
            <form action="register_patient_action.php" method="POST" id="registerForm">
                <input type="hidden" name="action" value="add">
                <p class="profile-section-title" style="margin-top:0;">New Registration</p>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Enter full name"
                            data-validation="required|min|max" data-min="3" data-max="50">
                        <small id="full_name_error"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email"
                            data-validation="required|email">
                        <small id="email_error"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="10-digit number"
                            data-validation="required|number|min|max" data-min="10" data-max="10">
                        <small id="phone_error"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="birthdate" class="form-control" data-validation="required">
                        <small id="birthdate_error"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Gender</label>
                        <select name="gender" class="form-select" data-validation="required">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <small id="gender_error"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Enter address"
                            data-validation="required">
                        <small id="address_error"></small>
                    </div>
                </div>
                <button type="submit" class="btn btn-brand rounded-pill px-4">Register Patient</button>
            </form>
        </div>
    </div>

    <div class="rcard">
        <div class="rcard-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="profile-section-title m-0">Recent Registrations</p>
                <form class="d-flex gap-2" method="GET">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-sm btn-brand">Search</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($patients_data)): ?>
                            <?php foreach ($patients_data as $p): ?>
                                <tr>
                                    <td>#P<?= str_pad($p['patient_id'], 4, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= htmlspecialchars($p['full_name']) ?></td>
                                    <td><?= htmlspecialchars($p['phone']) ?></td>
                                    <td><?= $p['gender'] ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['patient_id'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="register_patient_action.php" method="POST" onsubmit="return confirm('Remove patient record?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="patient_id" value="<?= $p['patient_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">No patients found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach ($patients_data as $p): ?>
    <div class="modal fade" id="editModal<?= $p['patient_id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="register_patient_action.php" method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="patient_id" value="<?= $p['patient_id'] ?>">

                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Edit Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="edit_full_name_<?= $p['patient_id'] ?>" class="form-control"
                                value="<?= htmlspecialchars($p['full_name']) ?>"
                                data-validation="required|min|max" data-min="3" data-max="50">
                            <small id="edit_full_name_<?= $p['patient_id'] ?>_error"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="edit_phone_<?= $p['patient_id'] ?>" class="form-control"
                                value="<?= htmlspecialchars($p['phone']) ?>"
                                data-validation="required|number|min|max" data-min="10" data-max="10">
                            <small id="edit_phone_<?= $p['patient_id'] ?>_error"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="edit_address_<?= $p['patient_id'] ?>" class="form-control" rows="3"
                                data-validation="required"><?= htmlspecialchars($p['address']) ?></textarea>
                            <small id="edit_address_<?= $p['patient_id'] ?>_error"></small>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand rounded-pill px-4">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/validation.js"></script>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>