<?php
$content_page = 'clinic-overview';
ob_start();
?>
<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="features-header text-start mb-0">
                <h2>Clinic <span>Management</span></h2>
                <div class="section-divider" style="margin-left:0;"></div>
                <p class="mb-0">Manage clinics, schedules, and doctor assignments</p>
            </div>

            <button class="hero-btn" data-bs-toggle="modal" data-bs-target="#addClinicModal">
                + Add Clinic
            </button>
        </div>

        <!-- Clinic Table -->
        <div class="feature-acard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Clinic ID</th>
                        <th>Clinic Name</th>
                        <th>Address</th>
                        <th>Working Days</th>
                        <th>Hours</th>
                        <th>Doctors</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>CLN-001</td>
                        <td>Main Clinic</td>
                        <td>Ahmedabad</td>
                        <td>Mon – Sat</td>
                        <td>09:00 – 18:00</td>
                        <td>6</td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal" data-bs-target="#editClinicModal">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-outline-secondary"
                                data-bs-toggle="modal" data-bs-target="#assignDoctorModal">
                                Assign Doctors
                            </button>

                            <button class="btn btn-sm btn-outline-warning">
                                Deactivate
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>CLN-002</td>
                        <td>Branch Clinic</td>
                        <td>Gandhinagar</td>
                        <td>Mon - Fri</td>
                        <td>10:00 - 17:00</td>
                        <td>3</td>
                        <td>
                            <span class="badge bg-secondary">Inactive</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                            <button class="btn btn-sm btn-outline-success">Activate</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>

<!-- ADD CLINIC MODAL -->
<div class="modal fade" id="addClinicModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Add New Clinic</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Clinic Name</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Contact Number</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Clinic Address</label>
                        <textarea class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Working Days</label>
                            <select class="form-select">
                                <option>Mon - Fri</option>
                                <option>Mon - Sat</option>
                                <option>All Days</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Opening Time</label>
                            <input type="time" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Closing Time</label>
                            <input type="time" class="form-control">
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="hero-btn">Add Clinic</button>
            </div>

        </div>
    </div>
</div>

<!-- ASSIGN DOCTORS MODAL -->
<div class="modal fade" id="assignDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Assign Doctors</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Dr. Raj Patel (Cardiology)</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">Dr. Meena Shah (Physician)</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="hero-btn">Save Assignment</button>
            </div>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>