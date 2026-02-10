<?php
$content_page = 'staff-management';
ob_start();
?>
    <main class="admin-dashboard" style="margin-top:20px;">
        <div class="container">

            <!-- Heading and Add Staff button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="features-header text-start mb-0">
                    <h2>Staff <span>Management</span></h2>
                    <div class="section-divider" style="margin-left:0;"></div>
                    <p class="mb-0">Manage receptionists</p>
                </div>

                <button class="hero-btn" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                    + Add Staff
                </button>
            </div>

            <!-- Staff Table -->
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
                        <tr>
                            <td>Anita Shah</td>
                            <td>Receptionist</td>
                            <td>Main Clinic</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td class="text-center">

                                <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal" data-bs-target="#editStaffModal">
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

                        <tr>
                            <td>Rahul Mehta</td>
                            <td>Receptionist</td>
                            <td>Branch Clinic</td>
                            <td>
                                <span class="badge bg-secondary">Inactive</span>
                            </td>
                            <td class="text-center">

                                <button class="btn btn-sm btn-outline-primary">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-outline-success">
                                    Activate
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

    <!-- ADD STAFF MODAL -->
    <div class="modal fade" id="addStaffModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Add New Staff</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Full Name</label>
                                <input type="text" class="form-control" placeholder="Staff Name">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Phone</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Assign Clinic</label>
                                <select class="form-select">
                                    <option>Main Clinic</option>
                                    <option>Branch Clinic</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Role</label>
                            <select class="form-select">
                                <option selected>Receptionist</option>
                            </select>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="hero-btn">Add Staff</button>
                </div>

            </div>
        </div>
    </div>

    <!-- EDIT STAFF MODAL -->
    <div class="modal fade" id="editStaffModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Edit Staff Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Full Name</label>
                                <input type="text" class="form-control" value="Anita Shah">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" value="anita@email.com">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Clinic</label>
                                <select class="form-select">
                                    <option selected>Main Clinic</option>
                                    <option>Branch Clinic</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select class="form-select">
                                    <option selected>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
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