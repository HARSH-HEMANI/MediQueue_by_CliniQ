<?php
$content_page = 'Manage Appointments | Reception | MediQueue';
ob_start();
?>

<div class="reception-dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">Control patient flow</small>
            <h1 class="dashboard-title mt-1">Manage <span>Appointments</span></h1>
            <p class="dashboard-subtitle">Update status, manage walk-ins and patient flow</p>
        </div>
        <button class="btn btn-brand rounded-pill" data-bs-toggle="modal" data-bs-target="#walkinModal">
            <i class="bi bi-person-plus me-1"></i>Walk-In Patient
        </button>
    </div>

    <!-- Stat Cards -->
    <div class="stats-row mb-4">
        <div class="rstat-card">
            <h6>Total Today</h6>
            <h2>18</h2>
        </div>
        <div class="rstat-card">
            <h6>Waiting</h6>
            <h2>10</h2>
        </div>
        <div class="rstat-card">
            <h6>In Progress</h6>
            <h2>1</h2>
        </div>
        <div class="rstat-card">
            <h6>Completed</h6>
            <h2>7</h2>
        </div>
    </div>

    <!-- Current Token -->
    <div class="dcard current-token-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex gap-2 mb-2">
                    <span class="badge bg-success">Currently Consulting</span>
                    <span class="badge-soft-primary">Follow-up</span>
                </div>
                <div class="current-token-number mt-1">Token <span>#12</span></div>
                <div class="r-patient-name mt-1">
                    Rahul Patel
                    <span class="text-muted fw-normal" style="font-size:0.9rem;">(32 / Male)</span>
                </div>
                <small class="text-muted d-block mt-1">
                    <i class="bi bi-person-badge me-1"></i>Dr. Mehta
                </small>
            </div>
            <div class="d-flex flex-column gap-2">
                <button class="btn btn-brand rounded-pill"><i class="bi bi-arrow-right-circle me-1"></i>Call Next</button>
                <button class="btn btn-outline-success rounded-pill"><i class="bi bi-check-circle me-1"></i>Complete</button>
                <button class="btn btn-outline-warning rounded-pill"><i class="bi bi-pause-circle me-1"></i>Hold</button>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="rcard mb-4">
        <div class="rcard-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">Search</label>
                    <input type="text" class="form-control rounded-pill" placeholder="Patient name or phone...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">Date</label>
                    <input type="date" class="form-control rounded-3">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">Doctor</label>
                    <select class="form-select rounded-3">
                        <option>All Doctors</option>
                        <option>Dr. Mehta</option>
                        <option>Dr. Shah</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary rounded-pill w-100"
                        data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="rcard">
        <div class="rcard-body">
            <h5 class="mb-3">Appointment List</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="r-thead">
                            <th>Token</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>#13</strong></td>
                            <td>
                                <a href="#" class="fw-semibold text-brand text-decoration-none"
                                    data-bs-toggle="modal" data-bs-target="#patientModal">Rahul Sharma</a>
                                <div class="text-muted small">9876543210</div>
                            </td>
                            <td>Dr. Shah</td>
                            <td class="text-muted" style="font-size:0.88rem;">10:30 AM</td>
                            <td><span class="badge-soft-primary">Follow-up</span></td>
                            <td>
                                <select class="form-select form-select-sm rounded-pill" style="min-width:120px;">
                                    <option selected>Waiting</option>
                                    <option>Consulting</option>
                                    <option>Completed</option>
                                    <option>Cancelled</option>
                                </select>
                            </td>
                            <td><input class="form-control form-control-sm rounded-pill" placeholder="Add note..."></td>
                        </tr>
                        <tr>
                            <td><strong>#14</strong></td>
                            <td>
                                <strong>Anita Patel</strong>
                                <div class="text-muted small">9123456780</div>
                            </td>
                            <td>Dr. Mehta</td>
                            <td class="text-muted" style="font-size:0.88rem;">11:00 AM</td>
                            <td><span class="badge-soft-danger">Emergency</span></td>
                            <td>
                                <select class="form-select form-select-sm rounded-pill" style="min-width:120px;">
                                    <option>Waiting</option>
                                    <option selected>Consulting</option>
                                    <option>Completed</option>
                                    <option>Cancelled</option>
                                </select>
                            </td>
                            <td><input class="form-control form-control-sm rounded-pill" placeholder="Add note..."></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-funnel text-brand me-2"></i>Filter Appointments</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Doctor</label>
                        <select class="form-select">
                            <option value="">All Doctors</option>
                            <option>Dr. Mehta</option>
                            <option>Dr. Shah</option>
                            <option>Dr. Patel</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select">
                            <option value="">All Status</option>
                            <option>Waiting</option>
                            <option>Consulting</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Reset</button>
                <button class="btn btn-brand">Apply Filter</button>
            </div>
        </div>
    </div>
</div>

<!-- Walk-In Modal -->
<div class="modal fade" id="walkinModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus text-brand me-2"></i>Register Walk-In Patient</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Patient Name</label>
                    <input type="text" class="form-control" placeholder="Enter full name">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone Number</label>
                    <input type="text" class="form-control" placeholder="Enter phone number">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Doctor</label>
                    <select class="form-select">
                        <option>Select Doctor</option>
                        <option>Dr. Mehta</option>
                        <option>Dr. Shah</option>
                    </select>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Time</label>
                        <input type="time" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Case Type</label>
                    <select class="form-select">
                        <option>Regular</option>
                        <option>Emergency</option>
                        <option>Follow-up</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-brand">Register Patient</button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>