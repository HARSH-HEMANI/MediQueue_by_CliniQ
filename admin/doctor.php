<?php
$content_page = 'doctor-management';
ob_start();
?>
<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <!-- Page Heading + Add Doctor -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="features-header text-start mb-0">
                <h2>Doctor <span>Management</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
            </div>

            <button class="hero-btn" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                + Add Doctor
            </button>
        </div>

        <!-- Doctor Table -->
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
                    <tr>
                        <td>Dr. Raj Patel</td>
                        <td>Cardiology</td>
                        <td>Main Clinic</td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td class="text-center">

                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal" data-bs-target="#editDoctorModal">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-outline-warning">
                                Deactivate
                            </button>

                            <button class="btn btn-sm btn-outline-danger">
                                Delete
                            </button>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>

<!-- ADD DOCTOR MODAL -->
<div class="modal fade" id="addDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Add New Doctor</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Doctor Name</label>
                            <input type="text" class="form-control" placeholder="Dr. John Doe">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Specialization</label>
                            <input type="text" class="form-control" placeholder="Cardiology">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Assign Clinic</label>
                        <select class="form-select">
                            <option>Main Clinic</option>
                            <option>Branch Clinic</option>
                        </select>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="hero-btn">Add Doctor</button>
            </div>

        </div>
    </div>
</div>

<!-- EDIT DOCTOR MODAL -->
<div class="modal fade" id="editDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit Doctor Information</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Doctor Name</label>
                            <input type="text" class="form-control" value="Dr. Raj Patel">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Specialization</label>
                            <input type="text" class="form-control" value="Cardiology">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Clinic</label>
                        <select class="form-select">
                            <option selected>Main Clinic</option>
                            <option>Branch Clinic</option>
                        </select>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="hero-btn">Save Changes</button>
            </div>

        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include './admin-layout.php';
?>