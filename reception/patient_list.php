<?php
$content_page = 'Patient Directory | Reception | MediQueue';
ob_start();
?>

<div class="reception-dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <small class="text-uppercase fw-semibold text-brand" style="font-size:0.76rem;letter-spacing:1px;">All scheduled patients</small>
            <h1 class="dashboard-title mt-1">Patient <span>Directory</span></h1>
            <p class="dashboard-subtitle">Patients with appointments scheduled for today</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <input type="text" class="form-control rounded-pill" style="width:220px;" placeholder="Search patient...">
            <a href="register_patient.php" class="btn btn-brand rounded-pill">
                <i class="bi bi-person-plus me-1"></i>Register Patient
            </a>
        </div>
    </div>

    <div class="rcard">
        <div class="rcard-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="r-thead">
                            <th>Name</th>
                            <th>Age / Gender</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Doctor</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Rahul Patel</strong></td>
                            <td class="text-muted">32 / Male</td>
                            <td class="text-muted">9876543210</td>
                            <td class="text-muted">rahul@email.com</td>
                            <td>Dr. Mehta</td>
                            <td class="text-muted" style="font-size:0.88rem;">10:30 AM</td>
                            <td><span class="badge-soft-primary">Follow-up</span></td>
                            <td><span class="badge-soft-warning">Waiting</span></td>
                        </tr>
                        <tr>
                            <td><strong>Neha Sharma</strong></td>
                            <td class="text-muted">28 / Female</td>
                            <td class="text-muted">9123456780</td>
                            <td class="text-muted">neha@email.com</td>
                            <td>Dr. Shah</td>
                            <td class="text-muted" style="font-size:0.88rem;">11:00 AM</td>
                            <td><span class="badge-soft-danger">Emergency</span></td>
                            <td><span class="badge-soft-success">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-muted mt-3 mb-0" style="font-size:0.85rem;">
                <i class="bi bi-info-circle me-1"></i>Showing patients scheduled for today
            </p>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include './reception-layout.php';
?>