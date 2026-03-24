<?php
include "doctor-auth.php";
include "../db.php";

// Handle success/error messages
$success_msg = $_SESSION['rec_success'] ?? '';
$error_msg = $_SESSION['rec_error'] ?? '';
unset($_SESSION['rec_success'], $_SESSION['rec_error']);

// Fetch all receptionists for this doctor
$doctor_id = $_SESSION['doctor_id'];
$query = "SELECT r.*, c.clinic_name FROM receptionists r 
          LEFT JOIN clinics c ON r.clinic_id = c.clinic_id 
          WHERE r.doctor_id = $doctor_id 
          ORDER BY r.receptionist_id DESC";
$result = mysqli_query($con, $query);

// Fetch clinics for dropdown
$clinics_query = "SELECT clinic_id, clinic_name FROM clinics WHERE is_active = 1";
$clinics_result = mysqli_query($con, $clinics_query);
$clinics = [];
while ($clinic = mysqli_fetch_assoc($clinics_result)) {
    $clinics[] = $clinic;
}

// Fetch single receptionist for edit
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $eq = mysqli_query($con, "SELECT * FROM receptionists WHERE receptionist_id = $edit_id AND doctor_id = $doctor_id");
    $edit_data = mysqli_fetch_assoc($eq);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue | Manage Receptionists</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css?v=vibrant">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css?v=vibrant" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css?v=vibrant">
    <link rel="stylesheet" href="../css/doctor.css?v=vibrant">
</head>

