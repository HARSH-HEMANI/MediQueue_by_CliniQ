<?php
require_once "admin-init.php";

$content_page = 'patient-management';
ob_start();

// ==========================================
// SEARCH & FILTER LOGIC
// ==========================================
$query = "SELECT * FROM patients";
$conditions = [];

// Text Search (Name, Phone, Email)
if (!empty($_GET['search'])) {
    $search_term = mysqli_real_escape_string($con, $_GET['search']);
    $conditions[] = "(full_name LIKE '%$search_term%' OR phone LIKE '%$search_term%' OR email LIKE '%$search_term%')";
}

// Gender Filter
if (!empty($_GET['gender'])) {
    $gender = mysqli_real_escape_string($con, $_GET['gender']);
    $conditions[] = "gender = '$gender'";
}

// Status Filter
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $status = mysqli_real_escape_string($con, $_GET['status']);
    $conditions[] = "is_active = '$status'";
}

// Combine conditions
if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Sort newest first
$query .= " ORDER BY patient_id DESC";

// Execute query
$patients_result = mysqli_query($con, $query);
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
                <h2>Patient <span>Management</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
                <p class="mb-0">Overview and manage registered patients</p>
            </div>

            <button class="btn btn-brand w-30 mb-2 py-2" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                + Add Patient
            </button>
        </div>

        <div class="feature-acard mb-4">
            <form class="row g-3 align-items-center" method="GET" action="patient-management.php">

                <div class="col-md-5">
                    <input type="text" class="form-control" name="search"
                        placeholder="Search by name, phone, or email"
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>

                <div class="col-md-3">
                    <select class="form-select" name="gender">
                        <option value="">All Genders</option>
                        <option value="Male" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="1" <?= (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-brand w-30 mb-2 py-2 w-100" type="submit">Search</button>
                    <?php if (!empty($_GET)): ?>
                        <a href="patient-management.php" class="btn btn-outline-secondary">Clear</a>
                    <?php endif; ?>
                </div>

            </form>
        </div>

        <div class="feature-acard">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Contact Info</th>
                            <th>Gender / DOB</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($patients_result && mysqli_num_rows($patients_result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($patients_result)): ?>
                                <tr>
                                    <td><strong>PT-<?= htmlspecialchars($row['patient_id']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                                    <td>
                                        <small class="text-muted d-block"><i class="bi bi-telephone"></i> <?= htmlspecialchars($row['phone'] ?? 'N/A') ?></small>
                                        <small class="text-muted d-block"><i class="bi bi-envelope"></i> <?= htmlspecialchars($row['email'] ?? 'N/A') ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['gender'] ?? 'N/A') ?><br>
                                        <small class="text-muted"><?= !empty($row['date_of_birth']) ? date('d M Y', strtotime($row['date_of_birth'])) : 'N/A' ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger bg-opacity-75"><?= htmlspecialchars($row['blood_group'] ?? 'N/A') ?></span>
                                    </td>
                                    <td>
                                        <?php if (isset($row['is_active']) && $row['is_active'] == 1): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">

                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPatientModal_<?= $row['patient_id'] ?>">
                                                Edit
                                            </button>

                                            <form action="manage-patient-action.php" method="POST" class="m-0">
                                                <input type="hidden" name="action" value="toggle_status">
                                                <input type="hidden" name="patient_id" value="<?= $row['patient_id'] ?>">
                                                <button type="submit" class="btn btn-sm <?= (isset($row['is_active']) && $row['is_active'] == 1) ? 'btn-outline-warning' : 'btn-outline-success' ?>" style="min-width: 85px;">
                                                    <?= (isset($row['is_active']) && $row['is_active'] == 1) ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                            </form>

                                            <form action="manage-patient-action.php" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to completely delete this patient record?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="patient_id" value="<?= $row['patient_id'] ?>">
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
                                <td colspan="7" class="text-center text-muted py-4">No patients found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<div class="modal fade" id="addPatientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="manage-patient-action.php" method="POST" id="addPatientForm">
                <input type="hidden" name="action" value="add">

                <div class="modal-header">
                    <h5>Register New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-start">
                    <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password (For App/Portal Login)</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave blank for default">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Blood Group</label>
                            <select name="blood_group" class="form-select">
                                <option value="">Select</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="border-bottom pb-2 mt-2 mb-3">Contact & Address</h6>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label>Full Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Pincode</label>
                            <input type="text" name="pincode" class="form-control">
                        </div>
                    </div>

                    <h6 class="border-bottom pb-2 mt-2 mb-3">Emergency Contact</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Relation</label>
                            <input type="text" name="emergency_contact_relation" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Emergency Phone</label>
                            <input type="text" name="emergency_contact_phone" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand w-30 mb-2 py-2">Register Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if ($patients_result && mysqli_num_rows($patients_result) > 0) {
    mysqli_data_seek($patients_result, 0);
    while ($row = mysqli_fetch_assoc($patients_result)):
        $modal_id = $row['patient_id'];
?>
        <div class="modal fade" id="editPatientModal_<?= $modal_id ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="manage-patient-action.php" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="patient_id" value="<?= $modal_id ?>">

                        <div class="modal-header">
                            <h5>Edit Patient Details (PT-<?= htmlspecialchars($modal_id) ?>)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-start">
                            <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($row['full_name']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Change Password (Optional)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="Male" <?= ($row['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($row['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= ($row['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="<?= $row['date_of_birth'] ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Blood Group</label>
                                    <select name="blood_group" class="form-select">
                                        <option value="">Select</option>
                                        <?php
                                        $b_groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                                        foreach ($b_groups as $bg) {
                                            $sel = ($row['blood_group'] == $bg) ? 'selected' : '';
                                            echo "<option value='$bg' $sel>$bg</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <h6 class="border-bottom pb-2 mt-2 mb-3">Contact & Address</h6>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label>Full Address</label>
                                    <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($row['address'] ?? '') ?></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Pincode</label>
                                    <input type="text" name="pincode" class="form-control" value="<?= htmlspecialchars($row['pincode'] ?? '') ?>">
                                </div>
                            </div>

                            <h6 class="border-bottom pb-2 mt-2 mb-3">Emergency Contact</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Contact Name</label>
                                    <input type="text" name="emergency_contact_name" class="form-control" value="<?= htmlspecialchars($row['emergency_contact_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Relation</label>
                                    <input type="text" name="emergency_contact_relation" class="form-control" value="<?= htmlspecialchars($row['emergency_contact_relation'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Emergency Phone</label>
                                    <input type="text" name="emergency_contact_phone" class="form-control" value="<?= htmlspecialchars($row['emergency_contact_phone'] ?? '') ?>">
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
<?php
    endwhile;
}
?>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>