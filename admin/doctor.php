<?php
require_once "admin-init.php";
$content_page = 'doctor-management';
ob_start();

// Fetch all doctors with their clinic names
$query = "SELECT d.*, c.clinic_name 
        FROM doctors d 
        LEFT JOIN clinics c ON d.clinic_id = c.clinic_id 
        ORDER BY d.doctor_id DESC";
$doctors_result = mysqli_query($con, $query);
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <?php if (isset($_SESSION['admin_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['admin_success'];
                unset($_SESSION['admin_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['admin_error'];
                unset($_SESSION['admin_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="features-header text-start mb-0">
                <h2>Doctor <span>Management</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
            </div>

            <button class="hero-btn" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                + Add Doctor
            </button>
        </div>

        <div class="feature-acard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Clinic</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($doctors_result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($doctors_result)): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($row['full_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['specialization']) ?></td>
                                <td><?= htmlspecialchars($row['clinic_name'] ?? 'No Clinic') ?></td>
                                <td>
                                    <?php if ($row['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">

                                    <button class="btn btn-sm btn-outline-primary edit-btn"
                                        data-bs-toggle="modal" data-bs-target="#editDoctorModal"
                                        data-id="<?= $row['doctor_id'] ?>"
                                        data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                        data-email="<?= htmlspecialchars($row['email']) ?>"
                                        data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                        data-spec="<?= htmlspecialchars($row['specialization']) ?>"
                                        data-qual="<?= htmlspecialchars($row['qualification']) ?>"
                                        data-exp="<?= $row['experience_years'] ?>">
                                        Edit
                                    </button>

                                    <form action="manage-doctor-action.php" method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="doctor_id" value="<?= $row['doctor_id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $row['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                            <?= $row['is_active'] ? 'Deactivate' : 'Activate' ?>
                                        </button>
                                    </form>

                                    <form action="manage-doctor-action.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this doctor? This action cannot be undone.');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="doctor_id" value="<?= $row['doctor_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No doctors found in the system.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<div class="modal fade" id="addDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="manage-doctor-action.php" method="POST">
                <input type="hidden" name="action" value="add">

                <div class="modal-header">
                    <h5>Add New Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Doctor Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="Dr. John Doe" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Specialization <span class="text-danger">*</span></label>
                            <input type="text" name="specialization" class="form-control" placeholder="Cardiology" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Qualification (e.g., MBBS, MD)</label>
                            <input type="text" name="qualification" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Experience (Years)</label>
                            <input type="number" name="experience_years" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Clinic Name (Will create a new clinic) <span class="text-danger">*</span></label>
                        <input type="text" name="clinic_name" class="form-control" placeholder="City Care Clinic" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="hero-btn">Add Doctor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="manage-doctor-action.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="doctor_id" id="edit_doctor_id">

                <div class="modal-header">
                    <h5>Edit Doctor Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Doctor Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Specialization <span class="text-danger">*</span></label>
                            <input type="text" name="specialization" id="edit_specialization" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Qualification</label>
                            <input type="text" name="qualification" id="edit_qualification" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Experience (Years)</label>
                            <input type="number" name="experience_years" id="edit_experience" class="form-control" min="0">
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted small mb-2">Leave password blank if you do not wish to change it.</p>
                    <div class="mb-3">
                        <label>Reset Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="New Password">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="hero-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get data from the clicked button's data-* attributes
                const docId = this.getAttribute('data-id');
                const docName = this.getAttribute('data-name');
                const docEmail = this.getAttribute('data-email');
                const docPhone = this.getAttribute('data-phone');
                const docSpec = this.getAttribute('data-spec');
                const docQual = this.getAttribute('data-qual');
                const docExp = this.getAttribute('data-exp');

                // Inject data into the Edit Modal's input fields
                document.getElementById('edit_doctor_id').value = docId;
                document.getElementById('edit_full_name').value = docName;
                document.getElementById('edit_email').value = docEmail;
                document.getElementById('edit_phone').value = docPhone;
                document.getElementById('edit_specialization').value = docSpec;
                document.getElementById('edit_qualification').value = docQual;
                document.getElementById('edit_experience').value = docExp;
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>