<body class="layout-with-sidebar">

    <?php include '../sidebar/doctor-sidebar.php'; ?>

    <main class="doctor-dashboard container-fluid pt-5 mt-5">

        <!-- Header -->
        <section class="mb-4">
            <h2>Welcome, <span>Dr. <?php echo htmlspecialchars($_SESSION['doctor_name']); ?></span></h2>
        </section>

        <section class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1">Manage Receptionists</h4>
                <p class="text-muted mb-0">Add, edit, or remove your clinic receptionists</p>
            </div>
            <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#receptionistModal" onclick="clearForm()">
                <i class="bi bi-person-plus-fill me-1"></i> Add Receptionist
            </button>
        </section>



        <!-- Receptionists Table -->
        <section>
            <div class="dcard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Your Receptionists</span>
                    <span class="badge bg-brand"><?php echo mysqli_num_rows($result); ?> Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Clinic</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($row['clinic_name'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php if ($row['is_active']): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <!-- Edit -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                                        onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <!-- Toggle Active -->
                                                    <form method="post" action="manage-receptionist-action.php" class="d-inline">
                                                        <input type="hidden" name="action" value="toggle">
                                                        <input type="hidden" name="receptionist_id" value="<?php echo $row['receptionist_id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-<?php echo $row['is_active'] ? 'warning' : 'success'; ?>"
                                                            title="<?php echo $row['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                            <i class="bi bi-<?php echo $row['is_active'] ? 'pause-circle' : 'play-circle'; ?>"></i>
                                                        </button>
                                                    </form>
                                                    <!-- Delete -->
                                                    <form method="post" action="manage-receptionist-action.php" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this receptionist?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="receptionist_id" value="<?php echo $row['receptionist_id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                            No receptionists found. Click "Add Receptionist" to get started.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Add/Edit Receptionist Modal -->
    <div class="modal fade" id="receptionistModal" tabindex="-1" aria-labelledby="receptionistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receptionistModalLabel">
                        <?php echo $edit_data ? 'Edit Receptionist' : 'Add Receptionist'; ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="manage-receptionist-action.php" id="receptionistForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="receptionist_id" id="formRecId" value="">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control" name="full_name" id="full_name"
                                    placeholder="Enter full name"
                                    data-validation="required|min|max" data-min="3" data-max="100">
                                <small id="full_name_error" class="text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="receptionist@email.com"
                                    data-validation="required|email">
                                <small id="email_error" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" class="form-control" name="phone" id="phone"
                                    placeholder="+91 XXXXXXXXXX"
                                    data-validation="required|number|min|max" data-min="10" data-max="10">
                                <small id="phone_error" class="text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender</label>
                                <select class="form-select" name="gender" data-validation="required">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <small id="gender_error" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Clinic</label>
                                <select class="form-select" name="clinic_id" id="clinic_id" data-validation="required">
                                    <option value="">Select Clinic</option>
                                    <?php foreach ($clinics as $c): ?>
                                        <option value="<?php echo $c['clinic_id']; ?>">
                                            <?php echo htmlspecialchars($c['clinic_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small id="clinic_id_error" class="text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea class="form-control" name="address" id="address" rows="1"
                                    placeholder="Enter address"
                                    data-validation="required"></textarea>
                                <small id="address_error" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="row g-3 mt-1" id="passwordRow">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Create password"
                                    data-validation="required|strongPassword">
                                <small id="password_error" class="text-danger"></small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                    placeholder="Confirm password"
                                    data-validation="required|confirmPassword" data-match="password">
                                <small id="confirm_password_error" class="text-danger"></small>
                            </div>
                        </div>

                        <!-- Reset Password (Edit mode only) -->
                        <div id="resetPasswordRow" style="display:none;" class="mt-3">
                            <hr>
                            <h6 class="fw-semibold mb-2"><i class="bi bi-key me-1"></i>Reset Password</h6>
                            <p class="text-muted mb-2" style="font-size:0.82rem;">Leave blank if you don't want to change the password</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="new_password" id="new_password"
                                        placeholder="New Password"
                                        data-validation="strongPassword">
                                    <small id="new_password_error" class="text-danger"></small>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password"
                                        placeholder="Confirm New Password"
                                        data-validation="confirmPassword" data-match="new_password">
                                    <small id="confirm_new_password_error" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand" id="modalSubmitBtn">
                            <i class="bi bi-person-plus me-1" id="modalSubmitIcon"></i>
                            <span id="modalSubmitText">Add Receptionist</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Popup Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-4" style="border-radius:16px;">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Success!</h5>
                <p class="text-muted mb-3" id="successMessage"></p>
                <button type="button" class="btn btn-brand w-100" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>

    <!-- Error Popup Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-4" style="border-radius:16px;">
                <div class="mb-3">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size:3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Error!</h5>
                <p class="text-muted mb-3" id="errorMessage"></p>
                <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>

    <?php include './doctor-footer.php'; ?>

    <script src="../css/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/validation.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success_msg): ?>
                document.getElementById('successMessage').textContent = <?php echo json_encode($success_msg); ?>;
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            <?php endif; ?>

            <?php if ($error_msg): ?>
                document.getElementById('errorMessage').textContent = <?php echo json_encode($error_msg); ?>;
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            <?php endif; ?>

            <?php if ($edit_data): ?>
                openEditModal(<?php echo json_encode($edit_data); ?>);
            <?php endif; ?>
        });

        // Switch modal to ADD mode
        function clearForm() {
            document.getElementById('receptionistForm').reset();
            document.getElementById('formAction').value = 'add';
            document.getElementById('formRecId').value = '';
            document.getElementById('receptionistModalLabel').textContent = 'Add Receptionist';
            document.getElementById('modalSubmitText').textContent = 'Add Receptionist';
            document.getElementById('modalSubmitIcon').className = 'bi bi-person-plus me-1';

            // Show password fields, hide reset password
            document.getElementById('passwordRow').style.display = '';
            document.getElementById('resetPasswordRow').style.display = 'none';
            $('#password').attr('data-validation', 'required|strongPassword');
            $('#confirm_password').attr('data-validation', 'required|confirmPassword');

            // Clear validation states
            $('#receptionistForm').find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
            $('#receptionistForm').find('small.text-danger').text('').hide();
        }

        // Switch modal to EDIT mode and populate data
        function openEditModal(data) {
            clearForm(); // reset first

            document.getElementById('formAction').value = 'edit';
            document.getElementById('formRecId').value = data.receptionist_id;
            document.getElementById('receptionistModalLabel').textContent = 'Edit Receptionist';
            document.getElementById('modalSubmitText').textContent = 'Save Changes';
            document.getElementById('modalSubmitIcon').className = 'bi bi-save me-1';

            // Fill fields
            document.getElementById('full_name').value = data.full_name || '';
            document.getElementById('email').value = data.email || '';
            document.getElementById('phone').value = data.phone || '';
            document.querySelector('[name="gender"]').value = data.gender || '';
            document.getElementById('clinic_id').value = data.clinic_id || '';
            document.getElementById('address').value = data.address || '';

            // Hide password fields, show reset password section
            document.getElementById('passwordRow').style.display = 'none';
            document.getElementById('resetPasswordRow').style.display = '';
            $('#password').removeAttr('data-validation');
            $('#confirm_password').removeAttr('data-validation');

            var modal = new bootstrap.Modal(document.getElementById('receptionistModal'));
            modal.show();
        }
    </script>

</body>

</html>
